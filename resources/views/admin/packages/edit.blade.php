@extends('admin.layouts.app')

@section('title', 'Edit Paket Wisata: ' . $package->name)

@section('content')
<form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- Top Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50/50 shadow-sm transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Edit Paket: {{ $package->name }}</h1>
                <p class="text-xs text-gray-500 mt-0.5">Perbarui informasi paket, harga, jadwal, fasilitas, atau kelola galeri foto.</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.packages.index') }}" class="px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-xl shadow-sm transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-blue-500/20 transition transform active:scale-95 cursor-pointer">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </div>

    <!-- 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Main Form Column (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Section 1: Basic Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2 text-base"></i> Informasi Umum Paket
                </h2>

                <div>
                    <label for="name" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Nama Paket Wisata <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $package->name) }}" required
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition font-medium">
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Deskripsi Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="5" required
                              class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition leading-relaxed">{{ old('description', $package->description) }}</textarea>
                </div>
            </div>

            <!-- Section 2: Destination & Location -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100 flex items-center">
                    <i class="fas fa-map-marker-alt text-rose-500 mr-2 text-base"></i> Lokasi Destinasi
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="location" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Daerah / Kawasan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="location" name="location" value="{{ old('location', $package->location) }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    </div>

                    <div>
                        <label for="city" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Kota / Kabupaten <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="city" name="city" value="{{ old('city', $package->city) }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="province" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Provinsi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="province" name="province" value="{{ old('province', $package->province) }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    </div>

                    <div>
                        <label for="address" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Alamat / Titik Kumpul Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="address" name="address" value="{{ old('address', $package->address) }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Section 3: Facilities (Includes & Excludes) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                <div class="pb-3 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center">
                        <i class="fas fa-list-check text-emerald-600 mr-2 text-base"></i> Fasilitas Termasuk & Tidak Termasuk
                    </h2>
                    <p class="text-xs text-gray-400 mt-0.5">Gunakan tanda strip (-) di awal baris untuk membuat daftar poin fasilitas.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="includes" class="block text-xs font-bold text-emerald-800 mb-1.5 flex items-center">
                            <i class="fas fa-check-circle text-emerald-500 mr-1.5"></i> Fasilitas Termasuk (Include)
                        </label>
                        <textarea id="includes" name="includes" rows="7"
                                  class="w-full text-xs font-mono border border-emerald-200 bg-emerald-50/20 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none leading-relaxed">{{ old('includes', $package->includes) }}</textarea>
                    </div>

                    <div>
                        <label for="excludes" class="block text-xs font-bold text-rose-800 mb-1.5 flex items-center">
                            <i class="fas fa-times-circle text-rose-500 mr-1.5"></i> Tidak Termasuk (Exclude)
                        </label>
                        <textarea id="excludes" name="excludes" rows="7"
                                  class="w-full text-xs font-mono border border-rose-200 bg-rose-50/20 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-rose-500 focus:outline-none leading-relaxed">{{ old('excludes', $package->excludes) }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar Options Column (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Price & Duration -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100">
                    Tarif & Durasi Paket
                </h3>

                <div>
                    <label for="price" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Harga per Peserta (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-gray-400">Rp</span>
                        <input type="number" id="price" name="price" value="{{ old('price', $package->price) }}" required min="0" step="1000"
                               class="w-full text-sm font-extrabold text-blue-600 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    </div>
                </div>

                <div>
                    <label for="duration" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Durasi Wisata <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="duration" name="duration" value="{{ old('duration', $package->duration) }}" required
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>

                <div class="pt-3 border-t border-gray-100">
                    <label class="flex items-center space-x-3 cursor-pointer select-none">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $package->is_featured) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <div>
                            <span class="text-xs font-bold text-gray-900 block">Paket Unggulan (Featured)</span>
                            <span class="text-[11px] text-gray-400 block">Tampilkan badge bintang di beranda utama</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Media Uploads & Existing Images -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100">
                    Foto & Dokumentasi
                </h3>

                <!-- Primary Image Current & Input -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Foto Sampul Utama</label>
                    @if($package->image)
                        <div class="mb-3 rounded-xl overflow-hidden border border-gray-200 relative group aspect-video bg-gray-100">
                            <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                            <div class="absolute bottom-2 left-2 px-2 py-0.5 rounded-md bg-black/60 text-white text-[10px] font-semibold">
                                Foto Saat Ini
                            </div>
                        </div>
                    @endif
                    <input type="file" id="image" name="image" accept="image/*"
                           class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-xl p-1.5 focus:outline-none">
                    <p class="text-[11px] text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti foto utama.</p>
                </div>

                <!-- Gallery Upload & Manager -->
                <div class="pt-3 border-t border-gray-100">
                    <label for="gallery" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Tambah Foto Galeri Baru
                    </label>
                    <input type="file" id="gallery" name="images[]" accept="image/*" multiple
                           class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-gray-200 rounded-xl p-1.5 focus:outline-none">

                    @php
                        $galleryImages = $package->images ? $package->images->where('is_primary', false) : collect();
                    @endphp

                    @if($galleryImages->count() > 0)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <span class="text-xs font-bold text-gray-700 block mb-2">Galeri Foto Tersimpan:</span>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($galleryImages as $image)
                                    <div class="relative group rounded-xl overflow-hidden border border-gray-200 aspect-video bg-gray-100">
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="Gallery Item" class="w-full h-full object-cover">
                                        <button type="button" 
                                                onclick="deleteImage({{ $package->id }}, {{ $image->id }}); return false;"
                                                class="absolute top-1.5 right-1.5 w-6 h-6 rounded-lg bg-rose-600/90 hover:bg-rose-600 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-sm cursor-pointer"
                                                title="Hapus foto ini">
                                            <i class="fas fa-trash-alt text-[10px]"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Action Card -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-3">
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-extrabold rounded-xl shadow-md shadow-blue-500/20 transition transform active:scale-95 cursor-pointer">
                    <i class="fas fa-check mr-2"></i> Perbarui Paket Wisata
                </button>
                <a href="{{ route('admin.packages.index') }}" class="block text-center py-2 text-xs font-semibold text-gray-500 hover:text-gray-800 transition">
                    Batalkan Perubahan
                </a>
            </div>

        </div>

    </div>
</form>

@push('scripts')
<script>
function deleteImage(packageId, imageId) {
    if (!confirm('Apakah Anda yakin ingin menghapus gambar ini dari galeri?')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = `/admin/packages/${packageId}/images/${imageId}`;
    const button = document.querySelector(`button[onclick*="${imageId}"]`);
    const imageContainer = button.closest('.relative');
    
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin text-[10px]"></i>';
    
    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(async (response) => {
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Terjadi kesalahan saat menghapus gambar');
        }
        
        if (imageContainer) {
            imageContainer.style.opacity = '0';
            setTimeout(() => imageContainer.remove(), 200);
        }
    })
    .catch(error => {
        alert(error.message || 'Terjadi kesalahan saat menghapus gambar');
        button.disabled = false;
        button.innerHTML = originalContent;
    });
}
</script>
@endpush
@endsection
