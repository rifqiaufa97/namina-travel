<!-- Auth Modals -->
<x-auth.login-modal />
<x-auth.register-modal />

<div class="drawer">
    <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col">
        <!-- Navbar -->
        <div class="w-full navbar sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-xs px-4 lg:px-8">
            <div class="flex-none lg:hidden">
                <label for="my-drawer-3" class="btn btn-square btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </label>
            </div>
            <div class="flex-1 px-2 mx-2">
                <a href="{{ route('home') }}" class="text-xl font-extrabold tracking-tight text-primary flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-primary text-white flex items-center justify-center shadow-md shadow-primary/30">
                        <i class="fas fa-compass text-lg"></i>
                    </span>
                    <span>Namina<span class="text-gray-900 font-bold">Travel</span></span>
                </a>
            </div>
            <div class="flex-none hidden lg:flex">
                <ul class="menu menu-horizontal px-1 gap-1">
                    <li><a href="{{ route('home') }}" class="font-medium rounded-lg hover:text-primary transition-colors {{ request()->routeIs('home') ? 'active font-semibold text-primary' : '' }}">Beranda</a></li>
                    <li><a href="{{ route('destinations.index') }}" class="font-medium rounded-lg hover:text-primary transition-colors {{ request()->routeIs('destinations.index') ? 'active font-semibold text-primary' : '' }}">Destinasi</a></li>
                    <li><a href="{{ route('tentang') }}" class="font-medium rounded-lg hover:text-primary transition-colors {{ request()->routeIs('tentang') ? 'active font-semibold text-primary' : '' }}">Tentang</a></li>
                    <li><a href="{{ route('contact') }}" class="font-medium rounded-lg hover:text-primary transition-colors {{ request()->routeIs('contact') ? 'active font-semibold text-primary' : '' }}">Kontak</a></li>
                </ul>
                <div class="flex items-center ml-4">
                    @auth
                    <a href="{{ route('wishlist.index') }}" class="btn btn-ghost btn-circle relative mr-2 hover:bg-red-50 hover:text-red-500 transition-colors {{ request()->routeIs('wishlist*') ? 'text-red-500 bg-red-50' : 'text-gray-600' }}" title="Wishlist">
                        <i class="fas fa-heart text-lg"></i>
                        @if(auth()->user()->wishlists()->count() > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full shadow-xs wishlist-count">
                                {{ min(auth()->user()->wishlists()->count(), 9) }}
                            </span>
                        @endif
                    </a>
                    @endauth
                    @auth
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-circle avatar ring-2 ring-primary/20 hover:ring-primary transition-all">
                                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center overflow-hidden font-semibold">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    @endif
                                </div>
                            </label>
                            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow-lg bg-base-100 rounded-box w-52 border border-gray-100">
                                @if(auth()->user()->isAdmin())
                                    <li><a href="{{ route('admin.dashboard') }}" class="text-primary font-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Admin Dashboard
                                    </a></li>
                                    <li class="menu-divider"></li>
                                @endif
                                <li><a href="{{ route('pesanan-saya') }}">Pesanan Saya</a></li>
                                <li><a href="{{ route('profil') }}" class="{{ request()->routeIs('profil') ? 'active' : '' }}">Profil Saya</a></li>
                                <li class="menu-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="#" class="logout-link text-red-600">
                                            Keluar
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="#" onclick="document.getElementById('loginModal').showModal()" class="btn btn-ghost font-medium">Masuk</a>
                        <a href="#" onclick="document.getElementById('registerModal').showModal()" class="btn btn-primary font-medium ml-2 shadow-sm">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Sidebar -->
    <div class="drawer-side z-50">
        <label for="my-drawer-3" class="drawer-overlay"></label>
        <ul class="menu p-4 w-80 min-h-full bg-base-200 text-base-content">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
            <li><a href="{{ route('destinations.index') }}" class="{{ request()->routeIs('destinations.index') ? 'active' : '' }}">Destinasi</a></li>
            <li><a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'active' : '' }}">Tentang</a></li>
            <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a></li>
            @auth
                @if(auth()->user()->isAdmin())
                    <li class="menu-title mt-4">
                        <span>Admin</span>
                    </li>
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="text-primary font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Admin Dashboard
                        </a>
                    </li>
                @endif
                <li class="menu-title mt-4">
                    <span>Akun Saya</span>
                </li>
                <li>
                    <a href="{{ route('wishlist.index') }}" class="{{ request()->routeIs('wishlist*') ? 'active' : '' }}">
                        <i class="fas fa-heart mr-2"></i> Wishlist
                        @if(auth()->user()->wishlists()->count() > 0)
                            <span class="badge badge-primary badge-sm wishlist-count">
                                {{ auth()->user()->wishlists()->count() }}
                            </span>
                        @endif
                    </a>
                </li>
                <li><a href="{{ route('pesanan-saya') }}">Pesanan Saya</a></li>
                <li><a href="{{ route('profil') }}" class="{{ request()->routeIs('profil') ? 'active' : '' }}">Profil Saya</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" class="logout-link">
                            Keluar
                        </a>
                    </form>
                </li>
            @else
                <li class="mt-4">
                    <a href="#" onclick="document.getElementById('loginModal').showModal()" class="btn btn-primary w-full">Masuk</a>
                </li>
                <li class="mt-2">
                    <a href="#" onclick="document.getElementById('registerModal').showModal()" class="btn btn-outline w-full">Daftar</a>
                </li>
            @endauth
        </ul>
    </div>
</div>

@push('scripts')
<script>
    // Handle logout functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Auto open auth modals if requested in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('auth') === 'login') {
            document.getElementById('loginModal')?.showModal();
        } else if (urlParams.get('auth') === 'register') {
            document.getElementById('registerModal')?.showModal();
        }

        // Handle logout form submission
        document.querySelectorAll('form[action="{{ route('logout') }}"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                handleLogout(form);
            });
        });
        
        // Handle logout links
        document.querySelectorAll('.logout-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                if (form) {
                    handleLogout(form);
                }
            });
        });
        
        // Handle logout function
        async function handleLogout(form) {
            try {
                // Create a hidden form if it doesn't exist
                if (!form) {
                    form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('logout') }}';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    form.appendChild(csrfToken);
                    document.body.appendChild(form);
                }
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(new FormData(form))
                });
                
                const data = await response.json();
                
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = '{{ route('home') }}';
                }
            } catch (error) {
                console.error('Logout error:', error);
                window.location.href = '{{ route('home') }}';
            }
        }
    });

    // Handle login form submission
    document.getElementById('loginForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (response.ok) {
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'Terjadi kesalahan saat login');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    });
    
    // Handle registration form submission
    document.getElementById('registerForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (response.ok) {
                window.location.href = data.redirect;
            } else {
                // Handle validation errors
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join('\n');
                    alert(errorMessages);
                } else {
                    alert(data.message || 'Terjadi kesalahan saat mendaftar');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    });
</script>
@endpush
