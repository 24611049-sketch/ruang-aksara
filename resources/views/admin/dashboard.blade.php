
@extends('layouts.app')

@section('title', (Auth::check() && Auth::user()->role === 'owner') ? 'Dashboard Owner - Ruang Aksara' : 'Dashboard Admin - Ruang Aksara')

@section('content')
<style>
    body {
        background: 
            linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1)),
            url('/images/background.jpg') center/cover fixed no-repeat !important;
        background-size: cover !important;
        background-position: center !important;
        background-attachment: fixed !important;
        min-height: 100vh !important;
        overflow-x: hidden !important;
    }
    
    .admin-header {
        background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%);
    }
    
    .stat-card {
        background-color: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
    
    .section-card {
        background-color: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .action-btn {
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
    
    .admin-container {
        max-width: 100%;
        width: 100%;
        overflow-x: hidden;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .main-content {
        margin-left: 0 !important;
    }
</style>

<div class="min-h-screen py-3 admin-container" style="margin: 0 !important; padding: 0 !important;">
    <div class="w-full" style="margin: 0 !important; padding-right: 1rem; padding-left: 0 !important;">
        <!-- Header -->
        <div class="text-white rounded-lg p-4 mb-4 shadow-lg" style="background: linear-gradient(135deg, rgba(45, 90, 61, 0.9) 0%, rgba(30, 62, 42, 0.9) 100%); backdrop-filter: blur(10px);">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl font-bold flex items-center">
                        <i class="fas fa-tachometer-alt mr-2 text-lg"></i>
                        <span class="truncate">
                        @if(Auth::check() && Auth::user()->role === 'owner')
                            Dashboard Owner
                        @else
                            Dashboard Admin
                        @endif
                        </span>
                    </h1>
                    <p class="text-green-100 mt-1 text-xs">Sistem manajemen Ruang Aksara</p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
            <div class="stat-card rounded-lg shadow-md p-3">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex-shrink-0">
                        <i class="fas fa-book text-white text-lg"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-xs font-medium text-gray-600 uppercase truncate">Buku</h3>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['total_books'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-lg shadow-md p-3">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex-shrink-0">
                        <i class="fas fa-shopping-cart text-white text-lg"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-xs font-medium text-gray-600 uppercase truncate">Order</h3>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-lg shadow-md p-3">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex-shrink-0">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-xs font-medium text-gray-600 uppercase truncate">User</h3>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card rounded-lg shadow-md p-3">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex-shrink-0">
                        <i class="fas fa-hourglass-end text-white text-lg"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-xs font-medium text-gray-600 uppercase truncate">Pending</h3>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['pending_orders'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid - Order Cards Horizontal -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <!-- Recent Orders -->
            <div class="section-card rounded-lg shadow-md overflow-hidden h-80">
                <div class="px-3 py-2 bg-gradient-to-r from-yellow-50 to-yellow-100 border-b border-yellow-200">
                    <h2 class="text-sm font-bold text-gray-800 flex items-center">
                        <i class="fas fa-clock mr-2 text-yellow-600 text-xs"></i>
                        Order Terbaru
                        @if($recentOrders->count() > 0)
                        <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                            {{ $recentOrders->count() }}
                        </span>
                        @endif
                    </h2>
                </div>
                <div class="p-3 h-full flex flex-col">
                    <div class="flex-1 overflow-y-auto">
                        @forelse($recentOrders as $order)
                        <div class="flex items-center justify-between py-2 border-b last:border-b-0 hover:bg-gray-50 -mx-2 px-2 rounded transition">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 text-sm">Order #{{ $order->id }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ $order->user->name ?? 'User' }}</p>
                            </div>
                            <div class="text-right ml-2">
                                <p class="font-bold text-green-600 text-xs">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                <span class="inline-block text-xs px-1.5 py-0.5 rounded-full mt-0.5 
                                    {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-6">
                            <i class="fas fa-inbox text-gray-300 text-2xl mb-1"></i>
                            <p class="text-gray-500 text-sm">Belum ada order</p>
                        </div>
                        @endforelse
                    </div>
                    
                    <div class="pt-2 border-t text-center">
                        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            Lihat semua →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Low Stock Books (Sales) -->
            <div class="section-card rounded-lg shadow-md overflow-hidden h-80">
                <div class="px-3 py-2 bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200">
                    <h2 class="text-sm font-bold text-gray-800 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2 text-red-600 text-xs"></i>
                        Stok Penjualan
                        @if($lowStockBooks->count() > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                            {{ $lowStockBooks->count() }}
                        </span>
                        @endif
                    </h2>
                </div>
                <div class="p-3 h-full flex flex-col">
                    <div class="flex-1 overflow-y-auto">
                        @forelse($lowStockBooks as $book)
                        <a href="{{ route('admin.books.edit', $book) }}" class="flex items-center justify-between py-2 border-b last:border-b-0 hover:bg-gray-50 -mx-2 px-2 rounded transition">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate text-sm">{{ $book->judul }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ $book->penulis }}</p>
                            </div>
                            <div class="text-right ml-2">
                                <p class="font-bold text-gray-900 text-sm">{{ $book->stok ?? 0 }}</p>
                                @if(($book->stok ?? 0) <= 5)
                                <span class="text-xs px-1.5 py-0.5 rounded-full bg-red-100 text-red-800 mt-0.5 block font-semibold">
                                    Rendah
                                </span>
                                @endif
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-6">
                            <i class="fas fa-check-circle text-green-300 text-2xl mb-1"></i>
                            <p class="text-gray-500 text-sm">Semua stok aman</p>
                        </div>
                        @endforelse
                    </div>
                    
                    <div class="pt-2 border-t text-center">
                        <a href="{{ route('admin.books.index') }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            Kelola stok →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Low Stock Loan Books -->
            <div class="section-card rounded-lg shadow-md overflow-hidden h-80">
                <div class="px-3 py-2 bg-gradient-to-r from-orange-50 to-orange-100 border-b border-orange-200">
                    <h2 class="text-sm font-bold text-gray-800 flex items-center">
                        <i class="fas fa-book-reader mr-2 text-orange-600 text-xs"></i>
                        Stok Peminjaman
                        @if($lowLoanStockBooks->count() > 0)
                        <span class="ml-auto bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                            {{ $lowLoanStockBooks->count() }}
                        </span>
                        @endif
                    </h2>
                </div>
                <div class="p-3 h-full flex flex-col">
                    <div class="flex-1 overflow-y-auto">
                        @forelse($lowLoanStockBooks as $book)
                        <a href="{{ route('admin.loan-stock.index') }}" class="flex items-center justify-between py-2 border-b last:border-b-0 hover:bg-gray-50 -mx-2 px-2 rounded transition">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate text-sm">{{ $book->judul }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ $book->penulis }}</p>
                            </div>
                            <div class="text-right ml-2">
                                <p class="font-bold text-gray-900 text-sm">{{ $book->loan_stok }}</p>
                                @if($book->loan_stok <= 5)
                                <span class="text-xs px-1.5 py-0.5 rounded-full bg-orange-100 text-orange-800 mt-0.5 block font-semibold">
                                    Rendah
                                </span>
                                @endif
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-6">
                            <i class="fas fa-check-circle text-green-300 text-2xl mb-1"></i>
                            <p class="text-gray-500 text-sm">Semua stok aman</p>
                        </div>
                        @endforelse
                    </div>
                    
                    <div class="pt-2 border-t text-center">
                        <a href="{{ route('admin.loan-stock.index') }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            Kelola stok →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section-card rounded-lg shadow-md overflow-hidden">
            <div class="px-3 py-2 bg-gradient-to-r from-gray-50 to-gray-100 border-b">
                <h2 class="text-sm font-bold text-gray-800 flex items-center">
                    <i class="fas fa-bolt mr-2 text-gray-600 text-xs"></i>
                    Aksi Cepat
                </h2>
            </div>
            <div class="p-3">
                <div class="grid grid-cols-4 lg:grid-cols-5 gap-2">
                    <a href="{{ route('admin.books.create') }}" 
                       class="action-btn bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white p-4 rounded-lg text-center shadow-md">
                        <i class="fas fa-plus text-2xl mb-2 block"></i>
                        <p class="font-semibold text-sm">Tambah Buku</p>
                    </a>
                    
                    <a href="{{ route('admin.books.index') }}" 
                       class="action-btn bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-4 rounded-lg text-center shadow-md">
                        <i class="fas fa-book text-2xl mb-2 block"></i>
                        <p class="font-semibold text-sm">Kelola Buku</p>
                    </a>
                    
                    <a href="{{ route('admin.orders.index') }}" 
                       class="action-btn bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white p-4 rounded-lg text-center shadow-md">
                        <i class="fas fa-receipt text-2xl mb-2 block"></i>
                        <p class="font-semibold text-sm">Kelola Order</p>
                    </a>
                    
                    <a href="{{ route('admin.settings.index') }}" 
                       class="action-btn bg-gradient-to-br from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white p-4 rounded-lg text-center shadow-md">
                        <i class="fas fa-cog text-2xl mb-2 block"></i>
                        <p class="font-semibold text-sm">Pengaturan</p>
                    </a>
                    @if(Auth::check() && Auth::user()->role === 'owner')
                    <a href="{{ route('admin.operational-costs') }}"
                       class="action-btn bg-gradient-to-br from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white p-4 rounded-lg text-center shadow-md">
                        <i class="fas fa-coins text-2xl mb-2 block"></i>
                        <p class="font-semibold text-sm">Biaya Operasional</p>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection