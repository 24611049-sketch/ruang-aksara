<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Book;
use App\Events\OrderDelivered;
use App\Models\PointLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\StockLog;

class OrderController extends Controller
{
    public function index()
    {
        // Ambil pesanan user yang sedang login (paginated untuk daftar)
        // Now load items relationship for new structure
        $orders = Order::with(['items.book', 'user'])
                      ->where('user_id', Auth::id())
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        // Ambil semua pesanan terbaru tanpa pagination untuk pengelompokan di view
        $allOrders = Order::with(['items.book', 'user'])
                      ->where('user_id', Auth::id())
                      ->orderBy('created_at', 'desc')
                      ->get();

        // Kelompokkan pesanan berdasarkan order_group_id
        // Since now we have single orders with multiple items, this is mostly for display organization
        $groupedOrders = $allOrders->groupBy('order_group_id')->filter(function ($group, $key) {
            return !empty($key);
        });

        /** @var \Illuminate\Pagination\LengthAwarePaginator $orders */
        /** @var \Illuminate\Support\Collection $groupedOrders */
        return view('orders.index', compact('orders', 'groupedOrders'));
    }

    /**
     * ADMIN/OWNER - View all orders with filters, search and pagination
     */
    public function adminIndex(Request $request)
    {
        // Load items relationship for new order structure
        $query = Order::with(['user', 'items.book'])->orderBy('created_at', 'desc');

        // Filter by payment status if provided (pending, verified, failed)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('payment_status', $request->status);
        }

        // Allow filtering by order status (shipping status)
        if ($request->filled('order_status')) {
            $orderStatus = $request->order_status;
            if ($orderStatus !== 'all') {
                $query->where('status', $orderStatus);
            }
        }

        // Search by order id, user name/email or book title
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qbuilder) use ($q) {
                $qbuilder->where('id', $q)
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    })
                    ->orWhereHas('items.book', function ($b) use ($q) {
                        $b->where('judul', 'like', "%{$q}%");
                    });
            });
        }

        $perPage = intval($request->input('per_page', 20));
        $perPage = $perPage > 0 && $perPage <= 200 ? $perPage : 20;

        $orders = $query->paginate($perPage)->withQueryString();

        // Kelompokkan orders berdasarkan order_group_id
        $groupedOrders = $orders->getCollection()->groupBy('order_group_id');

        return view('admin.orders.index', compact('orders', 'groupedOrders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.book', 'user'])
                     ->where('user_id', Auth::id())
                     ->findOrFail($id);

        // For backward compatibility: populate $orders from order items
        // Each item in the order now represents what was previously an individual order
        $orders = $order->items->map(function ($item) use ($order) {
            return (object)[
                'id' => $item->id,
                'book_id' => $item->book_id,
                'quantity' => $item->quantity,
                'total_price' => $item->subtotal,
                'book' => $item->book,
                'order_group_id' => $order->order_group_id,
            ];
        });

        return view('orders.show', compact('order', 'orders'));
    }

    public function store(Request $request, $bookId)
    {
        $book = Book::where('status', 'available')->findOrFail($bookId);

        // Validasi stok
        if ($book->stok < 1) {
            return redirect()->back()->with('error', 'Maaf, buku sedang habis!');
        }

        // Buat pesanan
        $order = Order::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'quantity' => 1,
            'total_price' => $book->harga,
            'status' => 'pending',
        ]);

        // Kurangi stok buku
        $book->decrement('stok');
        $book->increment('terjual');

        return redirect()->route('orders.index')
                        ->with('success', 'Pesanan berhasil dibuat! Silakan tunggu konfirmasi.');
    }

    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::with('book')->findOrFail($orderId);

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Jika order dibatalkan, kembalikan stok
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            if ($order->book) {
                $order->book->increment('stok', $order->quantity);
                $order->book->decrement('terjual', $order->quantity);
            }
        }

        // Jika order yang sebelumnya dibatalkan, sekarang diaktifkan kembali
        // maka kurangi stok lagi
        if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            if ($order->book) {
                // Cek stok terlebih dahulu
                if ($order->book->stok >= $order->quantity) {
                    $order->book->decrement('stok', $order->quantity);
                    $order->book->increment('terjual', $order->quantity);
                } else {
                    return redirect()->back()->with('error', 'Stok buku tidak cukup untuk mengaktifkan kembali pesanan ini!');
                }
            }
        }

        $order->update(['status' => $newStatus]);

        // If order just became delivered, dispatch event to award points
        if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
            try {
                event(new OrderDelivered($order));
            } catch (\Throwable $e) {
                // log but don't fail the status update
                \Log::error('Failed dispatching OrderDelivered for order '.$order->id.': '.$e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * USER CANCEL ORDER - Ubah status ke cancelled
     */
    public function cancel(Request $request, $orderId)
    {
        $order = Order::with('book')
                     ->where('user_id', Auth::id())
                     ->findOrFail($orderId);

        // Allow cancellation only when status is 'pending' or 'processing'
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'Pesanan hanya dapat dibatalkan jika berstatus pending atau processing.');
        }

        $oldStatus = $order->status;

        // Kembalikan stok
        if ($order->book) {
            $order->book->increment('stok', $order->quantity);
            $order->book->decrement('terjual', $order->quantity);
        }

        // Update status ke cancelled
        $order->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan dan stok dikembalikan!');
    }

    /**
     * USER CONFIRM DELIVERY + optional rating
     */
    public function confirmDelivery(Request $request, $orderId)
    {
        $order = Order::with('book')
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);

        $request->validate([
            'rating' => 'nullable|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $alreadyConfirmed = $order->confirmed_by_user;
        $alreadyDelivered = $order->status === 'delivered';

        // Langkah 1: konfirmasi diterima (tanpa wajib rating)
        if (!$alreadyConfirmed) {
            if (!in_array($order->status, ['shipped', 'processing', 'delivered'])) {
                return redirect()->back()->with('error', 'Pesanan ini belum dapat dikonfirmasi penerimaannya.');
            }

            $order->status = 'delivered';
            $order->confirmed_by_user = true;
            $order->delivered_at = $order->delivered_at ?? now();
            $order->save();

            // Trigger delivered event once (points awarded via listener) if not already logged
            if (!PointLog::where('order_id', $order->id)->exists()) {
                event(new OrderDelivered($order));
            }

            return redirect()->back()->with('success', 'Pesanan ditandai diterima. Poin Anda telah ditambahkan! Silakan beri rating.');
        }

        // Langkah 2: setelah diterima, user boleh menambahkan rating/ulasan
        if ($request->filled('rating')) {
            $order->user_rating = $request->integer('rating');
        }

        if ($request->filled('review')) {
            $order->user_review = $request->input('review');
        }

        $order->save();

        return redirect()->back()->with('success', 'Rating dan ulasan tersimpan.');
    }

    /**
     * ADMIN/OWNER VERIFY PAYMENT - approve or reject proof of payment
     * Uses DB transactions to ensure atomicity
     */
    public function verifyPayment(Request $request, $orderId)
    {
        $order = Order::with(['items.book', 'user'])->findOrFail($orderId);

        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $action = $request->input('action');

        if ($action === 'approve') {
            DB::beginTransaction();
            try {
                if ($order->payment_status !== 'pending') {
                    return redirect()->back()->with('error', 'Order sudah tidak menunggu verifikasi.');
                }

                // Process all items in this order
                foreach ($order->items as $item) {
                    $book = $item->book;
                    if (!$book) {
                        throw new \Exception('Buku tidak ditemukan untuk order #' . $order->id);
                    }

                    $qty = intval($item->quantity ?? 1);
                    if (($book->stok ?? 0) < $qty) {
                        throw new \Exception('Stok tidak cukup untuk buku "' . $book->judul . '" di order #' . $order->id);
                    }

                    $previous = $book->stok;
                    $book->decrement('stok', $qty);
                    $book->increment('terjual', $qty);

                    StockLog::create([
                        'book_id' => $book->id,
                        'user_id' => $order->user_id,
                        'type' => 'order',
                        'change' => -1 * $qty,
                        'previous_stock' => $previous,
                        'new_stock' => $book->stok,
                        'meta' => json_encode(['order_id' => $order->id, 'order_item_id' => $item->id]),
                    ]);
                }

                $order->payment_status = 'verified';
                // Set shipping status to 'processing' so admin continues fulfilment
                $order->status = 'processing';
                $order->save();

                DB::commit();

                // Send notification to user about verification
                try {
                    $order->user->notify(new \App\Notifications\PaymentApproved($order));
                } catch (\Throwable $e) {
                    \Log::warning('Failed to send PaymentApproved notification for order ' . $order->id . ': ' . $e->getMessage());
                }

                \Log::info('Order #' . $order->id . ' payment approved by admin/owner ' . auth()->id());
                return redirect()->back()->with('success', 'Pembayaran diverifikasi. Status pesanan telah diperbarui menjadi diproses. User telah dinotifikasi.');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Failed to verify order #' . $order->id . ': ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
            }
        }

        // reject
        else {
            try {
                if ($order->payment_status !== 'pending') {
                    return redirect()->back()->with('error', 'Order sudah tidak menunggu verifikasi.');
                }

                $order->payment_status = 'failed';
                // Per permintaan: set order status to delivered/selesai when payment rejected
                $order->status = 'delivered';
                $order->save();

                if ($order->proof_of_payment) {
                    Storage::disk('public')->delete($order->proof_of_payment);
                }

                // Send notification to user
                try {
                    $order->user->notify(new \App\Notifications\PaymentRejected($order));
                } catch (\Throwable $e) {
                    \Log::warning('Failed to send PaymentRejected notification for order ' . $order->id . ': ' . $e->getMessage());
                }

                \Log::info('Order #' . $order->id . ' payment rejected by admin/owner ' . auth()->id());
                return redirect()->back()->with('success', 'Pembayaran ditolak. User telah dinotifikasi.');
            } catch (\Exception $e) {
                \Log::error('Failed to reject order #' . $order->id . ': ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
            }
        }
    }

    /**
     * ADMIN/OWNER GET TRACKING NUMBER - Ambil data order untuk edit nomor resi
     * Validasi bahwa order status adalah 'shipped' atau lebih tinggi
     */
    public function editTrackingNumber(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        // Validasi bahwa order status adalah 'shipped' atau lebih tinggi
        $shippedStatuses = ['shipped', 'delivered', 'received'];
        if (!in_array($order->status, $shippedStatuses)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor resi hanya dapat diedit untuk pesanan dengan status shipped atau lebih tinggi.',
                'current_status' => $order->status
            ], 422);
        }

        // Return JSON response dengan data order
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'tracking_number' => $order->tracking_number,
                'status' => $order->status,
                'user_name' => $order->user->name ?? 'Unknown',
                'total_price' => $order->total_price
            ]
        ]);
    }

    /**
     * ADMIN/OWNER UPDATE TRACKING NUMBER - Set/update nomor resi untuk order yang sudah dikirim
     * Handle both initial input dan update/edit dengan validasi status
     */
    public function updateTrackingNumber(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $request->validate([
            'tracking_number' => 'required|string|min:3|max:50'
        ]);

        // Validasi bahwa tracking_number bisa diubah jika status adalah 'shipped' atau lebih tinggi
        $shippedStatuses = ['shipped', 'delivered', 'received'];
        if (!in_array($order->status, $shippedStatuses)) {
            // Jika status bukan shipped/delivered/received, izinkan hanya pada status tertentu
            // Status processing dapat berubah ke shipped dengan tracking number
            if ($order->status !== 'processing') {
                return redirect()->back()->with('error', 'Nomor resi hanya dapat ditambahkan untuk pesanan dengan status processing atau sudah dikirim.');
            }
        }

        $trackingNumber = $request->input('tracking_number');
        $order->update(['tracking_number' => $trackingNumber]);

        // Return JSON jika request expect JSON (dari modal)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Nomor resi berhasil disimpan!',
                'tracking_number' => $trackingNumber
            ]);
        }

        return redirect()->back()->with('success', 'Nomor resi berhasil disimpan!');
    }

    /**
     * Award points to user for an order
     * Called when payment is verified (immediate reward)
     */
    private function awardPointsForOrder(Order $order)
    {
        try {
            $user = $order->user;
            if (!$user) {
                \Log::warning('Cannot award points: order has no user', ['order_id' => $order->id]);
                return;
            }

            // Avoid double-award for this order
            if (\App\Models\PointLog::where('order_id', $order->id)->exists()) {
                \Log::info('Points already awarded for order, skipping', ['order_id' => $order->id]);
                return;
            }

            $pointsPer = (int) config('rewards.points_per_currency', 10000);
            $minPoints = (int) config('rewards.minimum_points', 0);

            // Calculate points: round(total_price / pointsPer)
            $calculated = (int) round(($order->total_price ?? 0) / max(1, $pointsPer));
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