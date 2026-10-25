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
                <div class="relative w-full" style="position: relative; width: 100%;">
                    <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); z-index: 10; pointer-events: none; display: flex; align-items: center; justify-content: center; width: 20px; color: #9ca3af;">
                        <i class="fas fa-user" style="font-size: 14px;"></i>
                    </span>
                    <input type="text" name="name" placeholder="Nama Lengkap Anda" 
                           class="w-full py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" 
                           style="width: 100%; padding-left: 42px; padding-right: 16px; height: 44px; box-sizing: border-box;"
                           required />
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                <div class="relative w-full" style="position: relative; width: 100%;">
                    <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); z-index: 10; pointer-events: none; display: flex; align-items: center; justify-content: center; width: 20px; color: #9ca3af;">
                        <i class="fas fa-envelope" style="font-size: 14px;"></i>
                    </span>
                    <input type="email" name="email" placeholder="nama@email.com" 
                           class="w-full py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" 
                           style="width: 100%; padding-left: 42px; padding-right: 16px; height: 44px; box-sizing: border-box;"
                           required />
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Password</label>
                <div class="relative w-full" style="position: relative; width: 100%;">
                    <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); z-index: 10; pointer-events: none; display: flex; align-items: center; justify-content: center; width: 20px; color: #9ca3af;">
                        <i class="fas fa-lock" style="font-size: 14px;"></i>
                    </span>
                    <input type="password" id="registerPassword" name="password" placeholder="Minimal 8 karakter" 
                           class="w-full py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" 
                           style="width: 100%; padding-left: 42px; padding-right: 44px; height: 44px; box-sizing: border-box;"
                           required />
                    <button type="button" onclick="togglePasswordVisibility('registerPassword', this)" 
                            style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); z-index: 10; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; border: none; background: transparent; padding: 0; color: #9ca3af;" 
                            title="Tampilkan / Sembunyikan Password">
                        <i class="fas fa-eye" style="font-size: 14px;"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                <div class="relative w-full" style="position: relative; width: 100%;">
                    <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); z-index: 10; pointer-events: none; display: flex; align-items: center; justify-content: center; width: 20px; color: #9ca3af;">
                        <i class="fas fa-shield-alt" style="font-size: 14px;"></i>
                    </span>
                    <input type="password" id="registerConfirmPassword" name="password_confirmation" placeholder="Ulangi password" 
                           class="w-full py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" 
                           style="width: 100%; padding-left: 42px; padding-right: 44px; height: 44px; box-sizing: border-box;"
                           required />
                    <button type="button" onclick="togglePasswordVisibility('registerConfirmPassword', this)" 
                            style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); z-index: 10; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; border: none; background: transparent; padding: 0; color: #9ca3af;" 
                            title="Tampilkan / Sembunyikan Password">
                        <i class="fas fa-eye" style="font-size: 14px;"></i>
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
