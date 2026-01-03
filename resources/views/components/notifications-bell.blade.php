@auth
@if(auth()->user()->role === 'user')
<div class="relative" id="notificationDropdown" style="position: relative; z-index: 100;">
    <!-- Bell Icon Button -->
    <button id="notificationButton" 
            type="button"
            class="relative text-white transition hover:opacity-80" 
            style="background: transparent; cursor: pointer; border: none; padding: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 24px;"
            title="Notifikasi">
        <i class="fas fa-bell"></i>
        <span id="notificationBadge" 
              class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold"
              style="display: none; min-width: 20px; min-height: 20px; font-size: 11px;">
            <span id="notificationCount">0</span>
        </span>
    </button>

    <!-- Notification Dropdown Panel -->
    <div id="notificationPanel" 
         class="hidden absolute right-0 bg-white rounded-lg shadow-2xl max-h-52 overflow-y-auto"
         style="z-index: 9999; position: absolute; right: 0; top: calc(100% + 10px); width: 320px; min-width: 320px; border: 1px solid #e5e7eb;">
        <!-- Notifications List -->
        <div id="notificationsList" class="divide-y">
            <!-- Notifications will be loaded here -->
            <div class="p-4 text-center text-gray-500">
                <p><i class="fas fa-inbox text-3xl mb-2"></i></p>
                <p>Tidak ada notifikasi</p>
            </div>
        </div>

        <!-- View All Button -->
        <div class="p-4 border-t text-center">
            <a href="{{ route('notifications.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                Lihat Semua Notifikasi
            </a>
        </div>
    </div>
</div>

<script>
// Toggle notification panel
function toggleNotifications() {
    console.log('toggleNotifications called');
    const panel = document.getElementById('notificationPanel');
    if (!panel) {
        console.error('notificationPanel element not found');
        return;
    }
    panel.classList.toggle('hidden');
    console.log('Panel hidden:', panel.classList.contains('hidden'));
}

// Set up button click handler
document.addEventListener('DOMContentLoaded', function() {
    const button = document.getElementById('notificationButton');
    const panel = document.getElementById('notificationPanel');
    const dropdown = document.getElementById('notificationDropdown');
    
    if (button) {
        console.log('Notification button found, attaching click listener');
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Button clicked!');
            toggleNotifications();
        });
    } else {
        console.error('notificationButton element not found');
    }
    
    // Close when clicking anywhere outside
    document.addEventListener('click', function(e) {
        if (dropdown && panel && !dropdown.contains(e.target)) {
            panel.classList.add('hidden');
            console.log('Panel closed - clicked outside');
        }
    });
});

// Fetch and display notifications
async function loadNotifications() {
    try {
        console.log('Loading notifications...');
        const response = await fetch('{{ route("api.notifications") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) throw new Error('Failed to fetch notifications');
        
        const data = await response.json();
        const notifications = data.notifications || [];
        const unreadCount = data.unreadCount || 0;
        
        console.log('Notifications loaded:', notifications);
        
        // Update badge
        const badge = document.getElementById('notificationBadge');
        const countEl = document.getElementById('notificationCount');
        if (unreadCount > 0) {
            badge.style.display = 'flex';
            countEl.textContent = unreadCount > 99 ? '99+' : unreadCount;
        } else {
            badge.style.display = 'none';
        }
        
        // Update notifications list
        const list = document.getElementById('notificationsList');
        if (notifications.length === 0) {
            list.innerHTML = `
                <div class="p-4 text-center text-gray-500">
                    <p><i class="fas fa-inbox text-3xl mb-2"></i></p>
                    <p>Tidak ada notifikasi</p>
                </div>
            `;
        } else {
            list.innerHTML = notifications.map(notif => `
                <div class="p-4 hover:bg-gray-50 cursor-pointer transition border-b">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            ${getNotificationIcon(notif.type)}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">${notif.title}</p>
                            <p class="text-sm text-gray-600 mt-1">${notif.message}</p>
                            <p class="text-xs text-gray-400 mt-2"><i class="fas fa-clock mr-1"></i>${notif.timeAgo}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
        const list = document.getElementById('notificationsList');
        list.innerHTML = `
            <div class="p-4 text-center text-red-500">
                <p><i class="fas fa-exclamation-circle text-lg mb-2"></i></p>
                <p class="text-sm">Gagal memuat notifikasi</p>
            </div>
        `;
    }
}

// Get notification icon based on type
function getNotificationIcon(type) {
    const icons = {
        'book_added': '<i class="fas fa-book text-blue-500 text-lg"></i>',
        'payment_success': '<i class="fas fa-check-circle text-green-500 text-lg"></i>',
        'order_processing': '<i class="fas fa-hourglass-start text-yellow-500 text-lg"></i>',
        'order_shipped': '<i class="fas fa-truck text-purple-500 text-lg"></i>',
        'order_delivered': '<i class="fas fa-box-open text-green-500 text-lg"></i>'
    };
    return icons[type] || '<i class="fas fa-bell text-gray-500 text-lg"></i>';
}

// Load notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded - starting loadNotifications');
    loadNotifications();
    // Refresh every 30 seconds
    setInterval(loadNotifications, 30000);
});
</script>

<style>
#notificationPanel::-webkit-scrollbar {
    width: 6px;
}

#notificationPanel::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#notificationPanel::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

#notificationPanel::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endif
@endauth
