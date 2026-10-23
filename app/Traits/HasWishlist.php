<?php

namespace App\Traits;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasWishlist
{
    /**
     * Get the user's wishlist items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
    
    /**
     * Get the destinations that the user has wishlisted.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function wishlistedDestinations()
    {
        return $this->belongsToMany(
            'App\Models\Destination',
            'wishlists',
            'user_id',
            'destination_id'
        )->withTimestamps()
         ->withPivot('created_at', 'updated_at');
    }
    
    /**
     * Check if a destination is in the user's wishlist.
     *
     * @param  int  $destinationId
     * @return bool
     */
    public function hasInWishlist($destinationId): bool
    {
        return $this->wishlists()->where('destination_id', $destinationId)->exists();
    }
    
    /**
     * Scope a query to only include users with wishlisted destinations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithWishlistedDestinations($query)
    {
        return $query->with(['wishlistedDestinations' => function($q) {
            $q->with('images');
        }]);
    }
    
    /**
     * Get the user's wishlist count.
     *
     * @return int
     */
    public function getWishlistCountAttribute(): int
    {
        return $this->wishlists_count ?? $this->wishlists()->count();
    }
    
    /**
     * Check if the user has any wishlisted items.
     *
     * @return bool
     */
    public function hasWishlistedItems(): bool
    {
        return $this->wishlists()->exists();
    }
}
