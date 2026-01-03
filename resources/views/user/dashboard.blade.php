<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard User - Ruang Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <style>
        body {
            background:
                linear-gradient(rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.15)),
                url('/images/background.jpg') center/cover fixed no-repeat !important;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .content-card {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-radius: 1.5rem;
            box-shadow: 0 14px 36px rgba(31, 124, 69, 0.12);
        }

        .stat-card {
            border-radius: 1.25rem;
            box-shadow: 0 12px 24px rgba(31, 124, 69, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 32px rgba(31, 124, 69, 0.14);
        }

        .quick-link {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            align-items: flex-start;
            padding: 1.5rem;
            border-radius: 1.25rem;
            background: linear-gradient(145deg, rgba(236, 253, 245, 0.95), rgba(209, 250, 229, 0.7));
            color: #047857;
            font-weight: 600;
            text-decoration: none;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45), 0 12px 20px rgba(16, 185, 129, 0.12);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .quick-link i {
            font-size: 1.4rem;
            color: #059669;
        }

        .quick-link:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 28px rgba(16, 185, 129, 0.18);
            color: #065f46;
        }

        .book-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.75rem;
            min-height: 420px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            color: #1f2937;
            transform: scale(var(--book-scale, 1));
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            box-shadow: 0 18px 40px rgba(31, 41, 55, 0.18);
        }

        .book-card:hover {
            transform: scale(calc(var(--book-scale, 1) + 0.02));
            box-shadow: 0 24px 56px rgba(31, 41, 55, 0.24);
        }

        /* Rank badge (reused across views) */
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
            z-index: 60;
            text-align: center;
        }
        .rank-badge .rank-emoji { font-size: 1.1rem; line-height:1; }
        .rank-badge .rank-num { display:block; font-size:0.85rem; margin-top:2px; }
        .rank-badge.rank-1 { width:56px; height:56px; background: radial-gradient(circle at 30% 30%, #FFD700, #FFA500); border:3px solid #FFD700; }
        .rank-badge.rank-2 { width:48px; height:48px; background: linear-gradient(135deg, #E8E8E8, #C0C0C0); border:2px solid #D3D3D3; color:#333; }
        .rank-badge.rank-3 { width:48px; height:48px; background: linear-gradient(135deg, #E8A76A, #CD7F32); border:2px solid #D4956E; }
        .rank-badge.rank-other { width:40px; height:40px; background: linear-gradient(135deg, #6B7280, #9CA3AF); }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            background: rgba(15, 118, 110, 0.28);
            color: #0f766e;
            font-size: 0.75rem;
            font-weight: 600;
            backdrop-filter: blur(4px);
        }

        .glass-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            background: radial-gradient(circle at 20% 20%, rgba(34, 197, 94, 0.08), transparent 40%),
                        radial-gradient(circle at 80% 0%, rgba(16, 185, 129, 0.08), transparent 30%),
                        rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(16, 185, 129, 0.16);
            box-shadow: 0 18px 42px rgba(16, 185, 129, 0.12);
            border-radius: 1.5rem;
            padding: 1.25rem 1.5rem;
            backdrop-filter: blur(12px);
        }

        .profile-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1.1rem;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(22, 163, 74, 0.12), rgba(22, 163, 74, 0.02));
            border: 1px solid rgba(16, 185, 129, 0.2);
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .profile-chip:hover {
            background: linear-gradient(135deg, rgba(22, 163, 74, 0.18), rgba(22, 163, 74, 0.08));
            border-color: rgba(16, 185, 129, 0.3);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.12);
        }

        .profile-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 800;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 20px rgba(34, 197, 94, 0.25);
            overflow: hidden;
            object-fit: cover;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .quick-actions {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .action-pill {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.16), rgba(16, 185, 129, 0.08));
            color: #065f46;
            font-weight: 600;
            border: 1px solid rgba(16, 185, 129, 0.18);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
        }

        .action-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(16, 185, 129, 0.18);
        }

        .action-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 999px;
            background: linear-gradient(135deg, #f59e0b, #f97316);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 800;
            display: grid;
            place-items: center;
            box-shadow: 0 8px 16px rgba(249, 115, 22, 0.25);
        }

        .notif-panel {
            position: absolute;
            top: calc(100% + 0.75rem);
            right: 0;
            width: min(24rem, 90vw);
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 18px 48px rgba(31, 41, 55, 0.16);
            border: 1px solid rgba(16, 185, 129, 0.15);
            overflow: hidden;
            z-index: 1200;
        }

        .notif-item {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 0.75rem;
            padding: 0.95rem 1.1rem;
            align-items: center;
            transition: background 0.15s ease;
        }

        .notif-item:hover {
            background: rgba(16, 185, 129, 0.06);
        }

        .notif-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.16), rgba(16, 185, 129, 0.08));
            color: #0f766e;
        }

        .pill-link {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.65rem 1rem;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.12), rgba(59, 130, 246, 0.06));
            border: 1px solid rgba(59, 130, 246, 0.24);
            color: #1d4ed8;
            font-weight: 600;
            text-decoration: none;
        }

        .pill-link:hover {
            box-shadow: 0 10px 22px rgba(59, 130, 246, 0.18);
            transform: translateY(-2px);
        }

        .floating-dots {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 30%, rgba(34, 197, 94, 0.12), transparent 35%),
                        radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.08), transparent 28%);
            pointer-events: none;
            z-index: 0;
        }

        .soft-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.96), rgba(236, 253, 245, 0.9));
            border: 1px solid rgba(16, 185, 129, 0.12);
            box-shadow: 0 20px 40px rgba(31, 41, 55, 0.12);
        }

        .soft-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 20%, rgba(16, 185, 129, 0.1), transparent 35%),
                        radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.12), transparent 45%);
            opacity: 0.7;
            pointer-events: none;
        }

        .soft-card > * {
            position: relative;
            z-index: 1;
        }

        .glow-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            background: rgba(16, 185, 129, 0.12);
            color: #0f766e;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .pulse-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #22c55e;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.6);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.55); }
            70% { box-shadow: 0 0 0 14px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
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
                <div class="glass-nav">
                    <a href="{{ route('profile') }}" class="profile-chip hover:shadow-md hover:scale-105 transition-all" title="Buka pengaturan profil">
                        <div class="profile-avatar" aria-hidden="true">
                            @if(Auth::user()->foto_profil)
                                <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Foto profil {{ Auth::user()->name }}" />
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            @endif
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-800">{{ Auth::user()->name }}</p>
                        </div>
                    </a>
                    <div class="quick-actions">
                        <a href="{{ route('orders.index') }}" class="pill-link" aria-label="Lihat pesanan aktif">
                            <i class="fas fa-receipt"></i>
                            Pesanan Saya
                        </a>
                        <a href="{{ route('cart.index') }}" class="action-pill" aria-label="Buka keranjang" id="cartButton">
                            <i class="fas fa-shopping-cart"></i>
                            Keranjang
                            <span class="action-badge hidden" id="cartCount">0</span>
                        </a>
                        <div class="relative">
                            <button type="button" class="action-pill" id="notifButton" aria-haspopup="true" aria-expanded="false" aria-controls="notifPanel">
                                <i class="fas fa-bell"></i>
                                Notifikasi
                                <span class="action-badge hidden" id="notifCount">0</span>
                            </button>
                            <div class="notif-panel hidden" id="notifPanel" role="region" aria-label="Notifikasi terbaru">
                                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-green-50 to-white">
                                    <div class="flex items-center gap-2 text-gray-800 font-semibold">
                                        <span class="pulse-dot"></span>
                                        Notifikasi Terbaru
                                    </div>
                                    <a href="{{ route('notifications.index') }}" class="text-sm font-semibold text-green-700 hover:text-green-800">Lihat semua</a>
                                </div>
                                <div id="notifList" class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                                    <div class="py-6 text-center text-gray-500 text-sm" id="notifEmpty">Belum ada notifikasi baru</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="rounded-3xl bg-gradient-to-r from-green-600 to-green-700 p-8 text-white shadow-xl">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                        <div class="space-y-3">
                            <p class="text-lg text-green-100">Selamat datang kembali</p>
                            <h2 class="text-4xl font-bold">{{ Auth::user()->name }}</h2>
                            <p class="text-lg text-green-100">Temukan buku favorit Anda dan mulai petualangan membaca.</p>
                        </div>
                        <div class="text-6xl md:text-7xl">🌟</div>
                    </div>
                </section>

                <section class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="stat-card bg-white border border-green-100 p-6">
                        <div class="flex items-center gap-4">
                            <div class="rounded-xl bg-blue-100 p-3">
                                <i class="fas fa-book text-xl text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Buku Dibeli</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $totalBooksBought ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card bg-white border border-green-100 p-6">
                        <div class="flex items-center gap-4">
                            <div class="rounded-xl bg-green-100 p-3">
                                <i class="fas fa-shopping-cart text-xl text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Order Aktif</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $activeOrders ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card bg-white border border-green-100 p-6">
                        <div class="flex items-center gap-4">
                            <div class="rounded-xl bg-yellow-100 p-3">
                                <i class="fas fa-star text-xl text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Point Reward</p>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($userPoints ?? 0) }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Akses Cepat section removed -->

                <section class="content-card">
                    <div class="flex items-center justify-between border-b border-green-100 px-8 py-6">
                        <h2 class="text-2xl font-semibold text-gray-800">🔥 Buku Terpopuler</h2>
                    </div>
                    <div class="p-8">
                        @if($popularBooks->count() > 0)
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                                @foreach($popularBooks as $idx => $book)
                                    @php
                                        $rank = $idx + 1;
                                        $cover = $book->image_url ?? asset('images/default-book-cover.svg');
                                        $isTop3 = $rank <= 3;
                                        $statusLabel = $book->status ?? 'available';
                                    @endphp
                                    <div class="book-card" style="--book-scale: {{ $isTop3 ? '1.02' : '1' }}; background-image: linear-gradient(rgba(255,255,255,0.04), rgba(255,255,255,0.04)), url('{{ $cover }}'); background-size: cover; background-position: center;">
                                        @php
                                            $cls = 'rank-other';
                                            if ($rank == 1) $cls = 'rank-1';
                                            elseif ($rank == 2) $cls = 'rank-2';
                                            elseif ($rank == 3) $cls = 'rank-3';
                                        @endphp
                                        <div class="rank-badge {{ $cls }}" aria-label="Peringkat {{ $rank }}">
                                            @if($rank == 1)
                                                <span class="rank-emoji">👑</span>
                                                <span class="rank-num">{{ $rank }}</span>
                                            @elseif($rank == 2)
                                                <span class="rank-emoji">🥈</span>
                                                <span class="rank-num">{{ $rank }}</span>
                                            @elseif($rank == 3)
                                                <span class="rank-emoji">🥉</span>
                                                <span class="rank-num">{{ $rank }}</span>
                                            @else
                                                <span class="rank-emoji">#</span>
                                                <span class="rank-num">{{ $rank }}</span>
                                            @endif
                                        </div>

                                        <div class="absolute top-3 right-3 status-pill">
                                            <i class="fas fa-circle-notch fa-spin text-xs"></i>
                                            <span>{{ ucfirst($statusLabel) }}</span>
                                        </div>

                                        <div style="backdrop-filter: blur(6px); background: linear-gradient(180deg, rgba(0,0,0,0.05), rgba(255,255,255,0.6));">
                                            <div class="relative flex h-[260px] w-full items-center justify-center">
                                                <img src="{{ $cover }}" alt="{{ $book->judul }}" class="object-contain" style="max-height:220px; max-width:70%; box-shadow:0 10px 30px rgba(0,0,0,0.12); border-radius:4px; background: rgba(255,255,255,0.6); padding:6px;">

                                                <div class="absolute bottom-3 left-3" style="background: rgba(0,0,0,0.55); backdrop-filter: blur(4px); color:#ffffff; font-size:0.75rem; padding:0.35rem 0.8rem; border-radius:0.75rem;">
                                                    <i class="fas fa-shopping-bag mr-1"></i>{{ $book->purchase_count ?? 0 }} terjual
                                                </div>
                                            </div>
                                            <div class="space-y-3 bg-white/95 p-5">
                                                <div>
                                                    <h3 class="truncate text-lg font-bold text-gray-800">{{ $book->judul }}</h3>
                                                    <p class="truncate text-sm text-gray-600">oleh {{ $book->penulis }}</p>
                                                </div>
                                                <div class="flex items-center justify-between text-sm text-gray-500">
                                                    <span class="text-lg font-bold text-green-600">Rp {{ number_format($book->harga, 0, ',', '.') }}</span>
                                                    <span>{{ $book->halaman }} halaman</span>
                                                </div>
                                                <form action="{{ route('cart.add.post', $book->id) }}" method="POST" class="space-y-2">
                                                    @csrf
                                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-green-500 to-green-600 py-2.5 font-semibold text-white transition hover:from-green-600 hover:to-green-700">
                                                        <i class="fas fa-cart-plus"></i>
                                                        Beli Sekarang
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="space-y-4 py-12 text-center">
                                <i class="fas fa-book-open text-6xl text-gray-300"></i>
                                <p class="text-lg text-gray-500">Tidak ada buku tersedia saat ini.</p>
                            </div>
                        @endif
                    </div>
                </section>
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
        document.addEventListener('DOMContentLoaded', () => {
            const cartCountEl = document.getElementById('cartCount');
            const notifCountEl = document.getElementById('notifCount');
            const notifPanel = document.getElementById('notifPanel');
            const notifButton = document.getElementById('notifButton');
            const notifList = document.getElementById('notifList');
            const notifEmpty = document.getElementById('notifEmpty');

            const updateCartCount = async () => {
                try {
                    const response = await fetch("{{ url('/cart/api/count') }}", { headers: { 'Accept': 'application/json' } });
                    if (!response.ok) throw new Error('Gagal memuat keranjang');
                    const data = await response.json();
                    const count = data.count ?? 0;
                    cartCountEl.textContent = count > 9 ? '9+' : count;
                    cartCountEl.classList.toggle('hidden', count === 0);
                } catch (error) {
                    console.warn('Cart count error', error);
                }
            };

            const renderNotifications = (notifications = []) => {
                notifList.innerHTML = '';

                if (!notifications.length) {
                    notifEmpty.classList.remove('hidden');
                    notifList.appendChild(notifEmpty);
                    return;
                }

                notifEmpty.classList.add('hidden');

                notifications.forEach((notif) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'notif-item';

                    const icon = document.createElement('div');
                    icon.className = 'notif-icon';
                    let iconHTML = '<i class="fas fa-bell"></i>';
                    if (notif.type === 'book_added') iconHTML = '<i class="fas fa-book"></i>';
                    else if (notif.type.startsWith('order_')) iconHTML = '<i class="fas fa-box"></i>';
                    else if (notif.type === 'loan_overdue') iconHTML = '<i class="fas fa-exclamation-triangle"></i>';
                    else if (notif.type === 'loan_reminder') iconHTML = '<i class="fas fa-clock"></i>';
                    else if (notif.type === 'loan_returned') iconHTML = '<i class="fas fa-check-circle"></i>';
                    icon.innerHTML = iconHTML;

                    const body = document.createElement('div');
                    body.className = 'space-y-1';
                    body.innerHTML = `<p class="font-semibold text-gray-800">${notif.title}</p><p class="text-sm text-gray-600">${notif.message}</p>`;

                    const time = document.createElement('span');
                    time.className = 'text-xs text-gray-500';
                    time.textContent = notif.timeAgo || '';

                    wrapper.appendChild(icon);
                    wrapper.appendChild(body);
                    wrapper.appendChild(time);

                    notifList.appendChild(wrapper);
                });
            };

            const markNotificationsAsRead = async (notificationIds) => {
                try {
                    await fetch("{{ route('api.notifications.markRead') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ notification_ids: notificationIds })
                    });
                } catch (error) {
                    console.warn('Mark as read error', error);
                }
            };

            const fetchNotifications = async () => {
                try {
                    notifList.innerHTML = '<div class="py-6 text-center text-gray-500 text-sm">Memuat notifikasi...</div>';
                    const response = await fetch("{{ route('api.notifications') }}", { headers: { 'Accept': 'application/json' } });
                    if (!response.ok) throw new Error('Gagal memuat notifikasi');
                    const data = await response.json();
                    const notifications = data.notifications || [];
                    
                    // Update badge count
                    const unreadCount = notifications.filter(n => !n.read_at).length;
                    notifCountEl.textContent = unreadCount > 9 ? '9+' : unreadCount;
                    notifCountEl.classList.toggle('hidden', unreadCount === 0);
                    
                    renderNotifications(notifications);
                } catch (error) {
                    notifList.innerHTML = '<div class="py-6 text-center text-red-500 text-sm">Tidak dapat memuat notifikasi.</div>';
                    console.warn('Notification error', error);
                }
            };

            notifButton?.addEventListener('click', async (e) => {
                e.stopPropagation();
                const willOpen = notifPanel.classList.contains('hidden');
                notifPanel.classList.toggle('hidden');
                notifButton.setAttribute('aria-expanded', willOpen);
                if (willOpen) {
                    await fetchNotifications();
                    // Mark all current notifications as read after opening
                    setTimeout(async () => {
                        const response = await fetch("{{ route('api.notifications') }}", { headers: { 'Accept': 'application/json' } });
                        if (response.ok) {
                            const data = await response.json();
                            const notificationIds = (data.notifications || []).map(n => n.id);
                            if (notificationIds.length > 0) {
                                await markNotificationsAsRead(notificationIds);
                                // Update badge after marking as read
                                setTimeout(() => fetchNotifications(), 500);
                            }
                        }
                    }, 1000);
                }
            });

            document.addEventListener('click', (e) => {
                if (notifPanel && !notifPanel.classList.contains('hidden')) {
                    const clickedInside = notifPanel.contains(e.target) || notifButton.contains(e.target);
                    if (!clickedInside) {
                        notifPanel.classList.add('hidden');
                        notifButton.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            updateCartCount();
            fetchNotifications();
            setInterval(fetchNotifications, 60000);
        });
    </script>
</body>
</html>