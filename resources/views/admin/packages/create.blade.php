@extends('admin.layouts.app')

@section('title', 'Tambah Paket Wisata')

@section('content')
<form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- Top Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50/50 shadow-sm transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Tambah Paket Wisata</h1>
                <p class="text-xs text-gray-500 mt-0.5">Isi seluruh informasi destinasi, fasilitas, harga, dan foto utama paket.</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.packages.index') }}" class="px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-xl shadow-sm transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-blue-500/20 transition transform active:scale-95 cursor-pointer">
                <i class="fas fa-save mr-2"></i> Simpan Paket Wisata
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
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Paket Eksotis Raja Ampat 4D3N"
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition font-medium">
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Deskripsi Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="5" required
                              placeholder="Jelaskan daya tarik, keunggulan, dan pengalaman wisata yang ditawarkan..."
                              class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition leading-relaxed">{{ old('description') }}</textarea>
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
                        <input type="text" id="location" name="location" value="{{ old('location') }}" required
                               placeholder="Misal: Kepulauan Raja Ampat"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    </div>

                    <div>
                        <label for="city" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Kota / Kabupaten <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" required
                               placeholder="Misal: Sorong"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="province" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Provinsi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="province" name="province" value="{{ old('province') }}" required
                               placeholder="Misal: Papua Barat Daya"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    </div>

                    <div>
                        <label for="address" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Alamat / Titik Kumpul Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}" required
                               placeholder="Misal: Bandara Domine Eduard Osok / Pelabuhan Rakyat"
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
                                  placeholder="- Tiket pesawat / speedboat&#10;- Akomodasi hotel 3 malam&#10;- Makan 3x sehari&#10;- Pemandu wisata lokal"
                                  class="w-full text-xs font-mono border border-emerald-200 bg-emerald-50/20 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none leading-relaxed">{{ old('includes') }}</textarea>
                    </div>

                    <div>
                        <label for="excludes" class="block text-xs font-bold text-rose-800 mb-1.5 flex items-center">
                            <i class="fas fa-times-circle text-rose-500 mr-1.5"></i> Tidak Termasuk (Exclude)
                        </label>
                        <textarea id="excludes" name="excludes" rows="7"
                                  placeholder="- Pengeluaran pribadi & suvenir&#10;- Tip opsional untuk guide / driver&#10;- Asuransi perjalanan tambahan"
                                  class="w-full text-xs font-mono border border-rose-200 bg-rose-50/20 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-rose-500 focus:outline-none leading-relaxed">{{ old('excludes') }}</textarea>
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
                        <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0" step="1000"
                               placeholder="1500000"
                               class="w-full text-sm font-extrabold text-blue-600 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    </div>
                </div>

                <div>
                    <label for="duration" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Durasi Wisata <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="duration" name="duration" value="{{ old('duration') }}" required
                           placeholder="Contoh: 3 Hari 2 Malam"
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>

                <div class="pt-3 border-t border-gray-100">
                    <label class="flex items-center space-x-3 cursor-pointer select-none">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <div>
                            <span class="text-xs font-bold text-gray-900 block">Paket Unggulan (Featured)</span>
                            <span class="text-[11px] text-gray-400 block">Tampilkan badge bintang di beranda utama</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Media Uploads -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100">
                    Foto & Dokumentasi
                </h3>

                <div>
                    <label for="image" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Foto Sampul Utama <span class="text-rose-500">*</span>
                    </label>
                    <input type="file" id="image" name="image" accept="image/*" required
                           class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-xl p-1.5 focus:outline-none">
                    <p class="text-[11px] text-gray-400 mt-1">Disarankan rasio landscape 16:9 (Maks. 2MB).</p>
                </div>

                <div class="pt-3 border-t border-gray-100">
                    <label for="gallery" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Galeri Foto Tambahan
                    </label>
                    <input type="file" id="gallery" name="images[]" accept="image/*" multiple
                           class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-gray-200 rounded-xl p-1.5 focus:outline-none">
                    <p class="text-[11px] text-gray-400 mt-1">Dapat memilih lebih dari satu foto sekaligus.</p>
                </div>
            </div>

            <!-- Quick Action Card -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-3">
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-extrabold rounded-xl shadow-md shadow-blue-500/20 transition transform active:scale-95 cursor-pointer">
                    <i class="fas fa-check mr-2"></i> Publikasikan Paket Wisata
                </button>
                <a href="{{ route('admin.packages.index') }}" class="block text-center py-2 text-xs font-semibold text-gray-500 hover:text-gray-800 transition">
                    Batalkan Perubahan
                </a>
            </div>

        </div>

    </div>
</form>
@endsection
