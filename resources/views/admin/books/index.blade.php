@extends('layouts.app')

@section('title', 'Kelola Buku - Admin')

@section('content')
<div class="w-full py-6" style="margin: 0 !important; padding: 0 1rem 0 0 !important;">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        <i class="fas fa-book mr-2"></i>Kelola Buku
    </h1>

    <!-- Tombol Tambah Buku -->
    <div class="mb-6">
        <a href="{{ route('admin.books.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Tambah Buku Baru
        </a>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.books.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Buku</label>
                    <input type="text" name="search" value="{{ request('search', '') }}" 
                           placeholder="Judul atau penulis..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Kategori --</option>
                        @php
                            $categoryOptions = $kategories ?? [];
                            if (empty($categoryOptions)) {
                                $categoryOptions = $books->pluck('kategori')->unique()->filter()->sort()->values()->all();
                            }
                        @endphp
                        @foreach($categoryOptions as $category)
                            @if(!empty($category))
                                <option value="{{ $category }}" {{ request('kategori') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="name" {{ request('sort', 'name') == 'name' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stok (Terendah)</option>
                        <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stok (Tertinggi)</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga (Terendah)</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga (Tertinggi)</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2 justify-between">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <a href="{{ route('admin.books.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Buku -->
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">Daftar Buku</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 gap-4">
                @forelse($books as $book)
                <div class="bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                    <div class="flex">
                        <!-- Cover Image -->
                        <div class="flex-shrink-0 w-28 h-40 bg-gray-100 flex items-center justify-center">
                            @if($book->image)
                                <img src="{{ asset('storage/book-covers/' . $book->image) }}" alt="{{ $book->judul }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center">
                                    <i class="fas fa-book text-gray-400 text-3xl mb-2"></i>
                                    <p class="text-xs text-gray-500">No Image</p>
                                </div>
                            @endif
                        </div>

                        <!-- Book Info -->
                        <div class="flex-grow p-4">
                            <div class="grid grid-cols-3 gap-4 h-full">
                                <!-- Left: Judul & Penulis -->
                                <div class="col-span-2">
                                    <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $book->judul }}</h3>
                                    <p class="text-sm text-gray-600 mb-3"><strong>Penulis:</strong> {{ $book->penulis }}</p>
                                    <p class="text-sm text-gray-600 mb-1"><strong>Kategori:</strong> {{ $book->kategori ?? '-' }}</p>
                                    <p class="text-sm text-gray-600"><strong>ISBN:</strong> {{ $book->isbn ?? '-' }}</p>
                                </div>

                                <!-- Right: Price & Stock -->
                                <div class="col-span-1">
                                    <div class="mb-3">
                                        <p class="text-xs text-gray-500 mb-1">Harga</p>
                                        <p class="text-xl font-bold text-green-600">Rp {{ number_format($book->harga, 0, ',', '.') }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <p class="text-xs text-gray-500 mb-1">Stok</p>
                                        <div class="flex items-center">
                                            <span class="text-lg font-bold">{{ $book->stok }}</span>
                                            @if($book->stok == 0)
                                                <span class="ml-2 px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded">HABIS</span>
                                            @elseif($book->stok <= 5)
                                                <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded">RENDAH</span>
                                            @else
                                                <span class="ml-2 px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">TERSEDIA</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        <p><strong>Terjual:</strong> {{ $book->terjual ?? 0 }} unit</p>
                                        <p><strong>Status:</strong> {{ $book->status == 'available' ? 'Aktif' : 'Nonaktif' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex-shrink-0 w-24 px-4 py-4 flex flex-col justify-between items-center border-l border-gray-200">
                            <a href="{{ route('admin.books.edit', $book->id) }}" class="text-blue-600 hover:text-blue-800 text-center text-sm font-medium py-2 px-2 rounded hover:bg-blue-50 transition w-full">
                                <i class="fas fa-edit mb-1"></i><br>Edit
                            </a>
                            <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-center text-sm font-medium py-2 px-2 rounded hover:bg-red-50 transition w-full" onclick="return confirm('Hapus buku ini?')">
                                    <i class="fas fa-trash mb-1"></i><br>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                    <p>Belum ada buku</p>
                </div>
                @endforelse
            </div>

            <!-- PAGINATION -->
            @if(method_exists($books, 'links'))
                <!-- Jika $books adalah Paginator -->
                <div class="mt-6 flex justify-between items-center bg-white p-4 rounded-lg shadow-md">
                    <!-- Previous Button -->
                    @if ($books->onFirstPage())
                        <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded cursor-not-allowed flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Previous
                        </span>
                    @else
                        <a href="{{ $books->previousPageUrl() }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Previous
                        </a>
                    @endif

                    <!-- Page Info -->
                    <div class="text-gray-600">
                        <span class="font-semibold">Page {{ $books->currentPage() }}</span>
                        <span class="mx-2">of</span>
                        <span class="font-semibold">{{ $books->lastPage() }}</span>
                        <span class="ml-4 text-sm text-gray-500">
                            (Total {{ $books->total() }} buku)
                        </span>
                    </div>

                    <!-- Next Button -->
                    @if ($books->hasMorePages())
                        <a href="{{ $books->nextPageUrl() }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center">
                            Next
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded cursor-not-allowed flex items-center">
                            Next
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    @endif
                </div>
            @else
                <!-- Jika $books adalah Collection -->
                <div class="mt-6 bg-blue-50 p-4 rounded-lg text-center">
                    <p class="text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Menampilkan semua {{ $books->count() }} buku
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- STATISTIK -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Buku</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalBooks ?? $books->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Stok Rendah</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $lowStockCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Habis Stok</p>
                    <p class="text-2xl font-bold text-red-600">{{ $outOfStockCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-tags text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Kategori</p>
                    <p class="text-2xl font-bold text-green-600">{{ $categoriesCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection