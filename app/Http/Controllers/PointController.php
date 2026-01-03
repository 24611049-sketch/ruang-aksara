<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PointLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PointController extends Controller
{
    /**
     * Show points history untuk user
     */
    public function history()
    {
        $user = auth()->user();
        $logs = PointLog::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        /** @var \Illuminate\Pagination\LengthAwarePaginator $logs */
        return view('points.history', compact('logs'));
    }

    /**
     * ADMIN - View all points logs
     */
    public function adminIndex(Request $request)
    {
        $query = PointLog::with(['user'])
                         ->orderBy('created_at', 'desc');

        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $logs = $query->paginate(50);
        $users = User::orderBy('name')->get();

        /** @var \Illuminate\Pagination\LengthAwarePaginator $logs */
        return view('admin.points.index', compact('logs', 'users'));
    }

    /**
     * ADMIN - Award points manually to a user
     */
    public function awardPoints(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|min:1|max:10000',
            'reason' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $points = $validated['points'];

        DB::beginTransaction();
        try {
            // Add points to user
            $user->increment('points', $points);

            // Log the points transaction
            PointLog::create([
                'user_id' => $user->id,
                'order_id' => null,
                'points' => $points,
                'type' => 'manual_award',
                'meta' => json_encode(['reason' => $validated['reason']]),
            ]);

            DB::commit();

            return redirect()->back()->with('success', "Berhasil memberikan {$points} poin ke {$user->name}!");
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed awarding points: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memberikan poin: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN - Deduct points from user
     */
    public function deductPoints(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|min:1|max:10000',
            'reason' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $points = $validated['points'];

        // Check if user has enough points
        if ($user->points < $points) {
            return redirect()->back()->with('error', 'Poin user tidak mencukupi untuk dikurangi!');
        }

        DB::beginTransaction();
        try {
            // Deduct points from user
            $user->decrement('points', $points);

            // Log the points transaction
            PointLog::create([
                'user_id' => $user->id,
                'order_id' => null,
                'points' => -1 * $points,
                'type' => 'manual_deduct',
                'meta' => json_encode(['reason' => $validated['reason']]),
            ]);

            DB::commit();

            return redirect()->back()->with('success', "Berhasil mengurangi {$points} poin dari {$user->name}!");
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed deducting points: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengurangi poin: ' . $e->getMessage());
        }
    }

    /**
     * ADMIN - Recalculate points from delivered orders
     */
    public function recalculatePoints(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
        ]);

        // If user_id provided, only recalculate for that user
        // Otherwise recalculate for all users
        $userIds = $validated['user_id'] 
            ? [$validated['user_id']]
            : User::pluck('id')->toArray();

        $pointsPerCurrency = (int) config('rewards.points_per_currency', 10000);
        $minPoints = (int) config('rewards.minimum_points', 0);
        $recalculated = 0;

        DB::beginTransaction();
        try {
            foreach ($userIds as $userId) {
                $user = User::find($userId);
                if (!$user) continue;

                // Get delivered orders
                $deliveredOrders = \App\Models\Order::where('user_id', $userId)
                                                    ->where('status', 'delivered')
                                                    ->get();

                foreach ($deliveredOrders as $order) {
                    // Check if points already logged for this order
                    $existingLog = PointLog::where('order_id', $order->id)
                                          ->where('type', 'order_reward')
                                          ->first();

                    if (!$existingLog) {
                        // Calculate points
                        $points = max($minPoints, (int) floor(($order->total_price ?? 0) / max(1, $pointsPerCurrency)));

                        if ($points > 0) {
                            // Add points
                            $user->increment('points', $points);

                            // Log it
                            PointLog::create([
                                'user_id' => $userId,
                                'order_id' => $order->id,
                                'points' => $points,
                                'type' => 'order_reward',
                                'meta' => json_encode(['total_price' => $order->total_price, 'recalculated' => true]),
                            ]);

                            $recalculated++;
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', "Berhasil memperhitungkan poin untuk {$recalculated} order!");
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed recalculating points: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperhitungkan poin: ' . $e->getMessage());
        }
    }
}
