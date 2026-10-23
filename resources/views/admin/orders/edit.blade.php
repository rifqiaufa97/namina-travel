@extends('admin.layouts.app')

@section('title', 'Edit Pesanan #' . $order->id)

@section('content')
<form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- Top Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50/50 shadow-sm transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <div class="flex items-center space-x-2">
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Edit Pesanan #{{ $order->id }}</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-mono font-semibold bg-gray-100 text-gray-700">
                        {{ $order->invoice_number }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Ubah rincian peserta, tanggal berangkat, status pesanan, atau data pemesan.</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.show', $order) }}" class="px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-xl shadow-sm transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-blue-500/20 transition transform active:scale-95 cursor-pointer">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </div>

    <!-- 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column: Customer & Trip Details (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Customer Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100 flex items-center">
                    <i class="fas fa-user-circle text-blue-600 mr-2 text-base"></i> Data Pelanggan & Pemesan
                </h2>

                <div>
                    <label for="user_id" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Tautkan ke Akun Terdaftar <span class="text-rose-500">*</span>
                    </label>
                    <select id="user_id" name="user_id" required
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition font-medium bg-white">
                        <option value="">-- Pilih Akun User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="customer_name" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Nama Pemesan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition font-medium">
                    </div>

                    <div>
                        <label for="customer_email" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Email Pemesan <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email', $order->customer_email) }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    </div>

                    <div>
                        <label for="customer_phone" class="block text-xs font-bold text-gray-700 mb-1.5">
                            No. WhatsApp / HP <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $order->customer_phone) }}" required
                               class="w-full text-sm font-mono border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Catatan Khusus Pesanan
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full text-xs border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">{{ old('notes', $order->notes) }}</textarea>
                </div>
            </div>

            <!-- Destination & Schedule Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100 flex items-center">
                    <i class="fas fa-map-marked-alt text-indigo-600 mr-2 text-base"></i> Pilihan Destinasi & Jadwal
                </h2>

                <div>
                    <label for="destination_id" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Paket Wisata Tujuan <span class="text-rose-500">*</span>
                    </label>
                    <select id="destination_id" name="destination_id" required
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition font-medium bg-white">
                        <option value="">-- Pilih Paket Destinasi Wisata --</option>
                        @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}" {{ old('destination_id', $order->destination_id) == $destination->id ? 'selected' : '' }}>
                                {{ $destination->name }} - Rp {{ number_format($destination->price, 0, ',', '.') }} ({{ $destination->duration }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="quantity" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Jumlah Orang / Peserta <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $order->quantity) }}" min="1" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition font-bold">
                    </div>

                    <div>
                        <label for="departure_date" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Tanggal Keberangkatan <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="departure_date" name="departure_date" 
                               value="{{ old('departure_date', $order->departure_date ? \Carbon\Carbon::parse($order->departure_date)->format('Y-m-d') : '') }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition font-medium">
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Status & Publish (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100">
                    Status Pesanan & Pembayaran
                </h3>

                <div>
                    <label for="status" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Status Pesanan <span class="text-rose-500">*</span>
                    </label>
                    <select id="status" name="status" required
                            class="w-full text-xs font-bold border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                        <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div>
                    <label for="payment_status" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Status Pembayaran <span class="text-rose-500">*</span>
                    </label>
                    <select id="payment_status" name="payment_status" required
                            class="w-full text-xs font-bold border border-gray-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                        <option value="pending" {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>Belum Bayar (Pending)</option>
                        <option value="pending_verification" {{ old('payment_status', $order->payment_status) == 'pending_verification' ? 'selected' : '' }}>Perlu Verifikasi Bukti</option>
                        <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        <option value="rejected" {{ old('payment_status', $order->payment_status) == 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                        <option value="failed" {{ old('payment_status', $order->payment_status) == 'failed' ? 'selected' : '' }}>Gagal / Batal</option>
                    </select>
                </div>

                <div class="p-3 bg-blue-50/60 rounded-xl border border-blue-100 text-[11px] text-blue-800 space-y-1">
                    <p class="font-bold flex items-center">
                        <i class="fas fa-info-circle mr-1.5 text-blue-600"></i> Rekening Manual BCA:
                    </p>
                    <p class="font-mono">0060849510 a.n. RIFQI AUFA R.</p>
                </div>
            </div>

            <!-- Submit Button Card -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-3">
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-extrabold rounded-xl shadow-md shadow-blue-500/20 transition transform active:scale-95 cursor-pointer">
                    <i class="fas fa-save mr-2"></i> Perbarui Data Pesanan
                </button>
                <a href="{{ route('admin.orders.show', $order) }}" class="block text-center py-2 text-xs font-semibold text-gray-500 hover:text-gray-800 transition">
                    Kembali ke Detail Pesanan
                </a>
            </div>

        </div>

    </div>
</form>
@endsection