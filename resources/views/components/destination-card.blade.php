@props([
    'destination',
    'showWishlist' => true,
    'showRating' => true,
    'showPrice' => true,
    'showLocation' => true,
    'class' => '',
])

@php
    $isInWishlist = auth()->check() ? auth()->user()->hasInWishlist($destination->id) : false;
    $wishlistClass = $isInWishlist ? 'text-red-500' : 'text-gray-400';
    $wishlistTitle = $isInWishlist ? 'Hapus dari wishlist' : 'Tambahkan ke wishlist';
    $primaryImage = $destination->images->where('is_primary', true)->first() ?? $destination->images->first();
    $imageUrl = $primaryImage ? asset('storage/' . $primaryImage->path) : asset('images/placeholder-destination.jpg');
@endphp

<div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow duration-300 {{ $class }}" data-destination-id="{{ $destination->id }}">
    <figure class="relative">
        <div class="aspect-w-16 aspect-h-9 w-full h-48 overflow-hidden">
            <img 
                src="{{ $imageUrl }}" 
                alt="{{ $destination->name }}"
                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                loading="lazy"
            >
        </div>
        
        <!-- Wishlist Button -->
        @if($showWishlist)
            <button 
                class="absolute top-3 right-3 z-10 w-10 h-10 flex items-center justify-center bg-white/80 rounded-full shadow-md hover:bg-red-100 transition-colors duration-200 wishlist-toggle {{ $wishlistClass }}"
                data-destination-id="{{ $destination->id }}"
                data-wishlist-action="toggle"
                title="{{ $wishlistTitle }}"
                type="button"
                @if(!auth()->check()) 
                    onclick="event.preventDefault(); document.getElementById('loginModal').showModal();" 
                @endif
            >
                <i class="fas fa-heart text-lg"></i>
            </button>
        @endif
        
        <!-- Badges -->
        <div class="absolute bottom-2 left-2 flex gap-2">
            @if($destination->is_featured)
                <span class="badge badge-primary">Featured</span>
            @endif
            @if($destination->is_popular)
                <span class="badge badge-secondary">Popular</span>
            @endif
        </div>
    </figure>
    
    <div class="card-body p-4">
        <h2 class="card-title text-lg font-semibold line-clamp-2 h-14">
            <a href="{{ route('destinations.show', $destination->slug) }}" class="hover:text-primary transition-colors">
                {{ $destination->name }}
            </a>
        </h2>
        
        @if($showLocation)
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <i class="fas fa-map-marker-alt mr-1.5"></i>
                <span class="truncate">{{ $destination->city }}, {{ $destination->province }}</span>
            </div>
        @endif
        
        @if($showRating)
            @php
                $averageRating = $destination->reviews->avg('rating') ?? 0;
                $reviewCount = $destination->reviews->count();
            @endphp
            @if($reviewCount > 0)
                <div class="flex items-center mb-2">
                    <div class="rating rating-xs">
                        @for($i = 1; $i <= 5; $i++)
                            <input 
                                type="radio" 
                                {{ $i <= round($averageRating) ? 'checked' : '' }}
                                class="mask mask-star-2 {{ $i <= round($averageRating) ? 'bg-yellow-400' : 'bg-gray-300' }}" 
                                disabled
                            >
                        @endfor
                    </div>
                    <div class="flex items-center">
                        <span class="text-xs ml-1.5 font-medium">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-xs text-gray-500 ml-1">({{ $reviewCount }} ulasan)</span>
                    </div>
                </div>
            @else
                <div class="text-xs text-gray-500 mb-2">Belum ada ulasan</div>
            @endif
        @endif
        
        @if($showPrice)
            <div class="card-actions justify-between items-center mt-2">
                <div>
                    <span class="text-lg font-bold text-primary">{{ format_currency($destination->price) }}</span>
                    @if($destination->discount > 0)
                        <span class="text-sm text-gray-500 line-through ml-1">{{ format_currency($destination->price + $destination->discount) }}</span>
                    @endif
                    <div class="text-xs text-gray-500">per orang</div>
                </div>
                <a 
                    href="{{ route('destinations.show', $destination->slug) }}" 
                    class="btn btn-primary btn-sm"
                >
                    Lihat Detail
                </a>
            </div>
        @else
            <div class="mt-2">
                <a 
                    href="{{ route('destinations.show', $destination->slug) }}" 
                    class="btn btn-primary btn-sm w-full"
                >
                    Lihat Detail
                </a>
            </div>
        @endif
    </div>
</div>
