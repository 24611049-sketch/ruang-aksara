@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-book-open text-emerald-700"></i> Katalog Buku
        </h1>
        <p class="text-gray-600 mt-2">Jelajahi koleksi buku terbaik kami untuk memperkaya wawasan dan pengetahuan Anda</p>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="fas fa-search text-emerald-700"></i>
            <h2 class="text-xl font-semibold text-gray-800">Temukan Buku Favoritmu</h2>
        </div>
        <form action="{{ route('books.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" 
                   name="search" 
                   placeholder="Cari judul, penulis, atau deskripsi..." 
                   value="{{ request('search') }}" 
                   class="col-span-1 md:col-span-2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            
            <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">Semua Kategori</option>
                @foreach($kategories as $kategori)
                    <option value="{{ $kategori }}" {{ request('category') == $kategori ? 'selected' : '' }}>
                        {{ $kategori }}
                    </option>
                @endforeach
            </select>
            
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-emerald-700 hover:bg-emerald-800 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Cari
                </button>
                @if(request('search') || request('category'))
                    <a href="{{ route('books.index') }}" class="px-4 py-2 border-2 border-gray-300 hover:border-emerald-500 text-gray-700 hover:text-emerald-700 rounded-lg font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Books Grid -->
    @if($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($books as $book)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                <!-- Book Cover -->
                <div class="aspect-[3/4] bg-gray-200 relative">
                    @php
                        $coverUrl = (!empty($book->image) && file_exists(public_path('storage/book-covers/' . $book->image))) 
                            ? asset('storage/book-covers/' . $book->image) 
                            : asset('images/default-book-cover.svg');
                    @endphp
                    <img src="{{ $coverUrl }}" alt="{{ $book->judul }}" class="w-full h-full object-cover">
                </div>

                <!-- Book Info -->
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 text-lg mb-1 line-clamp-2">{{ $book->judul }}</h3>
                    <p class="text-sm text-gray-600 mb-2">oleh {{ $book->penulis }}</p>

                    <!-- Rating -->
                    <div class="flex items-center gap-2 mb-2">
                        @if($book->total_reviews > 0)
                            <div class="flex items-center text-yellow-500">
                                @php
                                    $rating = $book->average_rating;
                                    $fullStars = floor($rating);
                                    $hasHalf = ($rating - $fullStars) >= 0.5;
                                @endphp
                                @for($i = 0; $i < $fullStars; $i++)
                                    <i class="fas fa-star text-sm"></i>
                                @endfor
                                @if($hasHalf)
                                    <i class="fas fa-star-half-alt text-sm"></i>
                                @endif
                                @for($i = ($hasHalf ? $fullStars + 1 : $fullStars); $i < 5; $i++)
                                    <i class="fas fa-star text-gray-300 text-sm"></i>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600">({{ number_format($rating, 1) }})</span>
                        @else
                            <div class="flex items-center text-gray-300">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star text-sm"></i>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-400">(Belum ada review)</span>
                        @endif
                    </div>

                    <!-- Price -->
                    <div class="text-2xl font-bold text-emerald-700 mb-2">
                        Rp {{ number_format($book->harga, 0, ',', '.') }}
                    </div>

                    <!-- Meta Info -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                            <i class="fas fa-check"></i> {{ $book->status }}
                        </span>
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                            <i class="fas fa-tag"></i> {{ $book->kategori }}
                        </span>
                        @if($book->purchase_count > 0)
                            <span class="inline-block bg-emerald-100 text-emerald-800 text-xs px-2 py-1 rounded-full font-semibold">
                                <i class="fas fa-shopping-bag"></i> {{ $book->purchase_count }} terjual
                            </span>
                        @endif
                    </div>

                    <!-- Description -->
                    <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                        {{ \Illuminate\Support\Str::words(strip_tags($book->deskripsi ?? ''), 15, '...') }}
                    </p>

                    <!-- Book Details -->
                    <div class="text-xs text-gray-500 mb-4 flex items-center gap-2">
                        <span><i class="fas fa-book-open"></i> {{ $book->halaman ?? '-' }} hal</span>
                        @if($book->penerbit)
                            <span>• {{ $book->penerbit }}</span>
                        @endif
                        @if($book->tahun)
                            <span>• {{ $book->tahun }}</span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('books.show', $book->id) }}" 
                           class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-center text-sm font-medium transition-colors flex items-center justify-center gap-1"
                           title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>

                        <form method="POST" action="{{ route('wishlists.store', $book->id) }}" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-white hover:bg-red-50 text-red-600 border border-red-200 hover:border-red-400 px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-1"
                                    title="Tambah ke Wishlist">
                                <i class="fas fa-heart"></i>
                            </button>
                        </form>

                        <button onclick="addToCartAjax({{ $book->id }}, event)" 
                                class="flex-1 bg-emerald-700 hover:bg-emerald-800 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-1 cart-button"
                                title="Tambah ke Keranjang">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
        <div class="flex justify-center">
            {{ $books->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-book-open text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Tidak ada buku yang ditemukan</h3>
            <p class="text-gray-600 mb-6">
                @if(request()->has('search') || request()->has('category'))
                    Coba ubah kata kunci pencarian atau filter kategori
                @else
                    Belum ada buku yang tersedia saat ini
                @endif
            </p>
            <a href="{{ route('books.index') }}" 
               class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-redo"></i> Tampilkan Semua Buku
            </a>
        </div>
    @endif
</div>

<!-- Add to Cart AJAX Script -->
<script>
function addToCartAjax(bookId, event) {
    event.preventDefault();
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const button = event.target.closest('.cart-button');
    const originalHTML = button ? button.innerHTML : null;
    
    fetch(`{{ url('/cart/add') }}/${bookId}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(async response => {
        const data = await response.json();
        
        if (response.status === 401 && data.requiresLogin) {
            alert('Silakan login untuk menambahkan ke keranjang');
            window.location.href = '{{ route("login") }}';
            return;
        }
        
        if (!response.ok) {
            throw new Error(data.message || 'Gagal menambahkan ke keranjang');
        }
        
        return data;
    })
    .then(data => {
        if (!data) return;
        
        if (data.success) {
            // Update cart count
            const cartCountEls = document.querySelectorAll('[id$="CartCount"]');
            cartCountEls.forEach(el => {
                if (el) el.textContent = data.cartCount ?? 0;
            });
            
            // Notify global listeners
            window.dispatchEvent(new Event('cart:updated'));
            
            // Show success message
            if (button) {
                button.innerHTML = '<i class="fas fa-check"></i> Ditambahkan';
                button.classList.remove('bg-emerald-700', 'hover:bg-emerald-800');
                button.classList.add('bg-green-600');
                
                setTimeout(() => {
                    if (originalHTML) button.innerHTML = originalHTML;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-emerald-700', 'hover:bg-emerald-800');
                }, 2000);
            }
            
            // Optional: Show toast notification
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2';
            toast.innerHTML = '<i class="fas fa-check-circle"></i> ' + (data.message || 'Buku berhasil ditambahkan ke keranjang!');
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        } else {
            throw new Error(data.message || 'Gagal menambahkan ke keranjang');
        }
    })
    .catch(error => {
        console.error('Add to cart error:', error);
        alert(error.message || 'Terjadi kesalahan. Silakan coba lagi.');
    });
}
</script>
@endsection
