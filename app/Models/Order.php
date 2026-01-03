<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $book_id
 * @property int $quantity
 * @property float $total_price
 * @property float $shipping_cost
 * @property string $status
 * @property string|null $tracking_number
 * @property int|null $order_group_id
 * @property string|null $alamat
 * @property string|null $telepon
 * @property string|null $payment_method
 * @property string|null $bank_account
 * @property string|null $shipping_method
 * @property string|null $proof_of_payment
 * @property string $payment_status
 * @property \Illuminate\Support\Carbon|null $delivered_at
 * @property bool $confirmed_by_user
 * @property int|null $user_rating
 * @property string|null $user_review
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'quantity',
        'total_price',
        'status',
        'tracking_number',
        'order_group_id',
        'alamat',
        'telepon',
        'payment_method',
        'bank_account',
        'shipping_method',
        'shipping_cost',
        'proof_of_payment',
        'payment_status',
        'delivered_at',
        'confirmed_by_user',
        'user_rating',
        'user_review',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
        'confirmed_by_user' => 'boolean',
        'user_rating' => 'integer',
        'user_review' => 'string',
        'total_price' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
    ];

    // Relationship dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship dengan book (deprecated - use items().book instead)
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // NEW: Relationship dengan order_items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Get all books in this order
    public function books()
    {
        return $this->hasManyThrough(Book::class, OrderItem::class, 'order_id', 'id', 'id', 'book_id');
    }

    // Relationship dengan order returns
    public function returns()
    {
        return $this->hasMany(OrderReturn::class);
    }
}