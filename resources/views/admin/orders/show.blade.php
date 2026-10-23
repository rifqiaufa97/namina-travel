@extends('admin.layouts.app')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50/50 shadow-sm transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <div class="flex items-center space-x-2">
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Pesanan #{{ $order->id }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                        {{ $order->invoice_number }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Dipesan pada {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }} WIB</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-purple-500/20 transition transform active:scale-95">
                <i class="fas fa-file-invoice mr-2"></i> Cetak / Buka Invoice
            </a>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-xl shadow-sm transition">
                Daftar Pesanan
            </a>
        </div>
    </div>

    <!-- Main Content 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column: Order & Customer Details (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Customer Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center">
                        <i class="fas fa-user-circle text-blue-600 mr-2 text-base"></i> Informasi Pelanggan
                    </h2>
                    @if($order->customer_phone || ($order->user->phone ?? false))
                        @php
                            $rawPhone = $order->customer_phone ?: ($order->user->phone ?? '');
                            $waPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $rawPhone));
                        @endphp
                        <a href="https://wa.me/{{ $waPhone }}" target="_blank" class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition border border-emerald-200">
                            <i class="fab fa-whatsapp mr-1.5 text-emerald-600"></i> Hubungi WA
                        </a>
                    @endif
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white font-extrabold text-xl flex items-center justify-center shadow-md shadow-blue-500/10 flex-shrink-0">
                        {{ strtoupper(substr($order->customer_name ?: ($order->user->name ?? 'G'), 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0 space-y-2">
                        <div>
                            <h3 class="text-base font-bold text-gray-900 leading-tight">
                                {{ $order->customer_name ?: ($order->user->name ?? '-') }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Akun Terdaftar: {{ $order->user ? $order->user->email : 'Tamu (Guest)' }}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-xs">
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 flex items-center space-x-3">
                                <i class="fas fa-envelope text-gray-400"></i>
                                <span class="text-gray-700 font-medium truncate">{{ $order->customer_email ?: ($order->user->email ?? '-') }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 flex items-center space-x-3">
                                <i class="fas fa-phone-alt text-gray-400"></i>
                                <span class="text-gray-700 font-mono font-medium">{{ $order->customer_phone ?: ($order->user->phone ?? '-') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($order->notes)
                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1.5">Catatan Khusus dari Pemesan:</span>
                        <div class="p-3.5 bg-amber-50/60 border border-amber-100 rounded-xl text-xs text-amber-900 leading-relaxed font-sans">
                            {{ $order->notes }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Destination & Trip Summary Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100 flex items-center">
                    <i class="fas fa-map-marked-alt text-indigo-600 mr-2 text-base"></i> Rincian Paket Wisata & Perjalanan
                </h2>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                    @if($order->destination && $order->destination->image)
                        <img src="{{ asset('storage/' . $order->destination->image) }}" alt="{{ $order->destination->name }}" class="w-20 h-20 rounded-xl object-cover border border-gray-200 flex-shrink-0 shadow-sm">
                    @else
                        <div class="w-20 h-20 rounded-xl bg-gray-200 text-gray-400 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-image text-2xl"></i>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <span class="inline-block px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-blue-700 bg-blue-100 rounded-md mb-1">
                            {{ $order->destination->duration ?? 'Durasi Fleksibel' }}
                        </span>
                        <h3 class="text-base font-extrabold text-gray-900 truncate">
                            {{ $order->destination->name ?? 'Paket Tidak Ditemukan' }}
                        </h3>
                        <p class="text-xs text-gray-500 flex items-center mt-0.5">
                            <i class="fas fa-location-dot mr-1.5 text-red-500"></i> {{ $order->destination->location ?? 'Indonesia' }}
                        </p>
                    </div>
                </div>

                <!-- Trip Schedule & Pax Metrics -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100 text-center">
                        <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider block">Tanggal Berangkat</span>
                        <span class="text-sm font-extrabold text-gray-900 mt-1 block">
                            {{ $order->departure_date ? \Carbon\Carbon::parse($order->departure_date)->format('d M Y') : '-' }}
                        </span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100 text-center">
                        <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider block">Jumlah Peserta</span>
                        <span class="text-sm font-extrabold text-gray-900 mt-1 block">
                            {{ $order->quantity }} Orang / Pax
                        </span>
                    </div>
                    <div class="col-span-2 sm:col-span-1 p-3.5 rounded-xl bg-gray-50 border border-gray-100 text-center">
                        <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider block">Harga Satuan</span>
                        <span class="text-sm font-extrabold text-gray-900 mt-1 block">
                            Rp {{ number_format($order->destination->price ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Price Breakdown Table -->
                <div class="pt-3 border-t border-gray-100">
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal ({{ $order->quantity }} x Rp {{ number_format($order->destination->price ?? 0, 0, ',', '.') }})</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format(($order->destination->price ?? 0) * $order->quantity, 0, ',', '.') }}</span>
                        </div>
                        @php
                            $subtotalCalc = ($order->destination->price ?? 0) * $order->quantity;
                            $diff = $subtotalCalc - $order->total;
                        @endphp
                        @if($diff > 0)
                            <div class="flex justify-between text-emerald-600 font-medium">
                                <span>Potongan / Diskon</span>
                                <span>- Rp {{ number_format($diff, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                            <span class="text-sm font-bold text-gray-900">Total Tagihan Final</span>
                            <span class="text-xl font-black text-blue-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Manual BCA Payment & Verification Box (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Card Header with BCA Brand -->
                <div class="p-6 bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white p-1.5 rounded-lg shadow-sm">
                                <img src="{{ asset('images/banks/bca.png') }}" alt="Bank BCA" class="h-5 w-auto">
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold tracking-wide">Transfer Manual BCA</h3>
                                <p class="text-[11px] text-blue-200">Verifikasi Rekening Resmi</p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div>
                            @if($order->payment_status === 'paid')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                                    <i class="fas fa-check-circle mr-1.5"></i> Lunas
                                </span>
                            @elseif($order->payment_status === 'pending_verification')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-400/30 animate-pulse">
                                    <i class="fas fa-clock mr-1.5"></i> Verifikasi
                                </span>
                            @elseif($order->payment_status === 'rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-400/30">
                                    <i class="fas fa-times-circle mr-1.5"></i> Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-500/20 text-slate-300 border border-slate-400/30">
                                    <i class="fas fa-hourglass-start mr-1.5"></i> Menunggu Bayar
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Target Account info -->
                    <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between text-xs">
                        <span class="text-blue-200">Rekening Tujuan:</span>
                        <span class="font-mono font-bold text-white tracking-wider">0060849510 (RIFQI AUFA R.)</span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6 space-y-5">
                    
                    <div class="space-y-2 text-xs text-gray-600 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Waktu Upload Bukti:</span>
                            <span class="font-semibold text-gray-800">
                                {{ $order->payment_date ? \Carbon\Carbon::parse($order->payment_date)->format('d M Y, H:i') . ' WIB' : 'Belum diupload' }}
                            </span>
                        </div>
                        @if($order->paid_at)
                            <div class="flex justify-between pt-1 border-t border-gray-200">
                                <span class="text-gray-500">Diverifikasi Pada:</span>
                                <span class="font-semibold text-emerald-700">
                                    {{ \Carbon\Carbon::parse($order->paid_at)->format('d M Y, H:i') . ' WIB' }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Bukti Transfer Preview Frame -->
                    <div>
                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wider block mb-2 flex items-center justify-between">
                            <span>Foto Bukti Struk Transfer:</span>
                            @if($order->payment_proof)
                                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-[11px] font-semibold">
                                    Buka Penuh <i class="fas fa-external-link-alt ml-1"></i>
                                </a>
                            @endif
                        </span>

                        @if($order->payment_proof)
                            <div class="relative group rounded-xl overflow-hidden border border-gray-200 bg-gray-900 flex items-center justify-center min-h-[220px]">
                                <img src="{{ asset('storage/' . $order->payment_proof) }}" 
                                     alt="Bukti Transfer BCA" 
                                     class="max-h-72 w-auto object-contain transition duration-300 group-hover:scale-105 cursor-pointer"
                                     onclick="window.open('{{ asset('storage/' . $order->payment_proof) }}', '_blank')">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                    <span class="px-3 py-1.5 rounded-lg bg-white/90 text-gray-900 text-xs font-bold shadow-lg">
                                        <i class="fas fa-search-plus mr-1"></i> Klik untuk Perbesar
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="rounded-xl border-2 border-dashed border-gray-200 p-8 text-center bg-gray-50/50">
                                <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center mb-2">
                                    <i class="fas fa-receipt text-xl"></i>
                                </div>
                                <p class="text-xs font-semibold text-gray-600">Belum Ada Bukti Transfer</p>
                                <p class="text-[11px] text-gray-400 mt-1">Pelanggan belum mengunggah struk transfer manual.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Action Verification Forms -->
                    @if($order->payment_proof && $order->payment_status !== 'paid')
                        <div class="pt-4 border-t border-gray-100 space-y-3">
                            <span class="text-xs font-bold text-gray-800 uppercase tracking-wider block">Panel Keputusan Verifikasi:</span>
                            
                            <!-- Form Approve -->
                            <form action="{{ route('admin.orders.verify-payment', $order) }}" method="POST" onsubmit="return confirm('Pastikan dana Rp {{ number_format($order->total, 0, ',', '.') }} telah benar-benar masuk ke rekening BCA Anda. Lanjutkan persetujuan?')">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white font-bold rounded-xl shadow-sm shadow-emerald-500/20 text-xs flex items-center justify-center transition active:scale-95 cursor-pointer">
                                    <i class="fas fa-check-circle mr-2 text-sm"></i> Verifikasi & Setujui Pembayaran (Lunas)
                                </button>
                            </form>

                            <!-- Form Reject -->
                            <form action="{{ route('admin.orders.verify-payment', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak bukti transfer ini?')">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <div class="space-y-2 pt-1">
                                    <input type="text" name="notes" placeholder="Tulis alasan penolakan (misal: nominal tidak sesuai / mutasi tidak ditemukan)..." class="w-full text-xs border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-rose-500 focus:outline-none transition">
                                    <button type="submit" class="w-full py-2.5 px-4 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl border border-rose-200 text-xs flex items-center justify-center transition active:scale-95 cursor-pointer">
                                        <i class="fas fa-times-circle mr-1.5"></i> Tolak Bukti Transfer
                                    </button>
                                </div>
                            </form>
                        </div>
                    @elseif($order->payment_status === 'paid')
                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <div class="text-xs">
                                <p class="font-extrabold text-emerald-900">Pembayaran Terverifikasi Lunas</p>
                                <p class="text-emerald-700 mt-0.5">Tiket perjalanan sah dan order siap diberangkatkan.</p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>

    </div>
</div>
@endsection