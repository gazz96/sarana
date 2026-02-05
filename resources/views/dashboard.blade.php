@extends('layouts')

@section('content')

<div class="max-w-7xl mx-auto">
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold">Dashboard Guru</h1>
                <p class="text-sm opacity-70 mt-1">
                    Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>! 
                    Kelola laporan kerusakan sarana prasarana dengan mudah.
                </p>
            </div>
            @if(isset($statistics['quick_actions']['create_problem']))
            <div>
                <a href="{{ $statistics['quick_actions']['create_problem'] }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Laporan Baru
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Stats Cards - Fokus untuk Guru -->
    @if(isset($statistics['overview']))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- My Problems -->
        @if(isset($statistics['overview']['my_problems']))
        <div class="stats stats-vertical shadow-sm bg-base-100">
            <div class="stat-title">Laporan Saya</div>
            <div class="stat-value text-primary">{{ number_format($statistics['overview']['my_problems']) }}</div>
            <div class="stat-desc">Total laporan yang dibuat</div>
        </div>
        @endif

        <!-- Draft Problems -->
        @if(isset($statistics['overview']['draft_problems']))
        <div class="stats stats-vertical shadow-sm bg-base-100">
            <div class="stat-title">Draft</div>
            <div class="stat-value text-warning">{{ number_format($statistics['overview']['draft_problems']) }}</div>
            <div class="stat-desc">Menunggu untuk disubmit</div>
        </div>
        @endif

        <!-- Pending Approval -->
        @if(isset($statistics['overview']['pending_approval']))
        <div class="stats stats-vertical shadow-sm bg-base-100">
            <div class="stat-title">Menunggu Persetujuan</div>
            <div class="stat-value text-info">{{ number_format($statistics['overview']['pending_approval']) }}</div>
            <div class="stat-desc">Sedang diproses</div>
        </div>
        @endif

        <!-- Completed -->
        @if(isset($statistics['overview']['completed_problems']))
        <div class="stats stats-vertical shadow-sm bg-base-100">
            <div class="stat-title">Selesai</div>
            <div class="stat-value text-success">{{ number_format($statistics['overview']['completed_problems']) }}</div>
            <div class="stat-desc">Laporan selesai</div>
        </div>
        @endif
    </div>
    @endif

    <!-- Charts Section - Fokus untuk Problem Tracking -->
    @if(isset($statistics['charts']))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- My Problem Status Chart -->
        @if(isset($statistics['charts']['my_problem_status']))
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Status Laporan Saya
                </h2>
                <div class="h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        <!-- Monthly Trend Chart -->
        @if(isset($statistics['charts']['monthly_my_problems']))
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Trend Laporan Bulanan
                </h2>
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
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="card-title text-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Laporan Terbaru Saya
                        </h2>
                        <div class="badge badge-primary">{{ count($statistics['recent_activities']) }}</div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="table table-zebra table-xs">
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
                                        <strong class="text-primary">{{ $activity['code'] ?? 'N/A' }}</strong>
                                    </td>
                                    <td class="max-w-xs truncate">{{ $activity['issue'] ?? '-' }}</td>
                                    <td>
                                        @php $statusClass = getStatusClass($activity['status'] ?? 0) @endphp
                                        <div class="badge badge-{{ $statusClass }} gap-1">
                                            {{ getStatusText($activity['status'] ?? 0) }}
                                        </div>
                                    </td>
                                    <td class="text-xs opacity-70">{{ $activity['updated_at'] ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(!empty($statistics['recent_activities']) && count($statistics['recent_activities']) > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('problems.index') }}" class="btn btn-sm btn-outline">
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
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Aksi Cepat
                    </h2>
                    
                    <div class="flex flex-col gap-2">
                        @foreach($statistics['quick_actions'] as $action => $url)
                        @php
                            $icon = getActionIcon($action);
                            $label = getActionLabel($action);
                            $btnClass = getActionButtonClass($action);
                        @endphp
                        <a href="{{ $url }}" class="{{ $btnClass }} btn-sm justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                            </svg>
                            <span class="flex-1 text-left">{{ $label }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        @endforeach
                    </div>

                    <!-- Helpful Tips for Guru -->
                    <div class="divider">Tips & Info</div>
                    <div class="space-y-3">
                        <div class="alert alert-info">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs">
                                <span class="font-bold">Draft:</span> Simpan laporan sebagai draft jika belum siap untuk disubmit
                            </div>
                        </div>
                        
                        <div class="alert alert-success">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs">
                                <span class="font-bold">Tracking:</span> Pantau status laporan secara real-time
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
    <div class="alert alert-warning mb-8">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <h3 class="font-bold">Ada {{ $statistics['overview']['draft_problems'] }} Draft Laporan</h3>
            <div class="text-sm">Anda memiliki laporan draft yang belum disubmit. Segera lengkapi dan submit untuk diproses.</div>
        </div>
        <div>
            <a href="{{ route('problems.index') }}" class="btn btn-sm btn-warning">
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statistics = @json($statistics);

    // daisyUI Chart configuration
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
                        family: 'Inter'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 10,
                titleFont: { size: 13, family: 'Inter' },
                bodyFont: { size: 12, family: 'Inter' },
                cornerRadius: 6,
                displayColors: true
            }
        },
        animation: {
            duration: 800,
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
                        '#64748b', // Draft - slate
                        '#f59e0b', // Diajukan - amber  
                        '#3b82f6', // Proses - blue
                        '#22c55e', // Selesai - green
                        '#ef4444', // Dibatalkan - red
                        '#8b5cf6', // Waiting Mgmt - purple
                        '#06b6d4', // Waiting Admin - cyan
                        '#f97316', // Waiting Keuangan - orange
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                ...chartDefaults,
                cutout: '65%',
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
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#3b82f6',
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
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
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

function getActionButtonClass($action) {
    $classes = [
        'create_problem' => 'btn btn-primary',
        'my_problems' => 'btn btn-outline btn-primary',
        'inventory' => 'btn btn-outline',
        'locations' => 'btn btn-outline',
        'my_jobs' => 'btn btn-outline',
    ];
    return $classes[$action] ?? 'btn btn-outline';
}

function getStatusClass($status) {
    $classes = [
        0 => 'neutral',     // Draft
        1 => 'warning',     // Diajukan
        2 => 'info',        // Proses
        3 => 'success',     // Selesai
        4 => 'error',       // Dibatalkan
        5 => 'secondary',   // Waiting Mgmt
        6 => 'primary',     // Waiting Admin
        7 => 'accent',      // Waiting Keuangan
    ];
    return $classes[$status] ?? 'neutral';
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