<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::orderBy('expense_date', 'desc')->paginate(20);
        $categories = Expense::categories();
        
        // Summary
        $totalThisMonth = Expense::whereYear('expense_date', date('Y'))
            ->whereMonth('expense_date', date('m'))
            ->sum('amount');
            
        $totalThisYear = Expense::whereYear('expense_date', date('Y'))
            ->sum('amount');
        
        return view('admin.expenses.index', compact('expenses', 'categories', 'totalThisMonth', 'totalThisYear'));
    }

    public function create()
    {
        $categories = Expense::categories();
        return view('admin.expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            $validated['receipt'] = $path;
        }

        Expense::create($validated);

        return redirect()->route('admin.expenses.index')->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    public function edit(Expense $expense)
    {
        $categories = Expense::categories();
        return view('admin.expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            $receiptPath = $expense->getAttributes()['receipt'] ?? null;
            if ($receiptPath) {
                \Storage::disk('public')->delete($receiptPath);
            }
            $path = $request->file('receipt')->store('receipts', 'public');
            $validated['receipt'] = $path;
        }

        $expense->update($validated);

        return redirect()->route('admin.expenses.index')->with('success', 'Pengeluaran berhasil diupdate');
    }

    public function destroy(Expense $expense)
    {
        // Delete receipt file if exists
        $receiptPath = $expense->getAttributes()['receipt'] ?? null;
        if ($receiptPath) {
            \Storage::disk('public')->delete($receiptPath);
        }
        
        $expense->delete();

        return redirect()->route('admin.expenses.index')->with('success', 'Pengeluaran berhasil dihapus');
    }
}
