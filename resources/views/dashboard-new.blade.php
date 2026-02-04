@extends('layouts')

@section('content')
<div class="container-fluid">
    <!-- Modern Dashboard Header -->
    <div class="mb-8 animate-fade-in">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex-1">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent mb-2">
                    Dashboard {{ ucfirst($role) }}
                </h1>
                <p class="text-gray-600 text-lg">
                    👋 Selamat datang, <span class="font-semibold text-primary-700">{{ Auth::user()->name }}</span>! 
                    Berikut overview sistem hari ini.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 bg-gradient-to-r from-primary-50 to-primary-100 rounded-xl border border-primary-200">
                    <span class="text-sm font-medium text-primary-700">{{ now()->format('l, d F Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Quick Stats Cards -->
    @if(isset($statistics['overview']))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($statistics['overview'] as $key => $value)
            @if(is_numeric($value) && $key !== 'id')
            <div class="stat-card animate-scale-in" style="animation-delay: {{ $loop->iteration * 0.1 }}s">
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <div class="stat-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                        <div class="stat-value">{{ number_format($value) }}</div>
                    </div>
                    <div class="stat-icon">
                        <span data-lucide="{{ getIconForStat($key) }}"></span>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    <!-- Modern Charts Section -->
    @if(isset($statistics['charts']))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Problems by Status Chart -->
        @if(isset($statistics['charts']['problems_by_status']) || isset($statistics['charts']['my_problem_status']) || isset($statistics['charts']['job_status']))
        <div class="chart-card animate-slide-in">
            <div class="card-header">
                <h5 class="text-lg font-semibold text-gray-800">{{ getChartTitle($role, 'status') }}</h5>
            </div>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
        @endif

        <!-- Monthly Trend Chart -->
        @if(isset($statistics['charts']['monthly_trend']) || isset($statistics['charts']['monthly_my_problems']) || isset($statistics['charts']['monthly_jobs']))
        <div class="chart-card animate-slide-in" style="animation-delay: 0.1s">
            <div class="card-header">
                <h5 class="text-lg font-semibold text-gray-800">{{ getChartTitle($role, 'trend') }}</h5>
            </div>
            <div class="chart-container">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
        @endif

        <!-- Additional Charts for Admin -->
        @if($role === 'admin' && isset($statistics['charts']['problems_by_location']))
        <div class="chart-card animate-slide-in">
            <div class="card-header">
                <h5 class="text-lg font-semibold text-gray-800">Problems by Location</h5>
            </div>
            <div class="chart-container">
                <canvas id="locationChart"></canvas>
            </div>
        </div>
        @endif

        @if($role === 'admin' && isset($statistics['charts']['top_damaged_goods']))
        <div class="chart-card animate-slide-in" style="animation-delay: 0.1s">
            <div class="card-header">
                <h5 class="text-lg font-semibold text-gray-800">Top Damaged Goods</h5>
            </div>
            <div class="chart-container">
                <canvas id="topDamagedChart"></canvas>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Modern Activity Feed & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Activities -->
        @if(isset($statistics['recent_activities']) && !empty($statistics['recent_activities']))
        <div class="lg:col-span-2">
            <div class="activity-card animate-slide-in">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h5 class="text-lg font-semibold text-gray-800">Recent Activities</h5>
                        <span class="badge badge-accent">{{ count($statistics['recent_activities']) }} Items</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Issue</th>
                                    <th>Status</th>
                                    <th>Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statistics['recent_activities'] as $activity)
                                <tr class="animate-fade-in" style="animation-delay: {{ $loop->iteration * 0.05 }}s">
                                    <td><strong class="text-primary-700">{{ $activity['code'] ?? 'N/A' }}</strong></td>
                                    <td class="text-truncate" style="max-width: 200px;">{{ $activity['issue'] ?? '' }}</td>
                                    <td>
                                        @php $statusClass = getStatusClass($activity['status'] ?? 0) @endphp
                                        <span class="badge badge-{{ $statusClass }}">{{ getStatusText($activity['status'] ?? 0) }}</span>
                                    </td>
                                    <td><small class="text-gray-500">{{ $activity['updated_at'] ?? '' }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        @if(isset($statistics['quick_actions']))
        <div>
            <div class="actions-card animate-slide-in" style="animation-delay: 0.2s">
                <div class="card-header">
                    <h5 class="text-lg font-semibold text-gray-800">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="grid gap-3">
                        @foreach($statistics['quick_actions'] as $action => $url)
                        <a href="{{ $url }}" class="action-btn">
                            <span data-lucide="{{ getIconForAction($action) }}"></span>
                            <span class="ml-2">{{ ucfirst(str_replace('_', ' ', $action)) }}</span>
                            <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Modern Pending Approvals/Payments for Management & Finance -->
    @if(isset($statistics['pending_approvals']) && !empty($statistics['pending_approvals']))
    <div class="animate-slide-in mb-8">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h5 class="text-lg font-semibold text-gray-800">Pending Approvals</h5>
                    <span class="badge badge-warning">{{ count($statistics['pending_approvals']) }} Pending</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Issue</th>
                                <th>Requester</th>
                                <th>Technician</th>
                                <th>Estimated Cost</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statistics['pending_approvals'] as $pending)
                            <tr>
                                <td><strong class="text-primary-700">{{ $pending['code'] }}</strong></td>
                                <td>{{ $pending['issue'] }}</td>
                                <td>{{ $pending['requester'] }}</td>
                                <td>{{ $pending['technician'] }}</td>
                                <td><span class="font-semibold text-gray-700">Rp {{ number_format($pending['estimated_cost'], 0, ',', '.') }}</span></td>
                                <td>{{ $pending['created_at'] }}</td>
                                <td>
                                    <a href="{{ route('problems.show', $pending['code']) }}" class="btn btn-primary text-sm">
                                        Review
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(isset($statistics['pending_payments']) && !empty($statistics['pending_payments']))
    <div class="animate-slide-in mb-8">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h5 class="text-lg font-semibold text-gray-800">Pending Payments</h5>
                    <span class="badge badge-success">{{ count($statistics['pending_payments']) }} Ready</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Issue</th>
                                <th>Technician</th>
                                <th>Total Cost</th>
                                <th>Approved Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statistics['pending_payments'] as $payment)
                            <tr>
                                <td><strong class="text-primary-700">{{ $payment['code'] }}</strong></td>
                                <td>{{ $payment['issue'] }}</td>
                                <td>{{ $payment['technician'] }}</td>
                                <td><span class="font-semibold text-gray-700">Rp {{ number_format($payment['total_cost'], 0, ',', '.') }}</span></td>
                                <td>{{ $payment['approved_date'] }}</td>
                                <td>
                                    <a href="{{ route('problems.show', $payment['code']) }}" class="btn btn-success text-sm">
                                        Process Payment
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const statistics = @json($statistics);
    const role = '{{ $role }}';

    // Modern Chart configuration
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    font: {
                        size: 13,
                        family: 'Inter'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14,
                    family: 'Inter'
                },
                bodyFont: {
                    size: 13,
                    family: 'Inter'
                },
                cornerRadius: 8,
                displayColors: true
            }
        },
        animation: {
            duration: 1000,
            easing: 'easeInOutQuart'
        }
    };

    // Status Chart
    @if(isset($statistics['charts']['problems_by_status']) || isset($statistics['charts']['my_problem_status']) || isset($statistics['charts']['job_status']))
    const statusData = statistics.charts.problems_by_status || statistics.charts.my_problem_status || statistics.charts.job_status;
    if (statusData && Object.keys(statusData).length > 0) {
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData),
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', 
                        '#8b5cf6', '#06b6d4', '#f97316', '#84cc16'
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
                                return context.label + ': ' + context.parsed + ' (' + Math.round((context.parsed / Object.values(statusData).reduce((a, b) => a + b, 0)) * 100)) + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
    @endif

    // Trend Chart
    @if(isset($statistics['charts']['monthly_trend']) || isset($statistics['charts']['monthly_my_problems']) || isset($statistics['charts']['monthly_jobs']))
    const trendData = statistics.charts.monthly_trend || statistics.charts.monthly_my_problems || statistics.charts.monthly_jobs;
    if (trendData && Object.keys(trendData).length > 0) {
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: Object.keys(trendData),
                datasets: [{
                    label: 'Problems',
                    data: Object.values(trendData),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#2563eb',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 3
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
                            font: {
                                family: 'Inter'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: 'Inter'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    @endif

    // Location Chart (Admin only)
    @if($role === 'admin' && isset($statistics['charts']['problems_by_location']))
    const locationData = statistics.charts.problems_by_location;
    if (locationData && Object.keys(locationData).length > 0) {
        new Chart(document.getElementById('locationChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(locationData),
                datasets: [{
                    label: 'Problems',
                    data: Object.values(locationData),
                    backgroundColor: '#10b981',
                    borderRadius: 8,
                    barThickness: 35,
                    hoverBackgroundColor: '#059669'
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
                            font: {
                                family: 'Inter'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: 'Inter'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    @endif

    // Top Damaged Goods Chart (Admin only)
    @if($role === 'admin' && isset($statistics['charts']['top_damaged_goods']))
    const topDamagedData = statistics.charts.top_damaged_goods;
    if (topDamagedData && Object.keys(topDamagedData).length > 0) {
        new Chart(document.getElementById('topDamagedChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(topDamagedData),
                datasets: [{
                    label: 'Damage Count',
                    data: Object.values(topDamagedData),
                    backgroundColor: '#ef4444',
                    borderRadius: 8,
                    barThickness: 30,
                    hoverBackgroundColor: '#dc2626'
                }]
            },
            options: {
                ...chartDefaults,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                family: 'Inter'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    y: {
                        ticks: {
                            font: {
                                family: 'Inter'
                            }
                        },
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
function getIconForStat($key) {
    $icons = [
        'total_goods' => 'list',
        'total_locations' => 'pin',
        'total_problems' => 'triangle-alert',
        'active_problems' => 'activity',
        'completed_problems' => 'check-circle',
        'total_users' => 'users',
        'my_problems' => 'file-text',
        'draft_problems' => 'file',
        'pending_jobs' => 'clock',
        'active_jobs' => 'tool',
        'completed_jobs' => 'check',
        'waiting_approval' => 'hourglass',
        'pending_approval' => 'clipboard',
        'approved_this_month' => 'calendar',
        'total_budget_approved' => 'dollar-sign',
        'pending_payments' => 'credit-card',
        'paid_this_month' => 'banknote',
        'total_expenditures' => 'trending-down',
    ];
    return $icons[$key] ?? 'bar-chart';
}

function getChartTitle($role, $type) {
    $titles = [
        'admin' => [
            'status' => 'Problems by Status',
            'trend' => 'Monthly Problem Trend',
        ],
        'guru' => [
            'status' => 'My Problems Status',
            'trend' => 'My Problems Monthly',
        ],
        'teknisi' => [
            'status' => 'Job Status',
            'trend' => 'Monthly Jobs',
        ],
        'lembaga' => [
            'status' => 'Approval Status',
            'trend' => 'Approval Trend',
        ],
        'keuangan' => [
            'status' => 'Payment Status',
            'trend' => 'Monthly Expenses',
        ],
    ];
    return $titles[$role][$type] ?? 'Chart';
}

function getIconForAction($action) {
    $icons = [
        'create_problem' => 'plus-circle',
        'my_problems' => 'list',
        'inventory' => 'package',
        'locations' => 'map-pin',
        'my_jobs' => 'wrench',
    ];
    return $icons[$action] ?? 'arrow-right';
}

function getStatusClass($status) {
    $classes = [
        0 => 'secondary',
        1 => 'warning',
        2 => 'info',
        3 => 'success',
        4 => 'danger',
        5 => 'primary',
        6 => 'primary',
        7 => 'primary',
    ];
    return $classes[$status] ?? 'secondary';
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
@endsection