<?php

namespace App\Http\Controllers;

use App\Models\LoanBook;
use App\Models\StockLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LoanStockController extends Controller
{
    /**
     * Display the loan stock management page
     */
    public function index(Request $request)
    {
        $query = LoanBook::query();

        // Search by title or author
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Sort options
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'stock_asc':
                $query->orderBy('loan_stok', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('loan_stok', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('judul', 'asc');
                break;
        }

        $books = $query->paginate(20);

        // Get categories for filter
        try {
            $categories = LoanBook::select('kategori')
                            ->distinct()
                            ->whereNotNull('kategori')
                            ->where('kategori', '!=', '')
                            ->orderBy('kategori')
                            ->pluck('kategori')
                            ->toArray();
        } catch (\Exception $e) {
            $categories = [];
        }

        // Get users for loan modal
        try {
            $users = User::where('role', 'user')->get();
        } catch (\Exception $e) {
            $users = collect([]);
        }

        return view('admin.loan-stock.index', compact('books', 'categories', 'users'));
    }

    /**
     * Show create book form
     */
    public function create()
    {
        try {
            $categories = LoanBook::select('kategori')
                            ->distinct()
                            ->whereNotNull('kategori')
                            ->where('kategori', '!=', '')
                            ->orderBy('kategori')
                            ->pluck('kategori')
                            ->toArray();
        } catch (\Exception $e) {
            $categories = [];
        }

        return view('admin.loan-stock.create', compact('categories'));
    }

    /**
     * Store a new book
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn',
            'loan_stok' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'halaman' => 'nullable|integer|min:1',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        try {
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());
                $file->storeAs('book-covers', $filename, 'public');
                $validated['image'] = $filename;
            }

            $loanBook = LoanBook::create([
                'judul' => $validated['judul'],
                'penulis' => $validated['penulis'],
                'kategori' => $validated['kategori'],
                'penerbit' => $validated['penerbit'] ?? null,
                'isbn' => $validated['isbn'] ?? null,
                'loan_stok' => $validated['loan_stok'],
                'image' => $validated['image'] ?? null,
                'halaman' => $validated['halaman'] ?? null,
                'deskripsi' => $validated['deskripsi'] ?? null,
                'status' => 'available',
            ]);

            return redirect()->route('admin.loan-stock.index')
                           ->with('success', 'Buku peminjaman berhasil ditambahkan dengan stok: ' . $validated['loan_stok']);
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withErrors(['error' => 'Gagal menambahkan buku peminjaman: ' . $e->getMessage()])
                           ->withInput();
        }
    }

    /**
     * Update loan stock for a book
     */
    public function update(Request $request, LoanBook $loanBook)
    {
        $validated = $request->validate([
            'loan_stok' => 'required|integer|min:0|max:9999',
        ]);

        try {
            DB::transaction(function () use ($loanBook, $validated) {
                $oldStock = $loanBook->loan_stok ?? 0;
                $newStock = $validated['loan_stok'];
                $change = $newStock - $oldStock;

                $loanBook->update(['loan_stok' => $newStock]);

                // Log the stock change on stock_logs.loan_book_id
                if ($change != 0) {
                    StockLog::create([
                        'loan_book_id' => $loanBook->id,
                        'user_id' => auth()->id(),
                        'type' => 'loan_adjustment',
                        'change' => $change,
                        'previous_stock' => $oldStock,
                        'new_stock' => $newStock,
                        'meta' => json_encode(['reason' => 'manual_adjustment']),
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Stok peminjaman berhasil diperbarui',
                'new_stock' => $loanBook->loan_stok,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui stok peminjaman: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get stock history for a book
     */
    public function history(LoanBook $loanBook)
    {
        $history = StockLog::where('loan_book_id', $loanBook->id)
                          ->where(function($query) {
                              $query->where('type', 'loan')
                                    ->orWhere('type', 'loan_adjustment');
                          })
                          ->orderBy('created_at', 'desc')
                          ->limit(10)
                          ->get();

        return response()->json([
            'book' => $loanBook,
            'history' => $history,
        ]);
    }

    /**
     * Bulk update loan stock
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'updates' => 'required|array',
            'updates.*.book_id' => 'required|exists:loan_books,id',
            'updates.*.loan_stok' => 'required|integer|min:0|max:9999',
        ]);

        try {
            $results = [];
            
            foreach ($validated['updates'] as $update) {
                $loanBook = LoanBook::findOrFail($update['book_id']);
                $oldStock = $loanBook->loan_stok ?? 0;
                $newStock = $update['loan_stok'];
                $change = $newStock - $oldStock;

                $loanBook->update(['loan_stok' => $newStock]);

                if ($change != 0) {
                    StockLog::create([
                        'loan_book_id' => $loanBook->id,
                        'user_id' => auth()->id(),
                        'type' => 'loan_adjustment',
                        'change' => $change,
                        'previous_stock' => $oldStock,
                        'new_stock' => $newStock,
                        'meta' => json_encode(['reason' => 'bulk_adjustment']),
                    ]);
                }

                $results[] = [
                    'book_id' => $loanBook->id,
                    'success' => true,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil diperbarui untuk ' . count($results) . ' buku',
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan update massal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified book from storage (admin delete)
     */
    public function destroy(LoanBook $loanBook)
    {
        try {
            DB::transaction(function () use ($loanBook) {
                // record a log entry for deletion (previous stock snapshot)
                StockLog::create([
                    'loan_book_id' => $loanBook->id,
                    'user_id' => auth()->id(),
                    'type' => 'loan_adjustment',
                    'change' => -1 * ($loanBook->loan_stok ?? 0),
                    'previous_stock' => $loanBook->loan_stok ?? 0,
                    'new_stock' => 0,
                    'meta' => json_encode(['reason' => 'book_deleted']),
                ]);

                // delete the book record
                $loanBook->delete();
            });

            return response()->json(['success' => true, 'message' => 'Buku berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus buku: ' . $e->getMessage()], 500);
        }
    }
}
