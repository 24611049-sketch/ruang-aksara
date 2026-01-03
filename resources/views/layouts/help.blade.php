<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ruang Aksara')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <style>
        body {
            background:
                linear-gradient(rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.15)),
                url('/images/background.jpg') center/cover fixed no-repeat !important;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

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
    </style>
    @stack('styles')
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
    @stack('scripts')
</body>
</html>
