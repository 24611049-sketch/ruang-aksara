@extends('layouts.app')

@section('title', (Auth::check() && Auth::user()->role === 'owner') ? 'Dashboard Owner - Ruang Aksara' : 'Dashboard Admin - Ruang Aksara')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-tachometer-alt mr-3 text-blue-600"></i>
            @if(Auth::check() && Auth::user()->role === 'owner')
                Dashboard Owner
            @else
                Dashboard Admin
            @endif
        </h1>
        <p class="text-gray-600 mt-2 ml-11">Selamat datang di sistem manajemen Ruang Aksara</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Pendapatan Bulan Ini</h3>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($salesPerformance['month'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-book text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Buku</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_books'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Order</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-users text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total User</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-chart-line mr-2 text-green-500"></i>
                    Trend Pendapatan 6 Bulan Terakhir
                </h2>
            </div>
            <div class="p-6">
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-chart-pie mr-2 text-blue-500"></i>
                    Distribusi Kategori Buku
                </h2>
            </div>
            <div class="p-6">
                <div class="h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Business Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Sales Performance -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-trending-up mr-2 text-purple-500"></i>
                    Performa Penjualan
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-sun text-yellow-500 mr-3"></i>
                            <span class="text-gray-700">Hari Ini</span>
                        </div>
                        <span class="font-bold text-green-600">Rp {{ number_format($salesPerformance['today'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-week text-blue-500 mr-3"></i>
                            <span class="text-gray-700">Minggu Ini</span>
                        </div>
                        <span class="font-bold text-blue-600">Rp {{ number_format($salesPerformance['week'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt text-purple-500 mr-3"></i>
                            <span class="text-gray-700">Bulan Ini</span>
                        </div>
                        <span class="font-bold text-purple-600">Rp {{ number_format($salesPerformance['month'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-users mr-2 text-orange-500"></i>
                    Statistik Pengguna
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">Total User</span>
                        <span class="font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <span class="text-gray-700">User Baru (Bulan Ini)</span>
                        <span class="font-bold text-green-600">{{ $userRegistrations[date('n')] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                        <span class="text-gray-700">Aktivitas Order</span>
                        <span class="font-bold text-blue-600">{{ $stats['total_orders'] > 0 ? round(($stats['total_orders'] / $stats['total_users']) * 100) : 0 }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Low Stock -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-clock mr-2 text-yellow-500"></i>
                    Order Terbaru
                    <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                        {{ $recentOrders->count() }} order
                    </span>
                </h2>
            </div>
            <div class="p-6">
                @forelse($recentOrders as $order)
                <div class="flex items-center justify-between py-3 border-b last:border-b-0">
                    <div>
                        <p class="font-medium text-gray-900">Order #{{ $order->id }}</p>
                        <p class="text-sm text-gray-500">{{ $order->user->name ?? 'User' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                            {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : 
                               ($order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Belum ada order</p>
                @endforelse
            </div>
        </div>

        <!-- Low Stock Books (Penjualan) -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                    Stok Penjualan Menipis
                    <span class="ml-2 bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                        {{ $lowStockBooks->count() }} buku
                    </span>
                </h2>
            </div>
            <div class="p-6">
                @forelse($lowStockBooks as $book)
                <div class="flex items-center justify-between py-3 border-b last:border-b-0">
                    <div>
                        <p class="font-medium text-gray-900">{{ $book->judul ?? $book->title }}</p>
                        <p class="text-sm text-gray-500">{{ $book->penulis ?? $book->author }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">{{ $book->stok ?? $book->stock ?? 0 }} pcs</p>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Stok rendah
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Tidak ada buku dengan stok rendah</p>
                @endforelse
            </div>
        </div>

        <!-- Low Stock Loan Books -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-warning mr-2 text-orange-500"></i>
                    Stok Peminjaman Menipis
                    <span class="ml-2 bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">
                        {{ $lowLoanStockBooks->count() }} buku
                    </span>
                </h2>
            </div>
            <div class="p-6">
                @forelse($lowLoanStockBooks as $book)
                <div class="flex items-center justify-between py-3 border-b last:border-b-0">
                    <div>
                        <p class="font-medium text-gray-900">{{ $book->judul }}</p>
                        <p class="text-sm text-gray-500">{{ $book->penulis }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">{{ $book->loan_stok }} pcs</p>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            Stok rendah
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Tidak ada buku peminjaman dengan stok rendah</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Pendapatan (Juta Rupiah)',
                data: [8.2, 9.5, 10.2, 11.8, 12.1, 12.5],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value + ' Jt';
                        }
                    }
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Fiksi', 'Non-Fiksi', 'Edukasi', 'Bisnis', 'Anak'],
            datasets: [{
                data: [35, 25, 20, 15, 5],
                backgroundColor: [
                    '#10b981',
                    '#3b82f6',
                    '#8b5cf6',
                    '#f59e0b',
                    '#ef4444'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endsection