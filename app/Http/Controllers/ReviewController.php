<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a new review for a book
     */
    public function store(Request $request, $bookId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $book = Book::findOrFail($bookId);
        
        // Cek apakah user sudah pernah review buku ini
        $existingReview = Review::where('user_id', Auth::id())
                                ->where('book_id', $bookId)
                                ->first();
        
        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah pernah memberikan review untuk buku ini.');
        }

        // Opsional: Cek apakah user pernah beli/pinjam buku ini
        // $hasPurchased = Order::where('user_id', Auth::id())
        //     ->whereHas('items', function($q) use ($bookId) {
        //         $q->where('book_id', $bookId);
        //     })
        //     ->where('status', 'delivered')
        //     ->exists();
        
        // if (!$hasPurchased) {
        //     return redirect()->back()->with('error', 'Anda hanya bisa review buku yang sudah pernah Anda beli.');
        // }

        $isApproved = intval($request->rating) >= 4;

        Review::create([
            'user_id' => Auth::id(),
            'book_id' => $bookId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => $isApproved,
        ]);

        if ($isApproved) {
            return redirect()->back()->with('success', 'Terima kasih — review Anda telah dipublikasikan.');
        }

        return redirect()->back()->with('success', 'Review Anda telah dikirim dan menunggu persetujuan pemilik toko.');
    }

    /**
     * Admin: Approve review
     */
    public function approve($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->is_approved = true;
        $review->save();

        return redirect()->back()->with('success', 'Review telah disetujui.');
    }

    /**
     * Admin: Reject/Delete review
     */
    public function destroy($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->delete();

        return redirect()->back()->with('success', 'Review telah dihapus.');
    }

    /**
     * Admin: List all pending reviews
     */
    public function pending()
    {
        $reviews = Review::with(['user', 'book'])
                        ->where('is_approved', false)
                        ->latest()
                        ->paginate(20);

        return view('admin.reviews.pending', compact('reviews'));
    }
}
