@extends('admin.layouts.app')

@section('title', 'Kelola Paket Wisata')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Katalog Paket Wisata</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                    {{ $packages->total() ?? $packages->count() }} Paket
                </span>
            </div>
            <p class="text-xs text-gray-500 mt-1">Kelola destinasi liburan, durasi tur, harga tiket, dan paket unggulan yang tampil di website.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.packages.create') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-blue-500/20 transition transform active:scale-95">
                <i class="fas fa-plus mr-2"></i> Tambah Paket Baru
            </a>
        </div>
    </div>

    <!-- Table Card Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <th class="py-3.5 px-4 w-16">Foto</th>
                        <th class="py-3.5 px-4">Nama Paket & Lokasi</th>
                        <th class="py-3.5 px-4">Harga / Pax</th>
                        <th class="py-3.5 px-4">Durasi</th>
                        <th class="py-3.5 px-4 text-center">Tipe / Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($packages as $package)
                        <tr class="hover:bg-slate-50/60 transition group">
                            <!-- Thumbnail -->
                            <td class="py-3.5 px-4">
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0 shadow-sm">
                                    <img src="{{ $package->image ? asset('storage/' . $package->image) : asset('images/placeholder.jpg') }}" 
                                         alt="{{ $package->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                </div>
                            </td>

                            <!-- Name & Location -->
                            <td class="py-3.5 px-4">
                                <div class="font-extrabold text-gray-900 text-sm group-hover:text-blue-600 transition">
                                    {{ $package->name }}
                                </div>
                                <div class="text-[11px] text-gray-500 flex items-center mt-0.5">
                                    <i class="fas fa-location-dot text-rose-500 mr-1.5 text-[10px]"></i>
                                    <span>{{ $package->location }}</span>
                                    @if($package->city)
                                        <span class="mx-1">&bull;</span>
                                        <span>{{ $package->city }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Price -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-extrabold text-blue-600 text-sm">
                                    Rp {{ number_format($package->price, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-gray-400">per orang / pax</div>
                            </td>

                            <!-- Duration -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">
                                    <i class="far fa-clock mr-1.5 text-gray-500"></i> {{ $package->duration }}
                                </span>
                            </td>

                            <!-- Featured Status -->
                            <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                @if($package->is_featured)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fas fa-star mr-1 text-amber-500 text-[10px]"></i> Unggulan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium bg-gray-100 text-gray-600">
                                        Reguler
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <a href="{{ route('admin.packages.edit', $package) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm" 
                                       title="Edit Paket">
                                        <i class="fas fa-pencil-alt text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.packages.destroy', $package) }}" 
                                          method="POST" 
                                          class="inline-block" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket wisata \'{{ $package->name }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition shadow-sm cursor-pointer" 
                                                title="Hapus Paket">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <div class="w-16 h-16 rounded-full bg-gray-50 text-gray-300 mx-auto flex items-center justify-center mb-3">
                                    <i class="fas fa-umbrella-beach text-2xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-gray-700">Belum ada paket wisata</p>
                                <p class="text-xs text-gray-400 mt-1">Mulai buat paket perjalanan liburan pertama Anda sekarang.</p>
                                <a href="{{ route('admin.packages.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold shadow hover:bg-blue-700 transition">
                                    <i class="fas fa-plus mr-1.5"></i> Buat Paket Wisata
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($packages->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $packages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
