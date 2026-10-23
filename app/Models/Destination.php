<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\DestinationImage;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Support\Str;

class Destination extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'includes',
        'excludes',
        'location',
        'province',
        'city',
        'address',
        'image', // Keeping for backward compatibility
        'price',
        'rating',
        'total_reviews',
        'is_featured',
        'is_active',
        'created_by',
        'duration',
        'max_people',
        'accommodation',
        'facilities',
        'itinerary',
        'price_include',
        'price_exclude',
        'what_to_bring',
        'cancellation_policy',
        'additional_info'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
        'rating' => 'float',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
        'itinerary' => 'array',
        'max_people' => 'integer',
    ];

    protected $appends = ['formatted_facilities'];

    /**
     * Get the formatted facilities as an array with icons
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function getFormattedFacilitiesAttribute()
    {
        try {
            $facilities = [];
            
            if (!empty($this->facilities)) {
                $icons = [
                    'transportasi' => 'fas fa-bus',
                    'guide' => 'fas fa-user-tie',
                    'tiket' => 'fas fa-ticket-alt',
                    'makan' => 'fas fa-utensils',
                    'hotel' => 'fas fa-hotel',
                    'menginap' => 'fas fa-bed',
                    'asuransi' => 'fas fa-shield-alt',
                    'airport' => 'fas fa-plane',
                    'wifi' => 'fas fa-wifi',
                    'kolam renang' => 'fas fa-swimming-pool',
                ];

                $facilityItems = is_string($this->facilities) ? explode(',', $this->facilities) : [];
                
                foreach ($facilityItems as $facility) {
                    if (!is_string($facility)) continue;
                    
                    $facility = trim($facility);
                    if (empty($facility)) continue;
                    
                    $icon = 'fas fa-check-circle'; // default icon

                    // Find a matching icon
                    foreach ($icons as $key => $iconClass) {
                        if (stripos(strtolower($facility), $key) !== false) {
                            $icon = $iconClass;
                            break;
                        }
                    }

                    $facilities[] = [
                        'name' => $facility,
                        'icon' => $icon
                    ];
                }
            }


            return collect($facilities);
        } catch (\Exception $e) {
            \Log::error('Error in getFormattedFacilitiesAttribute: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Get the user that created the destination.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all orders for this destination.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all images for the destination.
     */
    public function images()
    {
        return $this->hasMany(DestinationImage::class)->orderBy('order');
    }

    /**
     * Get the reviews for the destination.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Get all of the wishlists for the destination.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
    
    /**
     * Get all of the users who have wishlisted this destination.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function wishlistedByUsers()
    {
        return $this->belongsToMany(
            User::class,
            'wishlists',
            'destination_id',
            'user_id'
        )->withTimestamps();
    }
    
    /**
     * Check if the destination is in the current user's wishlist.
     *
     * @return bool
     */
    public function getIsInWishlistAttribute(): bool
    {
        if (!auth()->check()) {
            return false;
        }
        
        if (isset($this->is_in_wishlist)) {
            return $this->is_in_wishlist;
        }
        
        return $this->wishlists()->where('user_id', auth()->id())->exists();
    }
    
    /**
     * Get the number of users who have wishlisted this destination.
     *
     * @return int
     */
    public function getWishlistCountAttribute(): int
    {
        return $this->wishlists_count ?? $this->wishlists()->count();
    }
    
    /**
     * Scope a query to only include destinations that are wishlisted by a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWishlistedByUser($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        if (!$userId) {
            return $query->whereRaw('1=0'); // Return no results if no user is authenticated
        }
        
        return $query->whereHas('wishlists', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
    
    /**
     * Get the facility items for the destination.
     */
    public function facilityItems()
    {
        return $this->belongsToMany(Facility::class, 'destination_facility');
    }

    /**
     * Get the primary image for the destination.
     */
    public function getPrimaryImageAttribute()
    {
        return $this->images->firstWhere('is_primary', true) ?? $this->images->first();
    }

    /**
     * Get the thumbnail URL attribute.
     *
     * @return string
     */
    public function getThumbnailUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/placeholder.jpg');
    }

    /**
     * Get the formatted price with currency.
     *
     * @return string
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
    
    /**
     * Scope a query to search destinations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('city', 'like', "%{$search}%")
              ->orWhere('province', 'like', "%{$search}%");
        });
    }
    
    /**
     * Scope a query to filter by price range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  float  $min
     * @param  float  $max
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }
    
    /**
     * Scope a query to filter by location.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $city
     * @param  string  $province
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLocation($query, $city = null, $province = null)
    {
        if ($city) {
            $query->where('city', $city);
        }
        
        if ($province) {
            $query->where('province', $province);
        }
        
        return $query;
    }
    
    /**
     * Scope a query to filter by minimum rating.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $rating
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMinRating($query, $rating)
    {
        return $query->where('rating', '>=', $rating);
    }
    
    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($destination) {
            $destination->slug = Str::slug($destination->name);
            if (auth()->check()) {
                $destination->created_by = auth()->id();
            }
        });

        static::updating(function ($destination) {
            $destination->slug = Str::slug($destination->name);
        });
    }

    /**
     * Scope a query to only include active destinations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured destinations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
