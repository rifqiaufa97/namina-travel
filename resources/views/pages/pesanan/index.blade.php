@extends('layouts.app')

@section('title', 'Pesanan Saya - Namina Travel')

@push('styles')
<style>
    .order-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pesanan Saya</h1>
        <a href="{{ route('destinations.index') }}" class="btn btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Pesan Tiket Baru
        </a>
    </div>
    
    @if($pesanan->count() > 0)
        <div class="space-y-6">
            @foreach($pesanan as $order)
                @php
                    $statusClasses = [
                        'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                        'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'cancelled' => 'bg-rose-100 text-rose-800 border-rose-200',
                        'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'confirmed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'expired' => 'bg-gray-100 text-gray-800 border-gray-200',
                        'refunded' => 'bg-purple-100 text-purple-800 border-purple-200',
                    ];
                    $statusClass = $statusClasses[strtolower($order->status)] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                @endphp

                <div class="bg-white rounded-2xl shadow-sm overflow-hidden order-card border border-gray-200/80 p-6 sm:p-7">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-mono font-semibold text-gray-500 uppercase tracking-wider">
                                    {{ $order->invoice_number ?? 'INV-' . $order->id }}
                                </span>
                                <span class="text-gray-300">•</span>
                                <span class="text-xs text-gray-400">
                                    <i class="far fa-calendar mr-1"></i>{{ $order->created_at->translatedFormat('d M Y, H:i') }}
                                </span>
                            </div>
                            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">
                                {{ $order->destination->name ?? 'Paket Wisata #' . $order->id }}
                            </h2>
                            @if(isset($order->destination->city))
                                <p class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                    <i class="fas fa-map-marker-alt text-primary text-[10px]"></i>
                                    {{ $order->destination->city }}, {{ $order->destination->province }}
                                </p>
                            @endif
                        </div>
                        <div class="md:text-right flex flex-row md:flex-col justify-between items-center md:items-end gap-2">
                            <div class="text-2xl font-extrabold text-primary">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </div>
                            <div>
                                @if($order->payment_status === 'pending_verification')
                                    <span class="badge badge-info text-white font-bold text-xs px-3 py-2">
                                        <i class="fas fa-clock mr-1 text-[10px]"></i>Verifikasi Admin
                                    </span>
                                @elseif($order->payment_status === 'rejected')
                                    <span class="badge badge-error text-white font-bold text-xs px-3 py-2">
                                        <i class="fas fa-times-circle mr-1 text-[10px]"></i>Bukti Ditolak
                                    </span>
                                @elseif($order->payment_status === 'paid')
                                    <span class="badge badge-success text-white font-bold text-xs px-3 py-2">
                                        <i class="fas fa-check-circle mr-1 text-[10px]"></i>Lunas
                                    </span>
                                @else
                                    <span class="badge badge-ghost font-semibold text-xs px-3 py-2 border {{ $statusClass }}">
                                        {{ $order->formatted_status }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($order->notes)
                        <div class="mt-4 p-3 bg-gray-50 rounded-xl text-xs text-gray-600 border border-gray-100 flex items-start gap-2">
                            <i class="fas fa-info-circle text-primary mt-0.5"></i>
                            <span>{{ $order->notes }}</span>
                        </div>
                    @endif
                    
                    <div class="mt-5 pt-4 border-t border-gray-100 text-xs text-gray-600">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                <span class="text-gray-400 font-semibold block text-[10px] uppercase">Jumlah Tiket</span>
                                <span class="font-bold text-gray-800 text-sm">{{ $order->quantity }} Orang</span>
                            </div>
                            <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                <span class="text-gray-400 font-semibold block text-[10px] uppercase">Keberangkatan</span>
                                <span class="font-bold text-gray-800 text-sm">
                                    @if($order->departure_date)
                                        {{ \Carbon\Carbon::parse($order->departure_date)->format('d M Y') }}
                                    @elseif($order->visit_date)
                                        {{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                <span class="text-gray-400 font-semibold block text-[10px] uppercase">Metode Bayar</span>
                                <span class="font-bold text-gray-800 text-sm">Transfer BCA</span>
                            </div>
                            <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                <span class="text-gray-400 font-semibold block text-[10px] uppercase">Status Pembayaran</span>
                                <span class="font-bold text-primary text-sm uppercase">
                                    {{ str_replace('_', ' ', $order->payment_status ?? 'pending') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5 pt-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div class="text-xs text-gray-400">
                            <span>ID Transaksi: </span><span class="font-mono text-gray-600 font-semibold">#{{ $order->id }}</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                            <a href="{{ route('payment.form', $order->id) }}" class="btn btn-sm btn-outline rounded-xl flex-1 sm:flex-none">
                                <i class="fas fa-eye mr-1 text-xs"></i> Rincian & Bukti
                            </a>
                            @if($order->payment_status === 'pending')
                                <a href="{{ route('payment.form', $order->id) }}" class="btn btn-sm btn-primary rounded-xl font-bold flex-1 sm:flex-none shadow-sm shadow-primary/25">
                                    <i class="fas fa-credit-card mr-1 text-xs"></i> Bayar / Upload Bukti
                                </a>
                            @elseif($order->payment_status === 'pending_verification')
                                <a href="{{ route('payment.form', $order->id) }}" class="btn btn-sm btn-info text-white rounded-xl font-bold flex-1 sm:flex-none">
                                    <i class="fas fa-clock mr-1 text-xs"></i> Cek Verifikasi
                                </a>
                            @elseif($order->payment_status === 'rejected')
                                <a href="{{ route('payment.form', $order->id) }}" class="btn btn-sm btn-error text-white rounded-xl font-bold flex-1 sm:flex-none">
                                    <i class="fas fa-upload mr-1 text-xs"></i> Upload Ulang Bukti
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $pesanan->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-12 text-center max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-ticket-alt text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-1">Belum Ada Pesanan</h3>
            <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                Anda belum melakukan pemesanan tiket liburan. Jelajahi pilihan paket wisata terbaik kami dan rencanakan petualangan Anda hari ini!
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('destinations.index') }}" class="btn btn-primary rounded-xl font-bold shadow-md shadow-primary/25">
                    <i class="fas fa-compass mr-1.5"></i> Jelajahi Destinasi
                </a>
                <a href="{{ route('tentang') }}" class="btn btn-outline rounded-xl">
                    <i class="fas fa-info-circle mr-1.5"></i> Cara Memesan
                </a>
            </div>
        </div>
    @endif
</div>

@if(session('success')) 
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
@endif
@endsection
