<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $useSidebarLayout = request()->routeIs('cart.*') && auth()->check() && auth()->user()->role === 'user';
    @endphp
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @if($useSidebarLayout)
        <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    @endif
    <style>
    /* BACKGROUND UNTUK SEMUA HALAMAN */
    body {
        background: 
            linear-gradient(rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.15)),
            url('/images/background.jpg') center/cover fixed no-repeat !important;
        background-size: cover !important;
        background-position: center !important;
        background-attachment: fixed !important;
        min-height: 100vh !important;
        display: flex !important;
        flex-direction: column !important;
    }
    
    /* HAPUS SEMUA BACKGROUND LAIN */
    .bg-gray-100,
    .bg-gray-50,
    .min-h-screen {
        background: transparent !important;
    }
    
    /* HEADER - SEMUA USER SAMA (HIJAU) */
    nav.bg-white {
        background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%) !important;
    }

    /* site-top/guest header override to blend with background and show yellow logo */
    nav.site-top {
        background: linear-gradient(135deg, rgba(20,90,72,0.92) 0%, rgba(26,120,86,0.92) 100%) !important;
        color: white !important;
        border-bottom: 1px solid rgba(255,255,255,0.06) !important;
        backdrop-filter: blur(6px);
    }

    nav.bg-white.site-top {
        background: linear-gradient(135deg, rgba(20,90,72,0.92) 0%, rgba(26,120,86,0.92) 100%) !important;
    }

    nav.site-top a.logo {
        color: #FFD166 !important;
    }
    
    /* HEADER TEXT PUTIH UNTUK SEMUA */
    nav a, nav button, nav .text-gray-800, nav .text-gray-600 {
        color: white !important;
    }
    
    nav a:hover, nav button:hover {
        color: #e2e8f0 !important;
    }
    
    /* BUTTON HEADER UNTUK SEMUA */
    nav .bg-blue-600 {
        background: rgba(255, 255, 255, 0.2) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
    }
    
    nav .bg-blue-600:hover {
        background: rgba(255, 255, 255, 0.3) !important;
    }
    
    /* KONTEN PUTIH TRANSPARAN UNTUK SEMUA */
    .bg-white {
        background-color: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(8px);
    }
    
    /* MAIN CONTENT FLEX GROW */
    main {
        flex: 1 !important;
    }
    
    /* FOOTER - SEMUA USER SAMA (HIJAU) */
    footer.bg-white {
        background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%) !important;
        color: white !important;
        margin-top: auto !important;
    }
    
    /* FOOTER TEXT PUTIH UNTUK SEMUA */
    footer .text-gray-600 {
        color: white !important;
    }

    /* TOMBOL BACK/NEXT FLOATING - SAMA UNTUK SEMUA */
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
    </style>
</head>
<body class="bg-gray-100">
    @if($useSidebarLayout)
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
                @yield('content')
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
    @else
    
    <!-- HEADER - SEMUA USER SAMA -->
    <nav class="bg-white shadow-lg site-top">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-8">
                    @if(request()->is('admin*') || request()->is('owner*') || (auth()->check() && in_array(auth()->user()->role, ['admin', 'owner'])))
                        <!-- HEADER ADMIN/OWNER -->
                        <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">
                            Ruang Aksara - 
                            @auth
                                @if(auth()->user()->isOwner())
                                    Owner
                                @else
                                    Admin
                                @endif
                            @else
                                Admin
                            @endauth
                        </a>
                        
                        @auth
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-200">
                                <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                            </a>
                            <a href="{{ route('admin.books.index') }}" class="hover:text-blue-200">
                                <i class="fas fa-book mr-1"></i>Kelola Buku
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="hover:text-blue-200">
                                <i class="fas fa-shopping-cart mr-1"></i>Pesanan
                            </a>
                            @if(auth()->user()->isOwner())
                                <a href="{{ route('owner.reports') }}" class="hover:text-blue-200">
                                    <i class="fas fa-chart-bar mr-1"></i>Laporan
                                </a>
                            @endif
                        @endauth
                    @else
                        <!-- HEADER USER BIASA - SAMA PERSIS STYLENYA -->
                        <a href="{{ url('/') }}" class="text-xl font-bold logo">Ruang Aksara</a>
                        
                        @auth
                            <a href="{{ route('books.index') }}" class="hover:text-blue-200">Buku</a>
                            <a href="{{ route('orders.index') }}" class="hover:text-blue-200">Pesanan</a>
                            <a href="{{ route('wishlists.index') }}" class="hover:text-blue-200">Wishlist</a>
                        @endauth
                    @endif
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        @if(request()->is('admin*') || request()->is('owner*') || (auth()->check() && in_array(auth()->user()->role, ['admin', 'owner'])))
                            <!-- MENU ADMIN/OWNER -->
                            <a href="{{ route('admin.settings.index') }}" class="hover:text-blue-200">
                                <i class="fas fa-cog mr-1"></i>Pengaturan
                            </a>
                            <a href="/help" class="hover:text-blue-200">
                                <i class="fas fa-question-circle mr-1"></i>Bantuan
                            </a>
                            <a href="{{ url('/') }}" class="hover:text-blue-200">
                                <i class="fas fa-home mr-1"></i>Kembali ke Site
                            </a>
                        @else
                            <!-- MENU USER BIASA -->
                            <a href="{{ route('profile') }}" class="hover:text-blue-200">Profil</a>
                            
                            @if(in_array(auth()->user()->role, ['admin', 'owner']))
                                <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 px-4 py-2 rounded hover:bg-blue-700">
                                    @if(auth()->user()->isOwner())
                                        Owner Panel
                                    @else
                                        Admin Panel
                                    @endif
                                </a>
                            @endif
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="hover:text-blue-200">
                                <i class="fas fa-sign-out-alt mr-1"></i>Logout
                            </button>
                        </form>
                        <a href="{{ route('cart.index') }}" class="hover:text-blue-200 relative ml-4" id="globalCartLink">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="ml-1">Keranjang</span>
                            <span id="globalCartCount" style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; border-radius: 999px; padding: 2px 6px; font-size: 12px; font-weight: 700;">0</span>
                        </a>
                    @else
                        <a href="/" class="hover:text-blue-200">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 px-4 py-2 rounded hover:bg-blue-700">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="py-8">
        @yield('content')
    </main>

    <!-- MODAL LOGIN PROMPT -->
    <div id="loginModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 8px; padding: 2rem; max-width: 400px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; color: #2d3748;">Silakan Login Terlebih Dahulu</h2>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Anda perlu login untuk menambahkan buku ke keranjang dan melakukan pembelian.</p>
            <div style="display: flex; gap: 1rem;">
                <button onclick="document.getElementById('loginModal').style.display='none'" style="flex: 1; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; background: white; color: #2d3748; font-weight: 600; cursor: pointer;">Nanti</button>
                <a href="/" style="flex: 1; padding: 0.75rem; border-radius: 4px; background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%); color: white; text-decoration: none; text-align: center; font-weight: 600;">Login Sekarang</a>
            </div>
        </div>
    </div>
    <style>
        #loginModal {
            display: none !important;
        }
        #loginModal.show {
            display: flex !important;
        }
    </style>

    <!-- TOMBOL BACK/NEXT FLOATING - SAMA UNTUK SEMUA -->
    <div class="nav-buttons">
        <button onclick="window.history.back()" title="Kembali">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button onclick="window.history.forward()" title="Maju" class="mt-2">
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>

    <!-- FOOTER - SEMUA USER SAMA -->
    <footer class="bg-white border-t py-6">
        <div class="max-w-7xl mx-auto px-4 text-center">
            @if(request()->is('admin*') || request()->is('owner*') || (auth()->check() && in_array(auth()->user()->role, ['admin', 'owner'])))
                &copy; {{ date('Y') }} Ruang Aksara - 
                @auth
                    @if(auth()->user()->isOwner())
                        Owner Panel
                    @else
                        Admin Panel
                    @endif
                @else
                    Admin Panel
                @endauth
            @else
                &copy; 2025 Ruang Aksara. All rights reserved.
            @endif
        </div>
    </footer>

    @endif

    <!-- SCRIPT BACKGROUND FALLBACK -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek jika background gagal load
            const bgImage = new Image();
            bgImage.onerror = function() {
                console.log('Background image failed, using fallback');
                document.body.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            };
            bgImage.src = '/images/background.jpg';
        });
    </script>
    <script>
        async function fetchGlobalCartCount() {
            try {
                const res = await fetch("/cart/api/count", {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                if (!res.ok) return;
                const data = await res.json();
                const el = document.getElementById('globalCartCount');
                if (el) {
                    el.textContent = data.count ?? 0;
                }
            } catch (error) {
                console.error('Error fetching cart count:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchGlobalCartCount();
            // Poll periodically
            setInterval(fetchGlobalCartCount, 15000);
            // Listen for manual updates
            window.addEventListener('cart:updated', fetchGlobalCartCount);
        });
    </script>
</body>
</html>