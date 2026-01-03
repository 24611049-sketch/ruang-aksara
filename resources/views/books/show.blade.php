<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $book->judul }} - Ruang Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Background untuk semua halaman */
        body {
            background: 
                linear-gradient(rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.15)),
                url('/images/background.jpg') center/cover fixed no-repeat !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
            min-height: 100vh !important;
        }

        /* Header hijau */
        .navbar {
            background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar a, .navbar button {
            color: white !important;
            text-decoration: none !important;
        }

        .navbar a:hover, .navbar button:hover {
            color: #e2e8f0 !important;
        }

        /* Content boxes: toned down from pure white so text sits on a softer background */
        .content-box {
            background-color: rgba(250, 252, 245, 0.72) !important; /* subtle warm/green tint */
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0,0,0,0.04);
        }

        /* Book cover container uses the book's cover as a blurred background */
        .book-cover {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            position: relative;
            background-size: cover;
            background-position: center;
            padding: 10px;
            min-height: 360px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .book-cover::before {
            content: '';
            position: absolute;
            inset: 0;
            background: inherit;
            background-size: cover;
            background-position: center;
            filter: blur(18px) saturate(110%);
            transform: scale(1.04);
            z-index: 0;
            opacity: 0.88;
        }

        .book-cover img {
            position: relative;
            z-index: 2;
            max-width: 60%;
            max-height: 88%;
            width: auto;
            height: auto;
            aspect-ratio: 2 / 3;
            object-fit: contain;
            border-radius: 6px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
            background: rgba(255,255,255,0.6);
            padding: 8px;
        }

        .book-icon {
            font-size: 6rem;
            color: #2d5a3d;
        }

        /* Price styling */
        .price-tag {
            font-size: 2.5rem;
            color: #16a34a;
            font-weight: bold;
        }

        /* Button styling */
        .btn-add-cart {
            background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-add-cart:hover {
            background: linear-gradient(135deg, #1e3e2a 0%, #2d5a3d 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(45, 90, 61, 0.3);
        }

        .btn-back {
            color: #2d5a3d;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            color: #1e3e2a;
            transform: translateX(-4px);
        }

        /* Related books grid */
        .related-book-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .related-book-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .book-icon {
                font-size: 4rem;
            }

            .price-tag {
                font-size: 1.875rem;
            }

            .btn-add-cart {
                padding: 0.75rem 1.5rem;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-book"></i> Ruang Aksara
                </a>
                <div class="flex gap-4">
                    <a href="/books" class="px-4 py-2 rounded-lg hover:bg-white/20 transition">
                        <i class="fas fa-book-open mr-2"></i>Katalog
                    </a>
                    <a href="/home" class="px-4 py-2 rounded-lg hover:bg-white/20 transition">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-8">
        <div class="container mx-auto px-4">
            <!-- Back Button -->
            <a href="{{ route('books.index') }}" class="btn-back mb-8 inline-block">
                <i class="fas fa-arrow-left"></i>Kembali ke Katalog
            </a>

            <!-- Book Detail Section -->
            <div class="content-box rounded-lg shadow-lg p-6 md:p-10 mb-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
                    <!-- Book Cover -->
                    <div class="md:col-span-1">
                        @php
                            $coverUrl = ($book->image && file_exists(public_path('storage/book-covers/' . $book->image))) ? asset('storage/book-covers/' . $book->image) : null;
                        @endphp
                        <div class="book-cover h-96 md:h-full min-h-96 flex items-center justify-center" @if($coverUrl) style="background-image: linear-gradient(rgba(255,255,255,0.10), rgba(255,255,255,0.10)), url('{{ $coverUrl }}');" @endif>
                            @if($coverUrl)
                                <img src="{{ $coverUrl }}" alt="{{ $book->judul }}">
                            @else
                                <i class="fas fa-book-open book-icon"></i>
                            @endif
                        </div>
                    </div>

                    <!-- Book Info -->
                    <div class="md:col-span-2">
                        <!-- Title & Author -->
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-1">{{ $book->judul }}</h1>
                        <p class="text-lg text-gray-600 mb-3">
                            <i class="fas fa-pen-nib mr-2"></i>oleh <strong>{{ $book->penulis }}</strong>
                        </p>

                        <!-- Book Meta Info -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6 pb-6 border-b border-gray-200">
                            <div>
                                <p class="text-sm text-gray-600 font-semibold mb-1">KATEGORI</p>
                                <p class="text-gray-800 font-medium">{{ $book->kategori }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-semibold mb-1">HALAMAN</p>
                                <p class="text-gray-800 font-medium">{{ $book->halaman }} hal</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-semibold mb-1">BERAT</p>
                                <p class="text-gray-800 font-medium">{{ $book->berat ?? 500 }}g</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-semibold mb-1">STOK</p>
                                <p class="text-gray-800 font-medium">
                                    @if($book->stok > 0)
                                        <span class="text-green-600">{{ $book->stok }} tersedia</span>
                                    @else
                                        <span class="text-red-600">Habis</span>
                                    @endif
                                </p>
                            </div>
                            @if($book->isbn)
                            <div>
                                <p class="text-sm text-gray-600 font-semibold mb-1">ISBN</p>
                                <p class="text-gray-800 font-medium">{{ $book->isbn }}</p>
                            </div>
                            @endif
                            @if($book->penerbit)
                            <div>
                                <p class="text-sm text-gray-600 font-semibold mb-1">PENERBIT</p>
                                <p class="text-gray-800 font-medium">{{ $book->penerbit }}</p>
                            </div>
                            @endif
                            @if($book->purchase_count > 0)
                            <div>
                                <p class="text-sm text-gray-600 font-semibold mb-1">TERJUAL</p>
                                <p class="text-gray-800 font-medium"><i class="fas fa-shopping-bag text-green-600"></i> {{ $book->purchase_count ?? 0 }} unit</p>
                            </div>
                            @endif
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Deskripsi Buku</h3>
                            <p class="text-gray-700" style="line-height:1.5">{{ $book->deskripsi }}</p>
                        </div>

                        <!-- Price & Action -->
                        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between pt-4 border-t border-gray-200">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">HARGA</p>
                                <p class="price-tag">Rp {{ number_format($book->harga, 0, ',', '.') }}</p>
                            </div>
                            @if($book->stok > 0)
                                <a href="{{ route('cart.add', $book->id) }}" class="btn-add-cart w-full sm:w-auto justify-center">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>Tambah ke Keranjang</span>
                                </a>
                            @else
                                <button class="btn-add-cart w-full sm:w-auto justify-center opacity-50 cursor-not-allowed" disabled>
                                    <i class="fas fa-ban"></i>
                                    <span>Stok Habis</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="content-box rounded-lg shadow-lg p-6 md:p-10 mb-12" id="reviews">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-star mr-2 text-yellow-500"></i>Review & Rating
                </h2>

                <!-- Rating Summary with Distribution -->
                <div class="mb-8 p-6 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg">
                    @if($book->total_reviews > 0)
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Average Rating -->
                            <div class="flex items-center gap-6">
                                <div class="text-center">
                                    <div class="text-5xl font-bold text-gray-800">
                                        {{ number_format($book->average_rating, 1) }}
                                    </div>
                                    <div class="text-yellow-500 text-2xl mt-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($book->average_rating))
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $book->average_rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">{{ number_format($book->total_reviews) }} review</p>
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-700 text-sm">Rating rata-rata dari pembaca</p>
                                </div>
                            </div>
                            
                            <!-- Rating Distribution -->
                            <div class="space-y-2">
                                @foreach($ratingDistribution as $star => $data)
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-700 w-12">{{ $star }} <i class="fas fa-star text-yellow-500 text-xs"></i></span>
                                        <div class="flex-1 bg-gray-200 rounded-full h-3">
                                            <div class="bg-yellow-500 h-3 rounded-full transition-all duration-500" style="width: {{ $data['percentage'] }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 w-12 text-right">{{ $data['count'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="text-gray-400 mb-3">
                                <i class="far fa-star text-5xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Rating</h3>
                            <p class="text-gray-600">Buku ini belum memiliki review. Jadilah yang pertama memberikan review!</p>
                        </div>
                    @endif
                </div>

                <!-- Write Review Form (Only for authenticated users) -->
                @auth
                    @php
                        $userReview = $book->reviews()->where('user_id', auth()->id())->first();
                    @endphp
                    
                    @if(!$userReview)
                        <div class="mb-8 p-6 bg-blue-50 rounded-lg border border-blue-200">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">
                                <i class="fas fa-edit mr-2"></i>Tulis Review Anda
                            </h3>
                            
                            <form action="{{ route('reviews.store', $book->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rating</label>
                                    <div class="flex gap-2">
                                        @for($i = 5; $i >= 1; $i--)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                                <div class="px-4 py-2 border-2 border-gray-300 rounded-lg peer-checked:border-yellow-500 peer-checked:bg-yellow-50 hover:border-yellow-400 transition">
                                                    <span class="font-semibold">{{ $i }}</span>
                                                    <i class="fas fa-star text-yellow-500 ml-1"></i>
                                                </div>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ulasan (opsional)</label>
                                    <textarea name="comment" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Bagikan pengalaman Anda dengan buku ini..."></textarea>
                                </div>
                                
                                <button type="submit" class="btn-add-cart">
                                    <i class="fas fa-paper-plane"></i>
                                    Kirim Review
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mb-8 p-6 bg-green-50 rounded-lg border border-green-200">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">
                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>Review Anda
                                    </h3>
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="text-yellow-500">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $userReview->rating ? '' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-600">{{ $userReview->rating }}/5</span>
                                    </div>
                                    @if($userReview->comment)
                                        <p class="text-gray-700">{{ $userReview->comment }}</p>
                                    @endif
                                    @if(!$userReview->is_approved)
                                        <p class="text-sm text-orange-600 mt-2">
                                            <i class="fas fa-clock mr-1"></i>Menunggu persetujuan admin
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200 text-center">
                        <p class="text-gray-600">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <a href="{{ route('welcome') }}" class="text-green-600 hover:text-green-700 font-semibold">Login</a> untuk memberikan review
                        </p>
                    </div>
                @endauth

                <!-- Filter/Sort Reviews -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b">
                    <h3 class="text-xl font-bold text-gray-800">
                        Semua Review ({{ number_format($book->total_reviews) }})
                    </h3>
                    
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600">Urutkan:</label>
                        <select onchange="window.location.href='?sort=' + this.value" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="latest" {{ $sortBy == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="highest" {{ $sortBy == 'highest' ? 'selected' : '' }}>Rating Tertinggi</option>
                            <option value="lowest" {{ $sortBy == 'lowest' ? 'selected' : '' }}>Rating Terendah</option>
                        </select>
                    </div>
                </div>

                <!-- Display Reviews -->
                <div class="space-y-4">
                    @forelse($reviews as $review)
                        <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                            <div class="flex items-start gap-3">
                                <!-- User Avatar -->
                                <img src="{{ $review->user->foto_profil ? asset('storage/' . $review->user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=2d5a3d&color=fff' }}" 
                                     alt="{{ $review->user->name }}" 
                                     class="w-10 h-10 rounded-full">
                                
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $review->user->name }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <div class="text-yellow-500 text-sm">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="text-xs text-gray-500">• {{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <span class="text-lg font-bold text-gray-800">{{ $review->rating }}.0</span>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-gray-700 text-sm leading-relaxed">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-comments text-5xl mb-4 opacity-50"></i>
                            <p class="text-lg">Belum ada review untuk buku ini</p>
                            <p class="text-sm mt-1">Jadilah yang pertama memberikan review!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($reviews->hasPages())
                    <div class="mt-6">
                        <div class="flex justify-center">
                            {{ $reviews->links('pagination::tailwind') }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Related Books Section -->
            @if($relatedBooks->count() > 0)
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-8">
                    <i class="fas fa-books mr-3"></i>Buku Lainnya dalam Kategori <span class="text-yellow-300">{{ $book->kategori }}</span>
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedBooks as $relatedBook)
                    <div class="related-book-card">
                        <!-- Book Image -->
                        <div class="book-cover h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            @if($relatedBook->image)
                                <img src="{{ asset('storage/book-covers/' . $relatedBook->image) }}" alt="{{ $relatedBook->judul }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-book-open text-4xl text-gray-500"></i>
                            @endif
                        </div>

                        <!-- Book Info -->
                        <div class="p-4">
                            <h4 class="font-bold text-gray-800 text-sm mb-1 line-clamp-2">{{ $relatedBook->judul }}</h4>
                            <p class="text-xs text-gray-600 mb-3">oleh {{ $relatedBook->penulis }}</p>
                            
                            <p class="text-lg font-bold text-green-600 mb-3">
                                Rp {{ number_format($relatedBook->harga, 0, ',', '.') }}
                            </p>

                            @if($relatedBook->purchase_count > 0)
                            <p class="text-xs text-gray-500 mb-2">
                                <i class="fas fa-shopping-bag text-green-600"></i> {{ $relatedBook->purchase_count }} terjual
                            </p>
                            @endif

                            <a href="{{ route('books.show', $relatedBook) }}" class="block w-full text-center bg-gray-100 hover:bg-green-600 hover:text-white text-gray-800 py-2 rounded-lg transition font-semibold text-sm">
                                <i class="fas fa-eye mr-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-16 bg-gradient-to-r from-green-900 to-green-800 text-white py-8 border-t">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2025 Ruang Aksara. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Auto update cart count
        async function updateCartCount() {
            try {
                const res = await fetch("/cart/api/count");
                const data = await res.json();
                const el = document.getElementById('globalCartCount');
                if (el) el.textContent = data.count ?? 0;
            } catch (error) {
                console.error('Error fetching cart count:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', updateCartCount);
        window.addEventListener('cart:updated', updateCartCount);
    </script>
</body>
</html>