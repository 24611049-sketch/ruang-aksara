<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanBook extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'loan_books';

    protected $fillable = [
        'original_book_id',
        'judul',
        'penulis',
        'kategori',
        'penerbit',
        'isbn',
        'loan_stok',
        'image',
        'halaman',
        'deskripsi',
        'status',
    ];

    // Accessor for image URL
    public function getImageUrlAttribute()
    {
        if (!empty($this->image)) {
            return asset('storage/book-covers/' . $this->image);
        }
        return asset('images/default-book.jpg');
    }

    public function originalBook()
    {
        return $this->belongsTo(Book::class, 'original_book_id');
    }
}
