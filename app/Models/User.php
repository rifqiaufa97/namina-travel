<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasWishlist;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'date_of_birth',
        'role',
        'avatar',
        'is_admin',
        'status',
        'email_verified_at',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'last_login_at' => 'datetime',
    ];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date_of_birth',
        'created_at',
        'updated_at',
        'deleted_at',
        'email_verified_at',
        'last_login_at',
    ];
    
    protected $attributes = [
        'is_admin' => false,
        'status' => 'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get all orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    use HasWishlist;

    /**
     * Check if the user is an admin.
     * Checks both is_admin flag and role field for backward compatibility.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin === true || $this->role === 'admin';
    }
}
