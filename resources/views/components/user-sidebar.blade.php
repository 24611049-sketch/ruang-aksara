<!-- Hamburger Menu Button -->
<button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()" title="Toggle Menu">
    <span></span>
    <span></span>
    <span></span>
</button>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

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
        <a href="{{ route('home') }}" class="sidebar-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('books.index') }}" class="sidebar-nav-link {{ request()->routeIs('books.index') ? 'active' : '' }}">
            <i class="fas fa-book"></i>
            <span>Katalog Buku</span>
        </a>
        <a href="{{ route('orders.index') }}" class="sidebar-nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i>
            <span>Order Saya</span>
        </a>
        <a href="{{ route('wishlists.index') }}" class="sidebar-nav-link {{ request()->routeIs('wishlists.index') ? 'active' : '' }}">
            <i class="fas fa-heart"></i>
            <span>Wishlist</span>
        </a>
        <a href="{{ route('loans.index') }}" class="sidebar-nav-link {{ request()->routeIs('loans.index') ? 'active' : '' }}">
            <i class="fas fa-book-open"></i>
            <span>Peminjaman</span>
        </a>

        <div class="sidebar-divider"></div>

        <a href="{{ route('help') }}" class="sidebar-nav-link {{ request()->routeIs('help*') ? 'active' : '' }}">
            <i class="fas fa-question-circle"></i>
            <span>Bantuan</span>
        </a>
        <a href="{{ route('profile') }}" class="sidebar-nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span>Pengaturan</span>
        </a>
    </nav>

    @auth
    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
            @csrf
            <button type="submit" class="sidebar-logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
    @endauth
</div>

<style>
    /* SIDEBAR STYLES */
    .hamburger-btn {
        position: fixed;
        top: 15px;
        left: 15px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%);
        border: none;
        border-radius: 0.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        cursor: pointer;
        z-index: 1000;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .hamburger-btn:hover {
        background: linear-gradient(135deg, #1e3e2a 0%, #2d5a3d 100%);
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
    }

    .hamburger-btn span {
        display: block;
        width: 24px;
        height: 2.5px;
        background: #a3e635;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .hamburger-btn.active span:nth-child(1) {
        transform: rotate(45deg) translate(8px, 8px);
    }

    .hamburger-btn.active span:nth-child(2) {
        opacity: 0;
    }

    .hamburger-btn.active span:nth-child(3) {
        transform: rotate(-45deg) translate(8px, -8px);
    }

    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 280px;
        height: 100vh;
        background: linear-gradient(180deg, #2d5a3d 0%, #1e3e2a 100%);
        box-shadow: 2px 0 12px rgba(0, 0, 0, 0.3);
        z-index: 999;
        overflow-y: auto;
        transition: transform 0.3s ease;
        padding-top: 60px;
    }

    .sidebar.hidden {
        transform: translateX(-100%);
    }

    .sidebar-brand {
        padding: 1.5rem;
        border-bottom: 2px solid rgba(163, 230, 53, 0.3);
        margin-bottom: 1.5rem;
    }

    .sidebar-brand a {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #a3e635;
        text-decoration: none;
        font-size: 1.25rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .sidebar-brand a:hover {
        color: #e8f87f;
        transform: translateX(4px);
    }

    .sidebar-brand i {
        font-size: 1.5rem;
    }

    .sidebar-user-info {
        padding: 1rem 1.5rem;
        background: rgba(163, 230, 53, 0.15);
        border-radius: 0.5rem;
        margin: 0 1rem 1.5rem 1rem;
    }

    .sidebar-user-name {
        font-weight: 600;
        color: #e8f6ec;
        font-size: 0.9rem;
        margin: 0;
    }

    .sidebar-user-address {
        font-size: 0.8rem;
        color: #a3e635;
        margin-top: 0.25rem;
        margin: 0.25rem 0 0 0;
    }

    .sidebar-user-points {
        display: flex;
        align-items: center;
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: #e8f6ec;
    }

    .sidebar-user-points i {
        color: #fbbf24;
        margin-right: 0.5rem;
    }

    .sidebar-nav {
        list-style: none;
        padding: 0 1rem;
    }

    .sidebar-nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1.25rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }

    .sidebar-nav-link i {
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
        color: #a3e635;
    }

    .sidebar-nav-link:hover {
        background: rgba(163, 230, 53, 0.15);
        color: #ffffff;
        padding-left: 1.5rem;
    }

    .sidebar-nav-link.active {
        background: rgba(163, 230, 53, 0.25);
        color: #a3e635;
        border-left: 3px solid #a3e635;
        padding-left: 1.22rem;
    }

    .sidebar-divider {
        height: 1px;
        background: rgba(163, 230, 53, 0.2);
        margin: 1rem 0.5rem;
    }

    .sidebar-logout {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 280px;
        padding: 1rem;
        border-top: 2px solid rgba(163, 230, 53, 0.3);
        background: linear-gradient(180deg, rgba(30, 62, 42, 0.5) 0%, rgba(20, 40, 28, 0.8) 100%);
    }

    .sidebar-logout-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1.25rem;
        background: #ef4444;
        color: white;
        text-decoration: none;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
        text-align: left;
        border: none;
        cursor: pointer;
    }

    .sidebar-logout-btn:hover {
        background: #dc2626;
        transform: translateX(-4px);
    }

    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 998;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .sidebar-overlay.show {
        opacity: 1;
        pointer-events: auto;
    }

    .main-wrapper {
        flex: 1;
        margin-left: 0;
        transition: margin-left 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .main-wrapper.sidebar-open {
        margin-left: 280px;
    }

    body {
        display: flex;
        flex-direction: column;
    }

    @media (max-width: 768px) {
        .sidebar-logout {
            width: 280px;
        }

        .hamburger-btn {
            position: fixed;
            top: 15px;
            left: 15px;
        }
    }
</style>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const hamburger = document.getElementById('hamburgerBtn');
        const mainWrapper = document.getElementById('mainWrapper');

        sidebar.classList.toggle('hidden');
        overlay.classList.toggle('show');
        hamburger.classList.toggle('active');

        // For desktop, also toggle main-wrapper margin
        if (window.innerWidth > 768) {
            mainWrapper.classList.toggle('sidebar-open');
        }

        // Prevent body scroll when sidebar is open
        if (!sidebar.classList.contains('hidden')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    }

    // Close sidebar when clicking on a link
    document.querySelectorAll('.sidebar-nav-link').forEach(link => {
        link.addEventListener('click', () => {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar.classList.contains('hidden')) {
                // Only close on mobile
                if (window.innerWidth <= 768) {
                    toggleSidebar();
                }
            }
        });
    });

    // Handle responsive behavior
    window.addEventListener('resize', () => {
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        
        if (window.innerWidth > 768) {
            // Desktop - keep sidebar visible
            sidebar.classList.remove('hidden');
            mainWrapper.classList.add('sidebar-open');
            document.body.style.overflow = 'auto';
        } else {
            // Mobile - hide sidebar by default
            if (!sidebar.classList.contains('hidden')) {
                sidebar.classList.add('hidden');
                document.getElementById('hamburgerBtn').classList.remove('active');
                document.getElementById('sidebarOverlay').classList.remove('show');
                document.body.style.overflow = 'auto';
            }
            mainWrapper.classList.remove('sidebar-open');
        }
    });

    // Initialize sidebar state on page load
    window.addEventListener('load', () => {
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        
        if (window.innerWidth > 768) {
            sidebar.classList.remove('hidden');
            mainWrapper.classList.add('sidebar-open');
        } else {
            sidebar.classList.add('hidden');
            mainWrapper.classList.remove('sidebar-open');
        }
    });
</script>
