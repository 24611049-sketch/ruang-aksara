<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ruang Aksara - Dashboard User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- CUSTOM CSS -->
    <style>
        /* BACKGROUND SAMA SEPERTI ADMIN/OWNER */
        body {
            background: 
                linear-gradient(rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.15)),
                url('/images/background.jpg') center/cover fixed no-repeat !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
            min-height: 100vh !important;
        }
        
        /* SIDEBAR HIJAU TUA */
        .w-64.bg-white {
            background: linear-gradient(180deg, #2d5a3d 0%, #1e3e2a 100%) !important;
        }
        
        /* TEXT SIDEBAR PUTIH */
        .w-64.bg-white .text-gray-800,
        .w-64.bg-white .text-gray-700,
        .w-64.bg-white .text-gray-600 {
            color: white !important;
        }
        
        /* KONTEN PUTIH TRANSPARAN */
        .bg-content {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(8px);
        }
        
        /* CARD STYLE DENGAN VARIASI HIJAU */
        .stat-card {
            background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%);
            color: white;
        }
        
        .stat-card.secondary {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
        }

        /* NAV LINK STYLING (konsisten dengan profile/edit) */
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

        .sidebar-panel {
            transition: transform 0.3s ease;
        }

        .sidebar-panel.collapsed-desktop {
            display: none;
        }

        .hamburger-btn {
            position: fixed;
            top: 1rem;
            left: 1rem;
            width: 3rem;
            height: 3rem;
            border: none;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            cursor: pointer;
            z-index: 60;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hamburger-btn span {
            display: block;
            width: 1.5rem;
            height: 0.2rem;
            background: #e8f6ec;
            border-radius: 999px;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .hamburger-btn.active span:nth-child(1) {
            transform: translateY(0.4rem) rotate(45deg);
        }

        .hamburger-btn.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger-btn.active span:nth-child(3) {
            transform: translateY(-0.4rem) rotate(-45deg);
        }

        .hamburger-btn:focus-visible {
            outline: 3px solid rgba(163, 230, 53, 0.6);
            outline-offset: 4px;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.55);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 50;
        }

        .sidebar-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        @media (max-width: 1024px) {
            .hamburger-btn {
                display: flex;
            }

            .sidebar-panel {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                height: 100vh;
                width: 280px;
                transform: translateX(-100%);
                z-index: 55;
                display: block;
            }

            .sidebar-panel.collapsed-desktop {
                display: block;
            }

            .sidebar-panel.mobile-open {
                transform: translateX(0);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
            }

            #sidebarToggle {
                display: none;
            }

            .flex.h-screen {
                flex-direction: column;
                min-height: 100vh;
                height: auto;
            }

            .content-panel {
                width: 100%;
                padding-top: 5rem;
            }
        }

        @media (min-width: 1025px) {
            .sidebar-overlay {
                display: none;
            }
        }

        /* Fixed footer helper */
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
<body class="font-sans antialiased">
    <button id="hamburgerBtn" class="hamburger-btn" aria-label="Toggle sidebar" title="Buka menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="flex h-screen" id="dashboardWrapper">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg sidebar-panel" id="sidebarPanel">
            <div class="p-4 border-b border-green-600">
                <a href="{{ route('home') }}" class="flex items-center">
                    <i class="fas fa-book text-2xl text-yellow-300 mr-2"></i>
                    <span class="text-xl font-bold text-white">Ruang Aksara</span>
                </a>
            </div>
            
            <nav class="mt-6">
                <!-- User Info -->
                <div class="px-4 py-2 text-sm bg-green-600/20 mx-2 rounded-lg mb-4">
                    <p class="font-semibold text-white">Halo, {{ Auth::user()->name }}</p>
                    <p class="text-xs text-green-200">{{ Auth::user()->alamat ?? 'Alamat belum diisi' }}</p>
                    <div class="flex items-center mt-1">
                        <i class="fas fa-star text-yellow-400 text-xs mr-1"></i>
                        <span class="text-xs text-green-200">{{ $userPoints ?? 0 }} Points</span>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <div class="mt-4 space-y-1 px-4">
                    <a href="{{ route('home') }}" class="nav-link nav-active">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('books.index') }}" class="nav-link">
                        <i class="fas fa-book"></i>
                        <span>Katalog Buku</span>
                    </a>
                    <a href="{{ route('orders.index') }}" class="nav-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Order Saya</span>
                    </a>
                    <a href="{{ route('wishlists.index') }}" class="nav-link">
                        <i class="fas fa-heart"></i>
                        <span>Wishlist</span>
                    </a>
                    <a href="{{ route('loans.index') }}" class="nav-link">
                        <i class="fas fa-book-open"></i>
                        <span>Peminjaman</span>
                    </a>
                </div>

                <!-- Common Menu -->
                <div class="mt-8 pt-4 border-t border-green-500/30 px-4">
                    <a href="{{ route('help') }}" class="nav-link">
                        <i class="fas fa-question-circle"></i>
                        <span>Bantuan</span>
                    </a>
                    <a href="{{ route('profile') }}" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto p-6 content-panel" id="contentPanel">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button id="sidebarToggle" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded transition" title="Sembunyikan/Tampilkan menu">
                        <i class="fas fa-chevron-left text-lg"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard User</h1>
                        <p class="text-gray-600">Selamat datang di sistem Ruang Aksara</p>
                    </div>
                </div>
            </div>

            <!-- Stats Grid - HANYA 2 STATS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Buku Dibeli -->
                <div class="stat-card rounded-lg p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-80">Total Buku Dibeli</p>
                            <p class="text-3xl font-bold">{{ $totalBooksBought }}</p>
                        </div>
                        <div class="text-3xl opacity-80">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>

                <!-- Point Reward -->
                <div class="stat-card secondary rounded-lg p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-80">Point Reward</p>
                            <p class="text-3xl font-bold">{{ $userPoints }}</p>
                        </div>
                        <div class="text-3xl opacity-80">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Terbaru -->
            <div class="bg-content rounded-lg shadow border mb-8">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-clock text-blue-500 mr-2"></i>
                        Order Terbaru
                    </h3>
                </div>
                <div class="p-4">
                    @php
                        $ordersToDisplay = isset($recentOrders) ? $recentOrders : collect([]);
                        $grouped = $ordersToDisplay->groupBy(function($order) {
                            return $order->created_at->format('Y-m-d H:i');
                        });
                    @endphp
                    @if($grouped->count() > 0)
                        <div class="space-y-3">
                            @foreach($grouped->take(6) as $groupKey => $group)
                                @php 
                                    $first = $group->first();
                                    $totalItems = $first->items->count();
                                @endphp
                                <a href="{{ route('orders.show', $first->id) }}" class="block">
                                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100 hover:shadow">
                                    <div>
                                        <p class="font-medium text-gray-800">
                                            @foreach($first->items as $index => $item)
                                                @if($index < 1)
                                                    {{ $item->book?->judul ?? 'Buku tidak tersedia' }}
                                                @endif
                                            @endforeach
                                            @if($totalItems > 1)
                                                <span class="text-sm text-gray-500">(+{{ $totalItems - 1 }} lagi)</span>
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($groupKey)->format('d M Y') }} • 
                                            <span class="capitalize {{ $first->status == 'delivered' ? 'text-green-600' : 'text-yellow-600' }}">{{ $first->status }}</span>
                                        </p>
                                        @if($first->payment_method != 'cash')
                                            <p class="text-xs mt-1">
                                                <span class="inline-block px-2 py-1 rounded text-xs font-medium {{ ($first->payment_status ?? 'pending') == 'verified' ? 'bg-green-100 text-green-800' : (($first->payment_status ?? 'pending') == 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($first->payment_status ?? 'pending') }}</span>
                                            </p>
                                        @endif
                                    </div>
                                    <span class="px-3 py-1 bg-blue-500 text-white text-sm rounded-full">Rp {{ number_format($group->sum('total_price'), 0, ',', '.') }}</span>
                                </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada order</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Aksi Cepat -->
            <div class="bg-content rounded-lg shadow border">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        Aksi Cepat
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('books.index') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors border border-green-200">
                            <div class="p-3 bg-green-100 rounded-lg mb-2">
                                <i class="fas fa-book text-green-600 text-xl"></i>
                            </div>
                            <span class="font-medium text-gray-800 text-center">Katalog Buku</span>
                            <span class="text-xs text-gray-600 mt-1 text-center">Jelajahi koleksi</span>
                        </a>

                        <a href="{{ route('orders.index') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors border border-blue-200">
                            <div class="p-3 bg-blue-100 rounded-lg mb-2">
                                <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                            </div>
                            <span class="font-medium text-gray-800 text-center">Order Saya</span>
                            <span class="text-xs text-gray-600 mt-1 text-center">Lihat pesanan</span>
                        </a>

                        <a href="{{ route('wishlists.index') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors border border-purple-200">
                            <div class="p-3 bg-purple-100 rounded-lg mb-2">
                                <i class="fas fa-heart text-purple-600 text-xl"></i>
                            </div>
                            <span class="font-medium text-gray-800 text-center">Wishlist</span>
                            <span class="text-xs text-gray-600 mt-1 text-center">Buku favorit</span>
                        </a>

                        <a href="{{ route('profile') }}" class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors border border-orange-200">
                            <div class="p-3 bg-orange-100 rounded-lg mb-2">
                                <i class="fas fa-cog text-orange-600 text-xl"></i>
                            </div>
                            <span class="font-medium text-gray-800 text-center">Pengaturan</span>
                            <span class="text-xs text-gray-600 mt-1 text-center">Akun saya</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 mt-12 app-footer">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4">
                        <i class="fas fa-book mr-2"></i> Ruang Aksara
                    </h3>
                    <p class="text-gray-300 leading-relaxed">
                        Toko buku online terpercaya dengan koleksi buku terlengkap. 
                        Mari bersama-sama membangun budaya membaca yang lebih baik.
                    </p>
                </div>
                <div>
                    <h4 class="text-xl font-semibold mb-4">Kontak Kami</h4>
                    <p class="text-gray-300 mb-2">
                        <i class="fas fa-map-marker-alt mr-3"></i> Jl. Membaca No. 123, Yogyakarta
                    </p>
                    <p class="text-gray-300 mb-2">
                        <i class="fas fa-phone mr-3"></i> <a href="tel:+62274123456" class="text-gray-300 hover:text-white">(0274) 123-456</a>
                    </p>
                    <p class="text-gray-300 mb-2">
                        <i class="fas fa-envelope mr-3"></i> <a href="mailto:ruangg.aksara@gmail.com" class="text-gray-300 hover:text-white">ruangg.aksara@gmail.com</a>
                    </p>
                    <p class="text-gray-300">
                        <i class="fab fa-whatsapp mr-3"></i> <a href="https://wa.me/628123456789" target="_blank" class="text-gray-300 hover:text-white">+62 812-3456-789</a>
                    </p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Ruang Aksara. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        (function () {
            var sidebar = document.getElementById('sidebarPanel');
            var toggle = document.getElementById('sidebarToggle');
            var hamburger = document.getElementById('hamburgerBtn');
            var overlay = document.getElementById('sidebarOverlay');

            function setDesktopState(hidden) {
                if (!sidebar) return;
                if (hidden) {
                    sidebar.classList.add('collapsed-desktop');
                } else {
                    sidebar.classList.remove('collapsed-desktop');
                }

                if (toggle) {
                    toggle.innerHTML = hidden
                        ? '<i class="fas fa-chevron-right text-lg"><\/i>'
                        : '<i class="fas fa-chevron-left text-lg"><\/i>';
                }
            }

            var storedHidden = localStorage.getItem('sidebarHidden') === '1';
            setDesktopState(storedHidden);

            if (toggle) {
                toggle.addEventListener('click', function () {
                    if (!sidebar) return;
                    var willHide = !sidebar.classList.contains('collapsed-desktop');
                    setDesktopState(willHide);
                    localStorage.setItem('sidebarHidden', willHide ? '1' : '0');
                });
            }

            function closeMobileSidebar() {
                if (!sidebar) return;
                sidebar.classList.remove('mobile-open');
                if (overlay) {
                    overlay.classList.remove('show');
                }
                if (hamburger) {
                    hamburger.classList.remove('active');
                }
                document.body.style.overflow = 'auto';
            }

            function toggleMobileSidebar() {
                if (!sidebar) return;
                var isOpening = !sidebar.classList.contains('mobile-open');
                if (isOpening) {
                    sidebar.classList.add('mobile-open');
                    if (overlay) overlay.classList.add('show');
                    if (hamburger) hamburger.classList.add('active');
                    document.body.style.overflow = 'hidden';
                } else {
                    closeMobileSidebar();
                }
            }

            if (hamburger) {
                hamburger.addEventListener('click', toggleMobileSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', closeMobileSidebar);
            }

            window.addEventListener('resize', function () {
                if (window.innerWidth > 1024) {
                    closeMobileSidebar();
                }
            });
        })();
    </script>
</body>
</html>