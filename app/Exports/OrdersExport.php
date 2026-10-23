<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Order::with(['user', 'destination'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Invoice',
            'Nama Pelanggan',
            'Email',
            'Destinasi',
            'Tanggal Pesan',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Jumlah',
            'Total Harga',
            'Status',
        ];
    }

    public function map($order): array
    {
        return [
            $order->invoice_number,
            $order->user->name,
            $order->user->email,
            $order->destination->name,
            $order->created_at->format('d/m/Y H:i'),
            $order->start_date->format('d/m/Y'),
            $order->end_date->format('d/m/Y'),
            $order->quantity,
            'Rp ' . number_format($order->total, 0, ',', '.'),
            $this->getStatusText($order->status),
        ];
    }

    protected function getStatusText($status)
    {
        $statuses = [
            'pending' => 'Menunggu Pembayaran',
            'processing' => 'Proses Verifikasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $statuses[$status] ?? $status;
    }
}
