<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ruang Aksara')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <style>
    /* GLOBAL STYLES */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* BACKGROUND UNTUK SEMUA HALAMAN */
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

    /* MAIN LAYOUT CONTAINER */
    body > div:first-of-type {
        display: flex;
        min-height: 100vh;
    }

    /* Ensure footer sticks to bottom on short pages across devices */
    html, body {
        height: 100%;
    }

    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* The outer flex container (sidebar + main) should expand to fill available space */
    body > div:first-of-type {
        flex: 1 1 auto;
        display: flex;
        min-height: 0; /* allow main to manage scrolling */
    }

    /* MAIN CONTENT */
    .main-content {
        flex: 1;
        transition: margin-left 0.3s ease;
        overflow-x: hidden;
        display: flex; /* allow footer alignment */
        flex-direction: column; /* stack content and footer vertically */
        min-height: 0; /* allow child to manage scrolling */
    }

    /* SIDEBAR RESPONSIVE */
    @media (min-width: 1024px) {
        .main-content {
            margin-left: 16rem; /* 64px = 16rem */
        }
        
        /* Remove sidebar margin for admin/owner pages */
        body:has(.admin-header) .main-content,
        body:has([class*="admin-"]) .main-content {
            margin-left: 0 !important;
        }
    }

    @media (max-width: 1023px) {
        .main-content {
            margin-left: 0;
        }
    }

    /* REMOVE DEFAULT BG COLORS */
    .bg-gray-100,
    .bg-gray-50,
    .min-h-screen {
        background: transparent !important;
    }

    /* NAV STYLES */
    nav {
        background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%) !important;
        color: #fff !important;
    }

    nav a, nav button, nav span, nav p {
        color: #fff !important;
    }

    nav a:hover, nav button:hover {
        color: #FFD600 !important;
        background: rgba(255, 215, 0, 0.15) !important;
    }

    /* Remove blue classes from nav */
    nav .bg-blue-600, nav .bg-blue-700, nav .bg-blue-800, 
    nav .bg-blue-100, nav .bg-blue-50, nav .text-blue-600 {
        background: transparent !important;
        color: inherit !important;
    }

    /* CUSTOM NAV LINK STYLES */
    .nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
        border: none;
        background: transparent;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .nav-link:hover {
        background: rgba(255, 215, 0, 0.2);
        color: #FFD600;
    }

    .nav-link.active,
    .nav-link.nav-active {
        background: rgba(255, 215, 0, 0.3);
        color: #FFD600;
        font-weight: 600;
    }

    .nav-link i {
        width: 1.25rem;
        text-align: center;
        font-size: 1.1rem;
    }

    /* CONTENT STYLES */
    .content-wrapper {
        padding: 2rem;
        flex: 1;
    }

    @media (max-width: 768px) {
        .content-wrapper {
            padding: 1rem;
        }
    }

    /* FORM & INPUT STYLES */
    .form-input,
    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="number"],
    input[type="tel"],
    textarea,
    select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        font-family: inherit;
        font-size: 1rem;
    }

    .form-input:focus,
    input:focus,
    textarea:focus,
    select:focus {
        outline: none;
        border-color: #2d5a3d;
        box-shadow: 0 0 0 3px rgba(45, 90, 61, 0.1);
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #1f2937;
    }

    /* BUTTON STYLES */
    .btn-primary {
        background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 600;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(45, 90, 61, 0.3);
    }

    /* CARD STYLES */
    .card {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    /* TABLE STYLES */
    table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 0.75rem;
        overflow: hidden;
    }

    thead {
        background: #f3f4f6;
    }

    th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    tbody tr:hover {
        background: #f9fafb;
    }

    /* BADGE STYLES */
    .badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    /* ALERT STYLES */
    .alert {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid;
    }

    .alert-success {
        background: #ecfdf5;
        border-color: #10b981;
        color: #065f46;
    }

    .alert-danger {
        background: #fef2f2;
        border-color: #ef4444;
        color: #991b1b;
    }

    .alert-warning {
        background: #fffbeb;
        border-color: #f59e0b;
        color: #92400e;
    }

    .alert-info {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #1e40af;
    }
    
    /* TEXT BLUE DALAM NAVBAR MENJADI PUTIH */
    nav .text-blue-600, nav .text-blue-700, nav .text-blue-800, nav .text-blue-900, nav .text-blue-100, nav .text-blue-200 {
        color: #fff !important;
    }
        color: #fff !important;
    }
    
    /* KONTEN PUTIH TRANSPARAN */
    .bg-white {
        background-color: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(8px);
    }
    
    /* MAIN CONTENT FLEX GROW */
    main {
        flex: 1 !important;
    }
    
    /* FOOTER - DEFAULT HIJAU */
    footer.bg-white {
        background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%) !important;
        color: white !important;
        margin-top: auto !important;
    }
    
    /* FOOTER TEXT PUTIH */
    footer .text-gray-600 {
        color: white !important;
    }

    /* TOMBOL BACK/NEXT FLOATING - PASTIKAN SELALU ADA */
    .nav-buttons {
        position: fixed;
        bottom: 100px;
        right: 20px;
        z-index: 1100;
    }

    .nav-buttons button {
        background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%) !important;
        color: white !important;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .nav-buttons button:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
    }

    /* ADMIN HEADER & FOOTER BIRU */
    .admin-header {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
    }

    .admin-footer {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
    }

    /* FALLBACK JIKA GAMBAR TIDAK LOAD */
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            linear-gradient(rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.15)),
            url('/images/background.jpg') center/cover fixed no-repeat;
        z-index: -1;
    }
    /* Browser autofill (Chrome) - make autofill look like normal input */
    input:-webkit-autofill,
    input:-webkit-autofill:focus,
    textarea:-webkit-autofill,
    textarea:-webkit-autofill:focus,
    select:-webkit-autofill,
    select:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0px 1000px #ffffff inset !important;
        box-shadow: 0 0 0px 1000px #ffffff inset !important;
        -webkit-text-fill-color: #111827 !important; /* text-gray-900 */
        transition: background-color 5000s ease-in-out 0s;
    }
    /* Reusable card style for translucent panels */
    .card {
        background-color: rgba(255,255,255,0.92) !important;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.6) !important;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06) !important;
        border-radius: 12px !important;
    }
    /* Top site header (blend with background image). Keep admin-header intact. */
    nav.site-top {
        background: linear-gradient(135deg, rgba(20,90,72,0.92) 0%, rgba(26,120,86,0.92) 100%) !important;
        color: white !important;
        border-bottom: 1px solid rgba(255,255,255,0.06) !important;
        backdrop-filter: blur(6px);
    }

    /* If Tailwind's `bg-white` class is present on the nav, make sure our site-top style still wins */
    nav.bg-white.site-top {
        background: linear-gradient(135deg, rgba(20,90,72,0.92) 0%, rgba(26,120,86,0.92) 100%) !important;
        color: white !important;
    }

    /* If admin header class is present, prefer admin-header colors */
    nav.site-top.admin-header {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
    }

    /* Logo color override (make it yellow) */
    nav.site-top a.logo {
        color: #FFD166 !important; /* warm yellow */
    }

    /* Also ensure logo contrasts when bg-white utility is present */
    nav.bg-white.site-top a.logo {
        color: #FFD166 !important;
    }

    /* SIDEBAR NAVIGATION LINK STYLES */
    .nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #e8f6ec;
        padding: 0.9rem 1rem;
        border-radius: 0.9rem;
        font-weight: 600;
        transition: all 0.15s ease;
        width: 100%;
        text-decoration: none;
        border: none;
        background: transparent;
        cursor: pointer;
        text-align: left;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }

    .nav-link:hover {
        background: rgba(31, 124, 69, 0.22);
        color: #fde047;
    }

    .nav-link.nav-active {
        background: #1f7c45;
        color: #ffffff;
        box-shadow: 0 12px 28px rgba(31,124,69,0.32);
    }

    .nav-link i {
        color: inherit;
        width: 1.1rem;
        text-align: center;
    }
    /* Make footer sit at page bottom when content is short */
    footer {
        margin-top: auto;
    }
    /* Fixed footer visibility helper (kehilangan footer pada area scroll) */
    .app-footer {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1200;
    }

    /* Reserve space so fixed footer doesn't cover content */
    body {
        padding-bottom: 88px;
    }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- SIDEBAR COMPONENT -->
        @include('components.sidebar')

        <!-- MAIN CONTENT AREA -->
        <main class="main-content flex-1 transition-all duration-300 overflow-y-auto">
            <!-- HEADER/NAV (only for non-user roles) -->
            @php
                $hideNavForUser = auth()->check() && auth()->user()->role === 'user';
            @endphp
            @if(!$hideNavForUser)
            <nav class="bg-white shadow-lg site-top 
                @if(request()->is('admin*') || request()->is('owner*') || (auth()->check() && in_array(auth()->user()->role, ['admin', 'owner'])))
                    admin-header
                @endif
            " style="background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%) !important;">
                <div class="max-w-full mx-auto px-6">
                    <div class="flex justify-between items-center py-3">
                <div class="flex items-center space-x-3">
                    @if(request()->is('admin*') || request()->is('owner*') || (auth()->check() && in_array(auth()->user()->role, ['admin', 'owner'])))
                        <!-- HEADER ADMIN/OWNER -->
                        @if(file_exists(public_path('images/logo.png')))
                            <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold whitespace-nowrap logo flex items-center" style="color:#FFD600;">
                                <img src="{{ asset('images/logo.png') }}" alt="Ruang Aksara" class="h-8 mr-2 inline-block" />
                                <span>
                                    Ruang Aksara - {{ auth()->check() ? (auth()->user()->role == 'owner' ? 'Owner' : 'Admin') : 'Admin' }}
                                </span>
                            </a>
                        @else
                            <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold whitespace-nowrap logo" style="color:#FFD600;">
                                Ruang Aksara - {{ auth()->check() ? (auth()->user()->role == 'owner' ? 'Owner' : 'Admin') : 'Admin' }}
                            </a>
                        @endif
                        
                        @auth
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded hover:text-yellow-300 transition" title="Dashboard">
                                    <i class="fas fa-tachometer-alt text-lg"></i>
                                    <span class="text-xs">Dashboard</span>
                                </a>
                                <a href="{{ route('admin.books.index') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded hover:text-yellow-300 transition" title="Kelola Buku">
                                    <i class="fas fa-book text-lg"></i>
                                    <span class="text-xs">Buku</span>
                                </a>
                                <a href="{{ route('admin.orders.index') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded hover:text-yellow-300 transition" title="Pesanan">
                                    <i class="fas fa-shopping-cart text-lg"></i>
                                    <span class="text-xs">Pesanan</span>
                                </a>
                                <a href="{{ route('admin.loans.index') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded hover:text-yellow-300 transition" title="Peminjaman">
                                    <i class="fas fa-book-open text-lg"></i>
                                    <span class="text-xs">Peminjaman</span>
                                </a>
                                <a href="{{ route('admin.loan-stock.index') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded hover:text-yellow-300 transition" title="Kelola Stok Peminjaman">
                                    <i class="fas fa-warehouse text-lg"></i>
                                    <span class="text-xs">Stok Pinjam</span>
                                </a>
                                <a href="{{ route('admin.attendance.index') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded hover:text-yellow-300 transition" title="Presensi">
                                    <i class="fas fa-user-check text-lg"></i>
                                    <span class="text-xs">Presensi</span>
                                </a>
                                <a href="{{ route('admin.reviews.pending') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded hover:text-yellow-300 transition" title="Review Buku">
                                    <i class="fas fa-star text-lg"></i>
                                    <span class="text-xs">Review</span>
                                </a>
                                @if(auth()->user()->role == 'owner')
                                    <a href="{{ route('owner.reports') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded hover:text-yellow-300 transition" title="Laporan">
                                        <i class="fas fa-chart-bar text-lg"></i>
                                        <span class="text-xs">Laporan</span>
                                    </a>
                                @endif
                            </div>
                        @endauth
                    @else
                        <!-- HEADER USER BIASA -->
                        <div class="flex items-center space-x-3">
                            <a href="{{ url('/') }}" class="text-lg font-bold logo">Ruang Aksara</a>
                            @auth
                                @if(auth()->user()->role === 'user')
                                    <!-- Toggle Sidebar dekat logo -->
                                    <button id="navbarToggleSidebar" onclick="toggleSidebar()" class="px-3 py-2 rounded bg-green-700 hover:bg-green-800 transition flex items-center gap-2" title="Toggle Sidebar">
                                        <span class="text-sm">☰</span>
                                    </button>
                                @endif
                            @endauth
                        </div>

                        @auth
                            <a href="{{ route('books.index') }}" class="hover:text-blue-200">Buku</a>
                            <a href="{{ route('orders.index') }}" class="hover:text-blue-200">Pesanan</a>
                            <a href="{{ route('wishlists.index') }}" class="hover:text-blue-200">Wishlist</a>
                            <a href="{{ route('loans.index') }}" class="hover:text-blue-200">Peminjaman</a>
                            <a href="{{ route('help') }}" class="hover:text-blue-200">Bantuan</a>
                        @endauth
                    @endif
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Notification Bell untuk User -->
                        @if(auth()->user()->role === 'user')
                            @include('components.notifications-bell')
                        @endif

                        @if(request()->is('admin*') || request()->is('owner*') || (auth()->check() && in_array(auth()->user()->role, ['admin', 'owner'])))
                            <!-- MENU ADMIN/OWNER -->
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.profile') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded hover:text-yellow-300 transition" title="Profil">
                                    <i class="fas fa-user-circle text-lg"></i>
                                    <span class="text-xs">Profil</span>
                                </a>
                                <a href="{{ route('admin.settings.index') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded hover:text-yellow-300 transition" title="Pengaturan">
                                    <i class="fas fa-cog text-lg"></i>
                                    <span class="text-xs">Pengaturan</span>
                                </a>
                                <a href="{{ url('/') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded hover:text-yellow-300 transition" title="Kembali ke Site">
                                    <i class="fas fa-home text-lg"></i>
                                    <span class="text-xs">Kembali ke</span>
                                </a>
                            </div>
                        @else
                            <!-- MENU USER BIASA -->
                            <a href="{{ route('profile') }}" class="hover:text-blue-200">Profil</a>
                            
                            @if(in_array(auth()->user()->role, ['admin', 'owner']))
                                <a href="{{ route('admin.dashboard') }}" class="bg-green-700 px-4 py-2 rounded hover:bg-green-900">Admin</a>
                            @endif
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex flex-col items-center gap-1 px-3 py-2 rounded hover:text-yellow-300 transition" title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                                <span class="text-xs">Logout</span>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    @endif

    <!-- LOGIN NOTIFICATION COMPONENT -->
    @include('components.login-notification')

    <!-- PAGE CONTENT -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    <!-- FLOATING BUTTONS -->
    <div class="nav-buttons">
        <button onclick="window.history.back()" title="Kembali">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button onclick="window.history.forward()" title="Maju" class="mt-2">
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>

        </main>
    </div>

    <!-- FOOTER -->
    <footer class="bg-white border-t py-6 mt-8 app-footer">
        <div class="max-w-full mx-auto px-6">
            <div class="text-center text-gray-600 text-sm">
                &copy; {{ date('Y') }} Ruang Aksara. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fallback untuk background image
            const bgImage = new Image();
            bgImage.onerror = function() {
                console.log('Background image failed, using fallback');
                document.body.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            };
            bgImage.src = '/images/background.jpg';
        });
    </script>

    <!-- STACK UNTUK CHILD VIEW SCRIPTS -->
    @stack('scripts')
</body>
</html>