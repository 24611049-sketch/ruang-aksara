<?php

namespace App\Http\Controllers;

use App\Models\OperationalCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationalCostController extends Controller
{
    public function index()
    {
        $costs = OperationalCost::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.operational_costs', compact('costs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'related_book_id' => 'nullable|integer|exists:books,id'
        ]);

        $data['created_by'] = Auth::id();
        OperationalCost::create($data);

        return back()->with('success', 'Biaya operasional berhasil ditambahkan');
    }

    public function update(Request $request, OperationalCost $operationalCost)
    {
        $data = $request->validate([
            'item' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $operationalCost->update($data);

        return back()->with('success', 'Biaya operasional berhasil diperbarui');
    }

    public function destroy(OperationalCost $operationalCost)
    {
        $operationalCost->delete();
        return back()->with('success', 'Biaya operasional berhasil dihapus');
    }
}
