@extends('layouts')

@section('title', 'Database Backup Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Database Backup Management</h1>

    <!-- Backup Statistics -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Backup Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-3xl font-bold text-blue-600">{{ $stats['total_backups'] }}</div>
                <div class="text-gray-600">Total Backups</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-3xl font-bold text-green-600">{{ $stats['total_size_mb'] }} MB</div>
                <div class="text-gray-600">Total Storage Used</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-3xl font-bold text-purple-600">{{ $stats['daily_backups'] }}</div>
                <div class="text-gray-600">Daily Backups</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-3xl font-bold text-orange-600">{{ $stats['weekly_backups'] }}</div>
                <div class="text-gray-600">Weekly Backups</div>
            </div>
        </div>
    </div>

    <!-- Backup Actions -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Backup Actions</h2>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex flex-wrap gap-4">
                <form method="POST" action="{{ route('backup.create') }}" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="daily">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Create Daily Backup
                    </button>
                </form>
                <form method="POST" action="{{ route('backup.create') }}" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="weekly">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Create Weekly Backup
                    </button>
                </form>
                <form method="POST" action="{{ route('backup.create') }}" class="inline">
                    @csrf
                    <input type="hidden" name="type" value="monthly">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                        Create Monthly Backup
                    </button>
                </form>
            </div>
            
            @if($stats['latest_backup'])
                <div class="mt-4 text-gray-700">
                    <strong>Latest Backup:</strong> {{ $stats['latest_backup']['file_name'] }}
                    ({{ $stats['latest_backup']['file_size_mb'] }} MB - {{ $stats['latest_backup']['created_at']->diffForHumans() }})
                </div>
            @endif
        </div>
    </div>

    <!-- Scheduled Backup Information -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Scheduled Backups</h2>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <h3 class="font-semibold text-gray-700">Daily Backups</h3>
                    <p class="text-gray-600">Every day at 2:00 AM</p>
                    <p class="text-sm text-gray-500">Retention: 7 days</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-700">Weekly Backups</h3>
                    <p class="text-gray-600">Every Sunday at 2:00 AM</p>
                    <p class="text-sm text-gray-500">Retention: 30 days</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-700">Monthly Backups</h3>
                    <p class="text-gray-600">1st of every month at 2:00 AM</p>
                    <p class="text-sm text-gray-500">Retention: 365 days</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Backups -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Available Backups</h2>
        <div class="bg-white rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($backups as $backup)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $backup['file_name'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($backup['type'] == 'daily') bg-blue-100 text-blue-800
                                        @elseif($backup['type'] == 'weekly') bg-green-100 text-green-800
                                        @elseif($backup['type'] == 'monthly') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($backup['type']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $backup['file_size_mb'] }} MB
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $backup['created_at']->format('Y-m-d H:i:s') }}
                                    <div class="text-xs text-gray-500">{{ $backup['created_at']->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('backup.download', $backup['file_name']) }}" 
                                       class="text-blue-600 hover:text-blue-900">Download</a>
                                    
                                    <form method="POST" action="{{ route('backup.test') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="file_name" value="{{ $backup['file_name'] }}">
                                        <button type="submit" class="text-green-600 hover:text-green-900"
                                                onclick="return confirm('Test integrity of this backup?')">Test</button>
                                    </form>
                                    
                                    <button onclick="confirmRestore('{{ $backup['file_name'] }}')" 
                                            class="text-orange-600 hover:text-orange-900">Restore</button>
                                    
                                    <form method="DELETE" action="{{ route('backup.delete') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="file_name" value="{{ $backup['file_name'] }}">
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Are you sure you want to delete this backup?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No backups available. Create your first backup using the buttons above.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Backup Logs -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Recent Backup Logs</h2>
        <div class="bg-white rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Error</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                    {{ $log->file_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ucfirst($log->backup_type) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($log->status == 'completed')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Success</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Failed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ $log->error_message ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No backup logs available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Restore Confirmation Modal -->
<div id="restoreModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-orange-100">
                <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Confirm Database Restore</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to restore the database? This will replace all current data!
                </p>
                <p class="text-sm text-red-600 mt-2 font-semibold">
                    Backup: <span id="backupFileName"></span>
                </p>
            </div>
            <form method="POST" action="{{ route('backup.restore') }}">
                @csrf
                <input type="hidden" name="file_name" id="restoreFileName">
                <input type="hidden" name="confirm" value="1">
                <div class="items-center px-4 py-3">
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-orange-700 focus:outline-none">
                        Yes, Restore Database
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 focus:outline-none">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmRestore(fileName) {
    document.getElementById('backupFileName').textContent = fileName;
    document.getElementById('restoreFileName').value = fileName;
    document.getElementById('restoreModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('restoreModal').classList.add('hidden');
}

// Close modal on outside click
document.getElementById('restoreModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection