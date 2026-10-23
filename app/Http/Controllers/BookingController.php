<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function showBookingForm($id)
    {
        try {
            $destination = Destination::findOrFail($id);
            return view('pages.booking.form', compact('destination'));
        } catch (\Exception $e) {
            return redirect()->route('destinations.index')
                ->with('error', 'Destinasi tidak ditemukan.');
        }
    }

    public function processBooking(Request $request)
    {
        try {
            $validated = $request->validate([
                'destination_id' => 'required|exists:destinations,id',
                'departure_date' => 'required|date|after_or_equal:today',
                'quantity' => 'required|integer|min:1|max:20',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20|regex:/^[0-9+\-\s()]*$/',
                'notes' => 'nullable|string|max:1000',
                'promo_code' => 'nullable|string|max:50',
            ], [
                'departure_date.after_or_equal' => 'Tanggal keberangkatan tidak boleh kurang dari hari ini.',
                'quantity.max' => 'Maksimal pemesanan 20 tiket sekaligus.',
                'customer_phone.regex' => 'Format nomor telepon tidak valid.',
            ]);
            
            // Check if destination is available
            $destination = Destination::findOrFail($validated['destination_id']);
            
            // Check quota availability for the chosen departure date
            $maxQuota = (int) ($destination->max_people ?: 20);
            $bookedCount = Order::where('destination_id', $destination->id)
                ->whereDate('departure_date', $validated['departure_date'])
                ->whereIn('status', ['pending', 'processing', 'completed'])
                ->whereNotIn('payment_status', ['cancelled', 'expired'])
                ->sum('quantity');

            $availableQuota = max(0, $maxQuota - $bookedCount);
            if ($validated['quantity'] > $availableQuota) {
                if ($availableQuota <= 0) {
                    return back()->withInput()->with('error', 'Maaf, kuota untuk tanggal yang dipilih sudah penuh. Silakan pilih tanggal lain.');
                }
                return back()->withInput()->with('error', "Maaf, sisa kuota untuk tanggal tersebut hanya {$availableQuota} tiket. Silakan sesuaikan jumlah tiket Anda.");
            }

            $order = new Order();
            $order->user_id = Auth::id();
            $order->destination_id = $destination->id;
            $order->invoice_number = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(8));
            $order->quantity = $validated['quantity'];
            
            // Calculate total with promo code discount
            $subtotal = $destination->price * $validated['quantity'];
            $discount = 0;
            if ($request->filled('promo_code')) {
                $code = strtoupper(trim($request->promo_code));
                if ($code === 'NAMINA10') {
                    $discount = round($subtotal * 0.1);
                } elseif ($code === 'NAMINA5') {
                    $discount = round($subtotal * 0.05);
                } elseif ($code === 'WELCOME50') {
                    $discount = 50000;
                }
            }
            $order->total = max($subtotal - $discount, 0);
            
            $order->departure_date = $validated['departure_date'];
            $order->customer_name = $validated['customer_name'];
            $order->customer_email = $validated['customer_email'];
            $order->customer_phone = $validated['customer_phone'];
            
            $notes = $validated['notes'] ?? '';
            if ($discount > 0) {
                $notes = trim($notes . "\n[PROMO] Kode: " . strtoupper($request->promo_code) . " (Potongan: Rp " . number_format($discount, 0, ',', '.') . ")");
            }
            $order->notes = $notes;
            
            $order->status = 'pending';
            $order->payment_status = 'pending';
            $order->save();

            return redirect()->route('payment.form', $order->id);
            
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi. ' . $e->getMessage());
        }
    }

    public function showPaymentForm($id)
    {
        try {
            $order = Order::with('destination')
                ->where('user_id', Auth::id())
                ->where('payment_status', 'pending')
                ->findOrFail($id);
                
            if ($order->payment_status !== 'pending') {
                return redirect()->route('pesanan-saya')
                    ->with('error', 'Pembayaran untuk pesanan ini sudah diproses.');
            }
            
            return view('pages.booking.payment', compact('order'));
            
        } catch (\Exception $e) {
            return redirect()->route('pesanan-saya')
                ->with('error', 'Pesanan tidak ditemukan atau sudah diproses.');
        }
    }

    public function processPayment(Request $request, $id)
    {
        try {
            $request->validate([
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            ], [
                'payment_proof.required' => 'Bukti pembayaran harus diunggah.',
                'payment_proof.image' => 'File harus berupa gambar.',
                'payment_proof.mimes' => 'Format file harus jpeg, png, atau jpg.',
                'payment_proof.max' => 'Ukuran file maksimal 5MB.'
            ]);

            $order = Order::where('user_id', Auth::id())
                ->where('id', $id)
                ->where('payment_status', 'pending')
                ->firstOrFail();

            // Handle file upload
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = 'payment_' . time() . '_' . $order->id . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('payment_proofs', $filename, 'public');
                
                // Update order with payment info
                $order->update([
                    'payment_proof' => $path,
                    'payment_status' => 'pending_verification',
                    'payment_date' => now(),
                    'status' => 'processing',
                    'notes' => $order->notes . "\n\nPembayaran dikonfirmasi pada: " . now()->format('d/m/Y H:i')
                ]);

                // Here you might want to send notification to admin
                // Notification::send(User::where('role', 'admin')->get(), new NewPaymentNotification($order));
                
                return redirect()->route('booking.success', $order->id);
            }
            
            throw new \Exception('Gagal mengunggah bukti pembayaran.');
            
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bookingSuccess($id)
    {
        try {
            $order = Order::with('destination')
                ->where('user_id', Auth::id())
                ->findOrFail($id);
            
            // Update order status based on payment status
            if (request()->has('status')) {
                $status = request('status');
                
                if ($status === 'success') {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                        'payment_date' => now()
                    ]);
                } elseif ($status === 'pending') {
                    $order->update([
                        'payment_status' => 'pending',
                        'status' => 'pending_payment'
                    ]);
                }
            }
            
            // Check if the layout file exists, if not use a simple layout
            $layoutPath = resource_path('views/layouts/app.blade.php');
            if (!file_exists($layoutPath)) {
                return "<h1>Pembayaran Berhasil!</h1><p>Terima kasih telah melakukan pembayaran. Nomor pesanan Anda: " . $order->invoice_number . "</p>";
            }
            
            return view('pages.booking.success', compact('order'));
            
        } catch (\Exception $e) {
            \Log::error('Booking success error: ' . $e->getMessage());
            return "<h1>Terjadi Kesalahan</h1><p>Silakan periksa halaman pesanan Anda untuk melihat status terbaru.</p>";
        }
    }

    // Admin methods
    public function index()
    {
        $orders = Order::with(['user', 'destination'])
            ->latest()
            ->paginate(10);
            
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $order->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $order->notes,
        ]);

        return back()->with('success', 'Status pemesanan berhasil diperbarui');
    }
}
