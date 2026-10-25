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

        <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
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
                    <input type="password" id="loginPassword" name="password" placeholder="••••••••" 
                           class="w-full py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition" 
                           style="width: 100%; padding-left: 42px; padding-right: 44px; height: 44px; box-sizing: border-box;"
                           required />
                    <button type="button" onclick="togglePasswordVisibility('loginPassword', this)" 
                            style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); z-index: 10; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; border: none; background: transparent; padding: 0; color: #9ca3af;" 
                            title="Tampilkan / Sembunyikan Password">
                        <i class="fas fa-eye" style="font-size: 14px;"></i>
                    </button>
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
