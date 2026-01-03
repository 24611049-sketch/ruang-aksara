@extends('layouts.app')

@section('title', 'Detail Order - Ruang Aksara')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white rounded-lg shadow">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold mb-1">Detail Order #{{ $order->order_group_id ?: $order->id }}</h1>
            @if($orders->count() > 1)
                <p class="text-gray-600 text-sm">Checkout dengan {{ $orders->count() }} buku</p>
            @endif
        </div>
        <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Books Section -->
        <div class="lg:col-span-2">
            <h2 class="text-lg font-bold mb-4 pb-2 border-b-2 border-gray-200">
                <i class="fas fa-book mr-2 text-blue-600"></i>Buku yang Dipesan
            </h2>
            
                @if($orders->count() == 1)
                <!-- Single Order Display -->
                @php $firstItem = $orders->first(); @endphp
                <div class="flex items-start gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                    @php $cover = ($firstItem->book && file_exists(public_path('storage/book-covers/' . $firstItem->book->image ?? ''))) ? asset('storage/book-covers/' . ($firstItem->book->image ?? '')) : asset('images/default-book-cover.svg'); @endphp
                    <div class="w-28 h-40 rounded overflow-hidden flex items-center justify-center flex-shrink-0" style="background-image: url('{{ $cover }}'); background-size: cover; background-position: center;">
                        {{-- show smaller visible cover centered to ensure full book visible --}}
                        <img src="{{ $cover }}" alt="{{ $firstItem->book?->judul }}" style="max-width:85%; max-height:85%; object-fit:contain; background: rgba(255,255,255,0.6); padding:6px; border-radius:3px;">
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">{{ $firstItem->book?->judul ?? 'Buku tidak tersedia' }}</h3>
                        <p class="text-sm text-gray-600">{{ $firstItem->book?->penulis ?? 'Unknown' }}</p>
                        <p class="mt-2 font-bold text-green-600">Rp {{ number_format($firstItem->total_price, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">Jumlah: {{ $firstItem->quantity }} item</p>
                        
                        @if($order->confirmed_by_user && $firstItem->book)
                            <a href="{{ route('books.show', $firstItem->book->id) }}#reviews" 
                               class="inline-flex items-center gap-1 mt-3 px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded transition">
                                <i class="fas fa-star"></i>
                                Review Buku Ini
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <!-- Multiple Orders Display -->
                <div class="space-y-3 mb-6">
                    @foreach($orders as $item)
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-blue-300 transition">
                            @php $cover = ($item->book && file_exists(public_path('storage/book-covers/' . $item->book->image ?? ''))) ? asset('storage/book-covers/' . ($item->book->image ?? '')) : asset('images/default-book-cover.svg'); @endphp
                            <div class="w-20 h-28 rounded overflow-hidden flex items-center justify-center flex-shrink-0" style="background-image: url('{{ $cover }}'); background-size: cover; background-position: center;">
                                <img src="{{ $cover }}" alt="{{ $item->book?->judul }}" style="max-width:80%; max-height:80%; object-fit:contain; background: rgba(255,255,255,0.6); padding:6px; border-radius:3px;">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-sm leading-tight truncate">{{ $item->book?->judul ?? 'Buku tidak tersedia' }}</h4>
                                <p class="text-xs text-gray-600 truncate">{{ $item->book?->penulis ?? 'Unknown' }}</p>
                                <p class="text-sm font-bold text-green-600 mt-1">Rp {{ number_format($item->total_price, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-600">Qty: {{ $item->quantity }}</p>
                                
                                @if($order->confirmed_by_user && $item->book)
                                    <a href="{{ route('books.show', $item->book->id) }}#reviews" 
                                       class="inline-flex items-center gap-1 mt-2 px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded transition">
                                        <i class="fas fa-star text-xs"></i>
                                        Review
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-3">Ringkasan Pesanan</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-700">Subtotal ({{ $orders->count() }} buku):</span>
                            <span class="font-medium">Rp {{ number_format($orders->sum('total_price'), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Total Jumlah:</span>
                            <span class="font-medium">{{ $orders->sum('quantity') }} item</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Status Pesanan -->
            <div class="mt-6">
                <h3 class="font-semibold mb-3 pb-2 border-b border-gray-200">Status Pesanan</h3>
                <div class="flex flex-wrap gap-2">
                    @if($order->payment_method != 'cash')
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ ($order->payment_status ?? 'pending') == 'verified' ? 'bg-green-100 text-green-800' : (($order->payment_status ?? 'pending') == 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            <i class="fas fa-credit-card mr-1"></i>
                            @if(($order->payment_status ?? 'pending') == 'pending')
                                Menunggu Verifikasi
                            @elseif($order->payment_status == 'verified')
                                Pembayaran Terverifikasi
                            @elseif($order->payment_status == 'failed')
                                Pembayaran Ditolak
                            @endif
                        </span>
                    @endif
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : ($order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status == 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                        @if($order->status == 'pending')
                            <i class="fas fa-hourglass-half mr-1"></i>Menunggu Diproses
                        @elseif($order->status == 'processing')
                            <i class="fas fa-cogs mr-1"></i>Diproses
                        @elseif($order->status == 'shipped')
                            <i class="fas fa-truck mr-1"></i>Sedang Dikirim
                        @elseif($order->status == 'delivered')
                            <i class="fas fa-check-circle mr-1"></i>Selesai
                        @else
                            <i class="fas fa-ban mr-1"></i>{{ ucfirst($order->status) }}
                        @endif
                    </span>
                </div>
            </div>

            <!-- Informasi Pengiriman -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="font-semibold mb-3 pb-2 border-b border-gray-200">Informasi Pengiriman</h3>
                <div class="space-y-2 text-sm">
                    <p><strong>Alamat:</strong> {{ $order->alamat ?? 'Alamat belum diisi' }}</p>
                    <p><strong>Metode:</strong> {{ ucfirst($order->shipping_method ?? 'standard') }}</p>
                    <p><strong>Nomor Resi:</strong> 
                        @if($order->tracking_number)
                            <span class="font-mono font-bold text-green-600">{{ $order->tracking_number }}</span>
                        @else
                            <span class="text-gray-500 italic">Belum diisi admin</span>
                        @endif
                    </p>
                    @if(empty($order->tracking_number) && in_array($order->status, ['shipped', 'processing']))
                        <p class="text-xs text-gray-500 pt-1"><i class="fas fa-info-circle mr-1"></i>Hubungi admin jika nomor resi belum tersedia setelah pesanan dikirim.</p>
                    @endif
                </div>
            </div>

            @if(($order->payment_status ?? 'pending') == 'failed')
            <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="font-semibold text-red-800 mb-1"><i class="fas fa-exclamation-circle mr-1"></i>Pembayaran Ditolak</p>
                <p class="text-sm text-red-700">Pembayaran Anda ditolak oleh admin. Silakan hubungi admin untuk bantuan lebih lanjut.</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Rincian Pembayaran -->
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg mb-4">
                <h4 class="font-semibold mb-3 pb-2 border-b border-gray-200">Rincian Pembayaran</h4>
                <div class="space-y-2 text-sm">
                    <p><strong>Metode:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                    @if($order->payment_method != 'cash' && $order->bank_account)
                        <p><strong>Bank / Rek:</strong> {{ $order->bank_account }}</p>
                    @endif
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <p class="flex justify-between"><strong>Total:</strong> <span class="text-green-600 font-bold">Rp {{ number_format($orders->sum('total_price'), 0, ',', '.') }}</span></p>
                    </div>
                </div>
            </div>

            @if($order->proof_of_payment)
            <div class="mb-4">
                <h4 class="font-semibold mb-2">Bukti Pembayaran</h4>
                <div class="border border-gray-300 rounded overflow-hidden">
                    <img src="{{ asset('storage/' . $order->proof_of_payment) }}" alt="Bukti pembayaran" class="w-full h-auto">
                </div>
            </div>
            @endif

            <!-- Konfirmasi Penerimaan -->
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <h4 class="font-semibold mb-2 pb-2 border-b border-green-200">Konfirmasi Penerimaan</h4>
                @if($order->confirmed_by_user)
                    <p class="text-sm text-green-700 mb-2"><i class="fas fa-check-circle mr-1"></i>Pesanan sudah dikonfirmasi diterima pada {{ $order->delivered_at ? $order->delivered_at->format('d M Y H:i') : '-' }}.</p>
                    @if($order->user_rating)
                        <div class="mt-2 p-2 bg-white rounded">
                            <p class="text-sm text-gray-700"><strong>Rating Anda:</strong> {{ $order->user_rating }}/5 ⭐</p>
                            @if($order->user_review)
                                <p class="text-sm text-gray-600 mt-1"><strong>Ulasan:</strong> "{{ $order->user_review }}"</p>
                            @endif
                        </div>
                    @endif
                @elseif(in_array($order->status, ['shipped', 'processing', 'delivered']))
                    <form method="POST" action="{{ route('orders.confirm', $order->id) }}" class="space-y-2">
                        @csrf
                        @method('PATCH')
                        <p class="text-xs text-gray-700 mb-2">Klik konfirmasi setelah Anda menerima paket.</p>
                        <button type="submit" class="w-full px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-xs font-semibold transition">
                            <i class="fas fa-check mr-1"></i>Tandai Diterima
                        </button>
                    </form>
                @else
                    <p class="text-xs text-gray-600 italic">Pesanan belum dikirim, konfirmasi penerimaan akan muncul setelah status dikirim.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
