@auth
@if(auth()->user()->role === 'user')
    <!-- Unified user sidebar toggle -->
    <button id="hamburgerBtn" class="hamburger-btn" type="button" title="Toggle Sidebar" aria-controls="sidebar" aria-expanded="false" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay" role="presentation"></div>

    <!-- Sidebar Navigation -->
    <aside class="sidebar hidden" id="sidebar" aria-label="Menu pengguna">
        @include('partials.user-sidebar-content')
    </aside>

    @once
        @push('scripts')
            <script src="{{ asset('js/sidebar.js') }}" defer></script>
        @endpush
    @endonce
@endif
@endauth

