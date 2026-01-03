<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - Ruang Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <style>
        /* BACKGROUND SAMA SEPERTI DASHBOARD */
        body {
            background:
                linear-gradient(rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.15)),
                url('/images/background.jpg') center/cover fixed no-repeat !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
            min-height: 100vh !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* KONTEN PUTIH TRANSPARAN (softer green-tinged panel) */
        .content-card {
            background-color: rgba(245, 250, 246, 0.98) !important; /* subtle green tint */
            backdrop-filter: blur(6px);
            border-radius: 16px;
            border: 1px solid rgba(163,230,53,0.06);
            padding: 18px;
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .book-card {
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(4px) !important;
            border-radius: 20px !important; /* softer rounded corners */
            box-shadow: 0 6px 12px rgba(16,24,32,0.06) !important;
            overflow: hidden !important;
            transition: transform 0.22s ease, box-shadow 0.22s ease !important;
            border: 1px solid rgba(30,62,42,0.04) !important;
            box-sizing: border-box;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .book-card:hover {
            transform: translateY(-4px) !important;
            box-shadow: 0 10px 20px rgba(16,24,32,0.08) !important;
        }

        .book-cover {
            width: 100%;
            height: 260px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: linear-gradient(135deg, #f7fbf8 0%, #eef3ef 100%);
            background-size: cover;
            background-position: center;
            overflow: hidden;
            padding: 6px;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .book-cover::before {
            content: '';
            position: absolute;
            inset: 0;
            background: inherit;
            filter: blur(12px) saturate(105%);
            transform: scale(1.04);
            z-index: 0;
            opacity: 0.7;
        }

        .book-cover img {
            position: relative;
            z-index: 2;
            max-width: 70%;
            max-height: 90%;
            width: auto;
            height: auto;
            aspect-ratio: 2 / 3;
            object-fit: contain;
            border-radius: 2px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            background: rgba(255,255,255,0.65);
            padding: 4px;
            display: block;
        }

        .book-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .book-author {
            color: #718096;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .book-category {
            display: inline-block;
            background: #2d5a3d;
            color: #ffffff;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            margin-bottom: 1rem;
            align-self: flex-start;
        }

        .book-price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .book-description {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: auto;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-size: 0.875rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            flex: 1;
        }

        .btn-primary {
            background: #2d5a3d;
            color: white;
        }

        .btn-primary:hover {
            background: #1e3e2a;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(45, 90, 61, 0.3);
        }

        .btn-danger {
            background: #e53e3e;
            color: white;
        }

        .btn-danger:hover {
            background: #c53030;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(229, 62, 62, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #718096;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 2rem 0;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #e2e8f0;
        }

        .empty-state h3 {
            margin-bottom: 1rem;
            color: #2d3748;
            font-size: 1.5rem;
        }

        .empty-state p {
            margin-bottom: 1.5rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            font-size: 1rem;
        }

        .wishlist-count {
            background: #2d5a3d;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .pagination .page-link {
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 5px;
            background: white;
            color: #2d5a3d;
            text-decoration: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.2s;
        }

        .pagination .page-link:hover {
            background: #2d5a3d;
            color: white;
        }

        .pagination .page-link.active {
            background: #2d5a3d;
            color: white;
        }

        .book-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            font-size: 0.8rem;
            color: #718096;
        }

        .book-rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .book-rating i {
            color: #f6ad55;
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-20px); }
        }

        .fade-out {
            animation: fadeOut 0.3s forwards;
        }

        .footer {
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
            color: #718096;
            font-size: 0.9rem;
            border-top: 1px solid rgba(0,0,0,0.1);
        }

        @media (max-width: 576px) {
            .books-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .header {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <button id="hamburgerBtn" class="hamburger-btn" type="button" title="Toggle Sidebar" aria-controls="sidebar" aria-expanded="false" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <aside class="sidebar hidden" id="sidebar">
        @include('partials.user-sidebar-content')
    </aside>

    <div class="main-wrapper" id="mainWrapper">
        <div class="content-wrapper">
            <div class="mx-auto max-w-6xl space-y-8">
                <header class="space-y-1">
                    <h1 class="text-3xl font-bold text-gray-800">Wishlist Saya</h1>
                    <p class="text-gray-600">Kumpulan buku yang ingin Anda baca nanti</p>
                </header>

                <section class="content-card space-y-6 p-6 md:p-8">
                    @if($wishlists->count() > 0)
                        <div class="books-grid">
                            @foreach($wishlists as $wishlist)
                                <div class="book-card">
                                    <div class="book-cover">
                                        @if(!empty($wishlist->book->image) && file_exists(public_path('storage/book-covers/' . $wishlist->book->image)))
                                            <img src="{{ asset('storage/book-covers/' . $wishlist->book->image) }}" alt="{{ $wishlist->book->judul }}">
                                        @else
                                            <img src="{{ asset('images/default-book-cover.svg') }}" alt="{{ $wishlist->book->judul }}">
                                        @endif
                                    </div>
                                    <div class="book-title">{{ $wishlist->book->judul }}</div>
                                    <div class="book-author">oleh {{ $wishlist->book->penulis }}</div>
                                    <div class="book-category">{{ $wishlist->book->kategori }}</div>
                                    <div class="book-meta">
                                        <div class="book-rating">
                                            @if($wishlist->book->average_rating > 0)
                                                <i class="fas fa-star"></i>
                                                <span>{{ number_format($wishlist->book->average_rating, 1) }}</span>
                                            @else
                                                <i class="fas fa-star" style="color: #d1d5db;"></i>
                                                <span style="color: #9ca3af;">Belum ada rating</span>
                                            @endif
                                        </div>
                                        <div>{{ $wishlist->book->halaman }} halaman</div>
                                    </div>
                                    <div class="book-price">Rp {{ number_format($wishlist->book->harga, 0, ',', '.') }}</div>
                                    <p class="book-description">{{ Str::limit($wishlist->book->deskripsi, 100) }}</p>

                                    <div class="action-buttons">
                                        <a href="{{ route('books.show', $wishlist->book->id) }}" class="btn btn-primary">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <form action="{{ route('wishlists.destroy', $wishlist->id) }}" method="POST" style="flex: 1;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-full remove-btn">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($wishlists->hasPages())
                            <div class="pagination">
                                {{ $wishlists->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="fas fa-heart-broken"></i>
                            <h3>Wishlist kosong</h3>
                            <p>Anda belum menambahkan buku ke wishlist. Yuk jelajahi katalog buku kami!</p>
                            <a href="{{ route('books.index') }}" class="btn btn-primary">
                                <i class="fas fa-compass"></i> Jelajahi Buku
                            </a>
                        </div>
                    @endif
                </section>

                <div class="footer">
                    <p>© 2023 Ruang Aksara. Seluruh hak cipta dilindungi.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="nav-buttons">
        <button type="button" onclick="window.history.back()" title="Kembali">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button type="button" onclick="window.history.forward()" title="Maju">
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>

    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
        document.querySelectorAll('.remove-btn').forEach((button) => {
            button.addEventListener('click', function () {
                const bookCard = this.closest('.book-card');

                if (!bookCard) {
                    return;
                }

                if (confirm('Apakah Anda yakin ingin menghapus buku ini dari wishlist?')) {
                    bookCard.classList.add('fade-out');

                    setTimeout(() => {
                        bookCard.remove();
                        updateWishlistCount();

                        if (document.querySelectorAll('.book-card').length === 0) {
                            const grid = document.querySelector('.books-grid');
                            if (grid) {
                                grid.innerHTML = `
                                    <div class="empty-state">
                                        <i class="fas fa-heart-broken"></i>
                                        <h3>Wishlist kosong</h3>
                                        <p>Anda belum menambahkan buku ke wishlist. Yuk jelajahi katalog buku kami!</p>
                                        <a href="{{ route('books.index') }}" class="btn btn-primary">
                                            <i class="fas fa-compass"></i> Jelajahi Buku
                                        </a>
                                    </div>
                                `;
                            }

                            const pagination = document.querySelector('.pagination');
                            if (pagination) {
                                pagination.style.display = 'none';
                            }
                        }
                    }, 300);
                }
            });
        });

        function updateWishlistCount() {
            const countElement = document.querySelector('.wishlist-count');

            if (!countElement) {
                return;
            }

            const bookCount = document.querySelectorAll('.book-card').length;
            countElement.textContent = bookCount;

            if (bookCount === 0) {
                countElement.style.display = 'none';
            }
        }
    </script>
</body>
</html>
