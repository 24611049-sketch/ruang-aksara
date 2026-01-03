<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        // Ambil wishlist user dengan data buku
        $wishlists = Wishlist::with('book')
                            ->where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->paginate(12);

        return view('wishlists.index', compact('wishlists'));
    }

    public function store(Request $request, $bookId)
    {
        $book = Book::where('status', 'available')->findOrFail($bookId);

        // Cek apakah sudah ada di wishlist
        $existingWishlist = Wishlist::where('user_id', Auth::id())
                                  ->where('book_id', $bookId)
                                  ->first();

        if ($existingWishlist) {
            return redirect()->back()->with('info', 'Buku sudah ada di wishlist!');
        }

        // Tambah ke wishlist
        Wishlist::create([
            'user_id' => Auth::id(),
            'book_id' => $bookId,
        ]);

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan ke wishlist!');
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlist->delete();

        return redirect()->back()->with('success', 'Buku berhasil dihapus dari wishlist!');
    }

    public function toggle($bookId)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
                           ->where('book_id', $bookId)
                           ->first();

        if ($wishlist) {
            $wishlist->delete();
            $message = 'Buku dihapus dari wishlist';
            $added = false;
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'book_id' => $bookId,
            ]);
            $message = 'Buku ditambahkan ke wishlist';
            $added = true;
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'added' => $added,
                'wishlist_count' => Wishlist::where('user_id', Auth::id())->count()
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}