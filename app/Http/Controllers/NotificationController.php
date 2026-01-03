<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user
     */
    public function getNotifications(Request $request)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'user') {
            return response()->json(['notifications' => [], 'unreadCount' => 0]);
        }

        $isFull = $request->boolean('full');
        $notifications = [];

        // 1. Get recent order status changes
        $recentOrders = Order::where('user_id', $user->id)
            ->where('updated_at', '>=', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->when(!$isFull, fn($q) => $q->limit(3))
            ->get();

        foreach ($recentOrders as $order) {
            $type = 'order_' . $order->status;
            $title = $this->getOrderStatusTitle($order->status);
            $message = "Order #" . $order->id . " - " . $this->getOrderStatusMessage($order->status);
            
            $notifications[] = [
                'id' => 'order_' . $order->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'timeAgo' => $this->getTimeAgo($order->updated_at),
                'timestamp' => $order->updated_at->timestamp
            ];
        }

        // 2. Get recently added books (books added in the last 7 days)
        $recentBooks = Book::where('created_at', '>=', now()->subDays(7))
            ->where('status', 'Available')
            ->orderBy('created_at', 'desc')
            ->when(!$isFull, fn($q) => $q->limit(3))
            ->get();

        foreach ($recentBooks as $book) {
            $notifications[] = [
                'id' => 'book_' . $book->id,
                'type' => 'book_added',
                'title' => 'Buku Baru Tersedia',
                'message' => "📚 " . $book->judul . " telah ditambahkan ke katalog",
                'timeAgo' => $this->getTimeAgo($book->created_at),
                'timestamp' => $book->created_at->timestamp
            ];
        }

        // 3. Get loan notifications (active loans with return date reminders)
        $activeLoans = Loan::where('user_id', $user->id)
            ->whereIn('status', ['active', 'dipinjam'])
            ->with(['book', 'loanBook'])
            ->get();

        foreach ($activeLoans as $loan) {
            // gunakan return_date bawaan model
            $returnDate = $loan->return_date ? Carbon::parse($loan->return_date) : null;
            if (!$returnDate) {
                continue;
            }

            $titleBook = $loan->book->judul ?? $loan->loanBook->judul ?? 'Buku';
            $isOverdue = method_exists($loan, 'isOverdue') ? $loan->isOverdue() : false;
            $daysUntilReturn = method_exists($loan, 'getDaysUntilReturn') ? $loan->getDaysUntilReturn() : Carbon::now()->diffInDays($returnDate, false);

            // Overdue notification (sudah lewat jatuh tempo)
            if ($isOverdue) {
                $daysPast = abs(Carbon::now()->diffInDays($returnDate, false));
                $notifications[] = [
                    'id' => 'loan_overdue_' . $loan->id,
                    'type' => 'loan_overdue',
                    'title' => '⚠️ Peminjaman Terlambat',
                    'message' => "Buku \"{$titleBook}\" sudah lewat {$daysPast} hari dari tanggal pengembalian. Harap segera kembalikan!",
                    'timeAgo' => $this->getTimeAgo($returnDate),
                    'timestamp' => $returnDate->timestamp
                ];
                continue;
            }

            if ($daysUntilReturn === 0) {
                $notifications[] = [
                    'id' => 'loan_due_today_' . $loan->id,
                    'type' => 'loan_reminder',
                    'title' => '⏰ Harus Dikembalikan Hari Ini',
                    'message' => "Buku \"{$titleBook}\" harus dikembalikan hari ini (" . $returnDate->format('d M Y') . ")",
                    'timeAgo' => 'Hari ini',
                    'timestamp' => $returnDate->timestamp
                ];
            } elseif ($daysUntilReturn === 1) {
                $notifications[] = [
                    'id' => 'loan_reminder_' . $loan->id,
                    'type' => 'loan_reminder',
                    'title' => '🔔 Pengingat Pengembalian',
                    'message' => "Buku \"{$titleBook}\" harus dikembalikan besok (" . $returnDate->format('d M Y') . ")",
                    'timeAgo' => 'Besok',
                    'timestamp' => $returnDate->timestamp
                ];
            } elseif ($daysUntilReturn === 3) {
                $notifications[] = [
                    'id' => 'loan_reminder_3d_' . $loan->id,
                    'type' => 'loan_reminder',
                    'title' => '📅 Pengingat Pengembalian',
                    'message' => "Buku \"{$titleBook}\" harus dikembalikan dalam 3 hari (" . $returnDate->format('d M Y') . ")",
                    'timeAgo' => '3 hari lagi',
                    'timestamp' => $returnDate->timestamp
                ];
            }
        }

        // 4. Get recently returned loans (last 7 days)
        $recentReturns = Loan::where('user_id', $user->id)
            ->whereIn('status', ['returned', 'dikembalikan'])
            ->where('updated_at', '>=', now()->subDays(7))
            ->with(['book', 'loanBook'])
            ->when(!$isFull, fn($q) => $q->limit(3))
            ->get();

        foreach ($recentReturns as $loan) {
            $titleBook = $loan->book->judul ?? $loan->loanBook->judul ?? 'Buku';
            $notifications[] = [
                'id' => 'loan_returned_' . $loan->id,
                'type' => 'loan_returned',
                'title' => '✅ Pengembalian Berhasil',
                'message' => "Terima kasih telah mengembalikan \"{$titleBook}\" tepat waktu",
                'timeAgo' => $this->getTimeAgo($loan->updated_at),
                'timestamp' => $loan->updated_at->timestamp
            ];
        }

        // Sort by timestamp (newest first)
        usort($notifications, function ($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        // Mark notifications as read/unread based on session
        $readNotifications = session('read_notifications', []);
        foreach ($notifications as &$notif) {
            $notif['read_at'] = in_array($notif['id'], $readNotifications) ? time() : null;
        }

        // Take only first 3 for dropdown, otherwise return all
        if (!$isFull) {
            $notifications = array_slice($notifications, 0, 3);
        }

        // Count unread notifications
        $unreadCount = count(array_filter($notifications, function ($notif) {
            return $notif['read_at'] === null;
        }));

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Get order status title
     */
    private function getOrderStatusTitle($status)
    {
        $titles = [
            'pending' => '⏳ Menunggu Pembayaran',
            'processing' => '⚙️ Sedang Diproses',
            'shipped' => '🚚 Sedang Dikirim',
            'delivered' => '✅ Pesanan Selesai',
            'cancelled' => '❌ Pesanan Dibatalkan'
        ];
        
        return $titles[$status] ?? 'Update Pesanan';
    }

    /**
     * Get order status message
     */
    private function getOrderStatusMessage($status)
    {
        $messages = [
            'pending' => 'Pembayaran Anda sedang menunggu verifikasi',
            'processing' => 'Pesanan Anda sedang diproses',
            'shipped' => 'Pesanan Anda sudah dikirim',
            'delivered' => 'Pesanan Anda telah diterima',
            'cancelled' => 'Pesanan Anda telah dibatalkan'
        ];
        
        return $messages[$status] ?? 'Status pesanan berubah';
    }

    /**
     * Get human-readable time ago
     */
    private function getTimeAgo($date)
    {
        if (!$date) return 'Baru saja';
        
        $now = Carbon::now();
        $diff = $now->diffInSeconds($date);

        if ($diff < 60) return 'Baru saja';
        if ($diff < 3600) return $now->diffInMinutes($date) . ' menit lalu';
        if ($diff < 86400) return $now->diffInHours($date) . ' jam lalu';
        if ($diff < 604800) return $now->diffInDays($date) . ' hari lalu';
        
        return $date->format('d M Y');
    }

    /**
     * Mark notifications as read
     */
    public function markAsRead(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Store read notifications in session
        $readNotifications = session('read_notifications', []);
        $notificationIds = $request->input('notification_ids', []);
        
        foreach ($notificationIds as $id) {
            if (!in_array($id, $readNotifications)) {
                $readNotifications[] = $id;
            }
        }
        
        session(['read_notifications' => $readNotifications]);

        return response()->json(['success' => true, 'message' => 'Notifications marked as read']);
    }

    /**
     * View all notifications
     */
    public function index()
    {
        return view('notifications.index');
    }
}
