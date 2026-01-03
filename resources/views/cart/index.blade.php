@extends('layouts.guest')

@section('content')
    <style>
        /* Main container dengan equal height */
        .cart-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            align-items: stretch;
        }

        .cart-items-wrapper {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .cart-items-card {
            display: flex;
            flex-direction: column;
            max-height: 520px;
            min-height: 520px; /* samakan tinggi dengan panel ringkasan */
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            box-sizing: border-box;
        }

        .cart-items-header {
            flex-shrink: 0;
            background: linear-gradient(to right, #16a34a, #15803d);
            color: white;
            padding: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .cart-items-scroll {
            flex: 1;
            overflow-y: auto;
            padding-right: 0.5rem;
            min-height: 0;
        }

        .cart-items-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .cart-items-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .cart-items-scroll::-webkit-scrollbar-thumb {
            background: #16a34a;
            border-radius: 10px;
        }

        .cart-items-scroll::-webkit-scrollbar-thumb:hover {
            background: #15803d;
        }

        .cart-summary {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .cart-summary-content {
            max-height: 520px;
            min-height: 520px; /* samakan tinggi dengan daftar item */
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
        }

        @media (max-width: 1024px) {
            .cart-container {
                grid-template-columns: 1fr;
            }

            .cart-items-card {
                max-height: 500px;
                min-height: auto;
            }

            .cart-summary {
                position: static;
                top: auto;
            }

            .cart-summary-content {
                min-height: auto;
                max-height: none;
            }
        }

        @media (max-width: 768px) {
            .cart-items-card {
                max-height: 400px;
                min-height: auto;
            }
        }

        /* Fade out animation untuk notifikasi */
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        .alert-success, .alert-error {
            animation: fadeOut 0.5s ease-in-out 1.5s forwards;
        }

        .cart-item-card {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1.5rem;
            transition: background-color 0.2s ease;
        }

        .cart-item-card:hover {
            background-color: #f9fafb;
        }

        .cart-item-card:last-child {
            border-bottom: none;
        }

        /* Ensure summary content grows properly */
        .cart-summary-content > div {
            flex-shrink: 0;
        }

        /* Make button area flexible */
        .cart-summary-content > a,
        .cart-summary-content > form {
            flex-shrink: 0;
        }
        .cart-summary-content {
            padding: 1.25rem;
            padding-bottom: 4rem; /* beri ruang di bawah agar elemen tidak melewati panel */
        }
        /* Typography improvements for better contrast */
        .cart-summary-content h3 {
            color: #111827 !important;
            font-weight: 700 !important;
        }

        .cart-summary-content .space-y-3 {
            color: #374151;
        }

        /* naikkan sedikit posisi info box supaya tidak melewati panel/footer */
        .cart-summary-content .info-box {
            margin-top: 0.75rem !important;
            margin-bottom: 2.5rem !important;
            padding-bottom: 1rem !important;
        }

        /* safe area on mobile to avoid touching bottom edge */
        @@supports(padding: env(safe-area-inset-bottom)) {
            .cart-summary-content { padding-bottom: calc(4rem + env(safe-area-inset-bottom)); }
        }

        .cart-summary-content .space-y-3 > div span {
            color: #374151;
        }

        .cart-summary-content .space-y-3 > div .font-semibold {
            color: #111827 !important;
            font-weight: 700 !important;
        }

        .cart-summary-content .mb-6:not(:has(.border-t)) > div span {
            color: #4b5563;
            font-weight: 500;
        }

        .cart-summary-content .mb-6:not(:has(.border-t)) > div span:last-child {
            color: #111827 !important;
            font-weight: 700 !important;
        }

        .cart-summary-content .border-t > span:first-child {
            color: #111827 !important;
            font-weight: 800 !important;
        }

        .cart-summary-content .border-t > span:last-child {
            color: #16a34a !important;
            font-weight: 800 !important;
        }

        /* Cart item text improvements */
        .cart-item-card h3 {
            color: #111827 !important;
            font-weight: 700 !important;
        }

        .cart-item-card > .flex > div:last-child > div h3 {
            color: #111827 !important;
        }

        .cart-item-card p.text-xs {
            color: #4b5563 !important;
            font-weight: 500;
        }

        .cart-item-card .text-xs.text-gray-600 {
            color: #4b5563 !important;
            font-weight: 500;
        }

        .cart-item-card span.text-xs {
            color: #4b5563 !important;
        }

        .cart-item-card span.font-semibold {
            color: #111827 !important;
        }

        .cart-item-card .text-green-600 {
            color: #16a34a !important;
            font-weight: 700 !important;
        }

        .cart-item-card .text-sm {
            color: #111827;
        }

        /* Continue shopping link */
        .cart-items-wrapper > div a {
            color: #16a34a !important;
            font-weight: 600;
        }

        .cart-items-wrapper > div a:hover {
            color: #15803d !important;
        }

        /* Info box text */
        .bg-blue-50 p {
            color: #1e40af !important;
            font-weight: 500;
        }

        /* Header improvements */
        .container > h1 {
            color: #111827 !important;
            font-weight: 800 !important;
        }

        .container > p {
            color: #4b5563 !important;
            font-weight: 500;
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Keranjang Belanja</h1>
            <p class="text-gray-600">Periksa dan sesuaikan pesanan Anda</p>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-700 bg-red-100 p-4 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 text-sm text-green-700 bg-green-100 p-4 rounded-lg alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 text-sm text-red-700 bg-red-100 p-4 rounded-lg alert-error">
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Content -->
        @if (count($cart) > 0)
            <div class="cart-container">
                <!-- Cart Items -->
                <div class="cart-items-wrapper">
                    <div class="cart-items-card bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="cart-items-header">
                            <h2 class="text-xl font-semibold">{{ count($cart) }} Item dalam Keranjang</h2>
                        </div>

                        <div class="cart-items-scroll">
                            @foreach ($books as $book)
                                @if (isset($cart[$book->id]))
                                    <div class="cart-item-card">
                                        <div class="flex gap-4">
                                            <!-- Book Image -->
                                            <div class="flex-shrink-0">
                                                <div class="w-20 h-28 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg flex items-center justify-center overflow-hidden">
                                                    @if(!empty($book->image) && file_exists(public_path('storage/book-covers/' . $book->image)))
                                                        <img src="{{ asset('storage/book-covers/' . $book->image) }}" alt="{{ $book->judul }}" class="w-full h-full object-cover">
                                                    @else
                                                        <img src="{{ asset('images/default-book-cover.svg') }}" alt="{{ $book->judul }}" class="w-full h-full object-cover">
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Book Details -->
                                            <div class="flex-grow min-w-0">
                                                <div class="flex justify-between items-start gap-2 mb-2">
                                                    <div class="min-w-0">
                                                        <h3 class="text-base font-semibold text-gray-800 truncate">{{ $book->judul }}</h3>
                                                        <p class="text-xs text-gray-600">{{ $book->penulis }}</p>
                                                    </div>
                                                    <form action="{{ route('cart.remove', $book->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-800 transition border-0 bg-transparent cursor-pointer flex-shrink-0">
                                                            <i class="fas fa-trash-alt text-sm"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- Quantity Controls -->
                                                <div class="flex items-center gap-2 mb-2">
                                                    <div class="flex items-center border border-gray-300 rounded">
                                                        <form action="{{ route('cart.decrease', $book->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="px-2 py-1 text-gray-600 hover:bg-gray-100 transition border-0 bg-transparent cursor-pointer text-sm">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </form>
                                                        <input type="number" 
                                                               value="{{ $cart[$book->id] }}" 
                                                               readonly
                                                               class="w-12 text-center border-0 focus:ring-0 text-sm">
                                                        <form action="{{ route('cart.increase', $book->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="px-2 py-1 text-gray-600 hover:bg-gray-100 transition border-0 bg-transparent cursor-pointer text-sm"
                                                                    @if ($cart[$book->id] >= $book->stok) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <span class="text-xs text-gray-600">Stok: <span class="font-semibold">{{ $book->stok }}</span></span>
                                                </div>

                                                <!-- Price Info -->
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-600">Rp {{ number_format($book->harga, 0, ',', '.') }}</span>
                                                    <span class="font-bold text-green-600 text-sm">Rp {{ number_format($book->harga * $cart[$book->id], 0, ',', '.') }}</span>
                                                </div>

                                                <!-- Stock Warning -->
                                                @if ($book->stok < 5)
                                                    <div class="mt-1 text-xs text-orange-600 bg-orange-50 p-1 rounded">
                                                        <i class="fas fa-exclamation-triangle mr-0.5"></i>
                                                        Stok terbatas! {{ $book->stok }} tersisa.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Continue Shopping Button -->
                    <div class="mt-4 px-5">
                        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 transition text-sm">
                            <i class="fas fa-arrow-left"></i>
                            Lanjutkan Belanja
                        </a>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="cart-summary">
                    <div class="cart-summary-content">
                        <h3 class="text-xl font-semibold text-gray-800 mb-6">Ringkasan Pesanan</h3>

                        <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
                            <div class="flex justify-between text-gray-600">
                                <span>Jumlah Item:</span>
                                <span class="font-semibold">{{ count($cart) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Total Kuantitas:</span>
                                <span class="font-semibold">{{ array_sum($cart) }}</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Ongkir (Estimasi):</span>
                                <span class="text-gray-800">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-green-600 font-bold text-lg pt-3 border-t border-gray-200">
                                <span>Total:</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <a href="{{ route('cart.checkoutForm') }}" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-3 rounded-lg font-semibold hover:from-green-700 hover:to-green-800 transition mb-3 block text-center">
                            <i class="fas fa-credit-card mr-2"></i>Lanjutkan ke Checkout
                        </a>

                        <!-- Clear Cart Button -->
                        <form action="{{ route('cart.clear') }}" method="POST" style="display:inline-block; width:100%;" onsubmit="return confirm('Yakin ingin mengosongkan keranjang?')">
                            @csrf
                            <button type="submit" class="w-full bg-red-100 text-red-600 py-2 rounded-lg font-semibold hover:bg-red-200 transition border-0 cursor-pointer">
                                <i class="fas fa-trash mr-2"></i>Kosongkan Keranjang
                            </button>
                        </form>

                        <!-- Info Box -->
                        <div class="mt-4 bg-blue-50 p-4 rounded-lg border border-blue-200 info-box">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                Pesanan Anda akan diproses setelah checkout. Kami akan mengirimkan konfirmasi via email.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <div class="mb-6">
                    <i class="fas fa-shopping-cart text-6xl text-gray-300"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">Keranjang Anda Kosong</h2>
                <p class="text-gray-600 mb-8">Mulai berbelanja dan tambahkan buku favorit Anda ke keranjang.</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-3 rounded-lg font-semibold hover:from-green-700 hover:to-green-800 transition">
                        <i class="fas fa-book-open"></i>
                        Jelajahi Katalog
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Auto-scroll to the last item in cart when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const cartItemsScroll = document.querySelector('.cart-items-scroll');
            if (cartItemsScroll) {
                setTimeout(function() {
                    cartItemsScroll.scrollTop = cartItemsScroll.scrollHeight;
                }, 100);
            }

            // Auto-hide notifications setelah 2 detik
            const alerts = document.querySelectorAll('.alert-success, .alert-error');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 2000);
            });
        });

        // Scroll ketika ada perubahan item
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                setTimeout(function() {
                    const cartItemsScroll = document.querySelector('.cart-items-scroll');
                    if (cartItemsScroll) {
                        cartItemsScroll.scrollTop = cartItemsScroll.scrollHeight;
                    }
                }, 300);
            });
        });
    </script>
@endsection

