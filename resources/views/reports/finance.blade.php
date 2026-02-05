@extends('layouts')

@section('content')

<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold">Laporan Keuangan</h1>
                <p class="text-sm opacity-70 mt-1">Analisis pengeluaran dan pembayaran perbaikan sarana prasarana</p>
            </div>
            @if(auth()->user()->hasRole(['admin', 'keuangan', 'lembaga']))
            <div>
                <a href="{{ route('reports.finance') }}?export=excel" class="btn btn-success">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Date Filter Card -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <form action="" method="GET">
                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Filter Tanggal</span>
                            </label>
                            <input type="text" name="date" id="i-date" 
                                   class="input input-bordered w-full" 
                                   value="{{request('date')}}" 
                                   placeholder="Pilih rentang tanggal">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if(request('date'))
                        <a href="{{ route('reports.finance') }}" class="btn btn-ghost">
                            Reset
                        </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Financial Statistics Cards -->
    @if(isset($statistics))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Expenses -->
        <div class="stats stats-vertical shadow-sm bg-base-100">
            <div class="stat-title">Total Pengeluaran</div>
            <div class="stat-value text-primary">Rp {{ number_format($statistics['total_expenses']) }}</div>
            <div class="stat-desc">{{ $statistics['total_items'] }} barang diperbaiki</div>
        </div>

        <!-- Average Cost -->
        <div class="stats stats-vertical shadow-sm bg-base-100">
            <div class="stat-title">Rata-rata Biaya</div>
            <div class="stat-value text-secondary">Rp {{ number_format($statistics['average_cost']) }}</div>
            <div class="stat-desc">Per barang</div>
        </div>

        <!-- Highest Cost -->
        <div class="stats stats-vertical shadow-sm bg-base-100">
            <div class="stat-title">Biaya Tertinggi</div>
            <div class="stat-value text-accent">Rp {{ number_format($statistics['highest_cost']) }}</div>
            <div class="stat-desc">Single expense</div>
        </div>

        <!-- Payment Status -->
        <div class="stats stats-vertical shadow-sm bg-base-100">
            <div class="stat-title">Pembayaran Selesai</div>
            <div class="stat-value text-success">{{ $statistics['paid_count'] }}</div>
            <div class="stat-desc">{{ $statistics['pending_count'] }} pending</div>
        </div>
    </div>
    @endif

    <!-- Charts Section -->
    @if(isset($monthlyTrends) && $monthlyTrends->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Monthly Cost Trend -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Trend Biaya Bulanan
                </h2>
                <div class="h-64">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Category Breakdown -->
        @if(isset($categoryBreakdown) && $categoryBreakdown->count() > 0)
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-lg mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    Breakdown per Lokasi
                </h2>
                <div class="h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Payment Tracking -->
    @if(isset($paymentTracking))
    <div class="card bg-base-100 shadow-sm mb-8">
        <div class="card-body">
            <h2 class="card-title mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Status Pembayaran
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-base-200 rounded-lg">
                    <div class="text-3xl font-bold">{{ $paymentTracking['total_invoices'] }}</div>
                    <div class="text-sm opacity-70">Total Invoice</div>
                </div>
                <div class="text-center p-4 bg-success/10 rounded-lg">
                    <div class="text-3xl font-bold text-success">{{ $paymentTracking['paid_invoices'] }}</div>
                    <div class="text-sm opacity-70">Sudah Dibayar</div>
                </div>
                <div class="text-center p-4 bg-warning/10 rounded-lg">
                    <div class="text-3xl font-bold text-warning">{{ $paymentTracking['pending_invoices'] }}</div>
                    <div class="text-sm opacity-70">Menunggu Pembayaran</div>
                </div>
                <div class="text-center p-4 bg-info/10 rounded-lg">
                    <div class="text-3xl font-bold text-info">{{ $paymentTracking['payment_rate'] }}%</div>
                    <div class="text-sm opacity-70">Tingkat Pembayaran</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Detailed Transactions Table -->
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                {!! $table !!}
            </div>
        </div>
    </div>

    <!-- Empty State -->
    @if(!isset($table) || strpos($table, 'No data') !== false)
    <div class="text-center py-16">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 mb-4">
            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold mb-2">Belum ada data keuangan</h3>
        <p class="opacity-70 max-w-md mx-auto">Data laporan keuangan akan muncul setelah ada perbaikan yang selesai diproses.</p>
    </div>
    @endif
</div>

@endsection

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Date Range Picker
    $("input[name='date']").daterangepicker({
        opens: "left",
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'YYYY/MM/DD',
            applyLabel: 'Terapkan',
            fromLabel: 'Dari',
            toLabel: 'Sampai',
            customRangeLabel: 'Custom'
        },
        ranges: {
            'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
            'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            '3 Bulan Terakhir': [moment().subtract(3, 'month'), moment()],
            '6 Bulan Terakhir': [moment().subtract(6, 'month'), moment()],
            'Tahun Ini': [moment().startOf('year'), moment().endOf('year')]
        }
    });

    $('input[name="date"]').on('apply.daterangepicker', function(e, picker){
        $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
        $(this).closest('form').trigger('submit');
    });

    $('input[name="date"]').on('cancel.daterangepicker', function(e){
        $(this).val('');
        $(this).closest('form').trigger('submit');
    });

    // Chart.js configurations
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    usePointStyle: true,
                    font: { size: 12, family: 'Inter' }
                }
            }
        }
    };

    // Monthly Trend Chart
    @if(isset($monthlyTrends) && $monthlyTrends->count() > 0)
    const monthlyTrendData = @json($monthlyTrends);
    
    new Chart(document.getElementById('monthlyTrendChart'), {
        type: 'line',
        data: {
            labels: monthlyTrendData.map(item => item.month_name),
            datasets: [{
                label: 'Total Biaya',
                data: monthlyTrendData.map(item => item.total_cost),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            ...chartDefaults,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
    @endif

    // Category Breakdown Chart
    @if(isset($categoryBreakdown) && $categoryBreakdown->count() > 0)
    const categoryData = @json($categoryBreakdown);
    
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: categoryData.map(item => item.location_name),
            datasets: [{
                data: categoryData.map(item => item.total_cost),
                backgroundColor: [
                    '#3b82f6', '#10b981', '#f59e0b', '#ef4444', 
                    '#8b5cf6', '#06b6d4', '#f97316', '#14b8a6'
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
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return context.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(value) + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endpush