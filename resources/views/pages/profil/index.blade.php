@extends('layouts.app')

@section('title', 'Profil Saya - Namina Travel')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle avatar preview
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.querySelector('.avatar-preview img');
    const avatarInitial = document.querySelector('.avatar-preview div');
    
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB');
                    this.value = '';
                    return;
                }
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan JPG atau PNG');
                    this.value = '';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (avatarPreview) {
                        avatarPreview.src = e.target.result;
                        avatarPreview.style.display = 'block';
                        if (avatarInitial) avatarInitial.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
                
                // Auto submit the form when a file is selected
                document.querySelector('form').submit();
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
    .avatar-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto 1rem;
    }
    .avatar-preview {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #e2e8f0;
        background-color: #f8fafc;
        position: relative;
    }
    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-upload {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #3b82f6;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .avatar-upload:hover {
        background: #2563eb;
    }
    .avatar-upload input[type="file"] {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="bg-base-200/40 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Pengaturan Akun & Profil</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola informasi pribadi, foto profil, dan keamanan password Anda.</p>
        </div>
        
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-center" role="alert">
                <i class="fas fa-check-circle text-emerald-500 text-lg mr-3"></i>
                <span class="text-sm font-semibold text-emerald-800">{{ session('success') }}</span>
            </div>
        @endif
        
        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm" role="alert">
                <div class="flex items-center mb-1">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                    <span class="text-sm font-bold text-red-800">Mohon perbaiki data berikut:</span>
                </div>
                <ul class="list-disc list-inside text-xs text-red-700 ml-5 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Member Card & Quick Navigation (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Profile Avatar Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 text-center">
                    <div class="avatar-container relative inline-block mx-auto mb-4">
                        <div class="avatar-preview w-28 h-28 rounded-full overflow-hidden border-4 border-primary/20 shadow-md bg-gray-50 relative mx-auto">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}?t={{ time() }}" alt="Foto Profil" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-primary text-white flex items-center justify-center text-3xl font-extrabold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <label for="avatar" class="absolute bottom-0 right-1 w-9 h-9 rounded-full bg-primary hover:bg-primary-focus text-white flex items-center justify-center cursor-pointer shadow-md transition-transform hover:scale-110" title="Ubah Foto Profil">
                            <i class="fas fa-camera text-xs"></i>
                        </label>
                    </div>

                    <h2 class="text-lg font-bold text-gray-900 leading-tight">{{ Auth::user()->name }}</h2>
                    <p class="text-xs text-gray-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                    <div class="mt-3 flex justify-center">
                        <span class="badge badge-primary badge-outline text-xs font-semibold px-3 py-2">
                            <i class="fas fa-user-check mr-1.5 text-[10px]"></i>Wisatawan Terverifikasi
                        </span>
                    </div>

                    <p class="text-[11px] text-gray-400 mt-4 border-t border-gray-100 pt-3">
                        Klik ikon kamera di atas untuk langsung mengganti foto profil (JPG/PNG maks. 2MB).
                    </p>
                </div>

                <!-- Quick Menu Links -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-4 space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 px-3 py-1 block">Menu Pelanggan</span>
                    <a href="{{ route('pesanan-saya') }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 text-gray-700 hover:text-primary transition-colors text-sm font-medium">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-receipt text-gray-400 w-5"></i>
                            <span>Pesanan & Tiket Saya</span>
                        </span>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 text-gray-700 hover:text-primary transition-colors text-sm font-medium">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-heart text-gray-400 w-5"></i>
                            <span>Destinasi Tersimpan (Wishlist)</span>
                        </span>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </a>
                    <a href="{{ route('destinations.index') }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 text-gray-700 hover:text-primary transition-colors text-sm font-medium">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-compass text-gray-400 w-5"></i>
                            <span>Jelajahi Paket Wisata</span>
                        </span>
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </a>
                </div>
            </div>

            <!-- Right Column: Profile Form & Password Security (8 Cols) -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Form Informasi Pribadi -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 sm:p-8">
                    <div class="border-b border-gray-100 pb-4 mb-6">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-id-card text-primary"></i>
                            <span>Informasi Pribadi</span>
                        </h2>
                        <p class="text-xs text-gray-500 mt-1">Perbarui rincian kontak dan profil utama Anda.</p>
                    </div>

                    <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Hidden avatar input triggered by camera icon in sidebar -->
                        <input type="file" name="avatar" id="avatar" class="hidden" accept="image/jpeg,image/png,image/jpg">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" 
                                    class="input input-bordered w-full rounded-xl text-sm focus:input-primary" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                                <div class="relative">
                                    <input type="email" value="{{ Auth::user()->email }}" 
                                        class="input input-bordered w-full rounded-xl text-sm bg-gray-50 text-gray-400 cursor-not-allowed" disabled>
                                    <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                                </div>
                                <span class="text-[11px] text-gray-400 mt-1 block">Email akun tidak dapat diubah</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nomor Telepon / WhatsApp</label>
                                <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" 
                                    class="input input-bordered w-full rounded-xl text-sm focus:input-primary" placeholder="Contoh: 081234567890">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Tanggal Lahir</label>
                                <input type="date" name="date_of_birth" 
                                    value="{{ old('date_of_birth', Auth::user()->date_of_birth ? \Carbon\Carbon::parse(Auth::user()->date_of_birth)->format('Y-m-d') : '') }}" 
                                    class="input input-bordered w-full rounded-xl text-sm focus:input-primary">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Alamat Tempat Tinggal</label>
                            <textarea name="address" rows="3" 
                                class="textarea textarea-bordered w-full rounded-xl text-sm focus:textarea-primary" 
                                placeholder="Masukkan alamat lengkap Anda...">{{ old('address', Auth::user()->address ?? '') }}</textarea>
                        </div>

                        <div class="pt-3 flex justify-end">
                            <button type="submit" class="btn btn-primary rounded-xl px-6 font-bold shadow-md shadow-primary/20">
                                <i class="fas fa-save mr-1.5"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Form Keamanan Password -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 sm:p-8">
                    <div class="border-b border-gray-100 pb-4 mb-6">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-lock text-primary"></i>
                            <span>Keamanan & Password</span>
                        </h2>
                        <p class="text-xs text-gray-500 mt-1">Pastikan akun Anda menggunakan password yang kuat dan unik.</p>
                    </div>

                    <form action="{{ route('password.update') }}" method="POST" class="space-y-4 max-w-xl">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Password Saat Ini</label>
                            <input type="password" name="current_password" required
                                class="input input-bordered w-full rounded-xl text-sm focus:input-primary" placeholder="••••••••">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Password Baru</label>
                                <input type="password" name="password" required
                                    class="input input-bordered w-full rounded-xl text-sm focus:input-primary" placeholder="Minimal 8 karakter">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" required
                                    class="input input-bordered w-full rounded-xl text-sm focus:input-primary" placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="pt-3 flex justify-end">
                            <button type="submit" class="btn btn-outline btn-primary rounded-xl px-6 font-bold">
                                <i class="fas fa-key mr-1.5"></i> Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
