@extends('layouts')

@section('title', 'Dashboard - SARANAS')

@section('content')

<div class="p-6">
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Dashboard</h1>
                <p class="text-sm text-gray-600">
                    Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>!
                </p>
            </div>
            @if(isset($statistics['quick_actions']['create_problem']))
            <div>
                <a href="{{ $statistics['quick_actions']['create_problem'] }}" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Laporan Baru
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Stats Cards - Modern Design like DashPro -->
    @if(isset($statistics['overview']))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- My Problems -->
        @if(isset($statistics['overview']['my_problems']))
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <div class="stats-icon bg-violet-700">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 text-sm font-semibold text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        12%
                    </div>
                </div>
                <div class="stats-value">{{ number_format($statistics['overview']['my_problems']) }}</div>
                <div class="stats-label">Laporan Saya</div>
            </div>
        </div>
        @endif

        <!-- Draft Problems -->
        @if(isset($statistics['overview']['draft_problems']))
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <div class="stats-icon bg-orange-500">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 text-sm font-semibold text-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                        8%
                    </div>
                </div>
                <div class="stats-value">{{ number_format($statistics['overview']['draft_problems']) }}</div>
                <div class="stats-label">Draft</div>
            </div>
        </div>
        @endif

        <!-- Pending Approval -->
        @if(isset($statistics['overview']['pending_approval']))
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <div class="stats-icon bg-blue-700">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 text-sm font-semibold text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        25%
                    </div>
                </div>
                <div class="stats-value">{{ number_format($statistics['overview']['pending_approval']) }}</div>
                <div class="stats-label">Menunggu Persetujuan</div>
            </div>
        </div>
        @endif

        <!-- Completed -->
        @if(isset($statistics['overview']['completed_problems']))
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <div class="stats-icon bg-green-600">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1 text-sm font-semibold text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        18%
                    </div>
                </div>
                <div class="stats-value">{{ number_format($statistics['overview']['completed_problems']) }}</div>
                <div class="stats-label">Selesai</div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Charts Section - Modern Design -->
    @if(isset($statistics['charts']))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- My Problem Status Chart -->
        @if(isset($statistics['charts']['my_problem_status']))
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 text-violet-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Status Laporan Saya
                </h2>
            </div>
            <div class="card-body">
                <div class="h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        <!-- Monthly Trend Chart -->
        @if(isset($statistics['charts']['monthly_my_problems']))
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 text-violet-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Trend Laporan Bulanan
                </h2>
            </div>
            <div class="card-body">
                <div class="h-64">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Recent Problems Table & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent My Problems -->
        @if(isset($statistics['recent_activities']) && !empty($statistics['recent_activities']))
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Laporan Terbaru Saya
                        </h2>
                        <div class="badge badge-primary">{{ count($statistics['recent_activities']) }}</div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Masalah</th>
                                    <th>Status</th>
                                    <th>Update Terakhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statistics['recent_activities'] as $activity)
                                <tr>
                                    <td>
                                        <strong class="text-violet-700">{{ $activity['code'] ?? 'N/A' }}</strong>
                                    </td>
                                    <td class="max-w-xs truncate">{{ $activity['issue'] ?? '-' }}</td>
                                    <td>
                                        @php $statusClass = getStatusClassNew($activity['status'] ?? 0) @endphp
                                        <div class="badge {{ $statusClass }}">
                                            {{ getStatusText($activity['status'] ?? 0) }}
                                        </div>
                                    </td>
                                    <td class="text-sm text-gray-500">{{ $activity['updated_at'] ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(!empty($statistics['recent_activities']) && count($statistics['recent_activities']) > 5)
                    <div class="p-4 text-center border-t">
                        <a href="{{ route('problems.index') }}" class="btn btn-secondary btn-sm">
                            Lihat Semua Laporan
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        @if(isset($statistics['quick_actions']))
        <div>
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Aksi Cepat
                    </h2>
                </div>
                <div class="card-body">
                    <div class="flex flex-col gap-2">
                        @foreach($statistics['quick_actions'] as $action => $url)
                        @php
                            $icon = getActionIcon($action);
                            $label = getActionLabel($action);
                            $btnClass = getActionButtonClassNew($action);
                        @endphp
                        <a href="{{ $url }}" class="sidebar-link">
                            <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                            </svg>
                            <span class="flex-1">{{ $label }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        @endforeach
                    </div>

                    <!-- Helpful Tips -->
                    <div class="mt-6 space-y-3">
                        <div class="alert alert-info">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs">
                                <span class="font-semibold">Draft:</span> Simpan laporan sebagai draft jika belum siap untuk disubmit
                            </div>
                        </div>
                        
                        <div class="alert alert-success">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs">
                                <span class="font-semibold">Tracking:</span> Pantau status laporan secara real-time
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Need Attention Section -->
    @if(isset($statistics['overview']['draft_problems']) && $statistics['overview']['draft_problems'] > 0)
    <div class="alert alert-warning">
        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div class="flex-1">
            <h3 class="font-semibold">Ada {{ $statistics['overview']['draft_problems'] }} Draft Laporan</h3>
            <div class="text-sm">Anda memiliki laporan draft yang belum disubmit. Segera lengkapi dan submit untuk diproses.</div>
        </div>
        <div>
            <a href="{{ route('problems.index') }}" class="btn btn-warning btn-sm">
                Lihat Draft
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statistics = @json($statistics);

    // Chart configuration for modern design
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    usePointStyle: true,
                    font: {
                        size: 12,
                        family: 'Poppins'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: { size: 13, family: 'Poppins', weight: '600' },
                bodyFont: { size: 12, family: 'Poppins' },
                cornerRadius: 8,
                displayColors: true
            }
        },
        animation: {
            duration: 1000,
            easing: 'easeInOutQuart'
        }
    };

    // Status Chart - Doughnut untuk status
    @if(isset($statistics['charts']['my_problem_status']))
    const statusData = statistics.charts.my_problem_status;
    if (statusData && Object.keys(statusData).length > 0) {
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData),
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: [
                        '#5417D7', // Primary purple
                        '#f59e0b', // Amber  
                        '#3b82f6', // Blue
                        '#22c55e', // Green
                        '#ef4444', // Red
                        '#8b5cf6', // Purple
                        '#06b6d4', // Cyan
                        '#f97316', // Orange
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                ...chartDefaults,
                cutout: '70%',
                plugins: {
                    ...chartDefaults.plugins,
                    tooltip: {
                        ...chartDefaults.plugins.tooltip,
                        callbacks: {
                            label: function(context) {
                                const total = Object.values(statusData).reduce((a, b) => a + b, 0);
                                const percentage = Math.round((context.parsed / total) * 100);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
    @endif

    // Trend Chart - Line untuk monthly
    @if(isset($statistics['charts']['monthly_my_problems']))
    const trendData = statistics.charts.monthly_my_problems;
    if (trendData && Object.keys(trendData).length > 0) {
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: Object.keys(trendData),
                datasets: [{
                    label: 'Laporan',
                    data: Object.values(trendData),
                    borderColor: '#5417D7',
                    backgroundColor: 'rgba(84, 23, 215, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#5417D7',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                ...chartDefaults,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            font: { family: 'Poppins' }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: 'Poppins' }
                        }
                    }
                }
            }
        });
    }
    @endif
});
</script>

@php
function getActionIcon($action) {
    $icons = [
        'create_problem' => 'M12 4v16m8-8H4',
        'my_problems' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        'inventory' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v18l-8-4m8 4v6',
        'locations' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',
        'my_jobs' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
    ];
    return $icons[$action] ?? 'M13 10V3L4 14h7v7l9-11h-7z';
}

function getActionLabel($action) {
    $labels = [
        'create_problem' => 'Buat Laporan Baru',
        'my_problems' => 'Laporan Saya',
        'inventory' => 'Lihat Inventaris',
        'locations' => 'Lihat Lokasi',
        'my_jobs' => 'Job Saya',
    ];
    return $labels[$action] ?? ucfirst(str_replace('_', ' ', $action));
}

function getActionButtonClassNew($action) {
    if ($action === 'create_problem') {
        return 'bg-violet-700 text-white hover:bg-violet-800';
    }
    return 'hover:bg-gray-50 text-gray-700';
}

function getStatusClassNew($status) {
    $classes = [
        0 => 'badge-secondary',   // Draft
        1 => 'badge-warning',     // Diajukan
        2 => 'badge-info',        // Proses
        3 => 'badge-success',     // Selesai
        4 => 'badge-danger',      // Dibatalkan
        5 => 'badge-primary',     // Waiting Mgmt
        6 => 'badge-info',        // Waiting Admin
        7 => 'badge-warning',     // Waiting Keuangan
    ];
    return $classes[$status] ?? 'badge-secondary';
}

function getStatusText($status) {
    $texts = [
        0 => 'DRAFT',
        1 => 'DIAJUKAN',
        2 => 'PROSES',
        3 => 'SELESAI',
        4 => 'DIBATALKAN',
        5 => 'WAITING MGMT',
        6 => 'WAITING ADMIN',
        7 => 'WAITING KEUANGAN',
    ];
    return $texts[$status] ?? 'UNKNOWN';
}
@endphp
@endpush