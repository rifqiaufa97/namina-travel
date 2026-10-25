<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - {{ config('app.name') }}</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            letter-spacing: -0.01em;
        }
        .sidebar {
            min-height: calc(100vh - 4rem);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .sidebar.collapsed {
            margin-left: -16rem;
        }
        .main-content {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            margin-left: 16rem;
            width: calc(100% - 16rem);
        }
        .main-content.expanded {
            margin-left: 0;
            width: 100%;
        }
        @media (max-width: 1024px) {
            .sidebar {
                margin-left: -16rem;
                position: fixed;
                z-index: 40;
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body class="bg-gray-50/70 text-gray-800 antialiased min-h-screen">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 bg-white shadow-sm border-r border-gray-200/80 w-64 sidebar z-40 flex flex-col justify-between" id="sidebar">
        <div>
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 text-lg font-extrabold text-gray-900 tracking-tight">
                    <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center shadow-xs">
                        <i class="fas fa-compass text-base"></i>
                    </div>
                    <div>
                        <span class="block leading-tight font-extrabold">Namina Travel</span>
                        <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider block">Admin Back-Office</span>
                    </div>
                </a>
                <button class="lg:hidden text-gray-400 hover:text-gray-700 p-1" id="closeSidebar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="p-4 space-y-6">
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider px-3 mb-2 block">Menu Utama</span>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium' }}">
                                <i class="fas fa-tachometer-alt w-5 text-center text-sm"></i>
                                <span class="ml-2.5">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.packages.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.packages.*') ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium' }}">
                                <i class="fas fa-suitcase-rolling w-5 text-center text-sm"></i>
                                <span class="ml-2.5">Paket Wisata</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.orders.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium' }}">
                                <i class="fas fa-receipt w-5 text-center text-sm"></i>
                                <span class="ml-2.5">Pesanan & Bukti</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.analytics') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.analytics*') ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium' }}">
                                <i class="fas fa-chart-line w-5 text-center text-sm"></i>
                                <span class="ml-2.5">Analitik & Laporan</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider px-3 mb-2 block">Pengaturan</span>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm transition-all {{ request()->routeIs('admin.users.*') ? 'bg-primary text-white font-semibold shadow-sm shadow-primary/25' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium' }}">
                                <i class="fas fa-users-cog w-5 text-center text-sm"></i>
                                <span class="ml-2.5">Manajemen Pengguna</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <div class="p-4 border-t border-gray-100">
            <a href="{{ route('home') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium transition-colors">
                <i class="fas fa-arrow-left w-5 text-center text-sm text-gray-400"></i>
                <span class="ml-2.5">Kembali ke Situs Utama</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content flex flex-col min-h-screen">
        <!-- Top Navigation -->
        <header class="bg-white/95 backdrop-blur-md sticky top-0 z-30 border-b border-gray-200/80 shadow-xs">
            <div class="flex items-center justify-between px-6 py-3.5">
                <div class="flex items-center gap-3">
                    <button id="toggleSidebar" class="p-2 text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-100 lg:hidden">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900 leading-tight">@yield('title', 'Dashboard')</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-external-link-alt text-[10px]"></i>
                        <span>Lihat Web</span>
                    </a>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-50 focus:outline-none transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <span class="block text-xs font-bold text-gray-800 leading-tight">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] text-gray-400 font-semibold uppercase">Administrator</span>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] text-gray-400 ml-1 transition-transform duration-200" :class="{ 'transform rotate-180': open }"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50 divide-y divide-gray-100"
                             style="display: none;">
                            <div class="px-4 py-2 text-xs">
                                <p class="text-gray-400">Masuk sebagai</p>
                                <p class="font-bold text-gray-800 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('profil') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 font-medium">
                                    <i class="fas fa-user-circle text-gray-400"></i> Profil Saya
                                </a>
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 font-medium">
                                    <i class="fas fa-tachometer-alt text-gray-400"></i> Dashboard
                                </a>
                            </div>
                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50 font-semibold">
                                        <i class="fas fa-sign-out-alt"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6 sm:p-8 flex-1">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl shadow-xs flex items-center">
                    <i class="fas fa-check-circle text-emerald-500 text-base mr-3"></i>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r-xl shadow-xs flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-base mr-3"></i>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r-xl shadow-xs">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <span class="text-sm font-bold">Mohon periksa kesalahan formulir:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs text-red-700 ml-5 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            // Alpine.js is initialized
        });

        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggleSidebar');
            const closeButton = document.getElementById('closeSidebar');
            const sidebar = document.getElementById('sidebar');

            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (!sidebar.contains(event.target) && 
                    (!toggleButton || !toggleButton.contains(event.target))) {
                    sidebar.classList.remove('show');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
