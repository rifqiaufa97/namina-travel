<?php $__env->startSection('title', 'Pembayaran Transfer BCA - ' . $order->invoice_number); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .bank-card {
        background: linear-gradient(135deg, #003366 0%, #00569c 60%, #0077c8 100%);
        color: #ffffff;
        box-shadow: 0 10px 25px -5px rgba(0, 51, 102, 0.4), 0 8px 10px -6px rgba(0, 51, 102, 0.3);
    }
    .upload-zone {
        transition: all 0.2s ease-in-out;
        border: 2px dashed #cbd5e1;
    }
    .upload-zone:hover, .upload-zone.dragover {
        border-color: #00569c;
        background-color: #f0f7ff;
    }
    .copy-btn {
        transition: all 0.2s ease;
    }
    .copy-btn:active {
        transform: scale(0.96);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8 max-w-5xl">
    
    <?php if(session('success')): ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm flex items-start" role="alert">
            <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="font-bold text-green-800">Berhasil</p>
                <p class="text-sm text-green-700"><?php echo e(session('success')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-start" role="alert">
            <svg class="h-6 w-6 text-red-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="font-bold text-red-800">Terjadi Kesalahan</p>
                <p class="text-sm text-red-700"><?php echo e(session('error')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if(isset($errors) && $errors->any()): ?>
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
            <div class="flex items-center mb-1">
                <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="font-bold text-red-800">Mohon periksa formulir:</p>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700 ml-5">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="text-center mb-8">
        <span class="inline-block py-1 px-3 rounded-full text-xs font-semibold uppercase tracking-wider bg-blue-100 text-blue-800 mb-2">
            Metode Pembayaran Transfer Manual
        </span>
        <h1 class="text-3xl font-extrabold text-gray-900">Pembayaran Pesanan</h1>
        <p class="text-gray-600 mt-1">Invoice: <span class="font-mono font-semibold text-gray-800"><?php echo e($order->invoice_number); ?></span></p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Kolom Kiri: Kartu Rekening BCA & Rincian Pesanan -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Kartu Transfer Bank BCA -->
            <div class="bank-card rounded-2xl p-6 sm:p-7 relative overflow-hidden">
                <!-- Background pattern / watermark -->
                <div class="absolute -right-8 -bottom-8 w-44 h-44 rounded-full bg-white opacity-5 pointer-events-none"></div>
                <div class="absolute -right-2 -top-8 w-32 h-32 rounded-full bg-white opacity-5 pointer-events-none"></div>

                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-xs font-semibold text-blue-200 tracking-wider uppercase">Transfer Bank Tujuan</p>
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-white mt-0.5">Bank Central Asia</h2>
                    </div>
                    <div class="bg-white rounded-xl px-3 py-2 shadow-md flex items-center justify-center">
                        <img src="<?php echo e(asset($bank['logo'] ?? 'images/banks/bca.png')); ?>" alt="Logo Bank BCA" class="h-8 sm:h-9 w-auto object-contain">
                    </div>
                </div>

                <!-- Nomor Rekening -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 mb-4 border border-white/15">
                    <p class="text-xs text-blue-200 uppercase font-medium">Nomor Rekening BCA</p>
                    <div class="flex items-center justify-between mt-1">
                        <span id="accountNumber" class="text-2xl sm:text-3xl font-mono font-bold tracking-wider text-white">0060849510</span>
                        <button type="button" 
                                onclick="copyToClipboard('0060849510', 'btnCopyRekening')" 
                                id="btnCopyRekening"
                                class="copy-btn inline-flex items-center px-3 py-1.5 rounded-lg bg-white text-blue-900 text-xs font-bold hover:bg-blue-50 shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span>Salin No. Rek</span>
                        </button>
                    </div>
                    <p class="text-xs text-blue-100 mt-2">
                        Atas Nama: <span class="font-bold text-white tracking-wide">RIFQI AUFA RACHMADI</span>
                    </p>
                </div>

                <!-- Total Nominal Transfer -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/15">
                    <p class="text-xs text-blue-200 uppercase font-medium">Total yang Harus Ditransfer</p>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-2xl sm:text-3xl font-extrabold text-white">
                            Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?>

                        </span>
                        <button type="button" 
                                onclick="copyToClipboard('<?php echo e((int)$order->total); ?>', 'btnCopyNominal')" 
                                id="btnCopyNominal"
                                class="copy-btn inline-flex items-center px-3 py-1.5 rounded-lg bg-white/20 hover:bg-white/30 text-white text-xs font-semibold border border-white/30">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span>Salin Nominal</span>
                        </button>
                    </div>
                    <p class="text-[11px] text-blue-200 mt-1">
                        *Pastikan mentransfer sesuai nominal tepat agar verifikasi berjalan cepat.
                    </p>
                </div>
            </div>

            <!-- Petunjuk Langkah Pembayaran -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Cara Pembayaran Manual BCA
                </h3>
                <ol class="space-y-3 text-sm text-gray-600 list-decimal list-inside">
                    <li class="leading-relaxed">
                        Buka aplikasi <span class="font-semibold text-gray-800">BCA Mobile / myBCA / KlikBCA</span> atau kunjungi ATM BCA terdekat.
                    </li>
                    <li class="leading-relaxed">
                        Pilih menu <span class="font-semibold text-gray-800">Transfer Antar Rekening BCA</span>.
                    </li>
                    <li class="leading-relaxed">
                        Masukkan nomor rekening: <span class="font-mono font-bold text-blue-900">0060849510</span> a.n. <span class="font-bold text-gray-800">RIFQI AUFA RACHMADI</span>.
                    </li>
                    <li class="leading-relaxed">
                        Masukkan nominal transfer tepat sejumlah: <span class="font-bold text-blue-900">Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?></span>.
                    </li>
                    <li class="leading-relaxed">
                        Simpan foto struk transfer atau screenshot bukti pembayaran berhasil.
                    </li>
                    <li class="leading-relaxed">
                        Unggah bukti transfer melalui formulir di halaman ini untuk diverifikasi oleh admin.
                    </li>
                </ol>
            </div>

            <!-- Rincian Pesanan Singkat -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Rincian Paket Wisata</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Destinasi</span>
                        <span class="font-semibold text-gray-800"><?php echo e($order->destination->name ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Tanggal Keberangkatan</span>
                        <span class="font-medium text-gray-800">
                            <?php echo e($order->departure_date ? \Carbon\Carbon::parse($order->departure_date)->format('d M Y') : '-'); ?>

                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Jumlah Tiket</span>
                        <span class="font-medium text-gray-800"><?php echo e($order->quantity); ?> Orang</span>
                    </div>
                    <div class="flex justify-between py-2 pt-3">
                        <span class="text-base font-bold text-gray-800">Total Pembayaran</span>
                        <span class="text-lg font-bold text-blue-600">Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Upload Bukti Pembayaran -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-sm border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Upload Bukti Transfer</h3>
                <p class="text-xs text-gray-500 mb-6">Unggah tangkapan layar (screenshot) m-Banking atau foto struk ATM BCA Anda.</p>

                
                <?php if($order->isPaid()): ?>
                    <div class="bg-green-50 border border-green-200 rounded-xl p-5 text-center">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-green-800 text-lg">Pembayaran Telah Lunas</h4>
                        <p class="text-xs text-green-600 mt-1">Pembayaran untuk pesanan ini telah diverifikasi dan diterima.</p>
                        <div class="mt-4">
                            <a href="<?php echo e(route('pesanan-saya')); ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700">
                                Lihat Tiket di Pesanan Saya
                            </a>
                        </div>
                    </div>
                <?php elseif($order->payment_status === 'pending_verification'): ?>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6 text-center">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-blue-900 text-base">Sedang Diverifikasi</h4>
                        <p class="text-xs text-blue-700 mt-1">
                            Bukti transfer Anda telah tersimpan dan sedang dalam antrean verifikasi oleh admin (estimasi 1x24 jam).
                        </p>
                        <?php if($order->payment_proof): ?>
                            <div class="mt-4 pt-4 border-t border-blue-200 text-left">
                                <p class="text-xs font-semibold text-blue-900 mb-2">Bukti Pembayaran Terunggah:</p>
                                <a href="<?php echo e(asset('storage/' . $order->payment_proof)); ?>" target="_blank" class="block relative group overflow-hidden rounded-lg border border-blue-300">
                                    <img src="<?php echo e(asset('storage/' . $order->payment_proof)); ?>" alt="Bukti Transfer" class="w-full h-40 object-cover object-top group-hover:scale-105 transition duration-200">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-white text-xs font-semibold opacity-0 group-hover:opacity-100 transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Klik untuk perbesar
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if(!$order->isPaid()): ?>
                    
                    <form action="<?php echo e(route('payment.upload-proof', $order->id)); ?>" method="POST" enctype="multipart/form-data" id="paymentProofForm">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                <?php echo e($order->payment_status === 'pending_verification' ? 'Unggah Ulang Bukti (Jika Salah)' : 'Pilih File Bukti Pembayaran'); ?>

                            </label>

                            <!-- Drag and drop zone -->
                            <div class="upload-zone rounded-xl p-6 text-center cursor-pointer bg-gray-50" id="dropZone" onclick="document.getElementById('payment_proof').click()">
                                <input type="file" name="payment_proof" id="payment_proof" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewImage(this)">
                                
                                <div id="uploadPrompt">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-700">Klik untuk memilih file foto</p>
                                    <p class="text-xs text-gray-500 mt-1">atau seret file ke area ini</p>
                                    <p class="text-[11px] text-gray-400 mt-2">Format: JPG, JPEG, PNG, WEBP (Maksimal 5MB)</p>
                                </div>

                                <!-- Live Preview Container -->
                                <div id="imagePreviewContainer" class="hidden">
                                    <div class="relative inline-block max-w-full">
                                        <img id="imagePreview" src="" alt="Preview Bukti" class="max-h-56 rounded-lg shadow-sm border border-gray-200 mx-auto">
                                        <button type="button" onclick="cancelPreview(event)" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-md" title="Hapus foto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p id="fileName" class="text-xs font-medium text-gray-700 mt-2 truncate max-w-xs mx-auto"></p>
                                    <p class="text-[11px] text-blue-600 mt-0.5 font-medium">Klik untuk mengganti foto</p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="btnSubmitProof" class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span><?php echo e($order->payment_status === 'pending_verification' ? 'Kirim Ulang Bukti Transfer' : 'Kirim Bukti Pembayaran'); ?></span>
                        </button>
                    </form>
                <?php endif; ?>

                <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <a href="<?php echo e(route('pesanan-saya')); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Ke Pesanan Saya
                    </a>
                    <span class="flex items-center text-gray-400">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Verifikasi Aman
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="copyToast" class="fixed bottom-6 right-6 bg-gray-900 text-white text-sm px-4 py-3 rounded-xl shadow-xl flex items-center space-x-2 opacity-0 pointer-events-none transition-all duration-300 z-50">
    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
    <span id="toastMessage">Tersalin ke clipboard!</span>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Copy to clipboard helper
    function copyToClipboard(text, buttonId) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Berhasil disalin: ' + text);
                animateButton(buttonId);
            }).catch(err => {
                fallbackCopy(text, buttonId);
            });
        } else {
            fallbackCopy(text, buttonId);
        }
    }

    function fallbackCopy(text, buttonId) {
        const tempInput = document.createElement('textarea');
        tempInput.value = text;
        tempInput.style.position = 'fixed';
        tempInput.style.opacity = '0';
        document.body.appendChild(tempInput);
        tempInput.focus();
        tempInput.select();
        try {
            document.execCommand('copy');
            showToast('Berhasil disalin: ' + text);
            animateButton(buttonId);
        } catch (err) {
            alert('Gagal menyalin otomatis. Silakan salin manual: ' + text);
        }
        document.body.removeChild(tempInput);
    }

    function animateButton(buttonId) {
        const btn = document.getElementById(buttonId);
        if (!btn) return;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = `<svg class="w-4 h-4 mr-1.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg><span class="text-green-700">Tersalin!</span>`;
        setTimeout(() => {
            btn.innerHTML = originalHtml;
        }, 2200);
    }

    function showToast(message) {
        const toast = document.getElementById('copyToast');
        const toastMsg = document.getElementById('toastMessage');
        if (!toast) return;
        toastMsg.textContent = message;
        toast.classList.remove('opacity-0', 'pointer-events-none');
        toast.classList.add('opacity-100');
        setTimeout(() => {
            toast.classList.remove('opacity-100');
            toast.classList.add('opacity-0', 'pointer-events-none');
        }, 2500);
    }

    // Image preview helper
    function previewImage(input) {
        const prompt = document.getElementById('uploadPrompt');
        const container = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');
        const fileName = document.getElementById('fileName');

        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validation: Max 5MB
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file maksimal 5MB.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                prompt.classList.add('hidden');
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function cancelPreview(e) {
        e.stopPropagation();
        const input = document.getElementById('payment_proof');
        const prompt = document.getElementById('uploadPrompt');
        const container = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');

        input.value = '';
        preview.src = '';
        container.classList.add('hidden');
        prompt.classList.remove('hidden');
    }

    // Drag and drop handlers
    const dropZone = document.getElementById('dropZone');
    if (dropZone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('dragover');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            const input = document.getElementById('payment_proof');
            if (files && files.length > 0) {
                input.files = files;
                previewImage(input);
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rifqi Aufa Rachmadi\Documents\Penulisan Ilmiah Aufa\nina-travel-2\resources\views/pages/booking/payment.blade.php ENDPATH**/ ?>