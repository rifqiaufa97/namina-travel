@extends('layouts.app')

@section('title', 'Pesanan Berhasil - ' . $order->invoice_number)

@section('content')
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-base-200/40 min-h-screen py-10 px-4 sm:px-6">
    <div class="max-w-3xl mx-auto">
        <!-- Main Card -->
        <div class="bg-white rounded-3xl shadow-lg border border-gray-200/80 overflow-hidden">
            <!-- Header Hero -->
            <div class="bg-gradient-to-br from-emerald-500 to-teal-700 px-6 py-10 text-center text-white relative">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mx-auto mb-4 ring-8 ring-white/10 checkmark">
                    <i class="fas fa-check text-white text-3xl"></i>
                </div>
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider rounded-full mb-2">
                    Pemesanan Berhasil Dibuat
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-2">Terima Kasih Atas Pesanan Anda!</h1>
                <p class="text-emerald-100 text-sm max-w-lg mx-auto">
                    Invoice pesanan perjalanan Anda telah dibuat. Silakan selesaikan proses transfer manual BCA agar tim kami dapat segera menerbitkan tiket.
                </p>
            </div>

            <!-- Receipt & Details -->
            <div class="p-6 sm:p-8 space-y-6">
                <!-- Invoice Badge Banner -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 gap-3">
                    <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Nomor Invoice</span>
                        <span class="text-lg font-mono font-bold text-gray-900">{{ $order->invoice_number }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-warning font-semibold text-xs px-3 py-2">
                            Status: {{ $order->formatted_status }}
                        </span>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl border border-gray-100 bg-white">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Destinasi Wisata</span>
                        <span class="font-bold text-gray-900 text-base flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                            {{ $order->destination->name }}
                        </span>
                    </div>

                    <div class="p-4 rounded-xl border border-gray-100 bg-white">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Jadwal Keberangkatan</span>
                        <span class="font-bold text-gray-900 text-base flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-primary"></i>
                            @if($order->start_date && $order->end_date)
                                {{ $order->start_date->translatedFormat('d F Y') }} - {{ $order->end_date->translatedFormat('d F Y') }}
                            @elseif($order->departure_date)
                                {{ $order->departure_date->translatedFormat('d F Y') }}
                            @else
                                Sesuai Konfirmasi Admin
                            @endif
                        </span>
                    </div>

                    <div class="p-4 rounded-xl border border-gray-100 bg-white">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Jumlah Tiket / Peserta</span>
                        <span class="font-bold text-gray-900 text-base flex items-center gap-2">
                            <i class="fas fa-users text-primary"></i>
                            {{ $order->quantity }} Orang
                        </span>
                    </div>

                    <div class="p-4 rounded-xl border border-emerald-100 bg-emerald-50/50">
                        <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider block mb-1">Total Pembayaran</span>
                        <span class="text-xl font-extrabold text-primary">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Next Steps Visual -->
                <div class="pt-4 border-t border-gray-100">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-4">Langkah Selanjutnya</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center mx-auto mb-2">1</div>
                            <span class="font-bold text-xs text-gray-900 block mb-1">Transfer ke BCA</span>
                            <p class="text-[11px] text-gray-500 leading-relaxed">Kirim sesuai nominal ke Rekening BCA Namina Travel.</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center mx-auto mb-2">2</div>
                            <span class="font-bold text-xs text-gray-900 block mb-1">Upload Bukti</span>
                            <p class="text-[11px] text-gray-500 leading-relaxed">Unggah struk atau screenshot pembayaran Anda.</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center mx-auto mb-2">3</div>
                            <span class="font-bold text-xs text-gray-900 block mb-1">Tiket Terbit</span>
                            <p class="text-[11px] text-gray-500 leading-relaxed">Admin memverifikasi dan tiket siap di Pesanan Saya.</p>
                        </div>
                    </div>
                </div>

                <!-- Action CTA -->
                <div class="pt-2 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('payment.form', $order->id) }}" class="btn btn-primary flex-1 rounded-xl h-12 text-base font-bold shadow-md shadow-primary/25">
                        <i class="fas fa-credit-card mr-2"></i> Bayar & Upload Bukti BCA
                    </a>
                    <a href="{{ route('pesanan-saya') }}" class="btn btn-outline border-gray-300 hover:border-primary text-gray-700 rounded-xl h-12">
                        <i class="fas fa-receipt mr-2"></i> Lihat Pesanan Saya
                    </a>
                </div>
            </div>

            <!-- Footer Help Note -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 text-center text-xs text-gray-500">
                <span>Perlu bantuan cepat? Hubungi CS WhatsApp kami di </span>
                <a href="https://wa.me/6281282069087?text=Halo%20Namina%20Travel,%20saya%20sudah%20membuat%20pesanan%20dengan%20nomor%20invoice%20{{ $order->invoice_number }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="font-bold text-emerald-600 hover:underline">
                    0812-8206-9087
                </a>
            </div>
        </div>
    </div>
</div>
@push('styles')
<style>
    @keyframes checkmark {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    .checkmark {
        animation: checkmark 0.6s ease-in-out;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Print the page if needed
        if (window.location.search.includes('print=1')) {
            window.print();
        }
        
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            const flashMessages = document.querySelectorAll('.alert');
            flashMessages.forEach(function(message) {
                message.style.transition = 'opacity 0.5s';
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 500);
            });
        }, 5000);
    });
</script>
@endpush

@endsection
