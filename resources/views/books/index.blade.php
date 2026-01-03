<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Katalog Buku - Ruang Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    
    <!-- CUSTOM CSS UNTUK BACKGROUND SAMA DENGAN DASHBOARD -->
    <style>
        /* BACKGROUND SAMA SEPERTI DASHBOARD */
        body {
            background: 
                linear-gradient(rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.08)),
                url('/images/background.jpg') center/cover fixed no-repeat !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
            min-height: 100vh !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2d3748;
            line-height: 1.6;
        }
        
        /* KONTEN PUTIH TRANSPARAN */
        .main-content {
            background-color: transparent;
            backdrop-filter: none;
            border-radius: 0;
            box-shadow: none;
            margin: 0;
            padding: 2rem 0;
            min-height: 80vh;
        }
        
        /* Navigation */
        .navbar {
            background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%) !important;
            backdrop-filter: blur(8px);
            padding: 0.75rem 2rem;
            margin: 0.75rem 1rem;
            border-radius: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            border-bottom: none;
        }
        
        .nav-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-back-btn {
            background: #a3e635 !important;
            color: #1e3e2a !important;
            font-weight: 700;
            padding: 0.6rem 1.5rem !important;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(163, 230, 53, 0.3);
        }
        
        .nav-back-btn:hover {
            background: #84cc16 !important;
            transform: translateX(-2px);
            box-shadow: 0 6px 16px rgba(163, 230, 53, 0.4);
        }
        
        .btn {
            padding: 0.5rem 0.75rem;
            border: none;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%);
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1e3e2a 0%, #2d5a3d 100%);
            transform: translateY(-1px);
        }

        .btn-cart {
            background: #ef4444;
            color: white;
            border-radius: 0.375rem;
            padding: 0.6rem 1.2rem;
        }
        
        .btn-cart:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }
        
        /* Search Area */
        .search-container {
            background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(45, 90, 61, 0.25);
            backdrop-filter: blur(12px);
            border: none;
        }
        
        .search-title {
            color: white;
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .search-title i {
            color: #a3e635;
        }

        /* Form Elements */
        .form-input {
            padding: 0.75rem 1rem;
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 0.5rem;
            font-size: 0.95rem;
            width: 100%;
            background: rgba(255,255,255,0.95);
            transition: all 0.3s ease;
            color: #2d3748;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #a3e635;
            box-shadow: 0 0 0 3px rgba(163, 230, 53, 0.2);
            background: white;
        }
        
        .form-input::placeholder {
            color: #94a3b8;
        }
        
        /* Books Grid */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .book-card {
            /* Slightly tinted, less bright than pure white to reduce glare behind text */
            background: rgba(250, 252, 245, 0.88) !important;
            backdrop-filter: blur(8px) !important;
            border-radius: 0.9rem !important;
            box-shadow: 0 6px 16px rgba(0,0,0,0.08) !important;
            overflow: hidden !important; /* constrain all content */
            transition: all 0.28s ease !important;
            border: 1px solid rgba(229, 231, 235, 0.6) !important;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            max-height: 100%;
        }
        
        .book-card:hover {
            transform: translateY(-6px) !important;
            box-shadow: 0 12px 24px rgba(0,0,0,0.16) !important;
            background: rgba(255, 255, 255, 0.95) !important;
            border-color: #a3e635 !important;
        }

        /* Book Cover dengan Gambar */
        .book-cover {
            height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            background-size: cover;
            background-position: center;
            overflow: hidden;
            padding: 6px;
        }

        /* Rank badge (consistent sizes) */
        .rank-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #fff;
            font-weight: 800;
            box-shadow: 0 6px 18px rgba(0,0,0,0.18);
            z-index: 50;
            text-align: center;
        }
        .rank-badge .rank-emoji { font-size: 1.2rem; line-height: 1; }
        .rank-badge .rank-num { display:block; font-size:0.85rem; margin-top:2px; line-height:1; }
        .rank-badge.rank-1 { width:56px; height:56px; background: radial-gradient(circle at 30% 30%, #FFD700, #FFA500); border:3px solid #FFD700; }
        .rank-badge.rank-2 { width:48px; height:48px; background: linear-gradient(135deg, #E8E8E8, #C0C0C0); border:2px solid #D3D3D3; color:#333; }
        .rank-badge.rank-3 { width:48px; height:48px; background: linear-gradient(135deg, #E8A76A, #CD7F32); border:2px solid #D4956E; }
        .rank-badge.rank-other { width:40px; height:40px; background: linear-gradient(135deg, #6B7280, #9CA3AF); }

        .book-cover::before{
            content: '';
            position: absolute;
            inset: 0;
            background: inherit;
            filter: blur(12px) saturate(105%);
            transform: scale(1.04);
            z-index: 0;
            opacity: 0.7;
        }

        .book-image {
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

        .book-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
            text-align: center;
            padding: 1rem;
        }

        .book-placeholder i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        .book-placeholder span {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .book-info {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            height: 100%;
            gap: 0.75rem;
            line-height: 1.5;
            width: 100%;
            box-sizing: border-box;
            overflow: hidden;
        }

        .book-head {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            height: auto;
            justify-content: flex-start;
            flex-shrink: 0;
        }

        .book-price-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            align-self: start;
            flex-shrink: 0;
        }

        .book-price-wrap {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 140px;
        }

        /* normalize badge and icon sizes for consistent alignment */
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.7rem;
            font-size: 0.75rem;
            border-radius: 0.375rem;
            white-space: nowrap;
            line-height: 1.3;
        }

        /* ensure icon buttons are same size */
        .icon-btn {
            width: 44px !important;
            height: 44px !important;
            font-size: 1.05rem !important;
            border-radius: 8px !important;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        /* keep actions aligned in a row, centered */
        .book-actions {
            display: flex;
            gap: 0.5rem;
            padding: 0;
            justify-content: flex-start;
            margin-top: auto;
            flex-shrink: 0;
            width: 100%;
            box-sizing: border-box;
            overflow: hidden;
        }

        /* keep price spacing consistent */
        .book-description {
            color: #374151;
            font-size: 0.9rem;
            line-height: 1.4;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
            word-break: break-word;
            width: 100%;
            box-sizing: border-box;
        }

        .book-points {
            color: #6b7280;
            font-size: 0.78rem;
            display: flex;
            gap: 0.4rem;
            align-items: center;
            margin: 0;
            flex-shrink: 0;
            line-height: 1.3;
            flex-wrap: wrap;
            overflow: hidden;
            max-width: 100%;
        }

        /* (book-bottom removed - price is shown above meta again) */
        
        .book-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-shrink: 0;
            min-height: 2.6rem;
        }
        
        .book-author {
            color: #6b7280;
            margin: 0;
            font-size: 0.85rem;
            flex-shrink: 0;
            min-height: 1.3rem;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .book-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #dc2626;
            margin: 0;
            flex-shrink: 0;
            display: block;
            line-height: 1.3;
        }

        .book-original-price {
            font-size: 0.9rem;
            color: #9ca3af;
            text-decoration: line-through;
            margin-right: 0.5rem;
        }

        .book-rating {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            margin: 0;
            flex-shrink: 0;
            min-height: 1.3rem;
            line-height: 1.3;
        }

        .book-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0;
            gap: 0.5rem;
            flex-shrink: 0;
            min-height: 1.8rem;
            line-height: 1.3;
            width: 100%;
            box-sizing: border-box;
            flex-wrap: wrap;
            overflow: hidden;
        }

        .rating-stars {
            color: #fbbf24;
            display: flex;
            align-items: center;
            gap: 0.1rem;
        }

        .rating-count {
            color: #6b7280;
            font-size: 0.8rem;
            white-space: nowrap;
        }
        
        .badge {
            padding: 0.3rem 0.8rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-success {
            background: #10b981;
            color: white;
        }

        /* Cart Button */
        .cart-button {
            background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%);
            color: white;
            border: none;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            width: 100%;
            justify-content: center;
        }

        .cart-button:hover {
            background: linear-gradient(135deg, #1e3e2a 0%, #2d5a3d 100%);
            transform: translateY(-1px);
        }

        /* Icon-only buttons with tooltip */
        .icon-btn {
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .icon-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .cart-button-icon {
            background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%);
            color: white;
        }

        .cart-button-icon:hover {
            background: linear-gradient(135deg, #1e3e2a 0%, #2d5a3d 100%);
        }

        /* Tooltip styling */
        .icon-btn[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            background: #2d3748;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 10;
            pointer-events: none;
        }

        /* Cart Badge di Navbar */
        .cart-badge {
            position: relative;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 2rem;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .pagination a, 
        .pagination span {
            padding: 0.6rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            text-decoration: none;
            color: #4b5563;
            transition: all 0.3s ease;
            background: white;
            display: inline-block;
            min-width: 45px;
            text-align: center;
            font-weight: 500;
            cursor: pointer;
        }
        
        .pagination a {
            cursor: pointer;
        }
        
        .pagination a:hover {
            background: #2d5a3d;
            color: white;
            border-color: #2d5a3d;
            transform: translateY(-2px);
        }
        
        .pagination .active span {
            background: #2d5a3d;
            color: white;
            border-color: #2d5a3d;
            font-weight: 600;
        }
        
        .pagination .disabled span {
            color: #9ca3af;
            cursor: not-allowed;
            background: #f3f4f6;
            border-color: #e5e7eb;
        }
        
        /* Header Style */
        .page-header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 2.5rem 2rem;
            background: linear-gradient(135deg, rgba(45, 90, 61, 0.15) 0%, rgba(30, 62, 42, 0.1) 100%);
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(163, 230, 53, 0.2);
        }
        
        .page-header h1 {
            font-size: 2.25rem;
            font-weight: 800;
            color: #a3e635;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            letter-spacing: -0.5px;
        }
        
        .page-header h1 i {
            color: #a3e635;
        }
        
        .page-header p {
            font-size: 1.05rem;
            color: #ffffff;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
            font-weight: 500;
        }
        }

        /* Container */
        .container {
            max-width: 1180px;
            width: calc(100% - 2rem);
            margin: 0 auto;
            padding: 0 20px;
        }

        .book-actions {
            display: flex;
            gap: 0.5rem;
            padding: 0;
            justify-content: center;
            margin-top: auto;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .books-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        @media (max-width: 992px) {
            .books-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        /* Ensure four columns on wide screens */
        @media (min-width: 1201px) {
            .books-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1rem;
            }
            
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .main-content {
                margin: 0.5rem auto;
                padding: 1rem;
            }
            
            .page-header h1 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 520px) {
            .books-grid {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
        }
        
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        
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
            <div class="mx-auto max-w-6xl space-y-6">
                <!-- Navigation -->
                <nav class="navbar" style="margin: 0.75rem auto 0; padding: 0 24px; width: 100%; box-sizing: border-box;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0;">
                        <a href="/" class="nav-brand">
                            <i class="fas fa-book" style="color: #a3e635; font-size: 1.25rem;"></i> 
                            Ruang Aksara
                        </a>
                        <div style="display: flex; gap: 0.75rem; align-items: center;">
                            @auth
                                <!-- Menu untuk user yang sudah login -->
                                <a href="{{ route('cart.index') }}" class="btn cart-badge" style="background: #a3e635; color: #1e3e2a; font-weight: 600; border-radius: 0.75rem; padding: 0.5rem 0.875rem; transition: all 0.3s ease; box-shadow: 0 4px 8px rgba(163, 230, 53, 0.2); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.95rem;" onmouseover="this.style.background='#84cc16'; this.style.boxShadow='0 6px 12px rgba(163, 230, 53, 0.3)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='#a3e635'; this.style.boxShadow='0 4px 8px rgba(163, 230, 53, 0.2)'; this.style.transform='translateY(0)';">
                                    <i class="fas fa-shopping-cart"></i> Keranjang
                                    <span class="cart-count" id="cartCount" style="background: #dc2626; color: white; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold;">0</span>
                                </a>
                                <a href="/home" class="btn btn-primary" style="background: transparent; color: #a3e635; border: 1.5px solid #a3e635; font-weight: 600; border-radius: 0.75rem; padding: 0.5rem 0.875rem; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.95rem;" onmouseover="this.style.backgroundColor='rgba(163, 230, 53, 0.1)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='transparent'; this.style.transform='translateY(0)';">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            @else
                                <!-- Menu untuk guest (belum login) - tombol kembali dengan style elegan -->
                                <a href="/" class="nav-back-btn">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            @endauth
                        </div>
                    </div>
                </nav>

                

                <div class="main-content">
            <!-- Header -->
            <div class="page-header">
                <h1><i class="fas fa-book-open"></i> Katalog Buku</h1>
                <p>Jelajahi koleksi buku terbaik kami untuk memperkaya wawasan dan pengetahuan Anda</p>
            </div>

            <!-- Search & Filter -->
            <div class="search-container">
                <div class="search-title">
                    <i class="fas fa-search"></i>
                    <span>Temukan Buku Favoritmu</span>
                </div>
                <form action="{{ route('books.index') }}" method="GET" class="search-form" id="searchForm" style="display: grid; grid-template-columns: 1fr auto auto auto; gap: 1rem;">
                    <input type="text" name="search" id="searchInput" placeholder="Cari judul, penulis, atau deskripsi..." value="{{ request('search') }}" class="form-input">
                    <select name="category" id="categorySelect" class="form-input">
                        <option value="">Semua Kategori</option>
                        @foreach($kategories as $kategori)
                            <option value="{{ $kategori }}" {{ request('category') == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary" style="background: #a3e635; color: #1e3e2a; font-weight: 600; padding: 0.75rem 1.5rem; box-shadow: 0 2px 8px rgba(163, 230, 53, 0.3); transition: all 0.3s ease;" onmouseover="this.style.background='#84cc16'; this.style.boxShadow='0 4px 12px rgba(163, 230, 53, 0.4)';" onmouseout="this.style.background='#a3e635'; this.style.boxShadow='0 2px 8px rgba(163, 230, 53, 0.3)';">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <button type="button" class="btn" id="clearBtn" style="background: transparent; color: #666; border: 2px solid #ddd; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 0.5rem; transition: all 0.3s ease; display: {{ request('search') || request('category') ? 'flex' : 'none' }}; align-items: center; gap: 0.5rem;" onmouseover="this.style.borderColor='#a3e635'; this.style.color='#a3e635';" onmouseout="this.style.borderColor='#ddd'; this.style.color='#666';">
                        <i class="fas fa-times"></i> Bersihkan
                    </button>
                </form>
            </div>

            <!-- Books Grid -->
            @if($books->count() > 0)
                <div class="books-grid" style="margin-left:0;margin-right:0;">
                    @foreach($books as $book)
                    <div class="book-card">
                        
<!-- Book Cover dengan Gambar -->
@php
    $coverUrl = (!empty($book->image) && file_exists(public_path('storage/book-covers/' . $book->image))) ? asset('storage/book-covers/' . $book->image) : null;
@endphp
<div class="book-cover" @if($coverUrl) style="background-image: linear-gradient(rgba(255,255,255,0.06), rgba(255,255,255,0.06)), url('{{ $coverUrl }}');" @endif>
    @if($coverUrl)
        <img src="{{ $coverUrl }}" alt="{{ $book->judul }}" class="book-image">
    @else
        <img src="{{ asset('images/default-book-cover.svg') }}" alt="{{ $book->judul }}" class="book-image">
    @endif
</div>

@php
    // Cek apakah buku ini termasuk top sellers (controller mengirim $topRanks mapping)
    $badgeRank = null;
    if (isset($topRanks) && is_array($topRanks)) {
        $badgeRank = $topRanks[$book->id] ?? null;
    }
@endphp

@if($badgeRank)
    @php
        $cls = 'rank-other';
        if ($badgeRank == 1) $cls = 'rank-1';
        elseif ($badgeRank == 2) $cls = 'rank-2';
        elseif ($badgeRank == 3) $cls = 'rank-3';
    @endphp
    <div class="rank-badge {{ $cls }}" aria-label="Peringkat {{ $badgeRank }}">
        @if($badgeRank == 1)
            <span class="rank-emoji">👑</span>
            <span class="rank-num">{{ $badgeRank }}</span>
        @elseif($badgeRank == 2)
            <span class="rank-emoji">🥈</span>
            <span class="rank-num">{{ $badgeRank }}</span>
        @elseif($badgeRank == 3)
            <span class="rank-emoji">🥉</span>
            <span class="rank-num">{{ $badgeRank }}</span>
        @else
            <span class="rank-emoji">#</span>
            <span class="rank-num">{{ $badgeRank }}</span>
        @endif
    </div>
@endif

                        
                        <div class="book-info">
                            <!-- Judul -->
                            <h3 class="book-title">{{ $book->judul }}</h3>
                            
                            <!-- Penulis -->
                            <p class="book-author">oleh {{ $book->penulis }}</p>
                            
                            <!-- Rating -->
                            <div class="book-rating">
                                @if($book->total_reviews > 0)
                                    <div class="rating-stars">
                                        @php
                                            $rating = $book->average_rating;
                                            $fullStars = floor($rating);
                                            $hasHalf = ($rating - $fullStars) >= 0.5;
                                        @endphp
                                        @for($i = 0; $i < $fullStars; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                        @if($hasHalf)
                                            <i class="fas fa-star-half-alt"></i>
                                            @for($i = $fullStars + 1; $i < 5; $i++)
                                                <i class="fas fa-star" style="color: #d1d5db;"></i>
                                            @endfor
                                        @else
                                            @for($i = $fullStars; $i < 5; $i++)
                                                <i class="fas fa-star" style="color: #d1d5db;"></i>
                                            @endfor
                                        @endif
                                    </div>
                                    <span class="rating-count">({{ number_format($rating, 1) }})</span>
                                @else
                                    <div class="rating-stars">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="fas fa-star" style="color: #d1d5db;"></i>
                                        @endfor
                                    </div>
                                    <span class="rating-count" style="color: #9ca3af;">(Belum ada review)</span>
                                @endif
                            </div>
                            
                            <!-- Harga -->
                            <span class="book-price">Rp {{ number_format($book->harga, 0, ',', '.') }}</span>

                            <!-- Status & Kategori -->
                            <div class="book-meta">
                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i> {{ $book->status }}
                                </span>
                                <span style="font-size: 0.8rem; color: #6b7280;">
                                    <i class="fas fa-tag"></i> {{ $book->kategori }}
                                </span>
                                @if($book->purchase_count > 0)
                                <span style="font-size: 0.8rem; color: #059669; font-weight: 600;">
                                    <i class="fas fa-shopping-bag"></i> {{ $book->purchase_count ?? 0 }} terjual
                                </span>
                                @endif
                            </div>

                            <!-- Deskripsi -->
                            <p class="book-description">{{ \Illuminate\Support\Str::words(strip_tags($book->deskripsi ?? ''), 18, '...') }}</p>
                            
                            <!-- Info Buku (halaman, penerbit, tahun) -->
                            <div class="book-points">
                                <span><i class="fas fa-book-open"></i> {{ $book->halaman ?? '-' }} hal</span>
                                @if($book->penerbit)
                                    <span>&bull; {{ $book->penerbit }}</span>
                                @endif
                                @if($book->tahun)
                                    <span>&bull; {{ $book->tahun }}</span>
                                @endif
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="book-actions">
                                <a href="{{ route('books.show', $book->id) }}" class="icon-btn" title="Lihat Detail Buku" style="background: #f3f4f6; color: #4b5563;">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <form method="POST" action="{{ route('wishlists.store', $book->id) }}" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="icon-btn" title="Tambah ke Wishlist" style="background: #fff; border:1px solid #e5e7eb; color:#dc2626;">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </form>

                                <button class="icon-btn cart-button-icon" onclick="addToCartAjax({{ $book->id }}, event)" title="Tambah ke Keranjang">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $books->links('vendor.pagination.custom') }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h3 style="margin-bottom: 1rem; color: #2d3748; font-size: 1.25rem;">Tidak ada buku yang ditemukan</h3>
                    <p style="margin-bottom: 1.5rem; color: #6b7280;">
                        @if(request()->has('search') || request()->has('category'))
                            Coba ubah kata kunci pencarian atau filter kategori
                        @else
                            Belum ada buku yang tersedia saat ini
                        @endif
                    </p>
                    <a href="{{ route('books.index') }}" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Tampilkan Semua Buku
                    </a>
                </div>
            @endif
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
        (function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const showNotification = (message, type = 'info') => {
                const notification = document.createElement('div');
                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 12px 20px;
                    border-radius: 8px;
                    font-weight: 600;
                    z-index: 9999;
                    animation: slideIn 0.3s ease-out;
                    background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
                    color: white;
                `;
                notification.textContent = message;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease-out';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            };

            window.showNotification = showNotification;

            const updateCartCount = () => {
                fetch('{{ route("cart.apiCount") }}')
                    .then((response) => response.json())
                    .then((data) => {
                        const cartCount = document.getElementById('cartCount');
                        if (cartCount) {
                            cartCount.textContent = data.count;
                        }
                        const global = document.getElementById('globalCartCount');
                        if (global) {
                            global.textContent = data.count;
                        }
                    })
                    .catch((error) => console.error('Error:', error));
            };

            window.updateCartCount = updateCartCount;

            window.addToCartAjax = function (bookId, event) {
                event.preventDefault();

                fetch(`{{ route('cart.add.post', ':id') }}`.replace(':id', bookId), {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                })
                    .then(async (response) => {
                        const contentType = response.headers.get('content-type') || '';
                        let data = null;

                        if (contentType.includes('application/json')) {
                            data = await response.json();
                        } else {
                            const text = await response.text();
                            throw new Error(text || 'Gagal menerima respons dari server');
                        }

                        if (response.status === 401 && data && data.requiresLogin) {
                            document.getElementById('loginModal')?.classList.add('show');
                            showNotification('Silakan login untuk menambahkan ke keranjang', 'error');
                            return null;
                        }

                        if (!response.ok) {
                            showNotification((data && data.message) ? data.message : 'Gagal menambahkan ke keranjang', 'error');
                            return null;
                        }

                        return data;
                    })
                    .then((data) => {
                        if (!data) {
                            return;
                        }

                        if (data.success) {
                            const cartCountEl = document.getElementById('cartCount');
                            const global = document.getElementById('globalCartCount');
                            if (cartCountEl) {
                                cartCountEl.textContent = data.cartCount ?? 0;
                            }
                            if (global) {
                                global.textContent = data.cartCount ?? 0;
                            }

                            window.dispatchEvent(new Event('cart:updated'));
                            showNotification(data.message || 'Buku berhasil ditambahkan ke keranjang!', 'success');

                            const button = event.target.closest('.cart-button-icon');
                            const originalHTML = button ? button.innerHTML : null;
                            if (button) {
                                button.innerHTML = '<i class="fas fa-check"></i>';
                                button.style.background = '#10b981';

                                setTimeout(() => {
                                    if (originalHTML) {
                                        button.innerHTML = originalHTML;
                                    }
                                    button.style.background = 'linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%)';
                                }, 2000);
                            }
                        } else {
                            showNotification((data && data.message) ? data.message : 'Gagal menambahkan ke keranjang', 'error');
                        }
                    })
                    .catch((error) => {
                        console.error('Add to cart error:', error);
                        const text = (error && error.message) ? error.message : '';
                        if (text && text.toLowerCase().includes('login')) {
                            showNotification('Silakan login terlebih dahulu untuk menambahkan ke keranjang', 'error');
                        } else {
                            showNotification('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
                        }
                    });
            };

            document.addEventListener('DOMContentLoaded', () => {
                updateCartCount();

                const categorySelect = document.querySelector('select[name="category"]');
                if (categorySelect && categorySelect.form) {
                    categorySelect.addEventListener('change', () => categorySelect.form.submit());
                }

                const searchInput = document.getElementById('searchInput');
                const categoryFilter = document.getElementById('categorySelect');
                const clearBtn = document.getElementById('clearBtn');
                const searchForm = document.getElementById('searchForm');

                const updateClearButton = () => {
                    if (!clearBtn || !searchInput || !categoryFilter) {
                        return;
                    }
                    const hasSearch = searchInput.value.trim() !== '' || categoryFilter.value !== '';
                    clearBtn.style.display = hasSearch ? 'flex' : 'none';
                };

                if (clearBtn && searchInput && categoryFilter && searchForm) {
                    clearBtn.addEventListener('click', (event) => {
                        event.preventDefault();
                        searchInput.value = '';
                        categoryFilter.value = '';
                        searchForm.submit();
                    });

                    searchInput.addEventListener('input', updateClearButton);
                    categoryFilter.addEventListener('change', updateClearButton);
                    updateClearButton();
                }

                const placeholderTitles = [
                    '📚 Buku Best Seller',
                    '🎓 Pendidikan Terbaik',
                    '📖 Novel Terbaru',
                    '🔬 Sains & Teknologi',
                    '💼 Bisnis & Karir',
                    '❤️ Romance & Drama',
                    '🕵️ Mystery & Thriller',
                    '🌟 Inspirasi Hidup'
                ];

                document.querySelectorAll('.book-placeholder span').forEach((element) => {
                    const randomTitle = placeholderTitles[Math.floor(Math.random() * placeholderTitles.length)];
                    element.textContent = randomTitle;
                });

                const styleEl = document.createElement('style');
                styleEl.textContent = `
                    @keyframes slideIn {
                        from {
                            transform: translateX(400px);
                            opacity: 0;
                        }
                        to {
                            transform: translateX(0);
                            opacity: 1;
                        }
                    }
                    @keyframes slideOut {
                        from {
                            transform: translateX(0);
                            opacity: 1;
                        }
                        to {
                            transform: translateX(400px);
                            opacity: 0;
                        }
                    }
                `;
                document.head.appendChild(styleEl);
            });

            window.addEventListener('cart:updated', updateCartCount);
        })();
    </script>
</body>
</html>