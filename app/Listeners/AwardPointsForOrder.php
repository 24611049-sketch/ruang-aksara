<?php

namespace App\Listeners;

use App\Events\OrderDelivered;
use App\Models\PointLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AwardPointsForOrder
{

    /**
     * Handle the event.
     */
    public function handle(OrderDelivered $event): void
    {
        $order = $event->order;
        
        \Log::info('AwardPointsForOrder.handle called', ['order_id' => $order->id]);

        // Ensure we have related user
        $user = $order->user;
        if (! $user) {
            Log::warning('OrderDelivered: order has no user', ['order_id' => $order->id]);
            return;
        }

        // Avoid double-award if any PointLog already exists for this order
        if (PointLog::where('order_id', $order->id)->exists()) {
            Log::info('Points already awarded for order, skipping', ['order_id' => $order->id]);
            return;
        }

        $pointsPer = (int) config('rewards.points_per_currency', 10000);
        $minPoints = (int) config('rewards.minimum_points', 0);

        // Calculate points: floor(total_price / pointsPer)
        $calculated = 0;
        try {
            $calculated = (int) floor(($order->total_price ?? 0) / max(1, $pointsPer));
        } catch (\Throwable $t) {
            Log::error('Error calculating points for order '.$order->id.': '.$t->getMessage());
            return;
        }

        $points = max($minPoints, $calculated);

        if ($points <= 0) {
            // Nothing to award
            return;
        }

        DB::beginTransaction();
        try {
            // increment user's points
            $user->increment('points', $points);

            // log into points_logs table if model exists
            if (class_exists(PointLog::class)) {
                PointLog::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'points' => $points,
                    'type' => 'order_reward',
                    'meta' => json_encode(['total_price' => $order->total_price]),
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed awarding points for order '.$order->id.': '.$e->getMessage());
        }
    }
}
