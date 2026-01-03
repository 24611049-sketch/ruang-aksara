<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\HelpController;
use Carbon\Carbon;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\LoanStockController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\OperationalCostController;
use App\Models\Book;
use App\Models\LoanBook;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Loan;
use App\Models\PointLog;
use App\Models\StockLog;
use App\Models\OperationalCost;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

// ✅ PUBLIC ROUTES
Route::get('/', function () {
    try {
        $featuredBooks = Book::where('status', 'available')
                            ->orderBy('created_at', 'desc')
                            ->take(3)
                            ->get();
    } catch (\Exception $e) {
        $featuredBooks = collect([]);
    }
    return view('welcome', compact('featuredBooks'));
})->name('welcome');

Route::get('/about', function () {
    return view('about');
})->name('about');

// Public cart endpoints (AJAX) - allow adding/fetching count without auth
Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add.post');
Route::post('/cart/decrease/{book}', [CartController::class, 'decrease'])->name('cart.decrease.post');
Route::post('/cart/remove/{book}', [CartController::class, 'remove'])->name('cart.remove.post');
Route::get('/cart/api/count', [CartController::class, 'getCartCount'])->name('cart.apiCount.public');

// DEBUG: Check if purchase_count attribute exists
Route::get('/debug/check-purchase-count', function() {
    $popularBooks = Book::leftJoin('orders', 'books.id', '=', 'orders.book_id')
                       ->select('books.*')
                       ->selectRaw('COALESCE(SUM(orders.quantity), 0) as purchase_count')
                       ->groupBy('books.id')
                       ->orderByDesc('purchase_count')
                       ->take(5)
                       ->get();
    
    return response()->json([
        'count' => $popularBooks->count(),
        'books' => $popularBooks->map(function ($b) {
            return [
                'id' => $b->id,
                'judul' => $b->judul,
                'purchase_count' => $b->purchase_count,
                'all_attributes' => $b->toArray(),
            ];
        }),
    ]);
});

// DEBUG: Check popular books query
Route::get('/debug/popular-books', function() {
    // Check all tables
    $orders = Order::all();
    $orderItems = OrderItem::all();
    
    // Check Order model structure
    $firstOrder = Order::first();
    
    return response()->json([
        'orders_count' => $orders->count(),
        'order_items_count' => $orderItems->count(),
        'first_order' => $firstOrder ? [
            'id' => $firstOrder->id,
            'user_id' => $firstOrder->user_id,
            'total_price' => $firstOrder->total_price,
            'status' => $firstOrder->status,
            'all_fields' => array_keys($firstOrder->toArray())
        ] : null,
        'order_items_sample' => $orderItems->take(3)->map(function ($oi) {
            return [
                'id' => $oi->id,
                'order_id' => $oi->order_id,
                'book_id' => $oi->book_id,
                'quantity' => $oi->quantity,
            ];
        }),
        'books_with_order_items' => Book::select('books.id', 'books.judul')
            ->selectRaw('COUNT(order_items.id) as items_count, COALESCE(SUM(order_items.quantity), 0) as total_qty')
            ->leftJoin('order_items', 'books.id', '=', 'order_items.book_id')
            ->groupBy('books.id')
            ->orderBy('total_qty', 'desc')
            ->take(10)
            ->get()
            ->map(function ($b) {
                return [
                    'id' => $b->id,
                    'judul' => $b->judul,
                    'items_count' => $b->items_count,
                    'total_qty' => $b->total_qty,
                ];
            })
    ]);
});

// ✅ AUTH ROUTES
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', function () {
        return view('welcome');
    })->name('login');
});

// Move POST /login outside guest to avoid CSRF/session issues for returning users
Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            \Log::info('Login successful - User ID: ' . $user->id . ', Role: ' . $user->role);
            
            if ($user->role === 'owner') {
                \Log::info('Redirecting owner to admin.dashboard');
                return redirect()->route('admin.dashboard')
                    ->with('login_notification', [
                        'message' => 'Kamu login sebagai Owner',
                        'role' => $user->role,
                        'name' => $user->name
                    ]);
            } elseif ($user->role === 'admin') {
                \Log::info('Redirecting admin to admin.dashboard');
                return redirect()->route('admin.dashboard')
                    ->with('login_notification', [
                        'message' => 'Kamu login sebagai Admin',
                        'role' => $user->role,
                        'name' => $user->name
                    ]);
            } else {
                \Log::info('Redirecting user to home');
                return redirect()->route('home');
            }
        }
        
        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    })->name('login.post');

    // Google Account Picker - for Login
    Route::get('/auth/google/login-picker', function () {
        return view('google-account-picker', ['mode' => 'login']);
    })->name('google.login.picker');

    // Google Account Picker - for Register
    Route::get('/auth/google/register-picker', function () {
        return view('google-account-picker', ['mode' => 'register']);
    })->name('google.register.picker');

    // Google Confirmation Page
    Route::get('/auth/google/confirm', function () {
        if (!session('google_login_pending') && !session('google_register_pending')) {
            return redirect('/');
        }
        return view('google-confirm');
    })->name('google.confirm.page');

    // Google OAuth Routes
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    
    // Google Quick Login (detect existing Google account from cookies)
    Route::post('/google/quick-login', [GoogleController::class, 'quickLogin'])->name('google.quick.login');

    // Google Login Confirmation
    Route::post('/google/confirm-login', function (Request $request) {
        if (!session('google_login_pending')) {
            return redirect('/');
        }

        $userId = session('pending_user_id');
        $user = User::find($userId);

        if ($user) {
            Auth::login($user, true);

            // Redirect based on role
            if ($user->role === 'owner' || $user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('login_notification', [
                        'message' => 'Kamu login sebagai ' . ($user->role === 'owner' ? 'Owner' : 'Admin'),
                        'role' => $user->role,
                        'name' => $user->name
                    ])
                    ->with('success', 'Login berhasil dengan Google!');
            }

            return redirect()->route('home')
                ->with('success', 'Login berhasil dengan Google!');
        }

        return redirect('/')->with('error', 'User tidak ditemukan.');
    })->name('google.confirm.login');

    // Google Registration Confirmation
    Route::post('/google/confirm-register', function (Request $request) {
        if (!session('google_register_pending')) {
            return redirect('/');
        }

        // Validate password and optional address fields
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'alamat' => 'nullable|string|max:500',
            'province' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
        ]);

        // Build user data from session + request
        $userData = [
            'name' => session('pending_user_name'),
            'email' => session('pending_user_email'),
            'email_verified_at' => now(),
            'role' => session('pending_user_role', 'user'),
            'points' => 0,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat ?? '-',
            'province' => $request->province ?? null,
            'city' => $request->city ?? null,
            'district' => $request->district ?? null,
        ];

        // Attach google fields if present in session
        if (session('pending_google_token')) {
            $userData['google_token'] = session('pending_google_token');
        }
        if (session('pending_google_refresh_token')) {
            $userData['google_refresh_token'] = session('pending_google_refresh_token');
        }
        if (session('pending_google_id')) {
            $userData['google_id'] = session('pending_google_id');
        }
        if (session('pending_user_avatar')) {
            $userData['avatar'] = session('pending_user_avatar');
        }

        // Ensure email not already taken
        if (User::where('email', $userData['email'])->exists()) {
            return redirect()->route('google.confirm.page')->with('error', 'Email sudah terdaftar. Silakan login.');
        }

        $user = User::create($userData);
        Auth::login($user, true);

        // Clear pending google session values
        session()->forget(['google_register_pending','pending_user_email','pending_user_name','pending_user_role','pending_user_avatar','pending_google_token','pending_google_refresh_token','pending_google_id']);

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil! Selamat datang di Ruang Aksara!');
    })->name('google.confirm.register');

    // Clear Google Session
    Route::post('/google/clear-session', function (Request $request) {
        $request->session()->forget(['google_login_pending', 'google_register_pending', 'pending_user_id', 'pending_user_email', 'pending_user_name', 'pending_user_role', 'pending_user_avatar']);
        return response()->json(['status' => 'success']);
    })->name('google.clear.session');

    // Register
    Route::get('/register', function () {
        return view('register-new');
    })->name('register');

    Route::post('/register', function (Request $request) {
        // Check if from Google
        $isFromGoogle = $request->filled('from_google');

        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];

        // Alamat only required if not from Google
        if (!$isFromGoogle) {
            $validationRules['alamat'] = 'required|string|max:500';
            $validationRules['province'] = 'required|string|max:255';
            $validationRules['city'] = 'required|string|max:255';
            $validationRules['district'] = 'required|string|max:255';
        }

        $request->validate($validationRules);
        
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat ?? '-',
            'province' => $request->province ?? null,
            'city' => $request->city ?? null,
            'district' => $request->district ?? null,
            'role' => 'user',
            'points' => 0,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
        ];

        if ($isFromGoogle) {
            // Store Google credentials too
            $userData['google_id'] = $request->google_id;
            $userData['google_token'] = $request->google_token;
            $userData['google_refresh_token'] = $request->google_refresh_token;
            $userData['avatar'] = $request->avatar;
        }

        $user = User::create($userData);
        
        Auth::login($user);

        // Clear google session data
        session()->forget('google_data');

        return redirect()->route('home')->with('success', 'Registrasi berhasil! Selamat datang di Ruang Aksara.');
    });

// ✅ LOGOUT
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// ✅ HELP ROUTES
Route::get('/help', function () { return view('help.index'); })->name('help');
Route::get('/help/faq', function () { return view('help.faq'); })->name('help.faq');
Route::get('/help/contact', function () { return view('help.contact'); })->name('help.contact');
Route::get('/help/shipping', function () { return view('help.shipping'); })->name('help.shipping');
Route::get('/help/returns', function () { return view('help.returns'); })->name('help.returns');
Route::get('/help/payment-methods', function () { return view('help.payment-methods'); })->name('help.payment-methods');
Route::get('/help/account-security', function () { return view('help.account-security'); })->name('help.account-security');

// Handle contact form submissions from the public help page
use Illuminate\Support\Facades\Log;

Route::post('/help/contact', function (Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'nullable|string|max:255',
        'message' => 'required|string|max:2000',
    ]);

    // Log the contact message for now. In production, integrate Mail/DB or ticketing.
    Log::info('Help contact form submitted', $data);

    return redirect()->route('help.contact')->with('success', 'Terima kasih — pesan Anda telah kami terima. Tim kami akan menghubungi Anda segera.');
})->name('help.contact.submit');

// ✅ PUBLIC BOOKS ROUTES (Tidak perlu login)
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// Review routes (perlu login)
Route::middleware(['auth'])->group(function () {
    Route::post('/books/{book}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

// ✅ USER ROUTES (Perlu login)
Route::middleware(['auth'])->group(function () {
    // Notifications API
    Route::get('/api/notifications', [NotificationController::class, 'getNotifications'])->name('api.notifications');
    Route::post('/api/notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('api.notifications.markRead');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', function () {
        $user = Auth::user();
        $user->load(['orders', 'wishlists']);
        return view('profile.edit', compact('user'));
    })->name('profile');

    Route::patch('/profile', function (Request $request) {
        $user = Auth::user();
        
        // Check if any profile data is being changed
        $nameChanged = $request->input('name') !== $user->name;
        $alamatChanged = $request->input('alamat') !== ($user->alamat ?? '');
        $teleponChanged = $request->input('telepon') !== ($user->telepon ?? '');
        $tanggalLahirChanged = $request->input('tanggal_lahir') !== ($user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '');
        
        // Validate always
        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'telepon' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
        ]);
        
        // If any change, require password verification
        if ($nameChanged || $alamatChanged || $teleponChanged || $tanggalLahirChanged) {
            $request->validate([
                'current_password' => 'required|string'
            ], [
                'current_password.required' => 'Password saat ini diperlukan untuk menyimpan perubahan',
            ]);
            
            if (!\Hash::check($request->current_password, $user->password)) {
                return redirect()->route('profile')->with('error', 'Password saat ini tidak sesuai!');
            }
        }
        
        // Only update name, alamat, telepon, tanggal_lahir (NEVER email)
        $user->update($request->only('name', 'alamat', 'telepon', 'tanggal_lahir'));
        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    })->name('profile.update');

    // Upload foto profil
    Route::post('/profile/foto', function (Request $request) {
        try {
            $validated = $request->validate([
                'foto_profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120' // max 5MB
            ]);
            
            $user = Auth::user();
            
            // Delete old photo if exists
            if ($user->foto_profil) {
                try {
                    Storage::disk('public')->delete($user->foto_profil);
                } catch (\Exception $e) {
                    // Old file not found, continue
                }
            }
            
            // Store new photo
            if ($request->hasFile('foto_profil')) {
                $path = $request->file('foto_profil')->store('profile-pictures', 'public');
                $user->foto_profil = $path;
                $user->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Foto profil berhasil diperbarui!',
                    'path' => $path
                ], 200);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan'
            ], 400);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Foto upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    })->name('profile.foto.upload');

    // Points history for authenticated users
    Route::get('/points/history', function () {
        // If migration not run yet, avoid throwing an exception
        if (! Schema::hasTable('points_logs')) {
            $logs = new LengthAwarePaginator([], 0, 20, Paginator::resolveCurrentPage(), ['path' => request()->url(), 'query' => request()->query()]);
            $userPoints = auth()->user()->points ?? 0;
            return view('points.history', compact('logs', 'userPoints'))->with('warning', 'Tabel riwayat poin belum dibuat. Jalankan migrasi (php artisan migrate).');
        }

        $logs = PointLog::where('user_id', auth()->id())->latest()->paginate(20);
        $userPoints = auth()->user()->points ?? 0;
        /** @var \Illuminate\Pagination\LengthAwarePaginator $logs */
        return view('points.history', compact('logs', 'userPoints'));
    })->name('points.history');

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        // Route::post('/{book}', [OrderController::class, 'store'])->name('store'); // Disabled - use cart checkout instead
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::patch('/{order}/confirm', [OrderController::class, 'confirmDelivery'])->name('confirm');
    });

    // Wishlists
    Route::prefix('wishlists')->name('wishlists.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/{book}', [WishlistController::class, 'store'])->name('store');
        Route::delete('/{wishlist}', [WishlistController::class, 'destroy'])->name('destroy');
        Route::post('/toggle/{book}', [WishlistController::class, 'toggle'])->name('toggle');
    });

    // ✅ CART ROUTES
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::get('/add/{book}', [CartController::class, 'add'])->name('add');
        Route::post('/add/{book}', [CartController::class, 'add'])->name('add.post');
        Route::get('/api/count', [CartController::class, 'getCartCount'])->name('apiCount');
        Route::post('/increase/{book}', [CartController::class, 'add'])->name('increase');
        Route::post('/decrease/{book}', [CartController::class, 'decrease'])->name('decrease');
        Route::post('/remove/{book}', [CartController::class, 'remove'])->name('remove');
        Route::post('/update-quantity/{book}', [CartController::class, 'updateQuantity'])->name('updateQuantity');
        Route::get('/checkout-form', [CartController::class, 'checkoutForm'])->name('checkoutForm');
        Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
        Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    });

    // ✅ ATTENDANCE ROUTES
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'dashboard'])->name('dashboard');
        Route::get('/history', [AttendanceController::class, 'history'])->name('history');
        Route::post('/checkin', [AttendanceController::class, 'checkIn'])->name('checkin');
        Route::post('/checkout', [AttendanceController::class, 'checkOut'])->name('checkout');
        Route::post('/', [AttendanceController::class, 'store'])->name('store');
    });

    // ✅ LOANS ROUTES (Book Lending System)
    Route::prefix('loans')->name('loans.')->group(function () {
        Route::get('/', function (Request $request) {
            $user = Auth::user();
            $activeLoans = $user->loans()->active()->with(['book','loanBook'])->get();
            $overdueLoans = $user->loans()->overdue()->with(['book','loanBook'])->get();
            $returnedLoans = $user->loans()->returned()->with(['book','loanBook'])->get();

            // Daftar buku yang tersedia untuk dipinjam
            // Gabungkan sumber: `books` (kolom loan_stok pada Book) dan `loan_books` (LoanBook model)
            $loanSearch = trim((string) $request->get('loan_search', ''));

            try {
                $booksFromBooks = \App\Models\Book::where('status', 'available')
                                    ->where('loan_stok', '>', 0);
                if ($loanSearch !== '') {
                    $booksFromBooks->where(function($q) use ($loanSearch) {
                        $q->where('judul', 'like', "%{$loanSearch}%")
                          ->orWhere('penulis', 'like', "%{$loanSearch}%");
                    });
                }
                $booksFromBooks = $booksFromBooks->orderBy('judul')->get();
            } catch (\Exception $e) {
                $booksFromBooks = collect([]);
            }

            try {
                $booksFromLoanBooks = \App\Models\LoanBook::where('status', 'available')
                                        ->where('loan_stok', '>', 0);
                if ($loanSearch !== '') {
                    $booksFromLoanBooks->where(function($q) use ($loanSearch) {
                        $q->where('judul', 'like', "%{$loanSearch}%")
                          ->orWhere('penulis', 'like', "%{$loanSearch}%");
                    });
                }
                $booksFromLoanBooks = $booksFromLoanBooks->orderBy('judul')->get();
            } catch (\Exception $e) {
                $booksFromLoanBooks = collect([]);
            }

            // Concat collections (Book and LoanBook) so view can render both types
            $loanBooks = $booksFromBooks->concat($booksFromLoanBooks)->values();

            // === LOAN TOP USERS (10 Peminjam Terbanyak) ===
            try {
                $loanByUsers = \App\Models\Loan::with('user')
                    ->select('user_id')
                    ->selectRaw('COUNT(*) as total_loans')
                    ->selectRaw('MAX(created_at) as last_loan_date')
                    ->groupBy('user_id')
                    ->orderByDesc('total_loans')
                    ->limit(10)
                    ->get();
            } catch (\Exception $e) {
                $loanByUsers = collect([]);
            }

            return view('loans.index', compact('activeLoans', 'overdueLoans', 'returnedLoans', 'loanBooks', 'loanByUsers'));
        })->name('index');

        Route::get('/{loan}', function (Loan $loan) {
            if ($loan->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return response()->json($loan->load('book', 'user'));
        })->name('show');

        Route::patch('/{loan}/return', function (Request $request, Loan $loan) {
            if ($loan->user_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Unauthorized');
            }

            if ($loan->status !== 'active') {
                return redirect()->back()->with('error', 'Peminjaman ini tidak dapat dikembalikan');
            }

            // User requests return — only admin/owner can mark as returned.
            // Record the request in notes (lightweight) and log it for admin review.
            try {
                $note = ($loan->notes ? $loan->notes . "\n" : "") . "Return requested by user (id:" . Auth::id() . ") at " . now();
                $loan->notes = $note;
                $loan->save();

                \Log::info('Return requested for loan ' . $loan->id . ' by user ' . Auth::id());
            } catch (\Exception $e) {
                \Log::error('Failed to record return request for loan ' . $loan->id . ': ' . $e->getMessage());
            }

            return redirect()->route('loans.index')->with('success', 'Permintaan pengembalian telah dikirim. Admin/Owner akan memprosesnya.');
        })->name('return');
    });
});

// ✅ ADMIN & OWNER ROUTES - SEMUA FITUR ADMIN BISA DIAKSES OWNER
Route::middleware(['auth', 'role:admin,owner'])->prefix('admin')->name('admin.')->group(function () {
    
    // ✅ PROFIL ADMIN/OWNER - HANYA BISA UBAH FOTO SAJA
    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('profile');

    // ✅ TAMBAH ROUTE DASHBOARD CONTROLLER (TANPA MENGHAPUS YANG LAMA)
    Route::get('/dashboard/controller', [HomeController::class, 'adminDashboard'])->name('dashboard.controller');
    
    // Dashboard (tetap pertahankan yang lama)
    Route::get('/dashboard', function () {
        // Group orders into processes (group by minute) so dashboard counts processes
        $allOrdersForGrouping = Order::orderBy('created_at', 'desc')->get();
        $groupedProcesses = $allOrdersForGrouping->groupBy(function ($order) {
            return $order->created_at->format('Y-m-d H:i');
        });

        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::where('status', 'available')->count(),
            'total_orders' => $groupedProcesses->count(),
            'pending_orders' => $groupedProcesses->filter(function ($group) { return $group->first()->status === 'pending'; })->count(),
            'total_users' => User::count(),
        ];
        
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        $books = Book::orderBy('created_at', 'desc')->take(5)->get();
        
        // ✅ PERBAIKAN: Low stock dari SALES BOOKS
        try {
            $lowStockBooks = Book::where('stok', '<=', 5)
                                ->orderBy('stok', 'asc')
                                ->take(5)
                                ->get();
        } catch (\Exception $e) {
            $lowStockBooks = collect([]);
        }

        // ✅ PERBAIKAN: Low stock dari LOAN BOOKS (PEMINJAMAN)
        try {
            $lowLoanStockBooks = LoanBook::where('loan_stok', '<=', 5)
                                        ->orderBy('loan_stok', 'asc')
                                        ->take(5)
                                        ->get();
        } catch (\Exception $e) {
            $lowLoanStockBooks = collect([]);
        }
            
        return view('admin.dashboard', compact('stats', 'recentOrders', 'books', 'lowStockBooks', 'lowLoanStockBooks'));
    })->name('dashboard');

    // Biaya Operasional - CRUD untuk Owner
    Route::get('/operational-costs', [OperationalCostController::class, 'index'])->name('operational-costs');
    Route::post('/operational-costs', [OperationalCostController::class, 'store'])->name('operational-costs.store');
    Route::patch('/operational-costs/{operationalCost}', [OperationalCostController::class, 'update'])->name('operational-costs.update');
    Route::delete('/operational-costs/{operationalCost}', [OperationalCostController::class, 'destroy'])->name('operational-costs.destroy');

    // Book Management
    Route::prefix('books')->name('books.')->group(function () {
        Route::get('/', [BookController::class, 'adminIndex'])->name('index');
        
        Route::get('/create', [BookController::class, 'create'])->name('create');
        Route::post('/', [BookController::class, 'store'])->name('store');
        Route::get('/{book}/edit', [BookController::class, 'edit'])->name('edit');
        Route::patch('/{book}', [BookController::class, 'update'])->name('update');
        Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');
        
        // ✅ ROUTE UNTUK KELOLA STOK
        Route::put('/{book}/stock', [BookController::class, 'updateStock'])->name('updateStock');
        Route::put('/{book}/loan-stock', [BookController::class, 'updateLoanStock'])->name('updateLoanStock');
        Route::get('/stock/low', [BookController::class, 'lowStock'])->name('lowStock');
        
        // ✅ ROUTE EXPORT
        Route::get('/export', [BookController::class, 'export'])->name('export');
    });
    
    // Admin Order Management
    Route::prefix('orders')->name('orders.')->group(function () {
        // ✅ TAMBAH ROUTE CONTROLLER (TANPA MENGHAPUS YANG LAMA)
        Route::get('/controller', [OrderController::class, 'adminIndex'])->name('index.controller');
        
        // Controller-based index to support searching, filtering and pagination
        Route::get('/', [OrderController::class, 'adminIndex'])->name('index');
        
        // Get order data for edit tracking modal (JSON response)
        Route::get('/{order}/json', function ($orderId) {
            $order = Order::with('user')->findOrFail($orderId);
            
            $totalPrice = $order->total_price ?? 0;
            if ($order->items) {
                $totalPrice = $order->items->sum('subtotal') + ($order->shipping_cost ?? 0);
            }
            
            return response()->json([
                'id' => $order->id,
                'user_name' => $order->user->name ?? 'Unknown',
                'tracking_number' => $order->tracking_number,
                'total' => $totalPrice
            ]);
        })->name('json');
        
        // Get proof image data for modal
        Route::get('/{order}/proof', function ($orderId) {
            $order = Order::findOrFail($orderId);
            if (!$order->proof_of_payment) {
                return response()->json(['error' => 'No proof found'], 404);
            }
            return response()->json([
                'proof_url' => asset('storage/' . $order->proof_of_payment),
                'order_id' => $order->id,
                'user_name' => $order->user->name,
                'total' => $order->total_price,
                'payment_method' => ucfirst($order->payment_method)
            ]);
        })->name('proof');
        
        // Verify or reject payment (handled by controller for transactional safety)
        Route::post('/{order}/verify', [OrderController::class, 'verifyPayment'])->name('verify');
        
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/{order}/tracking/edit', [OrderController::class, 'editTrackingNumber'])->name('editTracking');
        Route::patch('/{order}/tracking', [OrderController::class, 'updateTrackingNumber'])->name('updateTracking');
    });

    // ✅ KELOLA BANTUAN/HELP CONTENT
    Route::prefix('help')->name('help.')->group(function () {
        Route::get('/', [HelpController::class, 'index'])->name('index');
        Route::get('/create', [HelpController::class, 'create'])->name('create');
        Route::post('/', [HelpController::class, 'store'])->name('store');
        Route::get('/{help}/edit', [HelpController::class, 'edit'])->name('edit');
        Route::patch('/{help}', [HelpController::class, 'update'])->name('update');
        Route::delete('/{help}', [HelpController::class, 'destroy'])->name('destroy');
    });

    // ✅ KELOLA REVIEWS
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/pending', [ReviewController::class, 'pending'])->name('pending');
        Route::post('/{review}/approve', [ReviewController::class, 'approve'])->name('approve');
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
    });

    // ✅ SYSTEM SETTINGS
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SystemController::class, 'index'])->name('index');
        
        Route::post('/general', [SystemController::class, 'updateGeneral'])->name('updateGeneral');
        Route::post('/notifications', [SystemController::class, 'updateNotifications'])->name('updateNotifications');
        Route::post('/payment-verification', [SystemController::class, 'updatePaymentVerification'])->name('updatePaymentVerification');
        
        Route::post('/clear-cache', [SystemController::class, 'clearCache'])->name('clearCache');
        Route::post('/toggle-maintenance', [SystemController::class, 'toggleMaintenance'])->name('toggleMaintenance');
        Route::post('/backup-database', [SystemController::class, 'backupDatabase'])->name('backupDatabase');
    });

    // ✅ EXPENSE MANAGEMENT (PENGELUARAN)
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ExpenseController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\ExpenseController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\ExpenseController::class, 'store'])->name('store');
        Route::get('/{expense}/edit', [\App\Http\Controllers\Admin\ExpenseController::class, 'edit'])->name('edit');
        Route::put('/{expense}', [\App\Http\Controllers\Admin\ExpenseController::class, 'update'])->name('update');
        Route::delete('/{expense}', [\App\Http\Controllers\Admin\ExpenseController::class, 'destroy'])->name('destroy');
    });

    // ✅ ATTENDANCE TRACKING
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', function (Request $request) {
            $query = Attendance::where('user_id', Auth::id());
            
            // Filter berdasarkan bulan hanya jika user memilih
            if ($request->filled('year') && $request->filled('month_num')) {
                $month = $request->input('year') . '-' . str_pad($request->input('month_num'), 2, '0', STR_PAD_LEFT);
                $monthCarbon = Carbon::parse($month . '-01');
                $startDate = $monthCarbon->copy()->startOfMonth()->toDateString();
                $endDate = $monthCarbon->copy()->endOfMonth()->toDateString();
                $query->whereBetween('date', [$startDate, $endDate]);
            } elseif ($request->filled('month')) {
                $monthCarbon = Carbon::parse($request->input('month') . '-01');
                $startDate = $monthCarbon->copy()->startOfMonth()->toDateString();
                $endDate = $monthCarbon->copy()->endOfMonth()->toDateString();
                $query->whereBetween('date', [$startDate, $endDate]);
            }
            // Jika tidak ada filter, tampilkan semua data
            
            $attendances = $query->orderBy('date', 'desc')
                ->paginate(20);
            
            return view('admin.attendance.index', compact('attendances'));
        })->name('index');
        
        Route::post('/checkin', function (Request $request) {
            $today = today()->toDateString();
            
            // Check if already checked in today
            $existingAttendance = Attendance::where('user_id', Auth::id())
                ->where('date', $today)
                ->first();
            
            if (!$existingAttendance) {
                Attendance::create([
                    'user_id' => Auth::id(),
                    'date' => $today,
                    'check_in' => now(),
                    'status' => 'hadir'
                ]);
            }
            
            return redirect()->back()->with('success', 'Check-in berhasil.');
        })->name('checkin');
        
        Route::post('/checkout', function (Request $request) {
            $today = today()->toDateString();
            
            $attendance = Attendance::where('user_id', Auth::id())
                ->where('date', $today)
                ->first();
            
            if ($attendance && !$attendance->check_out) {
                $attendance->update(['check_out' => now()]);
            }
            
            return redirect()->back()->with('success', 'Check-out berhasil.');
        })->name('checkout');
    });

    // ✅ POINTS LOGS (Admin/Owner) - view all point awards
    Route::get('/points', function (Request $request) {
        // If points_logs table not present (migrations not run), gracefully show empty page with instruction
        if (! Schema::hasTable('points_logs')) {
            $logs = new LengthAwarePaginator([], 0, 30, Paginator::resolveCurrentPage(), ['path' => request()->url(), 'query' => request()->query()]);
            $users = User::where('role', 'user')->orderBy('name')->get();
            return view('admin.points.index', compact('logs', 'users'))->with('warning', 'Tabel `points_logs` belum ada. Jalankan `php artisan migrate`.');
        }

        $query = PointLog::with('user');
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        $logs = $query->orderBy('created_at', 'desc')->paginate(30)->withQueryString();
        $users = User::where('role', 'user')->orderBy('name')->get();
        /** @var \Illuminate\Pagination\LengthAwarePaginator $logs */
        return view('admin.points.index', compact('logs', 'users'));
    })->name('points.index');

    // Points management routes
    Route::post('/points/award', [PointController::class, 'awardPoints'])->name('points.award');
    Route::post('/points/deduct', [PointController::class, 'deductPoints'])->name('points.deduct');
    Route::post('/points/recalculate', [PointController::class, 'recalculatePoints'])->name('points.recalculate');

    // ✅ LOANS MANAGEMENT (Admin/Owner)
    Route::prefix('loans')->name('loans.')->group(function () {
        Route::get('/', function () {
            $status = request('status', 'active');
            
            $query = Loan::with('user', 'loanBook', 'book');
            
            if ($status !== 'all') {
                if ($status === 'overdue') {
                    $query->where('status', 'active')->where('return_date', '<', now());
                } else {
                    $query->where('status', $status);
                }
            }
            
            $loans = $query->orderBy('return_date', 'asc')->paginate(15);
            $users = User::where('role', 'user')->get();
            
            // Query LoanBook instead of Book for available loan books
            try {
                $books = LoanBook::where('loan_stok', '>', 0)->get();
            } catch (\Exception $e) {
                \Log::warning('Fetching LoanBooks for admin.loans failed: ' . $e->getMessage());
                // Fallback to empty collection
                $books = collect([]);
            }
            
            return view('admin.loans.index', compact('loans', 'users', 'books'));
        })->name('index');

        Route::post('/', function (Request $request) {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'book_id' => 'nullable|exists:loan_books,id',
                'borrowed_date' => 'required|date',
                'return_date' => 'required|date|after:borrowed_date',
                'quantity' => 'nullable|integer|min:1',
                'notes' => 'nullable|string|max:500',
            ]);

            // Handle backward compatibility: accept book_id but store as loan_book_id
            $loanBookId = $validated['book_id'];
            if (!$loanBookId) {
                return redirect()->back()->with('error', 'Pilih buku untuk dipinjamkan');
            }

            $quantity = intval($validated['quantity'] ?? 1);

            try {
                \DB::transaction(function () use ($validated, $loanBookId, $quantity) {
                    $loanBook = LoanBook::lockForUpdate()->findOrFail($loanBookId);

                    $available = $loanBook->loan_stok ?? 0;
                    if ($available < $quantity) {
                        throw new \Exception('Stok peminjaman tidak mencukupi (tersedia: ' . $available . ')');
                    }

                    $previous = $available;
                    $loanBook->decrement('loan_stok', $quantity);
                    StockLog::create([
                        'loan_book_id' => $loanBook->id,
                        'user_id' => auth()->id(),
                        'type' => 'loan',
                        'change' => -1 * $quantity,
                        'previous_stock' => $previous,
                        'new_stock' => $loanBook->loan_stok,
                        'meta' => json_encode(['note' => 'admin-created-loan', 'quantity' => $quantity]),
                    ]);

                    Loan::create([
                        'user_id' => $validated['user_id'],
                        'loan_book_id' => $loanBookId,
                        'quantity' => $quantity,
                        'borrowed_date' => $validated['borrowed_date'],
                        // normalize return_date to end of day to avoid timezone/partial-day issues
                        'return_date' => \Carbon\Carbon::parse($validated['return_date'])->endOfDay(),
                        'notes' => $validated['notes'] ?? null,
                        'status' => 'active',
                        'location' => 'offline-store',
                    ]);
                });
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal menambah peminjaman: ' . $e->getMessage());
            }

            return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil ditambahkan!');
        })->name('store');

        Route::get('/{loan}', function (Loan $loan) {
            return response()->json($loan->load('user', 'loanBook', 'book'));
        })->name('show');

        // Admin/Owner: Update an active loan
        Route::put('/{loan}', function (Request $request, Loan $loan) {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'book_id' => 'required|exists:loan_books,id',
                'borrowed_date' => 'required|date',
                'return_date' => 'required|date|after:borrowed_date',
                'quantity' => 'nullable|integer|min:1',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($loan->status !== 'active') {
                return redirect()->back()->with('error', 'Hanya peminjaman aktif yang dapat diedit');
            }

            $newLoanBookId = $validated['book_id'];
            $newQty = intval($validated['quantity'] ?? 1);
            $oldLoanBookId = $loan->loan_book_id;
            $oldQty = intval($loan->quantity ?? 1);

            try {
                \DB::transaction(function () use ($loan, $validated, $newLoanBookId, $newQty, $oldLoanBookId, $oldQty) {
                    // If loan book changed, return previous qty to previous loan book
                    if ($oldLoanBookId && $oldLoanBookId != $newLoanBookId) {
                        $oldLoanBook = LoanBook::lockForUpdate()->find($oldLoanBookId);
                        if ($oldLoanBook) {
                            $previous = $oldLoanBook->loan_stok ?? 0;
                            $oldLoanBook->increment('loan_stok', $oldQty);
                            StockLog::create([
                                'loan_book_id' => $oldLoanBook->id,
                                'user_id' => auth()->id(),
                                'type' => 'loan_adjustment',
                                'change' => $oldQty,
                                'previous_stock' => $previous,
                                'new_stock' => $oldLoanBook->loan_stok,
                                'meta' => json_encode(['note' => 'admin-edit-return-previous-loanbook', 'loan_id' => $loan->id]),
                            ]);
                        }
                    }

                    // Apply stock changes on the new loan book
                    $newLoanBook = LoanBook::lockForUpdate()->findOrFail($newLoanBookId);
                    if ($oldLoanBookId == $newLoanBookId) {
                        // same book, adjust by diff
                        $diff = $newQty - $oldQty;
                        if ($diff > 0) {
                            if (($newLoanBook->loan_stok ?? 0) < $diff) {
                                throw new \Exception('Stok peminjaman tidak mencukupi (peningkatan jumlah)');
                            }
                            $previous = $newLoanBook->loan_stok ?? 0;
                            $newLoanBook->decrement('loan_stok', $diff);
                            StockLog::create([
                                'loan_book_id' => $newLoanBook->id,
                                'user_id' => auth()->id(),
                                'type' => 'loan',
                                'change' => -1 * $diff,
                                'previous_stock' => $previous,
                                'new_stock' => $newLoanBook->loan_stok,
                                'meta' => json_encode(['note' => 'admin-edit-qty-increase', 'loan_id' => $loan->id]),
                            ]);
                        } elseif ($diff < 0) {
                            $previous = $newLoanBook->loan_stok ?? 0;
                            $newLoanBook->increment('loan_stok', -$diff);
                            StockLog::create([
                                'loan_book_id' => $newLoanBook->id,
                                'user_id' => auth()->id(),
                                'type' => 'loan_adjustment',
                                'change' => -1 * $diff * -1,
                                'previous_stock' => $previous,
                                'new_stock' => $newLoanBook->loan_stok,
                                'meta' => json_encode(['note' => 'admin-edit-qty-decrease', 'loan_id' => $loan->id]),
                            ]);
                        }
                    } else {
                        // different book: ensure enough stock and decrement new
                        if (($newLoanBook->loan_stok ?? 0) < $newQty) {
                            throw new \Exception('Stok peminjaman tidak mencukupi pada buku tujuan');
                        }
                        $previous = $newLoanBook->loan_stok ?? 0;
                        $newLoanBook->decrement('loan_stok', $newQty);
                        StockLog::create([
                            'loan_book_id' => $newLoanBook->id,
                            'user_id' => auth()->id(),
                            'type' => 'loan',
                            'change' => -1 * $newQty,
                            'previous_stock' => $previous,
                            'new_stock' => $newLoanBook->loan_stok,
                            'meta' => json_encode(['note' => 'admin-edit-change-loanbook', 'loan_id' => $loan->id]),
                        ]);
                    }

                    // Update loan record
                    $loan->user_id = $validated['user_id'];
                    $loan->loan_book_id = $newLoanBookId;
                    $loan->quantity = $newQty;
                    $loan->borrowed_date = $validated['borrowed_date'];
                    $loan->return_date = $validated['return_date'];
                    $loan->notes = $validated['notes'] ?? null;
                    $loan->save();
                });
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal memperbarui peminjaman: ' . $e->getMessage());
            }

            return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil diperbarui!');
        })->name('update');

        Route::patch('/{loan}/return', function (Request $request, Loan $loan) {
            $request->validate([
                'returned_at' => 'nullable|date',
            ]);

            if ($loan->status !== 'active') {
                return redirect()->back()->with('error', 'Peminjaman ini sudah tidak aktif');
            }

            $returnedAt = $request->returned_at ? \Carbon\Carbon::parse($request->returned_at) : \Carbon\Carbon::now();

            // Check if overdue
            try {
                \DB::transaction(function () use ($loan, $returnedAt) {
                    $book = Book::lockForUpdate()->find($loan->book_id);
                    if ($book) {
                        // return the book to the appropriate loan stock field (increase by loan quantity)
                        $loanField = Schema::hasColumn('books', 'loan_stok') ? 'loan_stok' : 'stok';
                        $previous = $book->{$loanField} ?? 0;
                        $qty = intval($loan->quantity ?? 1);
                        $book->increment($loanField, $qty);
                        StockLog::create([
                            'book_id' => $book->id,
                            'user_id' => auth()->id(),
                            'type' => 'loan',
                            'change' => $qty,
                            'previous_stock' => $previous,
                            'new_stock' => $book->{$loanField},
                            'meta' => json_encode(['loan_id' => $loan->id, 'action' => 'return', 'quantity' => $qty, 'field' => $loanField]),
                        ]);
                    }

                    // Compare using Carbon instances (normalize loan->return_date to endOfDay for fair comparison)
                    $loanReturn = \Carbon\Carbon::parse($loan->return_date);
                    if ($returnedAt->greaterThan($loanReturn)) {
                        $loan->status = 'overdue';
                    } else {
                        $loan->status = 'returned';
                    }

                    $loan->returned_at = $returnedAt;
                    $loan->save();
                });
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal memperbarui peminjaman: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'Status peminjaman berhasil diperbarui!');
        })->name('return');
    });

    // ✅ LOAN STOCK MANAGEMENT
    Route::prefix('loan-stock')->name('loan-stock.')->group(function () {
        Route::get('/', [LoanStockController::class, 'index'])->name('index');
        Route::get('/create', [LoanStockController::class, 'create'])->name('create');
        Route::post('/', [LoanStockController::class, 'store'])->name('store');
        Route::put('/{loan_book}', [LoanStockController::class, 'update'])->name('update');
        Route::delete('/{loan_book}', [LoanStockController::class, 'destroy'])->name('destroy');
        Route::get('/{loan_book}/history', [LoanStockController::class, 'history'])->name('history');
        Route::post('/bulk-update', [LoanStockController::class, 'bulkUpdate'])->name('bulk-update');
    });
});

// ✅ OWNER SPECIAL ROUTES
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    // Dashboard Owner
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    // ✅ TAMBAH ROUTE DASHBOARD CONTROLLER (TANPA MENGHAPUS YANG LAMA)
    Route::get('/dashboard/controller', [HomeController::class, 'ownerDashboard'])->name('dashboard.controller');
    
    // ✅ DETAIL PRESENSI UNTUK OWNER - LIHAT SEMUA STAFF (owner-only route)
    Route::get('/attendance/detail', [AttendanceController::class, 'detail'])->name('attendance.detail');
    
    // ✅ LAPORAN LABA RUGI
    Route::get('/reports/profit-loss', [\App\Http\Controllers\Owner\ReportController::class, 'profitLoss'])->name('reports.profit-loss');
    
    Route::get('/reports', function () {
        try {
            // === STATISTICS ===
            // Use 'delivered' (actual enum in orders table) as completed marker
            $totalRevenue = Order::where('status', 'delivered')->sum('total_price');
            $totalBooksSold = OrderItem::whereHas('order', function ($query) {
                $query->where('status', 'delivered');
            })->sum('quantity');
            $totalUsers = User::count();

            // Group orders into "processes" by created_at minute so multiple orders
            // created at the same time count as one process in reports/UI.
            $allOrdersForGrouping = Order::orderBy('created_at', 'desc')->get();
            $groupedProcesses = $allOrdersForGrouping->groupBy(function ($order) {
                return $order->created_at->format('Y-m-d H:i');
            });

            $pendingOrders = $groupedProcesses->filter(function ($group) {
                return $group->first()->status === 'pending';
            })->count();
            
            // === MONTHLY DATA (6 BULAN) ===
            $monthlyReports = [];
            $revenueChartData = [];
            $revenueChartLabels = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $startDate = now()->subMonths($i)->startOfMonth();
                $endDate = now()->subMonths($i)->endOfMonth();
                $monthLabel = $startDate->translatedFormat('M Y');
                
                $monthlyRevenue = Order::where('status', 'delivered')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('total_price');

                // Sum operational costs in the same month (purchases, restock, misc)
                try {
                    $monthlyOperational = OperationalCost::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
                } catch (\Exception $e) {
                    $monthlyOperational = 0;
                }
                
                $monthlyBooksSold = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->where('status', 'delivered')
                          ->whereBetween('created_at', [$startDate, $endDate]);
                })->sum('quantity');
                
                $newUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
                // Count grouped processes within the month (groups by minute)
                $ordersInMonth = Order::whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'desc')->get();
                $monthlyOrders = $ordersInMonth->groupBy(function ($order) {
                    return $order->created_at->format('Y-m-d H:i');
                })->count();
                
                $monthlyReports[] = [
                    'month' => $startDate->translatedFormat('F Y'),
                    'month_label' => $monthLabel,
                    'revenue' => $monthlyRevenue,
                    'books_sold' => $monthlyBooksSold,
                    'new_users' => $newUsers,
                    'orders' => $monthlyOrders
                ];
                
                $revenueChartLabels[] = $monthLabel;
                $revenueChartData[] = $monthlyRevenue;
                $operationalChartData[] = $monthlyOperational ?? 0;
            }
            
            // === CATEGORY DATA ===
            $categoryData = Book::select('kategori')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('kategori')
                ->get();
            
            $categoryLabels = $categoryData->pluck('kategori')->toArray();
            $categoryCounts = $categoryData->pluck('total')->toArray();
            
            // === TOP SELLERS ===
            $bestsellers = OrderItem::with('book')
                ->whereHas('order', function ($query) {
                    $query->where('status', 'delivered');
                })
                ->select('book_id')
                ->selectRaw('SUM(quantity) as total_sold')
                ->groupBy('book_id')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get();
            
            // === BOOKS SOLD DETAIL (untuk modal) ===
            $booksSoldDetail = OrderItem::with('book')
                ->whereHas('order', function ($query) {
                    $query->where('status', 'delivered');
                })
                ->select('book_id')
                ->selectRaw('SUM(quantity) as total_sold')
                ->selectRaw('COUNT(*) as order_count')
                ->groupBy('book_id')
                ->orderByDesc('total_sold')
                ->get();
            
            // === ORDER STATUS DATA ===
            // Build order status counts based on grouped processes (use first order's status)
            $orderStatusCounts = $groupedProcesses->map(function ($group) {
                return $group->first()->status;
            })->countBy()->map(function ($count, $status) {
                return (object)[
                    'status' => $status,
                    'total' => $count
                ];
            })->values();

            $orderStatusData = $orderStatusCounts;
            
            // === ATTENDANCE DATA (PRESENSI) ===
            $attendanceData = Attendance::select('status')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('status')
                ->get()
                ->keyBy('status');
            
            $totalPresensi = Attendance::count();
            $totalAttendance = $totalPresensi; // Total untuk denominator persentase
            $totalAlpha = Attendance::where('status', 'alpha')->count();
            $totalIzin = Attendance::where('status', 'izin')->count();
            
            // === LOANS DATA (PEMINJAMAN) ===
            $activeLoans = Loan::where('status', 'active')->count();
            $returnedLoans = Loan::where('status', 'returned')->count();
            $overdueLoans = Loan::where('status', 'overdue')->count();
            
            // === LOAN DETAILS - PENGGUNA PEMINJAM ===
            $loanByUsers = Loan::with(['user', 'book'])
                ->select('user_id')
                ->selectRaw('COUNT(*) as total_loans')
                ->selectRaw('MAX(created_at) as last_loan_date')
                ->groupBy('user_id')
                ->orderByDesc('total_loans')
                ->limit(10)
                ->get();
            
            
            // === LOW STOCK BOOKS ===
            try {
                if (Schema::hasColumn('books', 'stok')) {
                    $lowStockBooks = Book::where('stok', '<', 5)
                                        ->orderBy('stok', 'asc')
                                        ->take(5)
                                        ->get();
                } else {
                    $lowStockBooks = Book::where('status', 'available')
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();
                }
            } catch (\Exception $e) {
                $lowStockBooks = collect([]);
            }

            // === USER DISTRIBUTION ===
            // Count users by role (kept for summary cards)
            $usersByRole = DB::table('users')
                ->select('role')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('role')
                ->get()
                ->keyBy('role');

            $adminUsers = $usersByRole->get('admin')?->total ?? 0;
            $ownerUsers = $usersByRole->get('owner')?->total ?? 0;
            $normalUsers = $usersByRole->get('user')?->total ?? 0;

            // Users by region (exclude admin & owner) — group by `province`
            try {
                $usersByRegion = DB::table('users')
                    ->whereNotIn('role', ['admin', 'owner'])
                    ->select('province')
                    ->selectRaw('COUNT(*) as total')
                    ->groupBy('province')
                    ->orderByDesc('total')
                    ->get()
                    ->map(function ($r) {
                        return [
                            'province' => $r->province ?? 'Unknown',
                            'total' => (int) $r->total,
                        ];
                    });
            } catch (\Exception $e) {
                $usersByRegion = collect([]);
            }

            $totalUsersNonAdmin = DB::table('users')->whereNotIn('role', ['admin', 'owner'])->count();

            // Build full province labels and counts (include all provinces from config, fill 0 where none)
            $provinceList = config('indonesia.provinces') ?? [];
            $provinceCounts = collect($provinceList)->map(function ($prov) use ($usersByRegion) {
                $found = collect($usersByRegion)->firstWhere('province', $prov);
                return $found['total'] ?? 0;
            })->values()->all();

            return view('owner.reports', compact(
                'totalRevenue',
                'totalBooksSold', 
                'totalUsers',
                'pendingOrders',
                'monthlyReports',
                'revenueChartLabels',
                'revenueChartData',
                'operationalChartData',
                'categoryLabels', 
                'categoryCounts',
                'bestsellers',
                'booksSoldDetail',
                'orderStatusData',
                'lowStockBooks',
                'totalAttendance',
                'totalAlpha',
                'totalIzin',
                'totalPresensi',
                'activeLoans',
                'returnedLoans',
                'overdueLoans',
                'loanByUsers',
                'adminUsers',
                'ownerUsers',
                'normalUsers',
                'usersByRegion',
                'totalUsersNonAdmin',
                'provinceList',
                'provinceCounts'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Owner reports error: ' . $e->getMessage());
            return view('owner.reports')->with('error', 'Gagal memuat data laporan: ' . $e->getMessage());
        }
    })->name('reports');
});

// Fallback
Route::fallback(function () {
    return redirect('/');
});