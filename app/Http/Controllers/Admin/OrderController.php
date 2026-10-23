<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;
use Carbon\Carbon;

class OrderController extends BaseController
{
    public function index()
    {
        $orders = Order::with(['user', 'destination'])
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'destination', 'payments']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    /**
     * Verify or reject manual payment proof
     */
    public function verifyPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validated['action'] === 'approve') {
            $order->update([
                'payment_status' => Order::PAYMENT_PAID,
                'status' => Order::STATUS_PROCESSING,
                'paid_at' => now(),
                'notes' => trim($order->notes . "\n[Pembayaran diverifikasi oleh Admin pada: " . now()->format('d/m/Y H:i') . "]"),
            ]);

            return back()->with('success', 'Pembayaran berhasil diverifikasi dan disetujui (Lunas).');
        } else {
            $reason = $validated['notes'] ?: 'Bukti pembayaran tidak sesuai atau tidak dapat dibaca.';
            $order->markAsRejected($reason);

            return back()->with('warning', 'Bukti pembayaran ditolak. Status pembayaran telah diperbarui.');
        }
    }

    /**
     * Export orders to different formats
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:xlsx,pdf,csv'
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $filename = 'orders_export_' . now()->format('Y-m-d_H-i-s');

        switch ($request->format) {
            case 'pdf':
                $view = view('admin.orders.export_pdf', [
                    'orders' => $this->getOrdersForExport($startDate, $endDate),
                    'startDate' => $startDate,
                    'endDate' => $endDate
                ]);
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($view->render());
                return $pdf->download($filename . '.pdf');
                
            case 'csv':
                return Excel::download(new OrdersExport($startDate, $endDate), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
                
            case 'xlsx':
            default:
                return Excel::download(new OrdersExport($startDate, $endDate), $filename . '.xlsx');
        }
    }

    /**
     * Get orders for export
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getOrdersForExport($startDate, $endDate)
    {
        return Order::with(['user', 'destination'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Generate invoice for an order
     *
     * @param  Order  $order
     * @return \Illuminate\Http\Response
     */
    public function invoice(Order $order)
    {
        $order->load(['user', 'destination', 'payments']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.invoice', [
            'order' => $order,
            'date' => now()->format('d F Y'),
        ]);
        
        return $pdf->download('invoice-' . $order->invoice_number . '.pdf');
    }

    public function create()
    {
        $destinations = \App\Models\Destination::all();
        $users = \App\Models\User::all();
        return view('admin.orders.create', compact('destinations', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'destination_id' => 'required|exists:destinations,id',
            'quantity' => 'required|integer|min:1',
            'departure_date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
        ]);
        $validated['total'] = 0; // Atur sesuai logika harga jika perlu
        $validated['invoice_number'] = 'INV-' . strtoupper(uniqid());
        $order = Order::create($validated);
        return redirect()->route('admin.orders.index')->with('success', 'Order berhasil ditambahkan.');
    }

    public function edit(Order $order)
    {
        $destinations = \App\Models\Destination::all();
        $users = \App\Models\User::all();
        return view('admin.orders.edit', compact('order', 'destinations', 'users'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'destination_id' => 'required|exists:destinations,id',
            'quantity' => 'required|integer|min:1',
            'departure_date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
        ]);
        $order->update($validated);
        return redirect()->route('admin.orders.index')->with('success', 'Order berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order berhasil dihapus.');
    }
}
