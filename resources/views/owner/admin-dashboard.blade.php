@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('styles')
<style>
/* FORCE BACKGROUND FOR ADMIN DASHBOARD */
body {
    background: 
        linear-gradient(rgba(248, 250, 252, 0.9), rgba(248, 250, 252, 0.9)),
        url('/images/background.jpg') center/cover fixed no-repeat !important;
    background-size: cover !important;
    background-position: center !important;
    background-attachment: fixed !important;
    background-repeat: no-repeat !important;
    min-height: 100vh !important;
}

/* BUAT KONTEN LEBIH JELAS DAN MUDAH DIBACA */
.bg-white {
    background-color: rgba(255, 255, 255, 0.98) !important;
    border: 2px solid rgba(229, 231, 235, 0.9) !important;
    backdrop-filter: blur(15px);
}

.shadow {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

.rounded-lg {
    border-radius: 12px !important;
}

/* HEADER GRADIENT LEBIH MENARIK */
.bg-gradient-to-r {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border: none !important;
}

/* TEXT LEBIH BESAR DAN JELAS */
.text-2xl {
    font-size: 1.5rem !important;
    font-weight: 700 !important;
}

.text-3xl {
    font-size: 2rem !important;
    font-weight: 800 !important;
}

/* Rank badge styles (shared) */
.rank-badge {
    position: absolute;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #fff;
    font-weight: 800;
    box-shadow: 0 6px 18px rgba(0,0,0,0.18);
    z-index: 60;
    text-align: center;
}
.rank-badge .rank-emoji { font-size: 1.1rem; line-height:1; }
.rank-badge .rank-num { display:block; font-size:0.85rem; margin-top:2px; }
.rank-badge.rank-1 { width:56px; height:56px; background: radial-gradient(circle at 30% 30%, #FFD700, #FFA500); border:3px solid #FFD700; }
.rank-badge.rank-2 { width:48px; height:48px; background: linear-gradient(135deg, #E8E8E8, #C0C0C0); border:2px solid #D3D3D3; color:#333; }
.rank-badge.rank-3 { width:48px; height:48px; background: linear-gradient(135deg, #E8A76A, #CD7F32); border:2px solid #D4956E; }
.rank-badge.rank-other { width:40px; height:40px; background: linear-gradient(135deg, #6B7280, #9CA3AF); }

/* CARD STATS LEBIH MENONJOL */
.p-6 {
    padding: 1.5rem !important;
}

/* BORDER UNTUK PEMISAH YANG LEBIH JELAS */
.border-b {
    border-bottom: 2px solid #e5e7eb !important;
}

/* WARNING DAN ALERT LEBIH TERLIHAT */
.bg-yellow-50 {
    background-color: rgba(254, 252, 232, 0.9) !important;
    border: 1px solid #fef08a !important;
}

.text-yellow-600 {
    color: #ca8a04 !important;
    font-weight: 600 !important;
}

.text-red-600 {
    color: #dc2626 !important;
    font-weight: 700 !important;
}

/* BOOK CARD LEBIH MENARIK */
.border {
    border: 2px solid #e5e7eb !important;
}

.hover\:shadow-lg:hover {
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2) !important;
    transform: translateY(-5px) !important;
}
</style>
@endsection

@section('content')
<div class="p-6">
    <!-- Welcome Section - LEBIH BESAR DAN MENARIK -->
    <div class="bg-gradient-to-r from-blue-500 to-green-600 rounded-lg p-8 text-white mb-8">
        <h1 class="text-4xl font-bold mb-3">Admin Dashboard - Ruang Aksara</h1>
        <p class="text-xl text-blue-100">Halo, {{ Auth::user()->name }}! Kelola operasional toko buku.</p>
    </div>

    <!-- Admin Stats - LEBIH BESAR DAN JELAS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-4 bg-blue-100 rounded-lg">
                    <i class="fas fa-shopping-cart text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-lg font-semibold text-gray-600">Order Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $recentOrders->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-4 bg-green-100 rounded-lg">
                    <i class="fas fa-book text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-lg font-semibold text-gray-600">Buku Tersedia</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_books'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-4 bg-purple-100 rounded-lg">
                    <i class="fas fa-users text-purple-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-lg font-semibold text-gray-600">Customer Baru</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $userRegistrations[date('n')] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-4 bg-yellow-100 rounded-lg">
                    <i class="fas fa-star text-yellow-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-lg font-semibold text-gray-600">Pendapatan</p>
                    <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($stats['revenue'] / 1000000, 1) }}Jt</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Tasks - LEBIH JELAS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold text-gray-800">Order Perlu Diproses</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentOrders->whereIn('status', ['pending', 'processing'])->take(3) as $order)
                    <div class="flex justify-between items-center p-4 bg-yellow-50 rounded-lg">
                        <span class="font-semibold text-gray-800">#{{ $order->id }} - {{ $order->user->name }}</span>
                        <span class="text-lg font-bold text-yellow-600">{{ ucfirst($order->status) }}</span>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-6 text-lg">Tidak ada order yang perlu diproses</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold text-gray-800">Stok Menipis</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($lowStockBooks as $book)
                    <div class="flex justify-between items-center p-4 bg-red-50 rounded-lg">
                        <span class="font-semibold text-gray-800">{{ $book->judul }}</span>
                        <span class="text-lg font-bold text-red-600">{{ $book->stok ?? 0 }} left</span>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-6 text-lg">Tidak ada stok yang menipis</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Books - LEBIH MENARIK -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-2xl font-bold text-gray-800">Buku Terpopuler</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($popularBooks as $idx => $book)
                @php 
                    $rank = $idx + 1;
                    $isTop3 = $rank <= 3;
                    $cover = (!empty($book->image) && file_exists(public_path('storage/book-covers/' . $book->image))) ? asset('storage/book-covers/' . $book->image) : asset('images/default-book-cover.svg'); 
                @endphp
                <div class="rounded-lg overflow-hidden hover:shadow-lg transition duration-300 relative" style="background-image: linear-gradient(rgba(255,255,255,0.08), rgba(255,255,255,0.6)), url('{{ $cover }}'); background-size: cover; background-position: center; {{ $isTop3 ? 'border: 2px solid gold;' : '' }}">
                    {{-- Best Seller Badge untuk rank #1 --}}
                    @if($rank == 1)
                    <div class="absolute top-2 right-2 z-10">
                        <span style="background: linear-gradient(135deg, #DC2626, #991B1B); color: white; padding: 0.35rem 0.8rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; gap: 0.3rem; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);">
                            <i class="fas fa-fire"></i>BEST SELLER
                        </span>
                    </div>
                    @endif
                    <div class="p-4 bg-white/80">
                        <div style="display:flex; gap:12px; align-items:center; position:relative;">
                            {{-- Ranking Badge with unified badge classes --}}
                            @php
                                $cls = 'rank-other';
                                if ($rank == 1) $cls = 'rank-1';
                                elseif ($rank == 2) $cls = 'rank-2';
                                elseif ($rank == 3) $cls = 'rank-3';
                            @endphp
                            <div class="rank-badge {{ $cls }}" aria-label="Peringkat {{ $rank }}">
                                @if($rank == 1)
                                    <span class="rank-emoji">👑</span>
                                    <span class="rank-num">{{ $rank }}</span>
                                @elseif($rank == 2)
                                    <span class="rank-emoji">🥈</span>
                                    <span class="rank-num">{{ $rank }}</span>
                                @elseif($rank == 3)
                                    <span class="rank-emoji">🥉</span>
                                    <span class="rank-num">{{ $rank }}</span>
                                @else
                                    <span class="rank-emoji">#</span>
                                    <span class="rank-num">{{ $rank }}</span>
                                @endif
                            </div>
                            <div style="width:80px; height:120px; flex-shrink:0; display:flex; align-items:center; justify-content:center; margin-left: 20px;">
                                <img src="{{ $cover }}" alt="{{ $book->judul }}" style="max-width:100%; max-height:100%; object-fit:contain; border-radius:4px; background: rgba(255,255,255,0.6); padding:4px; box-shadow:0 8px 20px rgba(0,0,0,0.08);">
                            </div>
                            <div style="flex:1; min-width:0;">
                                <h3 class="font-bold text-xl mb-1 text-gray-800 truncate">{{ $book->judul }}</h3>
                                <p class="text-gray-600 text-sm mb-2 truncate">by {{ $book->penulis }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-green-600 font-bold text-lg">Rp {{ number_format($book->harga, 0, ',', '.') }}</span>
                                    <div style="display:flex; gap:8px; align-items:center;">
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded font-semibold">
                                            <i class="fas fa-shopping-bag mr-1"></i>{{ $book->purchase_count ?? 0 }} terjual
                                        </span>
                                        <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded font-semibold">{{ $book->status }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection