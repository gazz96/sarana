@extends('layouts')

@section('title', 'System Monitoring')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">System Monitoring Dashboard</h1>

    <!-- System Health -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">System Health</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="health-cards">
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-medium text-gray-700">Database</h3>
                <div id="database-status" class="mt-2">Loading...</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-medium text-gray-700">Cache</h3>
                <div id="cache-status" class="mt-2">Loading...</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-medium text-gray-700">Storage</h3>
                <div id="storage-status" class="mt-2">Loading...</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-medium text-gray-700">Memory</h3>
                <div id="memory-status" class="mt-2">Loading...</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-medium text-gray-700">Disk Space</h3>
                <div id="disk-status" class="mt-2">Loading...</div>
            </div>
        </div>
    </div>

    <!-- Error Statistics -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Error Statistics (Last 7 Days)</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-3xl font-bold text-red-600" id="total-errors">-</div>
                <div class="text-gray-600">Total Errors</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-3xl font-bold text-orange-600" id="errors-24h">-</div>
                <div class="text-gray-600">Last 24 Hours</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-3xl font-bold text-blue-600" id="error-types">-</div>
                <div class="text-gray-600">Error Types</div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Performance Metrics (Last 24 Hours)</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-green-600" id="avg-response">-</div>
                <div class="text-gray-600">Avg Response Time (ms)</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-red-600" id="slow-requests">-</div>
                <div class="text-gray-600">Slow Requests (>2s)</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-blue-600" id="total-requests">-</div>
                <div class="text-gray-600">Total Requests</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-purple-600" id="error-rate">-</div>
                <div class="text-gray-600">Error Rate</div>
            </div>
        </div>
    </div>

    <!-- Recent Errors -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Recent Errors</h2>
        <div class="bg-white rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="errors-table">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Recent User Activities</h2>
        <div class="bg-white rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="activities-table">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex space-x-4">
        <button onclick="refreshData()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Refresh Data
        </button>
        <button onclick="runHealthCheck()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Run Health Check
        </button>
    </div>
</div>

<script>
function refreshData() {
    loadHealth();
    loadErrors();
    loadActivities();
    loadStats();
}

function loadHealth() {
    fetch('/monitoring/health')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const health = data.data;
                
                // Update health cards
                updateHealthCard('database-status', health.database);
                updateHealthCard('cache-status', health.cache);
                updateHealthCard('storage-status', health.storage);
                updateHealthCard('memory-status', health.memory);
                updateHealthCard('disk-status', health.disk);
            }
        });
}

function updateHealthCard(elementId, health) {
    const element = document.getElementById(elementId);
    if (health.status === 'healthy') {
        element.innerHTML = '<span class="text-green-600 font-semibold">✓ Healthy</span>';
        if (health.response_time_ms) {
            element.innerHTML += `<div class="text-sm text-gray-600">${health.response_time_ms}ms</div>`;
        }
    } else if (health.status === 'warning') {
        element.innerHTML = '<span class="text-yellow-600 font-semibold">⚠ Warning</span>';
        if (health.usage_percent) {
            element.innerHTML += `<div class="text-sm text-gray-600">${health.usage_percent}% used</div>`;
        }
    } else {
        element.innerHTML = '<span class="text-red-600 font-semibold">✗ Unhealthy</span>';
        if (health.error) {
            element.innerHTML += `<div class="text-sm text-gray-600">${health.error}</div>`;
        }
    }
}

function loadErrors() {
    fetch('/monitoring/errors?limit=10')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('errors-table');
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No errors found</td></tr>';
                } else {
                    tbody.innerHTML = data.data.map(error => `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${new Date(error.created_at).toLocaleString()}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${error.type}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">${error.message}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${error.user_id || '-'}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">${error.file}:${error.line}</td>
                        </tr>
                    `).join('');
                }
            }
        });
}

function loadActivities() {
    fetch('/monitoring/activities?limit=10')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('activities-table');
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No activities found</td></tr>';
                } else {
                    tbody.innerHTML = data.data.map(activity => `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${new Date(activity.created_at).toLocaleString()}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${activity.name}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">${activity.action}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${activity.ip_address}</td>
                        </tr>
                    `).join('');
                }
            }
        });
}

function loadStats() {
    // Load error stats
    fetch('/monitoring/error-stats?days=7')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('total-errors').textContent = data.data.total_errors;
                document.getElementById('errors-24h').textContent = data.data.errors_last_24h;
                document.getElementById('error-types').textContent = data.data.errors_by_type.length;
            }
        });

    // Load performance metrics
    fetch('/monitoring/performance?hours=24')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('avg-response').textContent = Math.round(data.data.avg_response_time || 0);
                document.getElementById('slow-requests').textContent = data.data.slow_requests;
                document.getElementById('total-requests').textContent = data.data.total_requests;
                
                const errorRate = data.data.total_requests > 0 
                    ? ((data.data.slow_requests / data.data.total_requests) * 100).toFixed(1)
                    : 0;
                document.getElementById('error-rate').textContent = errorRate + '%';
            }
        });
}

function runHealthCheck() {
    fetch('/monitoring/health-check', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        alert('Health check completed!');
        refreshData();
    });
}

// Load data on page load
document.addEventListener('DOMContentLoaded', function() {
    refreshData();
    
    // Auto-refresh every 30 seconds
    setInterval(refreshData, 30000);
});
</script>
@endsection