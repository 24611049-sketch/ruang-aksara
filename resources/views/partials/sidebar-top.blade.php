@auth
    @if(auth()->user()->role === 'user')
    <!-- Toggle Button (fixed) for user when navbar hidden -->
    <button class="hamburger-btn" id="navbarToggleSidebar" onclick="toggleSidebar()" title="Toggle Sidebar" style="position: fixed; top: 12px; left: 12px; z-index: 1001;">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <!-- Sidebar Overlay removed (no dark shadow) -->

    <!-- Sidebar Navigation -->
    <div class="sidebar hidden" id="sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('home') }}">
                <i class="fas fa-book-open"></i>
                <span>Ruang Aksara</span>
            </a>
        </div>

        @auth
        <div class="sidebar-user-info">
            <p class="sidebar-user-name">{{ Auth::user()->name }}</p>
            <p class="sidebar-user-address">{{ Auth::user()->alamat ?? 'Alamat belum diisi' }}</p>
            <div class="sidebar-user-points">
                <i class="fas fa-star"></i>
                <span>{{ number_format(Auth::user()->points ?? 0) }} Points</span>
            </div>
        </div>
        @endauth

        <nav class="sidebar-nav">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('books.index') }}" class="nav-link {{ request()->routeIs('books.index') ? 'nav-active' : '' }}">
                <i class="fas fa-book"></i>
                <span>Katalog Buku</span>
            </a>
            <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.index') ? 'nav-active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Order Saya</span>
            </a>
            <a href="{{ route('wishlists.index') }}" class="nav-link {{ request()->routeIs('wishlists.index') ? 'nav-active' : '' }}">
                <i class="fas fa-heart"></i>
                <span>Wishlist</span>
            </a>
            <a href="{{ route('loans.index') }}" class="nav-link {{ request()->routeIs('loans.index') ? 'nav-active' : '' }}">
                <i class="fas fa-book-open"></i>
                <span>Peminjaman</span>
            </a>

            <div class="sidebar-divider"></div>

            <a href="{{ route('help') }}" class="nav-link {{ request()->routeIs('help*') ? 'nav-active' : '' }}">
                <i class="fas fa-question-circle"></i>
                <span>Bantuan</span>
            </a>
            <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'nav-active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
            
            @auth
            <form method="POST" action="{{ route('logout') }}" style="width: 100%; margin: 0;">
                @csrf
                <button type="submit" class="nav-link" style="margin-bottom: 0;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </button>
            </form>
            @endauth
        </nav>
    </div>
    @endif
@endauth

<!-- Main Wrapper (ALWAYS RENDERED) -->
<div class="main-wrapper" id="mainWrapper">

