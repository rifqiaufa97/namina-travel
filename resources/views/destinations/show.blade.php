@extends('layouts.app')

@section('title', $destination->name . ' - ' . config('app.name'))

@push('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($destination->description), 160) }}">
    <meta name="keywords" content="{{ $destination->name }}, {{ $destination->city }}, {{ $destination->province }}, wisata, liburan">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $destination->name }} - {{ config('app.name') }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($destination->description), 160) }}">
    @php
        $primaryImage = $destination->images->firstWhere('is_primary', true) ?? $destination->images->first();
    @endphp
    @if($primaryImage)
    <meta property="og:image" content="{{ asset('storage/' . $primaryImage->path) }}">
    @endif
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $destination->name }} - {{ config('app.name') }}">
    <meta property="twitter:description" content="{{ Str::limit(strip_tags($destination->description), 160) }}">
    @if($primaryImage)
    <meta property="twitter:image" content="{{ asset('storage/' . $primaryImage->path) }}">
    @endif
@endpush

@section('content')
<div class="bg-base-200/40 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6 overflow-x-auto whitespace-nowrap py-1">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors flex items-center gap-1.5">
                <i class="fas fa-home text-xs"></i>
                <span>Beranda</span>
            </a>
            <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
            <a href="{{ route('destinations.index') }}" class="hover:text-primary transition-colors">Destinasi</a>
            <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
            <span class="text-gray-900 font-medium truncate max-w-xs">{{ $destination->name }}</span>
        </nav>

        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="badge badge-primary font-semibold px-3 py-2.5 text-xs tracking-wide">
                    <i class="fas fa-map-marker-alt mr-1.5 text-xs"></i>{{ $destination->city }}, {{ $destination->province }}
                </span>
                @if($destination->is_featured)
                    <span class="badge badge-warning text-white font-semibold px-3 py-2.5 text-xs">
                        <i class="fas fa-crown mr-1.5 text-xs"></i>Pilihan Populer
                    </span>
                @endif
                @if($destination->duration)
                    <span class="badge badge-ghost bg-white/80 border-gray-200 text-gray-700 px-3 py-2.5 text-xs">
                        <i class="fas fa-clock mr-1.5 text-primary"></i>{{ $destination->duration }}
                    </span>
                @endif
            </div>
            
            <h1 class="text-2xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight mb-2">
                {{ $destination->name }}
            </h1>

            @php
                $averageRating = $destination->reviews->avg('rating') ?? 0;
                $reviewCount = $destination->reviews->count();
            @endphp
            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-200/60 px-2.5 py-1 rounded-md">
                    <i class="fas fa-star text-amber-500 text-sm"></i>
                    <span class="font-bold text-gray-900">{{ number_format($averageRating, 1) }}</span>
                    <span class="text-gray-500 text-xs">({{ $reviewCount }} ulasan)</span>
                </div>
                @if($destination->address)
                    <span class="hidden sm:inline text-gray-300">•</span>
                    <span class="flex items-center gap-1.5 text-gray-600">
                        <i class="fas fa-location-dot text-gray-400"></i>
                        <span class="truncate max-w-md">{{ $destination->address }}</span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Main Interactive Gallery -->
        <div class="mb-8">
            @php
                $images = $destination->images;
                $primaryImage = $images->firstWhere('is_primary', true) ?? $images->first();
            @endphp
            <div class="bg-white p-2 sm:p-3 rounded-2xl shadow-sm border border-gray-200/80">
                <div class="relative overflow-hidden rounded-xl bg-gray-100 aspect-[16/9] md:aspect-[21/9] max-h-[460px] w-full">
                    <img id="main-destination-image" 
                         src="{{ $primaryImage ? asset('storage/' . $primaryImage->path) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&auto=format&fit=crop&q=80' }}" 
                         alt="{{ $destination->name }}" 
                         class="w-full h-full object-cover transition-all duration-300 transform hover:scale-[1.01]" />
                    <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-md text-white text-xs px-3 py-1.5 rounded-full flex items-center gap-1.5 pointer-events-none">
                        <i class="fas fa-images"></i>
                        <span>{{ $images->count() > 0 ? $images->count() : 1 }} Foto</span>
                    </div>
                </div>

                @if($images->count() > 1)
                    <div class="flex gap-2.5 overflow-x-auto pt-3 pb-1 scrollbar-thin">
                        @foreach($images as $img)
                            <button type="button" class="flex-shrink-0 focus:outline-none group">
                                <img src="{{ asset('storage/' . $img->path) }}" 
                                     alt="{{ $destination->name }}" 
                                     class="thumbnail-img w-20 sm:w-24 h-16 sm:h-18 object-cover rounded-lg border-2 {{ $img->is_primary ? 'border-primary ring-2 ring-primary/20' : 'border-transparent hover:border-gray-300' }} transition-all duration-200 cursor-pointer" 
                                     data-img-src="{{ asset('storage/' . $img->path) }}" />
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- 2-Column Split Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Details, Timeline, Features, Reviews (8 Cols) -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Highlight Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-white p-4 rounded-2xl shadow-sm border border-gray-200/80">
                    <div class="flex items-center gap-3 p-2">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-base"></i>
                        </div>
                        <div>
                            <span class="block text-[11px] text-gray-500 uppercase font-semibold">Durasi</span>
                            <span class="font-bold text-gray-900 text-sm">{{ $destination->duration ?? '1 Hari Penuh' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-2">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-users text-base"></i>
                        </div>
                        <div>
                            <span class="block text-[11px] text-gray-500 uppercase font-semibold">Grup Tour</span>
                            <span class="font-bold text-gray-900 text-sm">Min. 12 Orang</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-2">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shield-halved text-base"></i>
                        </div>
                        <div>
                            <span class="block text-[11px] text-gray-500 uppercase font-semibold">Jaminan</span>
                            <span class="font-bold text-gray-900 text-sm">Resmi & Aman</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-2">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-bolt text-base"></i>
                        </div>
                        <div>
                            <span class="block text-[11px] text-gray-500 uppercase font-semibold">Konfirmasi</span>
                            <span class="font-bold text-gray-900 text-sm">Instan 1x24 Jam</span>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Lengkap -->
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-200/80">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-align-left text-primary"></i>
                        <span>Tentang Destinasi Wisata</span>
                    </h2>
                    <div class="prose max-w-none text-gray-700 leading-relaxed space-y-3">
                        {!! $destination->description !!}
                    </div>
                </div>

                <!-- Fasilitas Wisata -->
                @if($destination->formatted_facilities->count())
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-200/80">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-concierge-bell text-primary"></i>
                            <span>Fasilitas yang Disediakan</span>
                        </h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($destination->formatted_facilities as $facility)
                                <div class="flex items-center gap-3 bg-gray-50/80 border border-gray-100 p-3 rounded-xl hover:bg-gray-100/60 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                                        <i class="{{ $facility['icon'] }} text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-800">{{ $facility['name'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Itinerary Timeline -->
                @if($destination->itinerary && is_array($destination->itinerary) && count($destination->itinerary))
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-200/80">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-route text-primary"></i>
                            <span>Rencana Perjalanan (Itinerary)</span>
                        </h2>
                        <div class="relative border-l-2 border-primary/20 ml-4 space-y-6">
                            @foreach($destination->itinerary as $index => $item)
                                <div class="relative pl-6">
                                    <div class="absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold shadow-md shadow-primary/25">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="bg-gray-50/90 border border-gray-100 p-4 rounded-xl">
                                        <p class="text-gray-800 font-medium text-sm leading-relaxed">{{ $item }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Paket Termasuk & Tidak Termasuk -->
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-200/80">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-clipboard-check text-primary"></i>
                        <span>Paket Termasuk & Tidak Termasuk</span>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($destination->includes)
                            <div class="bg-emerald-50/60 border border-emerald-200/60 p-5 rounded-xl">
                                <h3 class="font-bold text-base text-emerald-800 mb-3 flex items-center gap-2">
                                    <i class="fas fa-check-circle text-emerald-600"></i> Termasuk dalam Paket
                                </h3>
                                <ul class="space-y-2.5">
                                    @foreach(explode("\n", $destination->includes) as $include)
                                        @if(trim($include))
                                            <li class="flex items-start gap-2.5 text-sm text-gray-700">
                                                <i class="fas fa-check text-emerald-500 mt-1 flex-shrink-0 text-xs"></i>
                                                <span>{{ trim(str_replace('-', '', $include)) }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($destination->excludes)
                            <div class="bg-rose-50/60 border border-rose-200/60 p-5 rounded-xl">
                                <h3 class="font-bold text-base text-rose-800 mb-3 flex items-center gap-2">
                                    <i class="fas fa-times-circle text-rose-600"></i> Tidak Termasuk
                                </h3>
                                <ul class="space-y-2.5">
                                    @foreach(explode("\n", $destination->excludes) as $exclude)
                                        @if(trim($exclude))
                                            <li class="flex items-start gap-2.5 text-sm text-gray-700">
                                                <i class="fas fa-times text-rose-500 mt-1 flex-shrink-0 text-xs"></i>
                                                <span>{{ trim(str_replace('-', '', $exclude)) }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Penting: Yang Perlu Dibawa & Kebijakan -->
                @if($destination->what_to_bring || $destination->cancellation_policy)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($destination->what_to_bring)
                            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/80">
                                <h3 class="font-bold text-base text-gray-900 mb-3 flex items-center gap-2">
                                    <i class="fas fa-backpack text-primary"></i> Yang Perlu Dibawa
                                </h3>
                                <ul class="space-y-2 text-sm text-gray-700">
                                    @foreach(explode("\n", $destination->what_to_bring) as $item)
                                        @if(trim($item))
                                            <li class="flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></span>
                                                <span>{{ $item }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($destination->cancellation_policy)
                            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/80">
                                <h3 class="font-bold text-base text-gray-900 mb-3 flex items-center gap-2">
                                    <i class="fas fa-handshake-angle text-primary"></i> Kebijakan Pembatalan
                                </h3>
                                <div class="text-sm text-gray-700 leading-relaxed space-y-2">
                                    {!! nl2br(e($destination->cancellation_policy)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Peta Lokasi Google Maps -->
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-200/80">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marked-alt text-primary"></i>
                        <span>Lokasi Destinasi</span>
                    </h2>
                    @if($destination->address)
                        <p class="text-sm text-gray-600 mb-4 flex items-start gap-2">
                            <i class="fas fa-location-dot text-primary mt-1"></i>
                            <span>{{ $destination->address }}</span>
                        </p>
                    @endif
                    <div class="w-full h-80 rounded-xl overflow-hidden shadow-inner border border-gray-200">
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            style="border:0" 
                            src="https://maps.google.com/maps?q={{ urlencode($destination->address ?? $destination->city . ', ' . $destination->province) }}&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <!-- Ulasan Pengunjung -->
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-200/80">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-comment-dots text-primary"></i>
                                <span>Ulasan Pengunjung</span>
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">Pengalaman nyata dari pelanggan kami</p>
                        </div>
                        <div class="flex items-center gap-2 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200/60">
                            <i class="fas fa-star text-amber-500 text-sm"></i>
                            <span class="font-bold text-gray-900 text-sm">{{ number_format($averageRating, 1) }}</span>
                            <span class="text-gray-500 text-xs">/ 5.0</span>
                        </div>
                    </div>

                    @php
                        $reviews = $destination->reviews()->with('user')->latest()->take(5)->get();
                    @endphp
                    @if($reviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($reviews as $review)
                                <div class="bg-gray-50/80 rounded-xl p-4 sm:p-5 border border-gray-100 flex gap-4 items-start">
                                    <div class="w-11 h-11 rounded-full bg-primary/10 text-primary flex-shrink-0 flex items-center justify-center font-bold text-sm overflow-hidden">
                                        @if($review->user && $review->user->avatar)
                                            <img src="{{ asset('storage/' . $review->user->avatar) }}" alt="{{ $review->user->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span>{{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-1">
                                            <span class="font-bold text-gray-900 text-sm truncate">{{ $review->user->name ?? 'Wisatawan' }}</span>
                                            <span class="text-xs text-gray-400">{{ $review->created_at->translatedFormat('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}"></i>
                                            @endfor
                                        </div>
                                        @if($review->comment)
                                            <p class="text-gray-700 text-sm leading-relaxed">{{ $review->comment }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <i class="far fa-comment-dots text-3xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 text-sm font-medium">Belum ada ulasan untuk destinasi ini.</p>
                            <p class="text-gray-400 text-xs mt-1">Jadilah yang pertama memberikan ulasan setelah melakukan perjalanan!</p>
                        </div>
                    @endif

                    <!-- Form Tambah Ulasan -->
                    @auth
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <h3 class="font-bold text-base text-gray-900 mb-3 flex items-center gap-2">
                                <i class="fas fa-pen text-primary text-xs"></i>
                                <span>Tulis Ulasan Anda</span>
                            </h3>
                            <form method="POST" action="{{ route('reviews.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="destination_id" value="{{ $destination->id }}">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Rating Bintang</label>
                                    <select name="rating" class="select select-bordered select-sm w-full max-w-xs rounded-lg" required>
                                        <option value="">Pilih rating</option>
                                        <option value="5">⭐⭐⭐⭐⭐ (5 - Luar Biasa)</option>
                                        <option value="4">⭐⭐⭐⭐ (4 - Sangat Bagus)</option>
                                        <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                        <option value="2">⭐⭐ (2 - Kurang Puas)</option>
                                        <option value="1">⭐ (1 - Buruk)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Pengalaman Anda</label>
                                    <textarea name="comment" class="textarea textarea-bordered w-full rounded-xl text-sm" rows="3" placeholder="Ceritakan bagaimana pengalaman perjalanan Anda..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm px-5 rounded-lg shadow-sm">Kirim Ulasan</button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Right Column: Sticky Booking Widget (4 Cols) -->
            <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-5">
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200/80 relative">
                    <!-- Price Tag -->
                    <div class="border-b border-gray-100 pb-5 mb-5">
                        <span class="text-xs uppercase font-semibold text-gray-400 tracking-wider block mb-1">Mulai dari</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-3xl font-extrabold text-primary tracking-tight">
                                Rp {{ number_format($destination->price, 0, ',', '.') }}
                            </span>
                            <span class="text-sm text-gray-500 font-medium">/ orang</span>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">Minimal kuota grup: 12 orang</p>
                    </div>

                    <!-- Key Perks -->
                    <div class="space-y-3 mb-6 text-sm text-gray-600">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                            <span>Transfer Manual BCA Terverifikasi</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                            <span>Konfirmasi Cepat 1x24 Jam</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                            <span>Tour Guide & Dokumentasi Tersedia</span>
                        </div>
                    </div>

                    <!-- CTA Actions -->
                    <div class="space-y-3">
                        @auth
                            <a href="{{ route('booking.form', $destination->id) }}" class="btn btn-primary btn-block text-base font-bold shadow-md shadow-primary/25 rounded-xl h-12">
                                <i class="fas fa-ticket-alt mr-2"></i> Booking Sekarang
                            </a>
                        @else
                            <button onclick="event.preventDefault(); document.getElementById('loginModal').showModal();" class="btn btn-primary btn-block text-base font-bold shadow-md shadow-primary/25 rounded-xl h-12">
                                <i class="fas fa-lock mr-2"></i> Login untuk Booking
                            </button>
                        @endauth

                        <!-- Wishlist Button -->
                        @php
                            $isInWishlist = auth()->check() && auth()->user()->hasInWishlist($destination->id);
                        @endphp
                        <button 
                            id="wishlist-button"
                            class="btn btn-block {{ $isInWishlist ? 'btn-outline btn-error text-red-600' : 'btn-outline border-gray-300 hover:border-primary text-gray-700' }} rounded-xl h-11"
                            data-destination-id="{{ $destination->id }}"
                            data-in-wishlist="{{ $isInWishlist ? 'true' : 'false' }}"
                            type="button"
                        >
                            <i class="{{ $isInWishlist ? 'fas' : 'far' }} fa-heart mr-2 text-red-500"></i>
                            <span id="wishlist-text">{{ $isInWishlist ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist' }}</span>
                        </button>
                    </div>

                    <!-- Customer Support Mini Card -->
                    <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>Punya pertanyaan rute?</span>
                        <a href="https://wa.me/6281282069087?text=Halo%20Namina%20Travel,%20saya%20ingin%20tanya%20tentang%20{{ urlencode($destination->name) }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="text-emerald-600 font-bold hover:underline flex items-center gap-1">
                            <i class="fab fa-whatsapp text-sm"></i> Chat Admin
                        </a>
                    </div>
                </div>

                <!-- Guarantee Card -->
                <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-4 text-xs text-blue-900 flex items-start gap-3">
                    <i class="fas fa-shield-alt text-blue-600 text-lg mt-0.5 flex-shrink-0"></i>
                    <div>
                        <span class="font-bold block mb-0.5">Jaminan Kepuasan Perjalanan</span>
                        <p class="text-blue-700 leading-relaxed">
                            Pemesanan Anda dilindungi sistem transfer manual BCA resmi Namina Travel dengan transparansi bukti dan invoice langsung.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // CSRF Token setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Wishlist functionality
    $(document).ready(function() {
        $('#wishlist-button').on('click', function(e) {
            e.preventDefault();
            
            const button = $(this);
            const destinationId = button.data('destination-id');
            const isInWishlist = button.data('in-wishlist') === 'true';
            const url = isInWishlist ? 
                `/api/wishlist/${destinationId}` : 
                `/api/wishlist/${destinationId}`;
            const method = isInWishlist ? 'DELETE' : 'POST';
            
            $.ajax({
                url: url,
                type: method,
                success: function(response) {
                    if (response.success) {
                        // Toggle button state
                        const newState = !isInWishlist;
                        button.data('in-wishlist', newState);
                        
                        // Update button text and icon
                        const buttonText = newState ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist';
                        const iconClass = newState ? 'fas' : 'far';
                        
                        button.find('i').attr('class', `${iconClass} fa-heart me-2`);
                        button.find('#wishlist-text').text(buttonText);
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Update wishlist count in navbar if exists
                        const wishlistCount = $('.wishlist-count');
                        if (wishlistCount.length && response.wishlistCount !== undefined) {
                            wishlistCount.text(response.wishlistCount);
                        }
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401 && typeof showLoginAlert === 'function') {
                        showLoginAlert('untuk menyimpan destinasi ke wishlist');
                        return;
                    }

                    const response = xhr.responseJSON;
                    let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                    
                    if (response && response.message) {
                        errorMessage = response.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage
                    });
                }
            });
        });
    });
</script>

<script>
    function showLoginAlert(action = 'untuk melanjutkan') {
        Swal.fire({
            title: 'Login Diperlukan',
            text: 'Silakan login terlebih dahulu ' + action,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Login',
            cancelButtonText: 'Nanti Saja',
            confirmButtonColor: '#4F46E5',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route('login') }}';
            }
        });
    }
    
    // Initialize date picker
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const dateInput = document.querySelector('input[name="departure_date"]');
        if (dateInput) {
            dateInput.min = tomorrow.toISOString().split('T')[0];
        }
    });
</script>

<!-- Custom styles for lightbox -->
<style>
    .fancybox__toolbar {
        background: rgba(0, 0, 0, 0.5);
    }
    .fancybox__thumbs .carousel__slide {
        padding: 8px 4px;
    }
    .fancybox__thumbs .carousel__slide .fancybox__thumb::after {
        border: 2px solid #3b82f6;
    }
    .fancybox__toolbar__items--left,
    .fancybox__toolbar__items--right {
        margin: 0;
    }
    .fancybox__toolbar__items--left button,
    .fancybox__toolbar__items--right button {
        width: 44px;
        height: 44px;
    }
</style>

<script>
    // Galeri interaktif: klik thumbnail ganti gambar utama
    document.addEventListener('DOMContentLoaded', function() {
        const mainImg = document.getElementById('main-destination-image');
        document.querySelectorAll('.thumbnail-img').forEach(thumb => {
            thumb.addEventListener('click', function() {
                if (mainImg) {
                    mainImg.src = this.dataset.imgSrc;
                    // Highlight thumbnail aktif
                    document.querySelectorAll('.thumbnail-img').forEach(t => t.classList.remove('ring', 'ring-primary'));
                    this.classList.add('ring', 'ring-primary');
                }
            });
        });
    });
</script>
@endpush
