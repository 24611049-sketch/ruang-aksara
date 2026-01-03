<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;

    protected $table = 'stock_logs';

    protected $fillable = [
        'book_id',
        'loan_book_id',
        'user_id',
        'type',
        'change',
        'previous_stock',
        'new_stock',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function loanBook()
    {
        return $this->belongsTo(LoanBook::class, 'loan_book_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
