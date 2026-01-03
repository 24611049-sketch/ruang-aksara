<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $role
 * @property int|null $points
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Relations\HasMany $orders
 * @property-read \Illuminate\Database\Eloquent\Relations\HasMany $wishlists
 * @property-read \Illuminate\Database\Eloquent\Relations\HasMany $loans
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'points',
        'alamat',
        'province',
        'city',
        'district',
        'telepon',
        'tanggal_lahir',
        'foto_profil',
        'google_id',
        'google_token',
        'google_refresh_token',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'points' => 'integer',
        'tanggal_lahir' => 'date',
        'province' => 'string',
        'city' => 'string',
        'district' => 'string',
    ];

    /**
     * ✅ FIX: OVERRIDE CONSTRUCTOR UNTUK MEMASTIKAN ID ADA
     */
    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($user) {
            // Pastikan user memiliki ID
            $attrs = is_array($user->attributes) ? $user->attributes : [];
            if (!isset($attrs['id'])) {
                \Log::error('User retrieved without ID', ['user' => $user, 'available_attributes' => array_keys($attrs)]);
            }
        });
    }

    /**
     * ✅ FIX: SAFE PROPERTY ACCESS
     */
    public function __get($key)
    {
        try {
            // Handle khusus untuk 'id'
            if ($key === 'id') {
                $attributes = is_array($this->attributes) ? $this->attributes : [];
                if (!array_key_exists('id', $attributes)) {
                    \Log::error('User ID property missing', [
                        'user_email' => $attributes['email'] ?? 'unknown',
                        'attributes' => array_keys($attributes)
                    ]);
                    return null;
                }
            }

            return parent::__get($key);
        } catch (\Exception $e) {
            \Log::warning("Access undefined property: {$key} - " . $e->getMessage());
            return null;
        }
    }

    /**
     * ✅ FIX: SAFE ID GETTER
     */
    public function getIdAttribute()
    {
        try {
            $attributes = is_array($this->attributes) ? $this->attributes : [];
            if (array_key_exists('id', $attributes)) {
                return $attributes['id'];
            }

            \Log::error('User ID attribute missing in attributes array', [
                'available_attributes' => array_keys($attributes)
            ]);

            // Fallback: coba dapatkan ID dari route binding atau lainnya
            if (!empty($attributes['email'])) {
                $user = self::where('email', $attributes['email'])->first();
                if ($user && $user->getKey()) {
                    $this->attributes['id'] = $user->getKey();
                    return $this->attributes['id'];
                }
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Error getting user ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * ✅ FIX: GET KEY FOR ROUTE BINDING
     */
    public function getRouteKey()
    {
        return $this->getIdAttribute() ?? $this->getKey();
    }

    // Relasi ke orders
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Relasi ke wishlists
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // Relasi ke loans (peminjaman buku)
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    // Method helper untuk role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * ✅ FIX: SAFE STATISTICS METHODS
     */
    public function getTotalOrdersAttribute(): int
    {
        try {
            return $this->orders()->count();
        } catch (\Exception $e) {
            \Log::error('Error counting orders: ' . $e->getMessage());
            return 0;
        }
    }

    public function getTotalWishlistsAttribute(): int
    {
        try {
            return $this->wishlists()->count();
        } catch (\Exception $e) {
            \Log::error('Error counting wishlists: ' . $e->getMessage());
            return 0;
        }
    }

    public function getMemberSinceAttribute(): string
    {
        try {
            return $this->created_at->format('M Y');
        } catch (\Exception $e) {
            \Log::error('Error getting member since: ' . $e->getMessage());
            return 'Unknown';
        }
    }
}