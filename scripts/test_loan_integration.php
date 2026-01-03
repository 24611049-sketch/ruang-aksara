<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\LoanBook;
use App\Models\Loan;
use App\Models\User;
use App\Models\StockLog;

// Get first loan book and user
$loanBook = LoanBook::find(1); // ID 1: hayo dengan stok 229
$user = User::where('role', 'user')->first();

if (!$loanBook || !$user) {
    echo "Error: Tidak ada loan book atau user\n";
    exit;
}

$stockBefore = $loanBook->loan_stok;
echo "=== TEST PEMINJAMAN ===\n";
echo "Buku: " . $loanBook->judul . " (ID: " . $loanBook->id . ")\n";
echo "Stok Sebelum: " . $stockBefore . "\n";
echo "User: " . $user->name . "\n\n";

try {
    \DB::transaction(function () use ($loanBook, $user) {
        $quantity = 5;
        $available = $loanBook->loan_stok ?? 0;
        
        if ($available < $quantity) {
            throw new \Exception('Stok tidak cukup');
        }
        
        $previous = $available;
        $loanBook->decrement('loan_stok', $quantity);
        
        // Log transaction
        StockLog::create([
            'loan_book_id' => $loanBook->id,
            'user_id' => auth()->id() ?? 1,
            'type' => 'loan',
            'change' => -1 * $quantity,
            'previous_stock' => $previous,
            'new_stock' => $loanBook->loan_stok,
            'meta' => json_encode(['note' => 'test-loan', 'quantity' => $quantity]),
        ]);
        
        // Create loan
        Loan::create([
            'user_id' => $user->id,
            'loan_book_id' => $loanBook->id,
            'quantity' => $quantity,
            'borrowed_date' => now(),
            'return_date' => now()->addDays(7),
            'notes' => 'Test peminjaman dari script',
            'status' => 'active',
            'location' => 'offline-store',
        ]);
    });
    
    // Refresh model
    $loanBook->refresh();
    $stockAfter = $loanBook->loan_stok;
    
    echo "Peminjaman dibuat BERHASIL!\n";
    echo "Stok Sesudah: " . $stockAfter . "\n";
    echo "Stok berkurang: " . ($stockBefore - $stockAfter) . " (harus 5)\n\n";
    
    // Check last loan
    $lastLoan = Loan::where('loan_book_id', $loanBook->id)->latest()->first();
    echo "Loan terakhir: #" . $lastLoan->id . "\n";
    echo "Quantity: " . $lastLoan->quantity . "\n";
    echo "Status: " . $lastLoan->status . "\n\n";
    
    // Check stock log
    $lastLog = StockLog::where('loan_book_id', $loanBook->id)->latest()->first();
    echo "Stock log terakhir:\n";
    echo "Type: " . $lastLog->type . "\n";
    echo "Change: " . $lastLog->change . "\n";
    echo "Previous: " . $lastLog->previous_stock . " → New: " . $lastLog->new_stock . "\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
