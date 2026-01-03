<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\LoanBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema; //
use App\Models\OperationalCost;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Base categories used when database values are unavailable.
     */
    private function getDefaultCategories(): array
    {
        return ['Fiksi', 'Non-Fiksi', 'Sains', 'Teknologi', 'Sejarah', 'Biografi'];
    }

    /**
     * Merge default categories with any distinct categories stored in books.
     */
    private function getAllCategories(): array
    {
        $defaults = $this->getDefaultCategories();

        if (!Schema::hasColumn('books', 'kategori')) {
            return $defaults;
        }

        try {
            $dbCategories = Book::whereNotNull('kategori')
                ->where('kategori', '!=', '')
                ->distinct()
                ->orderBy('kategori')
                ->pluck('kategori')
                ->toArray();
        } catch (\Exception $e) {
            $dbCategories = [];
        }

        if (empty($dbCategories)) {
            return $defaults;
        }

        return array_values(array_unique(array_merge($defaults, $dbCategories)));
    }
    // ✅ METHOD UNTUK USER BIASA
    public function index(Request $request)
    {
        // Query dasar untuk buku yang tersedia dengan purchase_count dari terjual
        $query = Book::where('status', 'available')
                    ->select('books.*')
                    ->selectRaw('COALESCE(books.terjual, 0) as purchase_count');
        
        // Filter berdasarkan kategori jika ada
        if ($request->has('category') && $request->category != '') {
            $query->where('kategori', $request->category);
        }
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        // Tampilkan 16 buku terbaru per halaman
        $books = $query->orderBy('created_at', 'desc')->paginate(16);
        
        // Ambil kategori dari database
        try {
            $kategories = Book::where('status', 'available')
                            ->select('kategori')
                            ->distinct()
                            ->whereNotNull('kategori')
                            ->where('kategori', '!=', '')
                            ->orderBy('kategori')
                            ->pluck('kategori')
                            ->toArray();
        } catch (\Exception $e) {
            $kategories = ['Fiksi', 'Non-Fiksi', 'Sains', 'Teknologi', 'Sejarah', 'Biografi'];
        }
        
        // Jika tidak ada kategori di database, gunakan default
        if (empty($kategories)) {
            $kategories = ['Fiksi', 'Non-Fiksi', 'Sains', 'Teknologi', 'Sejarah', 'Biografi'];
        }
        
        // Ambil top sellers (berdasarkan kolom terjual) dan buat mapping id => rank (1..8)
        try {
            $top = Book::where('status', 'available')
                       ->select('id', 'terjual')
                       ->orderByDesc('terjual')
                       ->take(8)
                       ->get();

            $topRanks = [];
            foreach ($top as $i => $b) {
                $topRanks[$b->id] = $i + 1; // rank starts at 1
            }
        } catch (\Exception $e) {
            $topRanks = [];
        }

        return view('books.index', compact('books', 'kategories', 'topRanks'));
    }

    public function show(Request $request, $id)
    {
        $book = Book::where('status', 'available')
                   ->select('books.*')
                   ->selectRaw('COALESCE(books.terjual, 0) as purchase_count')
                   ->findOrFail($id);
        
        // Get related books dari kategori yang sama dengan purchase_count
        $relatedBooks = Book::where('status', 'available')
                            ->where('kategori', $book->kategori)
                            ->where('id', '!=', $book->id)
                            ->select('books.*')
                            ->selectRaw('COALESCE(books.terjual, 0) as purchase_count')
                            ->limit(4)
                            ->get();
        
        // Get reviews with pagination and sorting
        $sortBy = $request->get('sort', 'latest'); // latest, highest, lowest
        $reviewsQuery = $book->approvedReviews();
        
        switch ($sortBy) {
            case 'highest':
                $reviewsQuery->orderBy('rating', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'lowest':
                $reviewsQuery->orderBy('rating', 'asc')->orderBy('created_at', 'desc');
                break;
            default:
                $reviewsQuery->latest();
        }
        
        $reviews = $reviewsQuery->paginate(5)->appends(['sort' => $sortBy]);
        
        // Get rating distribution
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $book->approvedReviews()->where('rating', $i)->count();
            $percentage = $book->total_reviews > 0 ? ($count / $book->total_reviews) * 100 : 0;
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }
        
        return view('books.show', compact('book', 'relatedBooks', 'reviews', 'sortBy', 'ratingDistribution'));
    }

    // ✅ METHOD UNTUK ADMIN/OWNER - BOOK MANAGEMENT

    /**
     * Show the form for creating a new book - METHOD INI YANG MISSING!
     */
    public function create()
    {
        $kategories = $this->getAllCategories();
        return view('admin.books.create', compact('kategories'));
    }

    // Di BookController - method store()
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'profit_margin_percent' => 'required|numeric|min:0|max:100',
            'kategori' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'halaman' => 'required|integer|min:1',
            'isbn' => 'nullable|string|max:20',
            'penerbit' => 'nullable|string|max:255',
            'berat' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Validasi: minimal salah satu dari harga atau purchase_price harus diisi
        if (empty($validated['harga']) && empty($validated['purchase_price'])) {
            return back()->withErrors(['harga' => 'Minimal salah satu: Harga Jual atau Harga Beli harus diisi'])->withInput();
        }
        
        // Handle file upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('book-covers', $filename, 'public');
            $validated['image'] = $filename;
        }

        // Auto-calculate sesuai yang diisi
        if (!empty($validated['purchase_price']) && empty($validated['harga'])) {
            // Jika hanya harga beli diisi, hitung harga jual
            $margin = $validated['profit_margin_percent'] / 100;
            $validated['harga'] = round($validated['purchase_price'] / (1 - $margin), 0);
        } elseif (!empty($validated['harga']) && empty($validated['purchase_price'])) {
            // Jika hanya harga jual diisi, hitung harga beli
            $margin = $validated['profit_margin_percent'] / 100;
            $validated['purchase_price'] = round($validated['harga'] * (1 - $margin), 0);
        }

        // Set default values
        $validated['status'] = 'available';
        
        // Create book with validated data
        Book::create($validated);
        
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified book - METHOD INI MISSING!
     */
    public function edit(Book $book)
    {
        $kategories = $this->getAllCategories();
        return view('admin.books.edit', compact('book', 'kategories'));
    }

    // Di BookController - method update()  
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'profit_margin_percent' => 'required|numeric|min:0|max:100',
            'kategori' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'halaman' => 'required|integer|min:1',
            'isbn' => 'nullable|string|max:20',
            'penerbit' => 'nullable|string|max:255',
            'berat' => 'nullable|integer|min:1',
            'status' => 'required|in:available,unavailable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Validasi: minimal salah satu dari harga atau purchase_price harus diisi
        if (empty($validated['harga']) && empty($validated['purchase_price'])) {
            return back()->withErrors(['harga' => 'Minimal salah satu: Harga Jual atau Harga Beli harus diisi'])->withInput();
        }
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('book-covers', $filename, 'public');
            $validated['image'] = $filename;
        }

        // Auto-calculate sesuai yang diisi
        if (!empty($validated['purchase_price']) && empty($validated['harga'])) {
            // Jika hanya harga beli diisi, hitung harga jual
            $margin = $validated['profit_margin_percent'] / 100;
            $validated['harga'] = round($validated['purchase_price'] / (1 - $margin), 0);
        } elseif (!empty($validated['harga']) && empty($validated['purchase_price'])) {
            // Jika hanya harga jual diisi, hitung harga beli
            $margin = $validated['profit_margin_percent'] / 100;
            $validated['purchase_price'] = round($validated['harga'] * (1 - $margin), 0);
        }

        $book->update($validated);
        
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui!');
    }



    /**
     * Remove the specified book - METHOD INI MISSING!
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus!');
    }

    /**
     * ADMIN BOOK MANAGEMENT
     */
    public function adminIndex(Request $request)
    {
        $query = Book::query();
        
        // Search filter (gunakan nama kolom yang sesuai)
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%'.$request->search.'%')
                  ->orWhere('penulis', 'like', '%'.$request->search.'%');
            });
        }
        
        // Category filter - menggunakan 'kategori' (bukan 'category')
        if ($request->has('kategori') && $request->kategori && \Schema::hasColumn('books', 'kategori')) {
            $query->where('kategori', $request->kategori);
        }
        
        // Stock status filter
        if ($request->has('stock_status')) {
            switch($request->stock_status) {
                case 'low':
                    $query->where('stok', '<=', 5)->where('stok', '>', 0);
                    break;
                case 'out':
                    $query->where('stok', 0);
                    break;
                case 'available':
                    $query->where('stok', '>', 0);
                    break;
            }
        }

        // Sorting
        $sort = $request->get('sort', 'name');
        switch($sort) {
            case 'stock_asc':
                $query->orderBy('stok', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stok', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('harga', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('judul', 'asc');
                break;
        }

        $books = $query->paginate(10)->appends($request->except('page'));
        
        // STATISTIK YANG DIBUTUHKAN OLEH VIEW - DENGAN ERROR HANDLING
        $stats = [
            'totalBooks' => Book::count(),
            'lowStockCount' => Book::where('stok', '<=', 5)->where('stok', '>', 0)->count(),
            'outOfStockCount' => Book::where('stok', 0)->count(),
            'categoriesCount' => 0,
            'categories' => []
        ];

        $allCategories = $this->getAllCategories();
        $stats['categoriesCount'] = count($allCategories);
        $stats['categories'] = $allCategories;
        
        return view(
            'admin.books.index',
            array_merge([
                'books' => $books,
                'kategories' => $allCategories,
            ], $stats)
        );
    }

    /**
     * UPDATE STOCK - METHOD YANG MISSING!
     */
    public function updateStock(Request $request, Book $book)
    {
        $request->validate([
            'stok' => 'required|integer|min:0'
        ]);
        $oldStok = (int) $book->stok;
        $newStok = (int) $request->stok;
        $delta = $newStok - $oldStok;

        $book->update(['stok' => $newStok]);

        // Jika ada penambahan stok (restock), catat biaya pembelian berdasarkan purchase_price jika tersedia
        if ($delta > 0 && isset($book->purchase_price) && $book->purchase_price > 0) {
            OperationalCost::create([
                'item' => 'Pembelian stok: ' . ($book->judul ?? 'Buku'),
                'category' => 'Pembelian Stok',
                'amount' => (int) round($delta * ($book->purchase_price ?? 0)),
                'notes' => 'Auto-generated dari update stok (restock ' . $delta . ' unit)',
                'related_book_id' => $book->id,
                'created_by' => Auth::id()
            ]);
        }
        
        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json(['success' => true, 'stok' => $book->stok]);
        }

        return back()->with('success', 'Stok buku berhasil diperbarui!');
    }

    /**
     * UPDATE LOAN STOCK - specific column for loans
     */
    public function updateLoanStock(Request $request, Book $book)
    {
        $request->validate([
            'loan_stok' => 'required|integer|min:0'
        ]);

        $book->update(['loan_stok' => $request->loan_stok]);

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json(['success' => true, 'loan_stok' => $book->loan_stok]);
        }

        return back()->with('success', 'Stok peminjaman berhasil diperbarui!');
    }

    /**
     * LOW STOCK BOOKS - METHOD YANG MISSING!
     */
public function lowStock()
{
    // === STOK PENJUALAN ===
    // Buku stok rendah (3-5)
    $lowStockBooks = Book::where('stok', '<=', 5)
                        ->where('stok', '>', 2)
                        ->get();

    // Buku stok kritis (<=2)
    $criticalStockBooks = Book::where('stok', '<=', 2)->get();

    // Total semua buku penjualan
    $totalBooks = Book::count();

    // === STOK PEMINJAMAN ===
    // Buku peminjaman stok rendah (3-5) - dari tabel loan_books
    $lowLoanStockBooks = LoanBook::where('loan_stok', '<=', 5)
                            ->where('loan_stok', '>', 2)
                            ->get();

    // Buku peminjaman stok kritis (<=2)
    $criticalLoanStockBooks = LoanBook::where('loan_stok', '<=', 2)
                                ->get();

    // Total buku dengan stok peminjaman
    $totalLoanBooks = LoanBook::where('loan_stok', '>', 0)->count();

    return view('admin.books.low-stock', compact(
        'lowStockBooks',
        'criticalStockBooks',
        'totalBooks',
        'lowLoanStockBooks',
        'criticalLoanStockBooks',
        'totalLoanBooks'
    ));
}


    /**
     * EXPORT BOOKS - METHOD UNTUK ROUTE EXPORT
     */
    public function export()
    {
        // Untuk sementara redirect ke index
        return redirect()->route('admin.books.index')->with('info', 'Fitur export akan segera tersedia.');
    }

    // ✅ TAMBAHAN METHOD UNTUK PAGINATION DI USER SIDE
    public function userBooks(Request $request)
    {
        $query = Book::where('status', 'available');
        
        if ($request->has('category') && $request->category != '') {
            $query->where('kategori', $request->category);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }
        
        $books = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('books.user-index', compact('books'));
    }
}