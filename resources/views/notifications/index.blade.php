<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Notifikasi - Ruang Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
<style>
    body {
        background: 
            linear-gradient(rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.15)),
            url('/images/background.jpg') center/cover fixed no-repeat !important;
        background-size: cover !important;
        background-position: center !important;
        background-attachment: fixed !important;
        min-height: 100vh !important;
    }
    
    .notif-header {
        background: linear-gradient(135deg, #2d5a3d 0%, #1e3e2a 100%);
    }
    
    .notif-card {
        background-color: rgba(255, 255, 255, 0.92) !important;
        backdrop-filter: blur(8px);
        border-left: 4px solid #059669;
        transition: all 0.3s ease;
    }
    
    .notif-card:hover {
        transform: translateX(4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .notif-card.book-added {
        border-left-color: #3b82f6;
    }
    
    .notif-card.payment-success {
        border-left-color: #10b981;
    }
    
    .notif-card.order-processing {
        border-left-color: #f59e0b;
    }
    
    .notif-card.order-shipped {
        border-left-color: #8b5cf6;
    }
    
    .notif-card.order-delivered {
        border-left-color: #06b6d4;
    }
    
    .notif-empty {
        background-color: rgba(255, 255, 255, 0.92) !important;
        backdrop-filter: blur(8px);
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
            <div class="mx-auto max-w-4xl">
                <!-- Header -->
                <div class="notif-header text-white rounded-2xl p-8 mb-8 shadow-lg">
                    <h1 class="text-4xl font-bold mb-2">
                        <i class="fas fa-bell text-yellow-300 mr-3"></i>Notifikasi
                    </h1>
                    <p class="text-green-100">Pantau update buku baru dan status pesanan Anda</p>
                </div>

                <!-- Notifications Container -->
                <div class="space-y-4">
                    <div id="notificationsContainer">
                        <div class="text-center py-12 notif-empty rounded-2xl">
                            <i class="fas fa-spinner fa-spin text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600">Memuat notifikasi...</p>
                        </div>
                    </div>
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
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                const response = await fetch('{{ route("api.notifications") }}?full=1', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                const notifications = data.notifications || [];
                
                const container = document.getElementById('notificationsContainer');
                
                if (notifications.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-12 notif-empty rounded-2xl shadow">
                            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada notifikasi</h3>
                            <p class="text-gray-600">Anda akan menerima notifikasi ketika ada buku baru atau update pesanan</p>
                        </div>
                    `;
                } else {
                    container.innerHTML = notifications.map(notif => {
                        const typeClass = notif.type.replace(/_/g, '-');
                        return `
                            <div class="notif-card ${typeClass} rounded-xl shadow hover:shadow-md transition p-6">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 text-3xl">
                                        ${getNotificationEmoji(notif.type)}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-bold text-gray-900">${notif.title}</h3>
                                        <p class="text-gray-700 mt-2">${notif.message}</p>
                                        <p class="text-sm text-gray-500 mt-3">
                                            <i class="fas fa-clock mr-2"></i>${notif.timeAgo}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
                document.getElementById('notificationsContainer').innerHTML = `
                    <div class="text-center py-12 notif-empty rounded-2xl shadow">
                        <i class="fas fa-exclamation-circle text-6xl text-red-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Gagal memuat notifikasi</h3>
                        <p class="text-gray-600">Silakan refresh halaman untuk coba lagi</p>
                    </div>
                `;
            }
        });

        function getNotificationEmoji(type) {
            const emojis = {
                'book_added': '📚',
                'payment_success': '✅',
                'order_processing': '⚙️',
                'order_shipped': '🚚',
                'order_delivered': '📦',
                'order_pending': '⏳',
                'order_cancelled': '❌',
                'loan_overdue': '⚠️',
                'loan_reminder': '🔔',
                'loan_returned': '✅'
            };
            return emojis[type] || '🔔';
        }
    </script>
</body>
</html>
