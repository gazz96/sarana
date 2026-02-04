<!-- Notification Bell Component -->
<li class="nav-item dropdown notification-bell">
    <a class="nav-icon dropdown-toggle" href="#" id="notificationDropdown" 
       data-toggle="dropdown" aria-expanded="false"
       onclick="loadNotifications()">
        <div class="relative">
            <i class="align-middle text-body" data-lucide="bell"></i>
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
                <span class="indicator">
                    {{ $unreadCount }}
                </span>
            @endif
        </div>
    </a>
    
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0 notification-dropdown" 
         aria-labelledby="notificationDropdown"
         id="notificationList">
        <div class="dropdown-menu-header border-b border-gray-200 px-4 py-3">
            <div class="flex justify-between items-center">
                <span>Notifikasi</span>
                <a href="#" 
                   class="text-blue-600 hover:text-blue-800 text-sm"
                   onclick="markAllAsRead(event)">Tandai Semua Dibaca</a>
            </div>
        </div>
        <div class="notification-loading hidden">
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
function loadNotifications() {
    const notificationList = document.getElementById('notificationList');
    const loadingSpinner = notificationList.querySelector('.notification-loading');
    const notificationItems = notificationList.querySelector('.notification-items');
    
    // Show loading spinner
    loadingSpinner.classList.remove('d-none');
    
    fetch('{{ route('api.notifications.unread') }}')
        .then(response => response.json())
        .then(data => {
            loadingSpinner.classList.add('d-none');
            
            if (data.notifications && data.notifications.length > 0) {
                notificationItems.innerHTML = data.notifications.map(notification => `
                    <a href="${notification.data.link}" class="list-group-item ${notification.read_at ? '' : 'bg-light'}">
                        <div class="row g-0 align-items-center">
                            <div class="col-2">
                                <i class="text-${getNotificationColor(notification.data.event)}" data-lucide="${getNotificationIcon(notification.data.event)}"></i>
                            </div>
                            <div class="col-10">
                                <div class="text-dark">${notification.data.event_name}</div>
                                <div class="text-muted small mt-1">${notification.data.message}</div>
                                <div class="text-muted small mt-1">${formatTime(notification.created_at)}</div>
                            </div>
                        </div>
                    </a>
                `).join('');
                
                // Re-initialize lucide icons for new elements
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            } else {
                notificationItems.innerHTML = `
                    <div class="text-center py-3 text-muted">
                        <i class="d-block mb-2" data-lucide="bell-off" style="width: 40px; height: 40px; opacity: 0.5;"></i>
                        Tidak ada notifikasi baru
                    </div>
                `;
            }
        })
        .catch(error => {
            loadingSpinner.classList.add('d-none');
            notificationItems.innerHTML = `
                <div class="text-center py-3 text-danger">
                    <i class="d-block mb-2" data-lucide="alert-circle" style="width: 40px; height: 40px;"></i>
                    Gagal memuat notifikasi
                </div>
            `;
        });
}

function getNotificationIcon(event) {
    const icons = {
        'problem_created': 'plus-circle',
        'problem_submitted': 'send',
        'problem_accepted': 'check-circle',
        'problem_finished': 'tool',
        'problem_cancelled': 'x-circle',
        'problem_approved_management': 'shield',
        'problem_approved_admin': 'user-check',
        'problem_approved_finance': 'credit-card'
    };
    return icons[event] || 'bell';
}

function getNotificationColor(event) {
    const colors = {
        'problem_created': 'primary',
        'problem_submitted': 'warning',
        'problem_accepted': 'success',
        'problem_finished': 'info',
        'problem_cancelled': 'danger',
        'problem_approved_management': 'primary',
        'problem_approved_admin': 'success',
        'problem_approved_finance': 'info'
    };
    return colors[event] || 'secondary';
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
    fetch('{{ route('api.notifications.unread-count') }}')
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

// Auto-refresh notifications every 30 seconds
setInterval(() => {
    if (!document.hidden) {
        updateNotificationBadge();
    }
}, 30000);
</script>