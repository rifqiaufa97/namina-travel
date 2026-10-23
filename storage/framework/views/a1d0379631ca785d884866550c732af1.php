<dialog id="registerModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box rounded-2xl p-6 sm:p-8 max-w-md shadow-2xl border border-gray-100">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3 text-gray-400 hover:text-gray-700">✕</button>
        </form>

        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-compass text-xl"></i>
            </div>
            <h3 class="font-extrabold text-xl text-gray-900 tracking-tight">Buat Akun Baru</h3>
            <p class="text-xs text-gray-500 mt-1">Daftar sekarang untuk mulai memesan paket wisata impian Anda.</p>
        </div>

        <form id="registerForm" method="POST" action="<?php echo e(route('register')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                        <i class="fas fa-user text-xs"></i>
                    </span>
                    <input type="text" name="name" placeholder="Nama Lengkap Anda" class="input input-bordered w-full pl-9 rounded-xl text-sm focus:input-primary" required />
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                        <i class="fas fa-envelope text-xs"></i>
                    </span>
                    <input type="email" name="email" placeholder="nama@email.com" class="input input-bordered w-full pl-9 rounded-xl text-sm focus:input-primary" required />
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                        <i class="fas fa-lock text-xs"></i>
                    </span>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" class="input input-bordered w-full pl-9 rounded-xl text-sm focus:input-primary" required />
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 pointer-events-none">
                        <i class="fas fa-shield-alt text-xs"></i>
                    </span>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password" class="input input-bordered w-full pl-9 rounded-xl text-sm focus:input-primary" required />
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full rounded-xl font-bold text-base h-11 shadow-md shadow-primary/25 mt-2">
                Daftar Sekarang
            </button>

            <div class="text-center pt-2">
                <span class="text-xs text-gray-500">Sudah memiliki akun? </span>
                <a href="#" class="text-xs font-bold text-primary hover:underline" onclick="document.getElementById('registerModal').close(); document.getElementById('loginModal').showModal();">
                    Masuk di sini
                </a>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop bg-black/40 backdrop-blur-xs">
        <button>close</button>
    </form>
</dialog>
<?php /**PATH C:\Users\Rifqi Aufa Rachmadi\Documents\Penulisan Ilmiah Aufa\nina-travel-2\resources\views/components/auth/register-modal.blade.php ENDPATH**/ ?>