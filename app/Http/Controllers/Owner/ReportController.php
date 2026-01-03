<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderItem, Expense, Book, OperationalCost};
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function profitLoss()
    {
        $monthlyData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startDate = $date->copy()->startOfMonth();
            $endDate = $date->copy()->endOfMonth();
            
            // 1. PENDAPATAN (Revenue)
            $revenue = Order::where('status', 'delivered')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_price');
            
            // 2. HPP (Harga Pokok Penjualan / Cost of Goods Sold)
            $hpp = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('books', 'order_items.book_id', '=', 'books.id')
                ->where('orders.status', 'delivered')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->selectRaw('SUM(
                    COALESCE(books.purchase_price, books.harga * 0.6) * order_items.quantity
                ) as total_hpp')
                ->value('total_hpp') ?? 0;
            
            // 3. BIAYA OPERASIONAL (Operating Expenses)
            // Combine manual Expense entries and auto-created OperationalCost entries
            try {
                $expenseSum = Expense::whereBetween('expense_date', [
                    $startDate->toDateString(),
                    $endDate->toDateString()
                ])->sum('amount');
            } catch (\Exception $e) {
                $expenseSum = 0;
            }

            try {
                $operationalSum = OperationalCost::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
            } catch (\Exception $e) {
                $operationalSum = 0;
            }

            $operatingExpenses = ($expenseSum ?? 0) + ($operationalSum ?? 0);
            
            // 4. LABA KOTOR (Gross Profit)
            $grossProfit = $revenue - $hpp;
            
            // 5. LABA BERSIH (Net Profit)
            $netProfit = $grossProfit - $operatingExpenses;
            
            // 6. MARGIN LABA (Profit Margin %)
            $profitMargin = $revenue > 0 ? ($netProfit / $revenue) * 100 : 0;
            
            $monthlyData[] = [
                'month' => $date->translatedFormat('F Y'),
                'month_short' => $date->translatedFormat('M Y'),
                'revenue' => $revenue,
                'hpp' => $hpp,
                'operating_expenses' => $operatingExpenses,
                'gross_profit' => $grossProfit,
                'net_profit' => $netProfit,
                'profit_margin' => $profitMargin
            ];
        }
        
        // Summary untuk bulan ini
        $currentMonth = $monthlyData[count($monthlyData) - 1];
        
        // Total tahun ini
        $yearStart = Carbon::now()->startOfYear();
        $yearEnd = Carbon::now()->endOfYear();
        
        $yearRevenue = Order::where('status', 'delivered')
            ->whereBetween('created_at', [$yearStart, $yearEnd])
            ->sum('total_price');
            
        $yearHpp = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('books', 'order_items.book_id', '=', 'books.id')
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.created_at', [$yearStart, $yearEnd])
            ->selectRaw('SUM(
                COALESCE(books.purchase_price, books.harga * 0.6) * order_items.quantity
            ) as total_hpp')
            ->value('total_hpp') ?? 0;
            
        try {
            $yearExpenseSum = Expense::whereBetween('expense_date', [
                $yearStart->toDateString(),
                $yearEnd->toDateString()
            ])->sum('amount');
        } catch (\Exception $e) {
            $yearExpenseSum = 0;
        }

        try {
            $yearOperationalSum = OperationalCost::whereBetween('created_at', [$yearStart, $yearEnd])->sum('amount');
        } catch (\Exception $e) {
            $yearOperationalSum = 0;
        }

        $yearExpenses = ($yearExpenseSum ?? 0) + ($yearOperationalSum ?? 0);
        
        $yearNetProfit = $yearRevenue - $yearHpp - $yearExpenses;
        
        return view('owner.reports.profit-loss', compact(
            'monthlyData',
            'currentMonth',
            'yearRevenue',
            'yearHpp',
            'yearExpenses',
            'yearNetProfit'
        ));
    }
}
