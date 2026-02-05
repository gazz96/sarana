<!-- Notification Bell Component -->
<div class="notification-bell-wrapper" style="display: flex; align-items: center;">
    <a class="nav-icon" href="#" id="notificationDropdown" 
       role="button" 
       onclick="toggleNotificationDropdown(event)"
       style="display: flex; align-items: center; justify-content: center; padding: 0.5rem; text-decoration: none; color: inherit;">
        <div class="relative" style="display: flex; align-items: center; justify-content: center;">
            <!-- SVG Bell Icon -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #4b5563;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            @php
                $unreadCount = 0;
                try {
                    if (Auth::check() && Auth::user()->unreadNotifications) {
                        $unreadCount = Auth::user()->unreadNotifications->count();
                    }
                } catch (\Exception $e) {
                    $unreadCount = 0;
                }
            @endphp
            @if($unreadCount > 0)
                <span class="indicator" style="position: absolute; top: -6px; right: -6px; min-width: 18px; height: 18px; background: #ef4444; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 600; padding: 0 4px; border: 2px solid white;">
                    {{ $unreadCount }}
                </span>
            @endif
        </div>
    </a>
    
    <!-- Dropdown Menu -->
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0 notification-dropdown shadow-lg rounded-lg border" 
         style="display: none; position: absolute; right: 0; top: 100%; width: 350px; max-height: 400px; overflow-y: auto; z-index: 1000; background: white;"
         id="notificationList">
        <div class="dropdown-menu-header border-b border-gray-200 px-4 py-3 bg-gray-50">
            <div class="flex justify-between items-center">
                <span>Notifikasi</span>
                <a href="#" 
                   class="text-blue-600 hover:text-blue-800 text-sm"
                   onclick="markAllAsRead(event)">Tandai Semua Dibaca</a>
            </div>
        </div>
        <div class="notification-loading" style="display: none;">
            <div class="text-center py-3">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-600" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div class="list-group notification-items divide-y divide-gray-200">
            <!-- Notification items will be loaded here -->
        </div>
        <div class="dropdown-menu-footer">
            <a href="{{ route('notifications.index') }}" class="text-gray-600 hover:text-gray-800 block px-4 py-3 border-t border-gray-200">
                Lihat Semua Notifikasi
            </a>
        </div>
    </div>
</li>

<style>
    .notification-bell .indicator {
        position: absolute;
        top: 0;
        right: 0;
        width: 18px;
        height: 18px;
        background: #e52527;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 600;
    }
    
    .notification-dropdown {
        width: 350px;
        position: absolute;
        right: 0;
        margin-top: 0.5rem;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .notification-item {
        padding: 10px 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .notification-item:last-child {
        border-bottom: none;
    }
    
    .notification-item.unread {
        background-color: #f8f9fa;
    }
    
    .notification-content {
        font-size: 0.9rem;
    }
    
    .notification-time {
        font-size: 0.75rem;
        color: #6c757d;
    }
    
    .notification-link {
        color: #007bff;
        text-decoration: none;
    }
    
    .notification-link:hover {
        text-decoration: underline;
    }
</style>

<script>
// Toggle notification dropdown
function toggleNotificationDropdown(event) {
    event.preventDefault();
    event.stopPropagation();
    
    const dropdown = document.getElementById('notificationList');
    const isVisible = dropdown.style.display !== 'none';
    
    // Toggle dropdown visibility
    dropdown.style.display = isVisible ? 'none' : 'block';
    
    // Load notifications when opening
    if (!isVisible) {
        loadNotifications();
    }
}

// Load notifications via AJAX
function loadNotifications() {
    const notificationList = document.getElementById('notificationList');
    const loadingSpinner = notificationList.querySelector('.notification-loading');
    const notificationItems = notificationList.querySelector('.notification-items');
    
    // Show loading spinner
    if (loadingSpinner) {
        loadingSpinner.classList.remove('d-none');
        loadingSpinner.classList.remove('hidden');
        loadingSpinner.style.display = 'block';
    }
    
    fetch('{{ route('notifications.unread') }}')
        .then(response => {
            console.log('Notifications API response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Notifications data received:', data);
            if (loadingSpinner) {
                loadingSpinner.classList.add('d-none');
                loadingSpinner.classList.add('hidden');
            }
            
            if (data.notifications && data.notifications.length > 0) {
                notificationItems.innerHTML = data.notifications.map(notification => {
                    // Safely extract notification data
                    const notifData = notification.data || {};
                    const eventName = notifData.event_name || 'Notifikasi';
                    const message = notifData.message || 'Update status problem';
                    const link = notifData.link || '#';
                    const event = notifData.event || 'default';
                    
                    return `
                    <a href="${link}" class="list-group-item ${notification.read_at ? '' : 'bg-light'}" style="display: block; padding: 10px 15px; border-bottom: 1px solid #f0f0f0; text-decoration: none; color: inherit;">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <span class="w-8 h-8 rounded-full bg-${getNotificationColor(event)}-100 text-${getNotificationColor(event)}-600 flex items-center justify-center">
                                    ${getNotificationEmoji(event)}
                                </span>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-sm">${eventName}</div>
                                <div class="text-xs text-gray-600 mt-1">${message}</div>
                                <div class="text-xs text-gray-400 mt-1">${formatTime(notification.created_at)}</div>
                            </div>
                        </div>
                    </a>
                    `;
                }).join('');
            } else {
                notificationItems.innerHTML = `
                    <div class="text-center py-3 text-muted">
                        <svg class="d-block mx-auto mb-2" style="width: 40px; height: 40px; opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Tidak ada notifikasi baru
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            
            if (loadingSpinner) {
                loadingSpinner.classList.add('d-none');
                loadingSpinner.classList.add('hidden');
                loadingSpinner.style.display = 'none';
            }
            
            notificationItems.innerHTML = `
                <div class="text-center py-3 text-danger">
                    <svg class="d-block mx-auto mb-2" style="width: 40px; height: 40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Gagal memuat notifikasi
                    <small class="d-block mt-2 text-muted">${error.message || 'Unknown error'}</small>
                </div>
            `;
        })
        .finally(() => {
            // Always hide loading spinner
            if (loadingSpinner) {
                loadingSpinner.classList.add('d-none');
                loadingSpinner.classList.add('hidden');
                loadingSpinner.style.display = 'none';
            }
        });
}

function getNotificationEmoji(event) {
    const emojis = {
        'problem_created': '➕',
        'problem_submitted': '📤',
        'problem_accepted': '✅',
        'problem_finished': '🔧',
        'problem_cancelled': '❌',
        'problem_approved_management': '🛡️',
        'problem_approved_admin': '👤',
        'problem_approved_finance': '💳'
    };
    return emojis[event] || '🔔';
}

function getNotificationColor(event) {
    const colors = {
        'problem_created': 'blue',
        'problem_submitted': 'yellow',
        'problem_accepted': 'green',
        'problem_finished': 'blue',
        'problem_cancelled': 'red',
        'problem_approved_management': 'blue',
        'problem_approved_admin': 'green',
        'problem_approved_finance': 'blue'
    };
    return colors[event] || 'gray';
}

function markAsRead(event, notificationId) {
    event.preventDefault();
    event.stopPropagation();
    
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.remove('unread');
            }
            
            // Update badge count
            updateNotificationBadge();
        }
    });
}

function markAllAsRead(event) {
    event.preventDefault();
    
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove unread class from all items
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
            });
            
            // Update badge count
            updateNotificationBadge();
        }
    });
}

function updateNotificationBadge() {
    fetch('{{ route('notifications.unreadCount') }}')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification-bell .indicator');
            if (badge) {
                if (data.unread_count > 0) {
                    badge.textContent = data.unread_count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        });
}

function formatTime(dateTime) {
    const date = new Date(dateTime);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 60) {
        return `${diffMins} menit yang lalu`;
    } else if (diffHours < 24) {
        return `${diffHours} jam yang lalu`;
    } else if (diffDays < 7) {
        return `${diffDays} hari yang lalu`;
    } else {
        return date.toLocaleDateString('id-ID');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notificationList');
    const toggle = document.getElementById('notificationDropdown');
    
    if (dropdown && toggle && !dropdown.contains(event.target) && !toggle.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});

// Auto-refresh notifications every 30 seconds
setInterval(() => {
    if (!document.hidden) {
        updateNotificationBadge();
    }
}, 30000);
</script>