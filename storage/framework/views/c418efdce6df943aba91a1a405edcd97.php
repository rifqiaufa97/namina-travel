<dialog id="loginModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box rounded-2xl p-6 sm:p-8 max-w-md shadow-2xl border border-gray-100">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3 text-gray-400 hover:text-gray-700">✕</button>
        </form>

        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-compass text-xl"></i>
            </div>
            <h3 class="font-extrabold text-xl text-gray-900 tracking-tight">Selamat Datang Kembali</h3>
            <p class="text-xs text-gray-500 mt-1">Masuk ke akun Anda untuk memesan tiket dan memantau status perjalanan.</p>
        </div>

        <form id="loginForm" method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
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
                    <input type="password" name="password" placeholder="••••••••" class="input input-bordered w-full pl-9 rounded-xl text-sm focus:input-primary" required />
                </div>
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="cursor-pointer flex items-center gap-2">
                    <input type="checkbox" name="remember" value="1" class="checkbox checkbox-primary checkbox-xs rounded" />
                    <span class="text-gray-600 font-medium">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-full rounded-xl font-bold text-base h-11 shadow-md shadow-primary/25 mt-2">
                Masuk Sekarang
            </button>

            <div class="text-center pt-2">
                <span class="text-xs text-gray-500">Belum memiliki akun? </span>
                <a href="#" class="text-xs font-bold text-primary hover:underline" onclick="document.getElementById('loginModal').close(); document.getElementById('registerModal').showModal();">
                    Daftar di sini
                </a>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop bg-black/40 backdrop-blur-xs">
        <button>close</button>
    </form>
</dialog>
<?php /**PATH C:\Users\Rifqi Aufa Rachmadi\Documents\Penulisan Ilmiah Aufa\nina-travel-2\resources\views/components/auth/login-modal.blade.php ENDPATH**/ ?>