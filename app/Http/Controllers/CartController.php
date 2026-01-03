<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\ShippingCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Tampilkan keranjang belanja
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $books = [];
        $total = 0;
        $cartData = [];

        if (!empty($cart)) {
            $bookIds = array_keys($cart);
            $books = Book::whereIn('id', $bookIds)->where('status', 'available')->get();
            
            foreach ($books as $book) {
                if (isset($cart[$book->id])) {
                    $subtotal = $book->harga * $cart[$book->id];
                    $total += $subtotal;
                    $cartData[$book->id] = [
                        'quantity' => $cart[$book->id],
                        'book' => $book
                    ];
                }
            }
        }

        return view('cart.index', compact('cart', 'books', 'total', 'cartData'));
    }

    /**
     * Tambahkan buku ke keranjang
     * Bisa dipanggil via GET (legacy) atau POST (AJAX publik)
     */
    public function add(Request $request, $bookId)
    {
        // Cek jika AJAX dan user belum login
        if ($request->ajax() && !Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu',
                'requiresLogin' => true
            ], 401);
        }

        $book = Book::where('status', 'available')->findOrFail($bookId);

        // Validasi stok
        if ($book->stok < 1) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, buku sedang habis!'
                ], 400);
            }
            return redirect()->back()->with('error', 'Maaf, buku sedang habis!');
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$bookId])) {
            // Jika buku sudah ada, cek stok sebelum tambah
            if ($cart[$bookId] < $book->stok) {
                $cart[$bookId]++;
            } else {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak cukup untuk menambah lebih banyak!'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Stok tidak cukup untuk menambah lebih banyak!');
            }
        } else {
            $cart[$bookId] = 1;
        }

        session()->put('cart', $cart);
        
        // Jika AJAX, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan ke keranjang!',
                'cartCount' => count($cart)
            ]);
        }
        
        return redirect()->back()->with('success', 'Buku berhasil ditambahkan ke keranjang!');
    }

    /**
     * Kurangi kuantitas buku di keranjang (support POST AJAX)
     */
    public function decrease(Request $request, $bookId)
    {
        if ($request->ajax() && !Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu',
                'requiresLogin' => true
            ], 401);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$bookId])) {
            if ($cart[$bookId] > 1) {
                $cart[$bookId]--;
            } else {
                unset($cart[$bookId]);
            }
        }

        session()->put('cart', $cart);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kuantitas berhasil diperbarui!',
                'cartCount' => count($cart)
            ]);
        }
        
        return redirect()->back()->with('success', 'Kuantitas berhasil diperbarui!');
    }

    /**
     * Hapus buku dari keranjang (support POST AJAX)
     */
    public function remove(Request $request, $bookId)
    {
        if ($request->ajax() && !Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu',
                'requiresLogin' => true
            ], 401);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$bookId])) {
            unset($cart[$bookId]);
        }

        session()->put('cart', $cart);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dihapus dari keranjang!',
                'cartCount' => count($cart)
            ]);
        }
        
        return redirect()->back()->with('success', 'Buku berhasil dihapus dari keranjang!');
    }

    /**
     * Update kuantitas langsung via input
     */
    public function updateQuantity(Request $request, $bookId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $book = Book::where('status', 'available')->findOrFail($bookId);
        $quantity = $request->quantity;

        // Validasi stok
        if ($quantity > $book->stok) {
            return redirect()->back()->with('error', 'Stok hanya tersedia ' . $book->stok . ' buku!');
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$bookId])) {
            $cart[$bookId] = $quantity;
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Kuantitas berhasil diperbarui!');
    }

    /**
     * Tampilkan form checkout
     */
    public function checkoutForm()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        $books = [];
        $total = 0;
        $totalWeight = 0; // Total berat dalam gram

        $bookIds = array_keys($cart);
        $books = Book::whereIn('id', $bookIds)->where('status', 'available')->get();
        
        foreach ($books as $book) {
            if (isset($cart[$book->id])) {
                $subtotal = $book->harga * $cart[$book->id];
                $total += $subtotal;
                
                // Hitung total berat (default 500g jika tidak ada)
                $bookWeight = $book->berat ?? config('shipping.default_book_weight', 500);
                $totalWeight += ($bookWeight * $cart[$book->id]);
            }
        }

        $user = Auth::user();
        $bankAccounts = [
            'bca' => ['name' => 'Bank Central Asia (BCA)', 'account' => '1234567890', 'accountName' => 'PT Ruang Aksara'],
            'mandiri' => ['name' => 'Bank Mandiri', 'account' => '9876543210', 'accountName' => 'PT Ruang Aksara'],
            'bni' => ['name' => 'Bank Nasional Indonesia (BNI)', 'account' => '1122334455', 'accountName' => 'PT Ruang Aksara'],
        ];

        return view('checkout.form', compact('cart', 'books', 'total', 'totalWeight', 'user', 'bankAccounts'));
    }

    /**
     * Proses checkout - ubah cart ke orders
     */
    public function checkout(Request $request)
    {
        // Validation rules
        $rules = [
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kode_pos' => 'required|string|size:5',
            'alamat' => 'required|string|max:500',
            'payment_method' => 'required|in:bca,mandiri,bni,cash',
            'shipping_method' => 'required|in:jne,jnt,ninja,antera',
        ];

        // Proof of payment required untuk transfer (bukan cash)
        if ($request->payment_method !== 'cash') {
            $rules['proof_of_payment'] = 'required|image|max:5120'; // Max 5MB
        }

        $request->validate($rules);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        // Calculate cart totals and total weight
        $cartSubtotal = 0;
        $cartWeightGrams = 0;
        
        foreach ($cart as $bId => $qty) {
            $b = Book::find($bId);
            if (!$b) continue;
            $cartSubtotal += ($b->harga * $qty);
            $bookWeight = $b->berat ?? config('shipping.default_book_weight', 500);
            $cartWeightGrams += ($bookWeight * $qty);
        }

        // Calculate shipping cost using ShippingCalculator
        $shippingEstimate = ShippingCalculator::calculate(
            $cartWeightGrams,
            $request->provinsi,
            $request->shipping_method
        );
        
        $shippingCost = $shippingEstimate['cost'] ?? 0;
        $shippingZone = $shippingEstimate['zone_name'] ?? 'Unknown';

        // Check for free shipping
        if (ShippingCalculator::isFreeShipping($cartSubtotal)) {
            $shippingCost = 0;
        }

        // Distribute shipping cost proportionally per order line
        $remainingShipping = $shippingCost;

        // Format alamat lengkap
        $fullAddress = sprintf(
            "%s\n%s, Kec. %s\n%s, %s %s",
            $request->alamat,
            $request->kota,
            $request->kecamatan,
            $request->provinsi,
            $request->kode_pos,
            $shippingZone ? "($shippingZone)" : ''
        );

        // Generate order group ID untuk transaksi ini
        $orderGroupId = 'ORD-' . now()->format('Ymd-His') . '-' . strtoupper(substr(uniqid('', true), -6));

        // REFACTORED: Create SINGLE order with multiple items instead of multiple orders
        $totalOrderPrice = 0;
        $orderItems = [];
        
        // Validate all books first
        foreach ($cart as $bookId => $quantity) {
            $book = Book::where('status', 'available')->findOrFail($bookId);
            if ($book->stok < $quantity) {
                session()->flash('error', 'Stok buku "' . $book->judul . '" tidak cukup!');
                return redirect()->route('cart.index');
            }
            $totalOrderPrice += ($book->harga * $quantity);
            $orderItems[$bookId] = [
                'book' => $book,
                'quantity' => $quantity,
                'subtotal' => $book->harga * $quantity,
            ];
        }

        // Handle proof of payment file upload
        $proofOfPayment = null;
        if ($request->hasFile('proof_of_payment')) {
            $file = $request->file('proof_of_payment');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $proofOfPayment = $file->storeAs('proof-of-payment', $filename, 'public');
        }

        // Tentukan bank account based on payment method
        $bankAccount = null;
        if ($request->payment_method === 'cash') {
            $bankAccount = 'Pembayaran tunai di lokasi penjemputan';
        } else {
            $bankAccounts = [
                'bca' => 'BCA 1234567890 a.n. PT Ruang Aksara',
                'mandiri' => 'Mandiri 9876543210 a.n. PT Ruang Aksara',
                'bni' => 'BNI 1122334455 a.n. PT Ruang Aksara',
            ];
            $bankAccount = $bankAccounts[$request->payment_method] ?? null;
        }

        // Create SINGLE order with shipping cost
        $order = \App\Models\Order::create([
            'user_id' => Auth::id(),
            'book_id' => null, // No longer needed - see order_items instead
            'quantity' => array_sum(array_column($orderItems, 'quantity')), // Total qty
            'total_price' => $totalOrderPrice + $shippingCost,
            'status' => 'pending',
            'order_group_id' => $orderGroupId,
            'alamat' => $fullAddress,
            'telepon' => $request->telepon,
            'payment_method' => $request->payment_method,
            'bank_account' => $bankAccount,
            'shipping_method' => $request->shipping_method,
            'shipping_cost' => $shippingCost,
            'proof_of_payment' => $proofOfPayment,
            'payment_status' => $request->payment_method === 'cash' ? 'verified' : 'pending',
        ]);

        // Create order_items for each book
        foreach ($orderItems as $bookId => $itemData) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $bookId,
                'quantity' => $itemData['quantity'],
                'price' => $itemData['book']->harga,
                'subtotal' => $itemData['subtotal'],
            ]);
        }

        // If payment is already verified (cash), set status to 'processing' 
        if ($request->payment_method === 'cash') {
            try {
                $order->status = 'processing';
                $order->save();

                // Award points immediately for cash orders
                $this->awardPointsForOrder($order);

                // Notify user about verification/processing
                try {
                    $order->user->notify(new \App\Notifications\PaymentApproved($order));
                } catch (\Throwable $e) {
                    \Log::warning('Failed to send PaymentApproved notification for cash order ' . $order->id . ': ' . $e->getMessage());
                }
            } catch (\Throwable $e) {
                \Log::warning('Failed to set cash order to processing ' . ($order->id ?? 'unknown') . ': ' . $e->getMessage());
            }
        }

        // Kurangi stok untuk setiap item
        foreach ($orderItems as $bookId => $itemData) {
            $book = $itemData['book'];
            // PENTING: Hanya kurangi stok untuk cash/COD, transfer orders menunggu pembayaran diverifikasi
            if ($request->payment_method === 'cash') {
                $book->decrement('stok', $itemData['quantity']);
                $book->increment('terjual', $itemData['quantity']);
            }
        }

        // Hapus cart dari session
        session()->forget('cart');

        return redirect()->route('orders.index')->with('success', 'Checkout berhasil! Silakan ikuti instruksi pembayaran. Pesanan akan diproses setelah pembayaran kami verifikasi.');
    }

    /**
     * Bersihkan semua keranjang
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    /**
     * Get cart count (untuk header) - Return JSON
     */
    public function getCartCount()
    {
        \Log::info('Fetching cart count', ['session' => session()->all()]);
        $cart = session()->get('cart', []);
        return response()->json([
            'count' => count($cart),
            'items' => $cart
        ]);
    }

    /**
     * Award points to user for an order (same as in OrderController)
     * Called when payment is verified immediately
     */
    private function awardPointsForOrder(\App\Models\Order $order)
    {
        try {
            $user = $order->user;
            if (!$user) {
                \Log::warning('Cannot award points: order has no user', ['order_id' => $order->id]);
                return;
            }

            $pointsPer = (int) config('rewards.points_per_currency', 10000);
            $minPoints = (int) config('rewards.minimum_points', 0);

            // Calculate points: floor(total_price / pointsPer)
            $calculated = (int) floor(($order->total_price ?? 0) / max(1, $pointsPer));
            $points = max($minPoints, $calculated);

            if ($points <= 0) {
                return;
            }

            DB::beginTransaction();
            try {
                // Increment user's points
                $user->increment('points', $points);

                // Log into points_logs table if model exists
                if (class_exists(\App\Models\PointLog::class)) {
                    \App\Models\PointLog::create([
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'points' => $points,
                        'type' => 'payment_verified',
                        'meta' => json_encode(['total_price' => $order->total_price]),
                    ]);
                }

                DB::commit();
                \Log::info('Points awarded for order', [
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'points' => $points,
                ]);
            } catch (\Throwable $e) {
                DB::rollBack();
                \Log::error('Failed awarding points for order ' . $order->id . ': ' . $e->getMessage());
            }
        } catch (\Throwable $e) {
            \Log::error('Error in awardPointsForOrder: ' . $e->getMessage());
        }
    }
}