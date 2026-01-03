<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class OperationalCost extends Model
{
    use HasFactory;

    protected $table = 'operational_costs';

    protected $fillable = [
        'item',
        'category',
        'amount',
        'notes',
        'related_book_id',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'integer'
    ];

    public function relatedBook()
    {
        return $this->belongsTo(Book::class, 'related_book_id');
    }
}
