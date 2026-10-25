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
                <!-- Primary Image Current & Input -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 flex items-center justify-between">
                        <span>Foto Sampul Utama</span>
                        <span class="text-[11px] font-normal text-gray-400">Maks. 10MB (JPG, PNG, WEBP)</span>
                    </label>
                    <div class="mb-3 rounded-xl overflow-hidden border border-gray-200 relative group aspect-video bg-gray-100 flex items-center justify-center" id="coverPreviewContainer">
                        @if($package->image)
                            <img id="coverPreview" src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                            <div id="coverBadge" class="absolute bottom-2 left-2 px-2 py-0.5 rounded-md bg-black/60 text-white text-[10px] font-semibold">
                                Foto Saat Ini
                            </div>
                        @else
                            <img id="coverPreview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                            <span id="coverPlaceholder" class="text-xs text-gray-400">Belum ada foto sampul</span>
                        @endif
                    </div>
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp,image/gif"
                           class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-xl p-1.5 focus:outline-none cursor-pointer">
                    <p class="text-[11px] text-gray-400 mt-1">Pilih gambar baru untuk mengganti foto utama, atau biarkan kosong jika tidak diubah.</p>
                </div>

                <!-- Gallery Upload & Manager -->
                <div class="pt-4 border-t border-gray-100 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-gray-700">
                            Galeri Foto Tambahan
                        </label>
                        <span class="text-[11px] text-blue-600 font-semibold bg-blue-50 px-2 py-0.5 rounded-md">
                            Bisa pilih banyak foto
                        </span>
                    </div>

                    <!-- Interactive Drag/Click Upload Area -->
                    <div class="border-2 border-dashed border-gray-200 hover:border-blue-400 rounded-xl p-4 text-center bg-gray-50/50 hover:bg-blue-50/20 transition cursor-pointer group"
                         onclick="document.getElementById('galleryInput').click()">
                        <input type="file" id="galleryInput" name="images[]" accept="image/jpeg,image/png,image/jpg,image/webp,image/gif" multiple class="hidden">
                        <div class="space-y-1.5 pointer-events-none">
                            <div class="w-9 h-9 mx-auto rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition">
                                <i class="fas fa-images text-sm"></i>
                            </div>
                            <p class="text-xs font-bold text-gray-700">Klik untuk memilih foto atau seret ke sini</p>
                            <p class="text-[11px] text-gray-400">Pilih satu atau banyak foto sekaligus (Maks. 10MB per foto)</p>
                        </div>
                    </div>

                    <!-- Pending / New Selected Images Preview Queue -->
                    <div id="pendingGallerySection" class="hidden space-y-2 pt-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-emerald-700 flex items-center">
                                <i class="fas fa-cloud-upload-alt mr-1.5"></i> Foto Baru yang Akan Diunggah (<span id="pendingCount">0</span>)
                            </span>
                            <button type="button" onclick="clearAllPendingFiles()" class="text-[11px] text-rose-600 hover:underline cursor-pointer">
                                Batalkan Semua
                            </button>
                        </div>
                        <div id="pendingGalleryGrid" class="grid grid-cols-2 sm:grid-cols-3 gap-2"></div>
                    </div>

                    <!-- Existing Saved Gallery Images -->
                    @php
                        $galleryImages = $package->images ? $package->images->where('is_primary', false) : collect();
                    @endphp

                    <div class="pt-3 border-t border-gray-100" id="savedGalleryWrapper">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-gray-700 flex items-center">
                                <i class="fas fa-photo-film text-gray-400 mr-1.5"></i> Foto Galeri Tersimpan (<span id="savedCount">{{ $galleryImages->count() }}</span>)
                            </span>
                            <span class="text-[10px] text-gray-400">Arahkan kursor & klik ikon sampah untuk menghapus</span>
                        </div>

                        <div id="savedGalleryGrid" class="grid grid-cols-2 gap-2 {{ $galleryImages->count() == 0 ? 'hidden' : '' }}">
                            @foreach($galleryImages as $image)
                                <div class="relative group rounded-xl overflow-hidden border border-gray-200 aspect-video bg-gray-100 shadow-2xs transition hover:shadow-md"
                                     id="gallery-item-{{ $image->id }}">
                                    <img src="{{ asset('storage/' . $image->path) }}" alt="Galeri {{ $package->name }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <button type="button" 
                                                onclick="deleteImage({{ $package->id }}, {{ $image->id }}); return false;"
                                                id="del-btn-{{ $image->id }}"
                                                class="px-2.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold flex items-center space-x-1.5 shadow-lg transition transform hover:scale-105 cursor-pointer"
                                                title="Hapus foto dari server">
                                            <i class="fas fa-trash-alt text-[11px]"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </div>
                                    <div class="absolute bottom-1.5 left-1.5 px-1.5 py-0.5 rounded bg-black/60 text-white text-[9px] font-medium">
                                        Tersimpan
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div id="emptySavedGalleryText" class="p-4 text-center rounded-xl bg-gray-50 border border-dashed border-gray-200 text-gray-400 text-xs {{ $galleryImages->count() > 0 ? 'hidden' : '' }}">
                            <i class="far fa-images text-base mb-1 block"></i>
                            Belum ada foto galeri tambahan tersimpan untuk paket ini.
                        </div>
                    </div>
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
// 1. Cover Photo Live Preview
document.getElementById('image')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran foto sampul melebihi 10MB. Silakan pilih foto lain.');
            e.target.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(evt) {
            const preview = document.getElementById('coverPreview');
            const placeholder = document.getElementById('coverPlaceholder');
            const badge = document.getElementById('coverBadge');
            if (preview) {
                preview.src = evt.target.result;
                preview.classList.remove('hidden');
            }
            if (placeholder) placeholder.classList.add('hidden');
            if (badge) {
                badge.textContent = 'Foto Baru Dipilih';
                badge.className = 'absolute bottom-2 left-2 px-2 py-0.5 rounded-md bg-emerald-600 text-white text-[10px] font-semibold';
            }
        };
        reader.readAsDataURL(file);
    }
});

// 2. Multi-file Gallery Uploader with Live Queue & Previews
const galleryInput = document.getElementById('galleryInput');
const pendingSection = document.getElementById('pendingGallerySection');
const pendingGrid = document.getElementById('pendingGalleryGrid');
const pendingCount = document.getElementById('pendingCount');

let pendingDataTransfer = new DataTransfer();

if (galleryInput) {
    galleryInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (!files.length) return;

        // Append newly selected files to accumulated DataTransfer
        files.forEach(file => {
            if (file.size > 10 * 1024 * 1024) {
                alert(`File "${file.name}" melebihi batas 10MB dan dilewati.`);
                return;
            }
            // Check for duplicates by name and size
            const isDuplicate = Array.from(pendingDataTransfer.files).some(
                existing => existing.name === file.name && existing.size === file.size
            );
            if (!isDuplicate) {
                pendingDataTransfer.items.add(file);
            }
        });

        // Sync back to input
        galleryInput.files = pendingDataTransfer.files;
        renderPendingPreviews();
    });
}

function renderPendingPreviews() {
    if (!pendingGrid || !pendingSection) return;
    pendingGrid.innerHTML = '';
    const files = Array.from(pendingDataTransfer.files);

    if (files.length === 0) {
        pendingSection.classList.add('hidden');
        if (pendingCount) pendingCount.textContent = '0';
        return;
    }

    pendingSection.classList.remove('hidden');
    if (pendingCount) pendingCount.textContent = files.length;

    files.forEach((file, index) => {
        const reader = new FileReader();
        const card = document.createElement('div');
        card.className = 'relative group rounded-xl overflow-hidden border border-emerald-200 aspect-video bg-emerald-50/30 shadow-xs flex items-center justify-center';

        reader.onload = function(evt) {
            card.innerHTML = `
                <img src="${evt.target.result}" alt="${file.name}" class="w-full h-full object-cover">
                <button type="button" onclick="removePendingFile(${index})" 
                        class="absolute top-1 right-1 w-5 h-5 rounded-md bg-rose-600 hover:bg-rose-700 text-white text-[10px] flex items-center justify-center shadow cursor-pointer"
                        title="Hapus foto ini dari antrean">
                    <i class="fas fa-times"></i>
                </button>
                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 to-transparent p-1">
                    <p class="text-[9px] text-white truncate px-1">${file.name} (${(file.size / 1024 / 1024).toFixed(1)} MB)</p>
                </div>
            `;
        };
        reader.readAsDataURL(file);
        pendingGrid.appendChild(card);
    });
}

function removePendingFile(indexToRemove) {
    const newDt = new DataTransfer();
    Array.from(pendingDataTransfer.files).forEach((file, index) => {
        if (index !== indexToRemove) {
            newDt.items.add(file);
        }
    });
    pendingDataTransfer = newDt;
    if (galleryInput) galleryInput.files = pendingDataTransfer.files;
    renderPendingPreviews();
}

function clearAllPendingFiles() {
    pendingDataTransfer = new DataTransfer();
    if (galleryInput) galleryInput.files = pendingDataTransfer.files;
    renderPendingPreviews();
}

// 3. Delete Existing Gallery Image via AJAX
function deleteImage(packageId, imageId) {
    if (!confirm('Apakah Anda yakin ingin menghapus foto ini secara permanen dari galeri?')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                   || document.querySelector('input[name="_token"]')?.value 
                   || '{{ csrf_token() }}';
                   
    const url = `{{ url('admin/packages') }}/${packageId}/images/${imageId}`;
    const button = document.getElementById(`del-btn-${imageId}`);
    const itemCard = document.getElementById(`gallery-item-${imageId}`);
    
    if (button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin text-[10px]"></i> <span>Menghapus...</span>';
    }
    
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
        const data = await response.json().catch(() => ({}));
        
        if (!response.ok) {
            throw new Error(data.message || `Gagal menghapus gambar (${response.status})`);
        }
        
        if (itemCard) {
            itemCard.style.transition = 'all 0.3s ease';
            itemCard.style.transform = 'scale(0.8)';
            itemCard.style.opacity = '0';
            setTimeout(() => {
                itemCard.remove();
                
                // Update count and empty state
                const remaining = document.querySelectorAll('#savedGalleryGrid > div').length;
                const savedCountEl = document.getElementById('savedCount');
                if (savedCountEl) savedCountEl.textContent = remaining;
                
                if (remaining === 0) {
                    const grid = document.getElementById('savedGalleryGrid');
                    const emptyText = document.getElementById('emptySavedGalleryText');
                    if (grid) grid.classList.add('hidden');
                    if (emptyText) emptyText.classList.remove('hidden');
                }
            }, 300);
        }
    })
    .catch(error => {
        alert(error.message || 'Terjadi kesalahan saat menghapus gambar.');
        if (button) {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-trash-alt text-[11px]"></i> <span>Hapus</span>';
        }
    });
}
</script>
@endpush
@endsection
