@extends('layouts.app')

@section('title', 'Pesan Tiket - ' . $destination->name)

@section('content')
@php
    $initialQty = old('quantity', 12);
    $initialSubtotal = $destination->price * $initialQty;
    $initialDiscount = 0;
    $initialPromo = old('promo_code');
    if ($initialPromo) {
        $code = strtoupper(trim($initialPromo));
        if ($code === 'NAMINA10') {
            $initialDiscount = round($initialSubtotal * 0.1);
        } elseif ($code === 'NAMINA5') {
            $initialDiscount = round($initialSubtotal * 0.05);
        } elseif ($code === 'WELCOME50') {
            $initialDiscount = 50000;
        }
    }
    $initialTotal = max($initialSubtotal - $initialDiscount, 0);
@endphp
<div class="bg-base-200/40 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Progress Stepper -->
        <div class="mb-8 max-w-2xl mx-auto">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-200 -z-0"></div>
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1/3 h-1 bg-primary -z-0"></div>

                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-md shadow-primary/30">
                        1
                    </div>
                    <span class="text-xs font-bold text-primary mt-2">Data Pemesan</span>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-white border-2 border-gray-300 text-gray-500 flex items-center justify-center font-bold text-sm">
                        2
                    </div>
                    <span class="text-xs font-medium text-gray-500 mt-2">Transfer BCA</span>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-white border-2 border-gray-300 text-gray-500 flex items-center justify-center font-bold text-sm">
                        3
                    </div>
                    <span class="text-xs font-medium text-gray-500 mt-2">E-Tiket Terbit</span>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="max-w-6xl mx-auto mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm flex items-center" role="alert">
                <i class="fas fa-exclamation-circle text-red-500 text-lg mr-3"></i>
                <span class="text-sm font-medium text-red-800">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Form Fields (7 Cols) -->
            <div class="lg:col-span-7 bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 sm:p-8">
                <div class="border-b border-gray-100 pb-4 mb-6">
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Form Pemesanan Tiket</h1>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi data perjalanan Anda untuk melanjutkan ke instruksi transfer manual BCA.</p>
                </div>

                <form action="{{ route('booking.process') }}" method="POST" id="bookingForm" class="space-y-6">
                    @csrf
                    <input type="hidden" name="destination_id" value="{{ $destination->id }}">

                    <!-- Kontak Pemesan -->
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-2">
                            <i class="fas fa-user-circle text-primary"></i> Data Kontak Pemesan
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                                <div class="relative">
                                    <input type="text" name="customer_name" value="{{ auth()->user()->name }}" 
                                        class="input input-bordered w-full rounded-xl text-sm focus:input-primary" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Email Akun</label>
                                <div class="relative">
                                    <input type="email" value="{{ auth()->user()->email }}" 
                                        class="input input-bordered w-full rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed" disabled>
                                    <input type="hidden" name="customer_email" value="{{ auth()->user()->email }}">
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp / Telepon</label>
                                <input type="tel" name="customer_phone" value="{{ auth()->user()->phone ?? '' }}" 
                                    class="input input-bordered w-full rounded-xl text-sm focus:input-primary @error('customer_phone') input-error @enderror" 
                                    placeholder="Contoh: 081234567890" required>
                                <p class="text-[11px] text-gray-400 mt-1">Nomor ini digunakan tim kami untuk konfirmasi dan pengiriman invoice.</p>
                                @error('customer_phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal & Jumlah Tiket -->
                    <div class="pt-4 border-t border-gray-100">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-2">
                            <i class="fas fa-calendar-check text-primary"></i> Jadwal & Kuota Tiket
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Tanggal Keberangkatan</label>
                                <input type="date" 
                                    name="departure_date" 
                                    id="departure_date"
                                    min="{{ date('Y-m-d') }}" 
                                    class="input input-bordered w-full rounded-xl text-sm focus:input-primary @error('departure_date') input-error @enderror" 
                                    value="{{ old('departure_date') }}"
                                    required>
                                @error('departure_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Jumlah Tiket (Orang)</label>
                                <input type="number" name="quantity" id="quantity" min="12" max="20" value="{{ old('quantity', 12) }}" 
                                    class="input input-bordered w-full rounded-xl text-sm focus:input-primary @error('quantity') input-error @enderror" required>
                                <p class="text-[11px] text-primary font-semibold mt-1">Minimal kuota 12 orang (Maks. 20)</p>
                                @error('quantity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Kode Promo -->
                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Kode Promo (Opsional)</label>
                        <div class="flex gap-2">
                            <input type="text" name="promo_code" id="promo_code" 
                                class="input input-bordered flex-grow uppercase rounded-xl text-sm tracking-wider font-mono focus:input-primary" 
                                placeholder="Misal: NAMINA10, NAMINA5, WELCOME50"
                                value="{{ old('promo_code') }}">
                            <button type="button" id="apply_promo_btn" class="btn btn-outline btn-primary rounded-xl px-5">Gunakan</button>
                        </div>
                        <p id="promo_message" class="text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Khusus (Opsional)</label>
                        <textarea name="notes" rows="3" 
                            class="textarea textarea-bordered w-full rounded-xl text-sm focus:textarea-primary" 
                            placeholder="Tuliskan permintaan khusus seperti titik jemput alternatif atau kebutuhan rombongan...">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Dynamic explanation inside form if promo active -->
                    <div id="promo_explanation" class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs {{ $initialDiscount > 0 ? '' : 'hidden' }}">
                        <span class="font-bold block mb-0.5">💡 Promo Berhasil Terpasang</span>
                        Potongan harga otomatis dikalkulasikan ke dalam ringkasan biaya di kolom sebelah kanan.
                    </div>

                    <!-- Mobile Submit Buttons (Hidden on desktop, shown on mobile) -->
                    <div class="lg:hidden flex items-center justify-between pt-4 border-t border-gray-100">
                        <a href="{{ url()->previous() }}" class="btn btn-ghost rounded-xl">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-xl px-6">Lanjut ke Pembayaran</button>
                    </div>
                </form>
            </div>

            <!-- Right Column: Ticket Preview & Cost Summary (5 Cols) -->
            <div class="lg:col-span-5 lg:sticky lg:top-24 space-y-5">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/80 overflow-hidden">
                    <!-- Destination Preview Header -->
                    @php
                        $bookingImg = $destination->images->firstWhere('is_primary', true) ?? $destination->images->first();
                        $previewSrc = $bookingImg ? asset('storage/' . $bookingImg->path) : ($destination->image ? asset('storage/' . $destination->image) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&auto=format&fit=crop&q=80');
                    @endphp
                    <div class="relative h-36 bg-gray-100 overflow-hidden">
                        <img src="{{ $previewSrc }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-3 left-4 right-4 text-white">
                            <span class="badge badge-primary text-[10px] font-bold px-2 py-1 mb-1">{{ $destination->city }}, {{ $destination->province }}</span>
                            <h3 class="font-bold text-base text-white truncate drop-shadow-sm">{{ $destination->name }}</h3>
                        </div>
                    </div>

                    <!-- Cost Breakdown -->
                    <div class="p-5 sm:p-6 space-y-4">
                        <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                            <i class="fas fa-receipt text-primary"></i> Ringkasan Biaya
                        </h4>

                        <div class="space-y-2.5 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Harga Tiket Satuan</span>
                                <span class="font-medium text-gray-800">Rp <span id="summary_price">{{ number_format($destination->price, 0, ',', '.') }}</span></span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Jumlah Peserta</span>
                                <span class="font-medium text-gray-800"><span id="summary_quantity">{{ $initialQty }}</span> orang</span>
                            </div>
                            <div class="flex justify-between text-emerald-600 font-medium {{ $initialDiscount > 0 ? '' : 'hidden' }}" id="discount_row">
                                <span>Potongan Diskon</span>
                                <span>-Rp <span id="summary_discount">{{ number_format($initialDiscount, 0, ',', '.') }}</span></span>
                            </div>
                            <div class="border-t border-gray-100 pt-3 flex justify-between items-baseline">
                                <span class="font-bold text-gray-900">Total Pembayaran</span>
                                <span class="text-2xl font-extrabold text-primary">
                                    Rp <span id="summary_total">{{ number_format($initialTotal, 0, ',', '.') }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Submit CTA for Desktop -->
                        <div class="hidden lg:block pt-3 space-y-2">
                            <button type="button" onclick="document.getElementById('bookingForm').requestSubmit();" class="btn btn-primary btn-block rounded-xl font-bold h-12 shadow-md shadow-primary/25 text-base">
                                <span>Lanjutkan ke Pembayaran BCA</span>
                                <i class="fas fa-arrow-right ml-2 text-sm"></i>
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-ghost btn-block btn-sm text-gray-500 rounded-xl">
                                Kembali ke Detail Destinasi
                            </a>
                        </div>

                        <!-- Trust signals -->
                        <div class="border-t border-gray-100 pt-4 text-xs text-gray-500 space-y-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-shield-halved text-emerald-500"></i>
                                <span>Pembayaran aman via Transfer Rekening Resmi BCA</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-blue-500"></i>
                                <span>Verifikasi invoice manual maksimal 1x24 jam</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const pricePerTicket = {{ (int) $destination->price }};
    
    function init() {
        const departureDateInput = document.getElementById('departure_date');
        const form = document.getElementById('bookingForm');
        if (!form) return;
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const quantityInput = document.getElementById('quantity') || document.querySelector('input[name="quantity"]');
        const promoCodeInput = document.getElementById('promo_code');
        const applyPromoBtn = document.getElementById('apply_promo_btn');
        const promoMessage = document.getElementById('promo_message');
        
        const summaryQuantity = document.getElementById('summary_quantity');
        const summaryDiscount = document.getElementById('summary_discount');
        const discountRow = document.getElementById('discount_row');
        const summaryTotal = document.getElementById('summary_total');
        const promoExplanation = document.getElementById('promo_explanation');

        // Validate form before submission
        function validateForm(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...`;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            }
        }

        // Add input validation feedback
        function setupInputValidation() {
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.checkValidity()) {
                        this.classList.remove('border-red-500');
                        this.classList.add('border-green-300', 'focus:ring-green-200');
                    } else {
                        this.classList.add('border-red-500');
                        this.classList.remove('border-green-300', 'focus:ring-green-200');
                    }
                });
            });
        }

        // Dynamic Booking Cost Calculator
        function updateSummary() {
            if (!quantityInput || !summaryQuantity || !summaryTotal) return;
            
            const qty = parseInt(quantityInput.value) || 0;
            const subtotal = pricePerTicket * qty;
            
            let discount = 0;
            const code = promoCodeInput ? promoCodeInput.value.trim().toUpperCase() : '';
            
            if (code === 'NAMINA10') {
                discount = Math.round(subtotal * 0.1);
                if (promoMessage) {
                    promoMessage.textContent = 'Kode promo NAMINA10 berhasil digunakan! Diskon 10% terpasang.';
                    promoMessage.className = 'text-xs mt-1 text-green-600';
                    promoMessage.classList.remove('hidden');
                }
                if (discountRow) discountRow.classList.remove('hidden');
            } else if (code === 'NAMINA5') {
                discount = Math.round(subtotal * 0.05);
                if (promoMessage) {
                    promoMessage.textContent = 'Kode promo NAMINA5 berhasil digunakan! Diskon 5% terpasang.';
                    promoMessage.className = 'text-xs mt-1 text-green-600';
                    promoMessage.classList.remove('hidden');
                }
                if (discountRow) discountRow.classList.remove('hidden');
            } else if (code === 'WELCOME50') {
                discount = 50000;
                if (promoMessage) {
                    promoMessage.textContent = 'Kode promo WELCOME50 berhasil digunakan! Diskon Rp 50.000 terpasang.';
                    promoMessage.className = 'text-xs mt-1 text-green-600';
                    promoMessage.classList.remove('hidden');
                }
                if (discountRow) discountRow.classList.remove('hidden');
            } else if (code !== '') {
                discount = 0;
                if (promoMessage) {
                    promoMessage.textContent = 'Kode promo tidak valid.';
                    promoMessage.className = 'text-xs mt-1 text-red-500';
                    promoMessage.classList.remove('hidden');
                }
                if (discountRow) discountRow.classList.add('hidden');
            } else {
                discount = 0;
                if (promoMessage) promoMessage.classList.add('hidden');
                if (discountRow) discountRow.classList.add('hidden');
            }
            
            const formatter = new Intl.NumberFormat('id-ID');
            summaryQuantity.textContent = qty;
            if (summaryDiscount) summaryDiscount.textContent = formatter.format(discount);
            summaryTotal.textContent = formatter.format(Math.max(subtotal - discount, 0));
            
            // Toggle dynamic explanation box
            if (promoExplanation) {
                if (discount > 0) {
                    promoExplanation.classList.remove('hidden');
                } else {
                    promoExplanation.classList.add('hidden');
                }
            }
        }

        // Event Listeners
        form.addEventListener('submit', validateForm);
        setupInputValidation();
        
        if (quantityInput) {
            quantityInput.addEventListener('input', updateSummary);
            quantityInput.addEventListener('change', updateSummary);
        }
        
        if (applyPromoBtn) {
            applyPromoBtn.addEventListener('click', function(e) {
                e.preventDefault();
                updateSummary();
            });
        }
        
        // Initial run to sync visual elements
        updateSummary();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush
@endsection
