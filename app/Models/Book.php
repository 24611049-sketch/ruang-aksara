<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $judul
 * @property string $penulis
 * @property string|null $deskripsi
 * @property float $harga
 * @property string|null $kategori
 * @property string|null $penerbit
 * @property string|null $isbn
 * @property int|null $halaman
 * @property int $stok
 * @property int|null $loan_stok
 * @property string|null $berat
 * @property string $status
 * @property int $terjual
 * @property string|null $image
 */
class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'penulis',
        'deskripsi',
        'harga',
        'purchase_price',
        'profit_margin_percent',
        'kategori',
        'penerbit',
        'isbn',
        'halaman',
        'stok',
        'berat',
        'status',
        'terjual',
        'image',
        'loan_stok'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'profit_margin_percent' => 'decimal:2'
    ];

    /**
     * Hitung harga jual berdasarkan harga beli dan margin keuntungan
     * Formula: Harga Jual = Harga Beli ÷ (1 - Margin%)
     */
    public function calculateSellingPrice()
    {
        if ($this->purchase_price <= 0) {
            return $this->harga;
        }
        
        $margin = ($this->profit_margin_percent ?? 35) / 100;
        $sellingPrice = $this->purchase_price / (1 - $margin);
        
        return round($sellingPrice, 0);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    // Get average rating
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    // Get total reviews count
    public function getTotalReviewsAttribute()
    {
        return $this->approvedReviews()->count();
    }

    // Simple accessor untuk kompatibilitas - hanya access Indonesian fields yang ada di database
    public function getJudulAttribute()
    {
        return $this->attributes['judul'] ?? null;
    }

    public function getPenulisAttribute()
    {
        return $this->attributes['penulis'] ?? null;
    }

    public function getHargaAttribute()
    {
        return $this->attributes['harga'] ?? null;
    }

    public function getHalamanAttribute()
    {
        return $this->attributes['halaman'] ?? null;
    }

    public function getStokAttribute()
    {
        return $this->attributes['stok'] ?? 0;
    }

    public function getDeskripsiAttribute()
    {
        return $this->attributes['deskripsi'] ?? null;
    }

    // Alias untuk compatibility dengan English names (jika diperlukan di view)
    public function getTitleAttribute()
    {
        return $this->judul;
    }

    public function getAuthorAttribute()
    {
        return $this->penulis;
    }

    public function getPriceAttribute()
    {
        return $this->harga;
    }

    public function getPagesAttribute()
    {
        return $this->halaman;
    }

    public function getStockAttribute()
    {
        return $this->stok;
    }

    public function getLoanStockAttribute()
    {
        return $this->loan_stok ?? 0;
    }

    public function getIsPublishedAttribute()
    {
        return ($this->attributes['status'] ?? $this->status) === 'available';
    }

    public function getDescriptionAttribute()
    {
        return $this->deskripsi;
    }

    // Scope untuk buku yang published
    public function scopePublished($query)
    {
        return $query->where('status', 'tersedia');
    }

    // Accessor untuk URL gambar
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/book-covers/' . $this->image);
        }
        return asset('images/default-book.jpg');
    }
}