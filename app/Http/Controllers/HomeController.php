<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\LoanBook;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'total_orders' => Order::count(),
            'revenue' => Order::where('status', 'delivered')->sum('total_price') ?? 0,
        ];
            // Build grouped processes so dashboard counts processes (not raw orders)
            $allOrdersForGrouping = Order::orderBy('created_at', 'desc')->get();
            $groupedProcessesForStats = $allOrdersForGrouping->groupBy(function ($order) {
                return $order->created_at->format('Y-m-d H:i');
            });

            $stats = [
                'total_users' => User::count(),
                'total_books' => Book::count(),
                'total_orders' => $groupedProcessesForStats->count(),
                'revenue' => Order::where('status', 'delivered')->sum('total_price') ?? 0,
            ];

            // Pending processes for owner dashboard
            $stats['pending_orders'] = $groupedProcessesForStats->filter(function ($group) {
                return $group->first()->status === 'pending';
            })->count();

        // Recent orders
        $recentOrders = Order::with(['user', 'items.book'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        // Get all books untuk admin dashboard
        $books = Book::orderBy('created_at', 'desc')->get();

        // Cek kolom yang ada di tabel books untuk low stock (SALES)
        try {
            $lowStockBooks = Book::where('stok', '<=', 5)
                                 ->orderBy('stok', 'asc')
                                 ->take(5)
                                 ->get();
        } catch (\Exception $e) {
            $lowStockBooks = collect([]);
        }

        // Low stock dari LOAN BOOKS (PEMINJAMAN)
        try {
            $lowLoanStockBooks = LoanBook::where('loan_stok', '<=', 5)
                                        ->orderBy('loan_stok', 'asc')
                                        ->take(5)
                                        ->get();
        } catch (\Exception $e) {
            $lowLoanStockBooks = collect([]);
        }

        // User-specific stats
        $totalBooksBought = OrderItem::whereHas('order', function ($query) {
                                $query->where('user_id', auth()->id())
                                      ->where('status', 'delivered');
                            })
                            ->sum('quantity');

        // Fallback untuk order legacy yang belum punya order_items
        if ($totalBooksBought === 0) {
            $totalBooksBought = Order::where('user_id', auth()->id())
                                     ->where('status', 'delivered')
                                     ->sum('quantity');
        }

        $userPoints = auth()->user()->points ?? 0;
        $activeOrders = Order::where('user_id', auth()->id())
                            ->whereIn('status', ['pending', 'processing', 'shipped'])
                            ->count();

        // Popular books - integrate real sales count
        // Use books.terjual (kept in-sync on checkout/verification) to reflect items sold/checked out
        try {
            // Compute purchase_count dynamically from delivered order items
            $popularBooks = Book::select('books.*')
                               ->selectRaw("(SELECT COALESCE(SUM(oi.quantity), 0)
                                              FROM order_items oi
                                              JOIN orders o ON oi.order_id = o.id AND o.status = 'delivered'
                                              WHERE oi.book_id = books.id) as purchase_count")
                               ->orderByDesc('purchase_count')
                               ->take(8)
                               ->get();
        } catch (\Exception $e) {
            // Fallback to newest if something goes wrong
            $popularBooks = Book::orderBy('created_at', 'desc')
                               ->take(5)
                               ->get();
        }

        // Build topRanks mapping (book_id => rank) for badges
        try {
            $topRanks = [];
            foreach ($popularBooks as $i => $b) {
                $topRanks[$b->id] = $i + 1;
            }
        } catch (\Exception $e) {
            $topRanks = [];
        }

        // Charts data untuk owner
        $monthlyRevenue = $this->getMonthlyRevenue();
        $userRegistrations = $this->getUserRegistrations();
        
        // PERBAIKAN: Handle error untuk category distribution
        try {
            $categoryDistribution = $this->getCategoryDistribution();
        } catch (\Exception $e) {
            $categoryDistribution = [];
        }
        
        $salesPerformance = $this->getSalesPerformance();

        $user = auth()->user();
        
        // Cek dari route mana user mengakses untuk hindari redirect loop
        $currentRoute = request()->route()->getName();
        
        // Jika owner/admin akses /home atau /dashboard (bukan admin.dashboard), redirect
        if ($user->role === 'owner' && $currentRoute !== 'admin.dashboard') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'admin' && $currentRoute !== 'admin.dashboard') {
            return redirect()->route('admin.dashboard');
        }
        
        // Tentukan view berdasarkan role (untuk route spesifik mereka)
        if ($user->role === 'owner') {
            return view('admin.dashboard', compact(
                'stats', 
                'recentOrders', 
                'lowStockBooks',
                'lowLoanStockBooks',
                'monthlyRevenue',
                'userRegistrations',
                'categoryDistribution',
                'salesPerformance',
                'books',
                'totalBooksBought',
                'userPoints',
                'activeOrders',
                'popularBooks',
                'topRanks'
            ));
        } elseif ($user->role === 'admin') {
            return view('admin.dashboard', compact(
                'stats', 
                'recentOrders', 
                'lowStockBooks',
                'lowLoanStockBooks',
                'monthlyRevenue',
                'userRegistrations',
                'books',
                'totalBooksBought',
                'userPoints',
                'activeOrders',
                'popularBooks',
                'topRanks'
            ));
        }
        
        // User dashboard
        return view('user.dashboard', compact(
            'stats', 
            'recentOrders', 
            'lowStockBooks',
            'lowLoanStockBooks',
            'monthlyRevenue',
            'userRegistrations',
            'books',
            'totalBooksBought',
            'userPoints',
            'activeOrders',
            'popularBooks',
            'topRanks'
        ));
    }

public function adminDashboard()
{
    // Base stats
    $stats = [
        'total_users' => User::count(),
        'total_books' => Book::count(),
        'total_orders' => Order::count(),
        'revenue' => Order::where('status', 'delivered')->sum('total_price') ?? 0,
    ];

    // Recent orders (for quick view)
    $recentOrders = Order::with(['user', 'book'])
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

    // Group recent orders into processes (same-minute grouping)
    $groupedRecentOrders = $recentOrders->groupBy(function ($order) {
        return $order->created_at->format('Y-m-d H:i');
    });

    // All orders grouping to compute process-based stats
    $allOrdersForGrouping = Order::orderBy('created_at', 'desc')->get();
    $groupedProcesses = $allOrdersForGrouping->groupBy(function ($order) {
        return $order->created_at->format('Y-m-d H:i');
    });

    // Override total orders to be process count
    $stats['total_orders'] = $groupedProcesses->count();
    // Pending processes
    $stats['pending_orders'] = $groupedProcesses->filter(function ($group) {
        return $group->first()->status === 'pending';
    })->count();

    $books = Book::orderBy('created_at', 'desc')->get();

    // Low stock - sales
    try {
        $lowStockBooks = Book::where('stok', '<=', 5)
                             ->orderBy('stok', 'asc')
                             ->take(5)
                             ->get();
    } catch (\Exception $e) {
        $lowStockBooks = collect([]);
    }

    // Low stock - loan books
    try {
        $lowLoanStockBooks = LoanBook::where('loan_stok', '<=', 5)
                                    ->orderBy('loan_stok', 'asc')
                                    ->take(5)
                                    ->get();
    } catch (\Exception $e) {
        $lowLoanStockBooks = collect([]);
    }

    // Compute purchase_count dynamically from delivered order items for admin dashboard
    $popularBooks = Book::select('books.*')
                       ->selectRaw("(SELECT COALESCE(SUM(oi.quantity), 0)
                                      FROM order_items oi
                                      JOIN orders o ON oi.order_id = o.id AND o.status = 'delivered'
                                      WHERE oi.book_id = books.id) as purchase_count")
                       ->orderByDesc('purchase_count')
                       ->take(8)
                       ->get();

    return view('admin.dashboard', compact(
        'stats',
        'recentOrders',
        'lowStockBooks',
        'lowLoanStockBooks',
        'books',
        'popularBooks'
    ));
}


    private function getMonthlyRevenue()
    {
        return Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->where('status', 'delivered')
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
    }

    private function getUserRegistrations()
    {
        return User::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
    }

    // PERBAIKAN: Method getCategoryDistribution()
    private function getCategoryDistribution()
    {
        try {
            // Cek dulu apakah kolom 'kategori' ada
            $table = (new Book())->getTable();
            $columns = DB::getSchemaBuilder()->getColumnListing($table);
            
            if (in_array('kategori', $columns)) {
                return Book::select('kategori')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('kategori')
                    ->get()
                    ->pluck('total', 'kategori')
                    ->toArray();
            } else {
                // Fallback: gunakan data dummy atau kolom lain yang ada
                return [
                    'Fiksi' => Book::count() > 0 ? round(Book::count() * 0.4) : 0,
                    'Non-Fiksi' => Book::count() > 0 ? round(Book::count() * 0.3) : 0,
                    'Pendidikan' => Book::count() > 0 ? round(Book::count() * 0.2) : 0,
                    'Lainnya' => Book::count() > 0 ? round(Book::count() * 0.1) : 0,
                ];
            }
        } catch (\Exception $e) {
            // Return empty array jika masih error
            return [];
        }
    }

    private function getSalesPerformance()
    {
        return [
            'today' => Order::whereDate('created_at', today())
                         ->where('status', 'delivered')
                         ->sum('total_price') ?? 0,
            'week' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                        ->where('status', 'delivered')
                        ->sum('total_price') ?? 0,
            'month' => Order::whereMonth('created_at', now()->month)
                         ->where('status', 'delivered')
                         ->sum('total_price') ?? 0,
        ];
    }
}