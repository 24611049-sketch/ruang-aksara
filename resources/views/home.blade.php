@extends('layouts.app')

@section('title', 'Dashboard - Ruang Aksara')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Statistik cards -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">Total Buku</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalBooks ?? 0 }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">Total Pesanan</h3>
            <p class="text-3xl font-bold text-green-600">{{ $totalOrders ?? 0 }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">Pesanan Aktif</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $activeOrders ?? 0 }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">Total User</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $totalUsers ?? 0 }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Buku Terbaru -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Buku Terbaru</h2>
            <div class="space-y-3">
                @if(isset($recentBooks) && $recentBooks->count())
                    @foreach($recentBooks as $book)
                    <div class="flex items-center justify-between py-2 border-b">
                        <div>
                            <p class="font-medium">{{ $book->judul }}</p>
                            <p class="text-sm text-gray-600">{{ $book->penulis }}</p>
                        </div>
                        <span class="text-green-600 font-semibold">Rp {{ number_format($book->harga, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500">Tidak ada buku terbaru.</p>
                @endif
            </div>
        </div>

        <!-- Pesanan Terbaru -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Pesanan Terbaru</h2>
            <div class="space-y-3">
                @if(isset($recentOrders) && $recentOrders->count())
                    @foreach($recentOrders as $order)
                    <div class="flex justify-between items-center py-2 border-b">
                        <div>
                            <p class="font-medium">Order #{{ $order->id }}</p>
                            <p class="text-sm text-gray-600">{{ $order->book->judul ?? 'Buku tidak tersedia' }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($order->payment_method != 'cash')
                                <span class="px-2 py-1 text-xs rounded {{ ($order->payment_status ?? 'pending') == 'verified' ? 'bg-green-100 text-green-800' : (($order->payment_status ?? 'pending') == 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($order->payment_status ?? 'pending') }}</span>
                            @endif
                            <span class="px-2 py-1 text-xs rounded {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : ($order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500">Tidak ada pesanan terbaru.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
        <div class="flex space-x-4">
            {{-- ✅ PERBAIKAN: ganti route('user.profile') jadi route('profile') --}}
            <a href="{{ route('profile') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Edit Profil
            </a>
            <a href="{{ route('books.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Lihat Buku
            </a>
            <a href="{{ route('orders.index') }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                Lihat Pesanan
            </a>
        </div>
    </div>
</div>
@endsection