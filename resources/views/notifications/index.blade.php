@extends('layouts')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>Notifikasi</h3>
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Daftar Notifikasi</h5>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <button onclick="markAllAsReadPage()" class="btn btn-sm btn-primary">
                            Tandai Semua Dibaca
                        </button>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                @php
                                    // Handle both JSON string and array data
                                    if (is_string($notification->data)) {
                                        $data = json_decode($notification->data, true);
                                    } else {
                                        $data = $notification->data;
                                    }
                                    
                                    $link = $data['link'] ?? '#';
                                    $eventIcon = getNotificationEmoji($data['event'] ?? '');
                                    $eventColor = getNotificationColor($data['event'] ?? '');
                                @endphp
                                <a href="{{ $link }}" 
                                   class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}"
                                   style="display: block; padding: 15px; border-bottom: 1px solid #f0f0f0; text-decoration: none; color: inherit;">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="flex-shrink-0">
                                            <span class="w-10 h-10 rounded-full bg-{{ $eventColor }}-100 text-{{ $eventColor }}-600 d-flex align-items-center justify-center font-semibold">
                                                {{ $eventIcon }}
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $data['event_name'] ?? 'Notifikasi' }}</h6>
                                                    <p class="mb-1 text-muted">{{ $data['message'] ?? '' }}</p>
                                                    @if(isset($data['problem_code']))
                                                        <small class="text-muted">Kode: {{ $data['problem_code'] }}</small>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    @if(!$notification->read_at)
                                                        <span class="badge bg-primary">Baru</span>
                                                    @endif
                                                    <small class="text-muted d-block">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        
                        <div class="p-3">
                            {{ $notifications->appends(['page' => request('page')])->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <svg style="width: 60px; height: 60px; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <h5 class="text-muted mt-3">Tidak Ada Notifikasi</h5>
                            <p class="text-muted">Anda belum memiliki notifikasi apa pun.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

function markAllAsReadPage() {
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
            location.reload();
        }
    });
}
</script>

@php
function getNotificationEmoji($event) {
    $emojis = [
        'problem_created' => '➕',
        'problem_submitted' => '📤',
        'problem_accepted' => '✅',
        'problem_finished' => '🔧',
        'problem_cancelled' => '❌',
        'problem_approved_management' => '🛡️',
        'problem_approved_admin' => '👤',
        'problem_approved_finance' => '💳'
    ];
    return $emojis[$event] ?? '🔔';
}

function getNotificationColor($event) {
    $colors = [
        'problem_created' => 'blue',
        'problem_submitted' => 'yellow',
        'problem_accepted' => 'green',
        'problem_finished' => 'blue',
        'problem_cancelled' => 'red',
        'problem_approved_management' => 'blue',
        'problem_approved_admin' => 'green',
        'problem_approved_finance' => 'blue'
    ];
    return $colors[$event] ?? 'gray';
}
@endphp
@endsection