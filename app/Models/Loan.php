<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\Models\Loan
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $book_id
 * @property int|null $loan_book_id
 * @property Carbon|null $borrowed_date
 * @property Carbon|null $return_date
 * @property Carbon|null $returned_at
 * @property string $status
 * @property string|null $notes
 * @property string|null $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read User $user
 * @property-read Book|null $book
 * @property-read LoanBook|null $loanBook
 */
class Loan extends Model
{
    protected $fillable = [
        'user_id', 'book_id', 'loan_book_id', 'quantity', 'borrowed_date', 'return_date', 
        'returned_at', 'status', 'notes', 'location'
    ];

    protected $casts = [
        'borrowed_date' => 'datetime',
        'return_date' => 'datetime',
        'returned_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function loanBook()
    {
        return $this->belongsTo(LoanBook::class);
    }

    // Scope untuk active loans
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope untuk overdue loans
    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
                     ->where('return_date', '<', Carbon::now());
    }

    // Scope untuk loans yang sudah dikembalikan
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    // Check if loan is overdue
    public function isOverdue()
    {
        return $this->status === 'active' && Carbon::now()->isAfter($this->return_date);
    }

    // Get days until return
    public function getDaysUntilReturn()
    {
        if ($this->status !== 'active') {
            return null;
        }
        // Calculate signed difference in hours, convert to days (float),
        // then return ceiling as integer days remaining. If already past, return 0.
        $diffInDays = Carbon::now()->diffInHours($this->return_date, false) / 24;
        if ($diffInDays <= 0) {
            return 0;
        }
        return (int) ceil($diffInDays);
    }

    // Mark as returned
    public function markAsReturned()
    {
        $this->update([
            'status' => 'returned',
            'returned_at' => Carbon::now(),
        ]);
    }

    // Calculate late days
    public function getLateDays()
    {
        if ($this->returned_at && $this->returned_at->isAfter($this->return_date)) {
            return $this->returned_at->diffInDays($this->return_date);
        }
        return 0;
    }
}

