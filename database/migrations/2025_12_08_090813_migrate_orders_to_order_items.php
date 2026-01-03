<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidate existing orders into order_items structure
     */
    public function up(): void
    {
        // Step 1: Group orders by order_group_id (or create virtual groups)
        $orders = Order::all();
        $groups = $orders->groupBy('order_group_id');

        foreach ($groups as $groupId => $ordersInGroup) {
            // Each group becomes ONE order with multiple items
            $firstOrder = $ordersInGroup->first();
            
            // Calculate total price for all items in group
            $totalPrice = $ordersInGroup->sum('total_price');
            
            // Create/update the master order record
            $masterOrder = Order::find($firstOrder->id);
            $masterOrder->total_price = $totalPrice;
            $masterOrder->save();
            
            // Create order_items for each order in the group
            foreach ($ordersInGroup as $order) {
                DB::table('order_items')->insert([
                    'order_id' => $masterOrder->id,
                    'book_id' => $order->book_id,
                    'quantity' => $order->quantity,
                    'price' => $order->total_price / $order->quantity, // estimate unit price
                    'subtotal' => $order->total_price,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                ]);
            }
            
            // Delete duplicate orders in the group (keep only the master order)
            if ($ordersInGroup->count() > 1) {
                Order::where('order_group_id', $groupId)
                    ->where('id', '!=', $firstOrder->id)
                    ->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete all order_items
        DB::table('order_items')->delete();
        
        // Note: This doesn't restore deleted duplicate orders
        // Restore from backup if needed
    }
};
