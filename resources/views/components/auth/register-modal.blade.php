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

        <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <div class="relative flex items-center">
                    <input type="text" name="name" placeholder="Nama Lengkap Anda" class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" required />
                    <span class="absolute left-3.5 flex items-center pointer-events-none text-gray-400 z-10">
                        <i class="fas fa-user text-sm"></i>
                    </span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                <div class="relative flex items-center">
                    <input type="email" name="email" placeholder="nama@email.com" class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" required />
                    <span class="absolute left-3.5 flex items-center pointer-events-none text-gray-400 z-10">
                        <i class="fas fa-envelope text-sm"></i>
                    </span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Password</label>
                <div class="relative flex items-center">
                    <input type="password" id="registerPassword" name="password" placeholder="Minimal 8 karakter" class="w-full pl-10 pr-11 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" required />
                    <span class="absolute left-3.5 flex items-center pointer-events-none text-gray-400 z-10">
                        <i class="fas fa-lock text-sm"></i>
                    </span>
                    <button type="button" onclick="togglePasswordVisibility('registerPassword', this)" class="absolute right-3.5 flex items-center text-gray-400 hover:text-gray-600 transition cursor-pointer z-10" title="Tampilkan / Sembunyikan Password">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                <div class="relative flex items-center">
                    <input type="password" id="registerConfirmPassword" name="password_confirmation" placeholder="Ulangi password" class="w-full pl-10 pr-11 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" required />
                    <span class="absolute left-3.5 flex items-center pointer-events-none text-gray-400 z-10">
                        <i class="fas fa-shield-alt text-sm"></i>
                    </span>
                    <button type="button" onclick="togglePasswordVisibility('registerConfirmPassword', this)" class="absolute right-3.5 flex items-center text-gray-400 hover:text-gray-600 transition cursor-pointer z-10" title="Tampilkan / Sembunyikan Password">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
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
