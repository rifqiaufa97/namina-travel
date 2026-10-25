<!DOCTYPE html>
<html data-theme="fantasy" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Your Travel Partner')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Wishlist Script -->
    <script src="{{ asset('js/wishlist.js') }}"></script>
    
    @stack('styles')
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        ::selection {
            background-color: #6366f1;
            color: #ffffff;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Modal animation */
        dialog[open] {
            animation: modal-zoom-in 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        @keyframes modal-zoom-in {
            from {
                opacity: 0;
                transform: scale(0.96) translateY(4px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="min-h-screen bg-base-100 flex flex-col" x-data="{ showMobileMenu: false }">
    <!-- Navbar -->
    <x-layout.navbar />

    <!-- Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <x-layout.footer />

    @stack('scripts')
    
    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-6 left-6 z-50 hidden">
        <div class="alert shadow-lg">
            <div>
                <span id="toast-message">Notification message here</span>
            </div>
        </div>
    </div>
    
    <script>
        // Show toast notification
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            if (!toast || !toastMessage) return;
            
            // Set message and style based on type
            toastMessage.textContent = message;
            toast.className = `fixed bottom-6 left-6 z-50 transition-opacity duration-300`;
            
            // Set alert style based on type
            const alert = toast.querySelector('.alert');
            alert.className = 'alert shadow-lg';
            
            switch(type) {
                case 'success':
                    alert.classList.add('alert-success');
                    break;
                case 'error':
                    alert.classList.add('alert-error');
                    break;
                case 'warning':
                    alert.classList.add('alert-warning');
                    break;
                default:
                    alert.classList.add('alert-info');
            }
            
            // Show toast
            toast.classList.remove('hidden');
            toast.classList.add('flex');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                toast.classList.remove('flex');
                toast.classList.add('hidden');
            }, 5000);
        }
        
        // Handle form validation errors from server
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                @foreach ($errors->all() as $error)
                    showToast('{{ $error }}', 'error');
                @endforeach
            });
        @endif
        
        // Handle success/error messages from session
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('success') }}', 'success');
            });
        @endif
        
        @if (session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('error') }}', 'error');
            });
        @endif

        // Toggle Password Visibility in Modals & Forms
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        }
    </script>
    
    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/6281282069087?text=Halo%20CS%20Namina%20Travel,%20saya%20ingin%20bertanya%20tentang%20paket%20wisata." 
       target="_blank" 
       class="fixed bottom-6 right-6 z-50 flex items-center justify-center bg-green-500 hover:bg-green-600 text-white rounded-full p-4 shadow-2xl hover:scale-110 transition-transform duration-300 group"
       title="Hubungi CS via WhatsApp">
        <i class="fab fa-whatsapp text-3xl"></i>
        <span class="max-w-0 overflow-hidden group-hover:max-w-xs group-hover:ml-2 transition-all duration-300 ease-in-out font-medium whitespace-nowrap text-sm">
            Hubungi CS
        </span>
    </a>
</body>
</html>
