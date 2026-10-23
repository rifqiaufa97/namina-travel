<footer class="footer p-10 bg-gray-900 text-gray-100">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <div>
                <span class="footer-title text-xl font-bold text-primary">Namina Travel</span>  
                <p class="mt-4 text-gray-300">Mitra perjalanan terpercaya Anda untuk menjelajahi keindahan destinasi wisata impian dengan nyaman dan aman.</p>
                <div class="flex gap-4 mt-4">
                    <a href="https://wa.me/6281282069087" target="_blank" class="text-2xl hover:text-green-400 transition-colors" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="mailto:info@naminatravel.com" class="text-2xl hover:text-primary transition-colors" title="Email"><i class="fas fa-envelope"></i></a>
                    <a href="{{ route('contact') }}" class="text-2xl hover:text-sky-400 transition-colors" title="Lokasi"><i class="fas fa-map-marker-alt"></i></a>
                </div>
            </div>
            <div>
                <span class="footer-title text-lg font-semibold">Tautan Cepat</span>
                <a class="link link-hover block mt-2 hover:text-primary transition-colors" href="{{ route('home') }}">Beranda</a>
                <a class="link link-hover block mt-2 hover:text-primary transition-colors" href="{{ route('destinations.index') }}">Destinasi Wisata</a>
                <a class="link link-hover block mt-2 hover:text-primary transition-colors" href="{{ route('tentang') }}">Tentang Kami</a>
                <a class="link link-hover block mt-2 hover:text-primary transition-colors" href="{{ route('contact') }}">Hubungi Kami</a>
            </div>
            <div>
                <span class="footer-title text-lg font-semibold">Bantuan & Layanan</span>
                <a class="link link-hover block mt-2 hover:text-primary transition-colors" href="{{ route('contact') }}">Pusat Bantuan</a>
                <a class="link link-hover block mt-2 hover:text-primary transition-colors" href="https://wa.me/6281282069087" target="_blank">Chat CS 24/7</a>
                <a class="link link-hover block mt-2 hover:text-primary transition-colors" href="{{ route('tentang') }}">Kebijakan Privasi</a>
                <a class="link link-hover block mt-2 hover:text-primary transition-colors" href="{{ route('tentang') }}">Syarat & Ketentuan</a>
            </div>
            <div>
                <span class="footer-title text-lg font-semibold">Info & Promo</span>
                <div class="form-control w-full max-w-sm mt-2">
                    <label class="label">
                        <span class="label-text text-gray-300">Dapatkan update promo wisata</span>
                    </label>
                    <div class="relative">
                        <input type="email" placeholder="Email Anda" class="input input-bordered w-full pr-24 bg-gray-800 border-gray-700 text-gray-100 placeholder-gray-400" />
                        <button class="btn btn-primary absolute top-0 right-0 rounded-l-none font-semibold">Kirim</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-12 pt-6 text-center">
            <p class="text-gray-400">© {{ date('Y') }} Namina Travel. Hak cipta dilindungi.</p>
        </div>
    </div>
</footer>
