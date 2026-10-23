@extends('admin.layouts.app')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="space-y-6">
    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Kelola & Verifikasi Pesanan</h1>
            <p class="text-xs text-gray-500 mt-1">Daftar transaksi pemesanan tiket liburan dan status verifikasi transfer BCA.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.orders.export') }}" class="btn btn-sm btn-outline rounded-xl font-semibold text-xs">
                <i class="fas fa-file-export mr-1.5 text-xs"></i> Ekspor Data
            </a>
            <a href="{{ route('admin.orders.create') }}" class="btn btn-sm btn-primary rounded-xl font-bold text-xs shadow-sm shadow-primary/25">
                <i class="fas fa-plus mr-1.5 text-xs"></i> Tambah Order
            </a>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-gray-50/70 text-gray-400 uppercase text-[11px] tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="py-3.5 px-6 font-bold w-12">No</th>
                        <th class="py-3.5 px-6 font-bold">Invoice / Tanggal</th>
                        <th class="py-3.5 px-6 font-bold">Pemesan</th>
                        <th class="py-3.5 px-6 font-bold">Paket Wisata</th>
                        <th class="py-3.5 px-6 font-bold">Tiket</th>
                        <th class="py-3.5 px-6 font-bold">Total Biaya</th>
                        <th class="py-3.5 px-6 font-bold">Status Bayar</th>
                        <th class="py-3.5 px-6 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="py-4 px-6 text-xs text-gray-400 font-mono">
                                {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-mono font-bold text-xs text-gray-900 bg-gray-100 px-2 py-0.5 rounded-md block w-fit mb-0.5">
                                    {{ $order->invoice_number }}
                                </span>
                                <span class="text-[11px] text-gray-400">
                                    {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                                </span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary font-bold text-xs flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($order->user->name ?? ($order->customer_name ?? 'U'), 0, 1)) }}
                                    </div>
                                    <div class="truncate max-w-xs">
                                        <span class="font-bold text-gray-900 block truncate">{{ $order->customer_name ?: ($order->user->name ?? 'Pelanggan') }}</span>
                                        <span class="text-xs text-gray-400 truncate block">{{ $order->customer_phone ?: ($order->user->phone ?? ($order->customer_email ?? '-')) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-medium text-gray-800">
                                    {{ $order->destination->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-xs text-gray-600">
                                <span class="font-bold text-gray-800">{{ $order->quantity }}</span> org
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-extrabold text-primary">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                @if($order->payment_status === 'pending_verification')
                                    <span class="badge badge-info text-white font-bold text-xs px-2.5 py-1.5 animate-pulse">
                                        <i class="fas fa-clock mr-1 text-[10px]"></i>Verifikasi
                                    </span>
                                @elseif($order->payment_status === 'paid' || $order->status === 'paid' || $order->status === 'completed')
                                    <span class="badge badge-success text-white font-bold text-xs px-2.5 py-1.5">
                                        <i class="fas fa-check-circle mr-1 text-[10px]"></i>Lunas
                                    </span>
                                @elseif($order->payment_status === 'rejected')
                                    <span class="badge badge-error text-white font-bold text-xs px-2.5 py-1.5">
                                        <i class="fas fa-times-circle mr-1 text-[10px]"></i>Ditolak
                                    </span>
                                @elseif($order->status === 'cancelled')
                                    <span class="badge badge-ghost text-xs text-gray-500 px-2.5 py-1.5">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="badge badge-warning text-white font-bold text-xs px-2.5 py-1.5">
                                        Menunggu Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-xs btn-ghost text-primary hover:bg-primary/10 rounded-lg p-1.5" title="Detail & Verifikasi">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-xs btn-ghost text-purple-600 hover:bg-purple-50 rounded-lg p-1.5" title="Cetak Invoice" target="_blank">
                                        <i class="fas fa-file-invoice text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-xs btn-ghost text-amber-600 hover:bg-amber-50 rounded-lg p-1.5" title="Edit Pesanan">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-ghost text-red-500 hover:bg-red-50 rounded-lg p-1.5" title="Hapus">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-gray-400">
                                <i class="fas fa-receipt text-3xl mb-2 text-gray-300"></i>
                                <p class="text-sm font-medium">Belum ada pesanan ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
