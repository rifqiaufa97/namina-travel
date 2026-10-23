@extends('layouts.app')

@section('title', 'Daftar Destinasi')

@section('content')
<!-- Hero Section -->
<section class="bg-base-200 py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-center mb-4">Temukan Destinasi Terbaik</h1>
        <p class="text-center text-lg max-w-2xl mx-auto">
            Jelajahi berbagai destinasi wisata menarik yang siap membuat liburan Anda tak terlupakan.
        </p>
    </div>
</section>

<!-- Destinations Grid -->
<section class="container mx-auto px-4 py-12">
    <!-- Search and Filters -->
    <div class="bg-base-200 p-6 rounded-lg mb-8">
        <form action="{{ route('destinations.index') }}" method="GET" class="space-y-4">
            <!-- Search Bar -->
            <div class="form-control">
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Cari destinasi, kota, atau provinsi..." 
                        class="input input-bordered w-full pr-12"
                        value="{{ request('search') }}"
                    >
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 btn btn-ghost btn-sm">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Location Filter -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Kota</span>
                    </label>
                    <input 
                        type="text" 
                        name="city" 
                        placeholder="Semua Kota" 
                        class="input input-bordered w-full"
                        value="{{ request('city') }}"
                    >
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Provinsi</span>
                    </label>
                    <input 
                        type="text" 
                        name="province" 
                        placeholder="Semua Provinsi" 
                        class="input input-bordered w-full"
                        value="{{ request('province') }}"
                    >
                </div>

                <!-- Price Range -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Harga Min</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2">Rp</span>
                        <input 
                            type="number" 
                            name="min_price" 
                            placeholder="Min" 
                            class="input input-bordered w-full pl-10"
                            value="{{ request('min_price') }}"
                        >
                    </div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Harga Maks</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2">Rp</span>
                        <input 
                            type="number" 
                            name="max_price" 
                            placeholder="Maks" 
                            class="input input-bordered w-full pl-10"
                            value="{{ request('max_price') }}"
                        >
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-4 items-center">
                <!-- Rating -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Rating Minimal</span>
                    </label>
                    <div class="rating">
                        @for($i = 1; $i <= 5; $i++)
                            <input 
                                type="radio" 
                                name="min_rating" 
                                value="{{ $i }}" 
                                class="mask mask-star-2 bg-orange-400"
                                {{ request('min_rating') == $i ? 'checked' : '' }}
                                onchange="this.form.submit()"
                            >
                        @endfor
                    </div>
                </div>

                <!-- Sort -->
                <div class="form-control ml-auto">
                    <label class="label">
                        <span class="label-text">Urutkan</span>
                    </label>
                    <select class="select select-bordered" name="sort" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    </select>
                </div>

                <!-- Additional Filters -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Filter</span>
                    </label>
                    <select class="select select-bordered" name="filter" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="popular" {{ request('filter') == 'popular' ? 'selected' : '' }}>Populer</option>
                        <option value="featured" {{ request('filter') == 'featured' ? 'selected' : '' }}>Rekomendasi</option>
                    </select>
                </div>

                <!-- Reset Filters -->
                @if(request()->hasAny(['search', 'city', 'province', 'min_price', 'max_price', 'min_rating', 'sort', 'filter']))
                    <div class="form-control self-end">
                        <a href="{{ route('destinations.index') }}" class="btn btn-ghost">
                            <i class="fas fa-times mr-1"></i> Hapus Filter
                        </a>
                    </div>
                @endif
            </div>
        </form>
    </div>

    <!-- Destinations -->
    @if($destinations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($destinations as $destination)
                <x-destination-card :destination="$destination" />
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $destinations->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-base-100 rounded-2xl shadow-sm border border-base-200 p-8 max-w-lg mx-auto">
            <div class="text-5xl mb-4">🔍</div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Destinasi Tidak Ditemukan</h3>
            <p class="text-gray-500 mb-6">Tidak ada destinasi yang cocok dengan kriteria pencarian atau filter yang Anda pilih.</p>
            <a href="{{ route('destinations.index') }}" class="btn btn-primary">
                <i class="fas fa-undo mr-2"></i> Reset Semua Filter
            </a>
        </div>
    @endif
</section>
@endsection
