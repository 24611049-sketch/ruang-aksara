@extends('layouts.app')

@section('title', 'Stok Menipis - Ruang Aksara')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>Stok Menipis
        </h1>
        <p class="text-gray-600 mt-2">Pantau buku dengan stok menipis (Penjualan & Peminjaman)</p>
    </div>

    <!-- SECTION 1: STOK PENJUALAN MENIPIS -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-shopping-cart mr-2 text-blue-600"></i>Stok Penjualan Menipis
        </h2>
        
        <!-- Stats for Sales Stock -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Stok Rendah</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $lowStockBooks->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-box text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Stok Kritis</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $criticalStockBooks->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total Buku</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalBooks }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Books Table -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">Buku Penjualan dengan Stok Menipis</h3>
            </div>
            <div class="p-6">
                @if($lowStockBooks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($lowStockBooks as $book)
                            <tr class="{{ $book->stok <= 2 ? 'bg-red-50' : 'bg-yellow-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $book->judul }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $book->penulis }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $book->kategori }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $book->stok }} pcs</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($book->stok <= 2)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Kritis
                                    </span>
                                    @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Rendah
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="showUpdateStockModal({{ $book->id }}, '{{ $book->judul }}', {{ $book->stok }})" 
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i> Update
                                    </button>
                                    <a href="{{ route('admin.books.edit', $book->id) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-4xl text-green-300 mb-4"></i>
                    <p class="text-gray-500">Tidak ada buku penjualan dengan stok rendah.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- SECTION 2: STOK PEMINJAMAN MENIPIS -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-book-open mr-2 text-green-600"></i>Stok Peminjaman Menipis
        </h2>

        <!-- Stats for Loan Stock -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Stok Rendah</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $lowLoanStockBooks->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-box text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Stok Kritis</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $criticalLoanStockBooks->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total Buku</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalLoanBooks }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loan Stock Books Table -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">Buku Peminjaman dengan Stok Menipis</h3>
            </div>
            <div class="p-6">
                @if($lowLoanStockBooks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($lowLoanStockBooks as $book)
                            <tr class="{{ $book->loan_stok <= 2 ? 'bg-red-50' : 'bg-yellow-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $book->judul }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $book->penulis }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $book->kategori }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $book->loan_stok ?? 0 }} pcs</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(($book->loan_stok ?? 0) <= 2)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Kritis
                                    </span>
                                    @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Rendah
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="showUpdateLoanStockModal({{ $book->id }}, '{{ $book->judul }}', {{ $book->loan_stok ?? 0 }})" 
                                            class="text-green-600 hover:text-green-900 mr-3">
                                        <i class="fas fa-edit"></i> Update
                                    </button>
                                    <a href="{{ route('admin.loan-stock.index') }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-4xl text-green-300 mb-4"></i>
                    <p class="text-gray-500">Tidak ada buku peminjaman dengan stok rendah.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Back to Dashboard -->
    <div class="mt-6 text-center">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
        </a>
    </div>
</div>

<!-- Update Stock Modal (for Sales) -->
<div id="updateStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Stok Penjualan</h3>
            <form id="updateStockForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Judul Buku</label>
                    <p id="bookTitle" class="mt-1 text-sm text-gray-900 font-semibold"></p>
                </div>
                <div class="mb-4">
                    <label for="stock" class="block text-sm font-medium text-gray-700">Stok Saat Ini</label>
                    <p id="currentStock" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div class="mb-4">
                    <label for="new_stock" class="block text-sm font-medium text-gray-700">Stok Baru</label>
                    <input type="number" name="new_stock" id="new_stock" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           min="0" required>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Loan Stock Modal -->
<div id="updateLoanStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Stok Peminjaman</h3>
            <form id="updateLoanStockForm" method="POST">
                @csrf
                @method('POST')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Judul Buku</label>
                    <p id="loanBookTitle" class="mt-1 text-sm text-gray-900 font-semibold"></p>
                </div>
                <div class="mb-4">
                    <label for="stock" class="block text-sm font-medium text-gray-700">Stok Peminjaman Saat Ini</label>
                    <p id="currentLoanStock" class="mt-1 text-sm text-gray-900"></p>
                </div>
                <div class="mb-4">
                    <label for="loan_stok" class="block text-sm font-medium text-gray-700">Stok Peminjaman Baru</label>
                    <input type="number" name="loan_stok" id="loan_stok" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                           min="0" required>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeLoanModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Update Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showUpdateStockModal(bookId, bookTitle, currentStock) {
    document.getElementById('bookTitle').textContent = bookTitle;
    document.getElementById('currentStock').textContent = currentStock + ' pcs';
    document.getElementById('new_stock').value = currentStock;
    
    const form = document.getElementById('updateStockForm');
    form.action = `/admin/books/${bookId}/stock`;
    
    document.getElementById('updateStockModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('updateStockModal').classList.add('hidden');
}

function showUpdateLoanStockModal(bookId, bookTitle, currentLoanStock) {
    document.getElementById('loanBookTitle').textContent = bookTitle;
    document.getElementById('currentLoanStock').textContent = currentLoanStock + ' pcs';
    document.getElementById('loan_stok').value = currentLoanStock;
    
    const form = document.getElementById('updateLoanStockForm');
    form.action = `/admin/loan-stock/${bookId}`;
    
    document.getElementById('updateLoanStockModal').classList.remove('hidden');
}

function closeLoanModal() {
    document.getElementById('updateLoanStockModal').classList.add('hidden');
}

document.getElementById('updateStockModal').addEventListener('click', function(e) {
    if (e.target.id === 'updateStockModal') {
        closeModal();
    }
});

document.getElementById('updateLoanStockModal').addEventListener('click', function(e) {
    if (e.target.id === 'updateLoanStockModal') {
        closeLoanModal();
    }
});
</script>
@endsection