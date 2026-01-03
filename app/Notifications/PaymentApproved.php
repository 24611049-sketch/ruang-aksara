<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pembayaran Diverifikasi - Order #' . $this->order->id)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Pembayaran Anda untuk order #' . $this->order->id . ' telah diverifikasi.')
            ->line('Total: Rp ' . number_format($this->order->total_price, 0, ',', '.'))
            ->line('Buku: ' . ($this->order->book->judul ?? 'Buku Tidak Tersedia'))
            ->line('Stok buku telah dikurangi dan pesanan Anda sedang diproses.')
            ->action('Lihat Pesanan', route('orders.index'))
            ->line('Terima kasih telah berbelanja di Ruang Aksara!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'status' => 'verified',
            'total' => $this->order->total_price,
        ];
    }
}
