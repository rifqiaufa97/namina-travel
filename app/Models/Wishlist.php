<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Wishlist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wishlists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'destination_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Add global scope to only include active users and destinations
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->whereHas('user', function($q) {
                $q->where('status', 'active');
            })->whereHas('destination', function($q) {
                $q->where('is_active', true);
            });
        });
        
        // Update destination's wishlist_count when a wishlist item is created
        static::created(function ($wishlist) {
            $wishlist->destination()->increment('wishlist_count');
        });
        
        // Update destination's wishlist_count when a wishlist item is deleted
        static::deleted(function ($wishlist) {
            $destination = $wishlist->destination;
            if ($destination && $destination->wishlist_count > 0) {
                $destination->decrement('wishlist_count');
            }
        });
    }

    /**
     * Get the user that owns the wishlist item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the destination that is wishlisted.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class)->with('images');
    }
    
    /**
     * Scope a query to only include wishlist items for the current user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|null  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCurrentUser($query, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        return $query->where('user_id', $userId);
    }
    
    /**
     * Scope a query to only include wishlist items for a specific destination.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $destinationId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDestination($query, $destinationId)
    {
        return $query->where('destination_id', $destinationId);
    }
    
    /**
     * Add a destination to the user's wishlist.
     *
     * @param  int  $userId
     * @param  int  $destinationId
     * @return \App\Models\Wishlist|bool
     */
    public static function addToWishlist($userId, $destinationId)
    {
        // Check if already in wishlist
        if (static::where('user_id', $userId)->where('destination_id', $destinationId)->exists()) {
            return false;
        }
        
        return static::create([
            'user_id' => $userId,
            'destination_id' => $destinationId
        ]);
    }
    
    /**
     * Remove a destination from the user's wishlist.
     *
     * @param  int  $userId
     * @param  int  $destinationId
     * @return bool
     */
    public static function removeFromWishlist($userId, $destinationId)
    {
        $wishlistItem = static::where('user_id', $userId)
            ->where('destination_id', $destinationId)
            ->first();
            
        if ($wishlistItem) {
            return $wishlistItem->delete();
        }
        
        return false;
    }
}
