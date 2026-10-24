<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'destination',
    'showWishlist' => true,
    'showRating' => true,
    'showPrice' => true,
    'showLocation' => true,
    'class' => '',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'destination',
    'showWishlist' => true,
    'showRating' => true,
    'showPrice' => true,
    'showLocation' => true,
    'class' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $isInWishlist = auth()->check() ? auth()->user()->hasInWishlist($destination->id) : false;
    $wishlistClass = $isInWishlist ? 'text-red-500' : 'text-gray-400';
    $wishlistTitle = $isInWishlist ? 'Hapus dari wishlist' : 'Tambahkan ke wishlist';
    $primaryImage = $destination->images->where('is_primary', true)->first() ?? $destination->images->first();
    $imageUrl = $primaryImage ? asset('storage/' . $primaryImage->path) : asset('images/placeholder-destination.jpg');
?>

<div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow duration-300 <?php echo e($class); ?>" data-destination-id="<?php echo e($destination->id); ?>">
    <figure class="relative">
        <div class="aspect-w-16 aspect-h-9 w-full h-48 overflow-hidden">
            <img 
                src="<?php echo e($imageUrl); ?>" 
                alt="<?php echo e($destination->name); ?>"
                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                loading="lazy"
            >
        </div>
        
        <!-- Wishlist Button -->
        <?php if($showWishlist): ?>
            <button 
                class="absolute top-3 right-3 z-10 w-10 h-10 flex items-center justify-center bg-white/80 rounded-full shadow-md hover:bg-red-100 transition-colors duration-200 wishlist-toggle <?php echo e($wishlistClass); ?>"
                data-destination-id="<?php echo e($destination->id); ?>"
                data-wishlist-action="toggle"
                title="<?php echo e($wishlistTitle); ?>"
                type="button"
                <?php if(!auth()->check()): ?> 
                    onclick="event.preventDefault(); document.getElementById('loginModal').showModal();" 
                <?php endif; ?>
            >
                <i class="fas fa-heart text-lg"></i>
            </button>
        <?php endif; ?>
        
        <!-- Badges -->
        <div class="absolute bottom-2 left-2 flex gap-2">
            <?php if($destination->is_featured): ?>
                <span class="badge badge-primary">Featured</span>
            <?php endif; ?>
            <?php if($destination->is_popular): ?>
                <span class="badge badge-secondary">Popular</span>
            <?php endif; ?>
        </div>
    </figure>
    
    <div class="card-body p-4">
        <h2 class="card-title text-lg font-semibold line-clamp-2 h-14">
            <a href="<?php echo e(route('destinations.show', $destination->slug)); ?>" class="hover:text-primary transition-colors">
                <?php echo e($destination->name); ?>

            </a>
        </h2>
        
        <?php if($showLocation): ?>
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <i class="fas fa-map-marker-alt mr-1.5"></i>
                <span class="truncate"><?php echo e($destination->city); ?>, <?php echo e($destination->province); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if($showRating): ?>
            <?php
                $averageRating = $destination->reviews->avg('rating') ?? 0;
                $reviewCount = $destination->reviews->count();
            ?>
            <?php if($reviewCount > 0): ?>
                <div class="flex items-center mb-2">
                    <div class="rating rating-xs">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <input 
                                type="radio" 
                                <?php echo e($i <= round($averageRating) ? 'checked' : ''); ?>

                                class="mask mask-star-2 <?php echo e($i <= round($averageRating) ? 'bg-yellow-400' : 'bg-gray-300'); ?>" 
                                disabled
                            >
                        <?php endfor; ?>
                    </div>
                    <div class="flex items-center">
                        <span class="text-xs ml-1.5 font-medium"><?php echo e(number_format($averageRating, 1)); ?></span>
                        <span class="text-xs text-gray-500 ml-1">(<?php echo e($reviewCount); ?> ulasan)</span>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-xs text-gray-500 mb-2">Belum ada ulasan</div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if($showPrice): ?>
            <div class="card-actions justify-between items-center mt-2">
                <div>
                    <span class="text-lg font-bold text-primary"><?php echo e(format_currency($destination->price)); ?></span>
                    <?php if($destination->discount > 0): ?>
                        <span class="text-sm text-gray-500 line-through ml-1"><?php echo e(format_currency($destination->price + $destination->discount)); ?></span>
                    <?php endif; ?>
                    <div class="text-xs text-gray-500">per orang</div>
                </div>
                <a 
                    href="<?php echo e(route('destinations.show', $destination->slug)); ?>" 
                    class="btn btn-primary btn-sm"
                >
                    Lihat Detail
                </a>
            </div>
        <?php else: ?>
            <div class="mt-2">
                <a 
                    href="<?php echo e(route('destinations.show', $destination->slug)); ?>" 
                    class="btn btn-primary btn-sm w-full"
                >
                    Lihat Detail
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\Rifqi Aufa Rachmadi\Documents\Penulisan Ilmiah Aufa\nina-travel-2\resources\views/components/destination-card.blade.php ENDPATH**/ ?>