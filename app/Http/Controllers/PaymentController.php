<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Show manual BCA payment form
     */
    public function showPaymentForm($orderId)
    {
        $order = Order::with(['user', 'destination'])
            ->where('user_id', auth()->id())
            ->findOrFail($orderId);

        // Check if payment is expired
        if ($order->isExpired() || $order->isPaymentExpired()) {
            if (!$order->isExpired()) {
                $order->markAsExpired();
            }
            return redirect()->route('pesanan-saya')
                ->with('error', 'Pembayaran telah kedaluwarsa. Silakan lakukan pemesanan ulang.');
        }

        $bank = config('payment.bank', [
            'name' => 'Bank Central Asia (BCA)',
            'code' => 'BCA',
            'account_number' => '0060849510',
            'account_name' => 'RIFQI AUFA RACHMADI',
            'logo' => 'images/banks/bca.png',
        ]);

        return view('pages.booking.payment', compact('order', 'bank'));
    }

    /**
     * Upload payment proof (bukti transfer manual BCA)
     */
    public function uploadPaymentProof(Request $request, $orderId)
    {
        $order = Order::where('user_id', auth()->id())
            ->findOrFail($orderId);

        if ($order->isPaid()) {
            return redirect()->route('pesanan-saya')
                ->with('info', 'Pesanan ini sudah lunas terverifikasi.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'payment_proof.required' => 'Silakan pilih file bukti pembayaran untuk diunggah.',
            'payment_proof.image' => 'File bukti pembayaran harus berupa gambar.',
            'payment_proof.mimes' => 'Format file yang didukung: JPEG, PNG, JPG, atau WEBP.',
            'payment_proof.max' => 'Ukuran file bukti pembayaran maksimal 5MB.',
        ]);

        try {
            if ($request->hasFile('payment_proof')) {
                // Delete previous file if exists
                if ($order->payment_proof && Storage::disk('public')->exists($order->payment_proof)) {
                    Storage::disk('public')->delete($order->payment_proof);
                }

                $file = $request->file('payment_proof');
                $filename = 'proof_' . $order->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('payment_proofs', $filename, 'public');

                $order->update([
                    'payment_proof' => $path,
                    'payment_status' => Order::PAYMENT_PENDING_VERIFICATION,
                    'status' => Order::STATUS_PROCESSING,
                    'payment_date' => now(),
                    'notes' => trim($order->notes . "\n[Bukti transfer diunggah: " . now()->format('d/m/Y H:i') . "]"),
                ]);

                Log::info("Payment proof uploaded for order #{$order->id} (Invoice: {$order->invoice_number})");

                return redirect()->route('payment.form', $order->id)
                    ->with('success', 'Bukti pembayaran berhasil diunggah! Tim kami akan segera memverifikasi pembayaran Anda.');
            }

            return back()->with('error', 'Gagal memproses file. Silakan coba lagi.');
        } catch (\Exception $e) {
            Log::error('Error uploading payment proof: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengunggah bukti pembayaran: ' . $e->getMessage());
        }
    }
}