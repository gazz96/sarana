<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== CREATE TEST NOTIFICATIONS ===\n\n";

// Get first user
$user = User::first();
if (!$user) {
    echo "❌ No users found! Please create a user first.\n";
    exit;
}

echo "✅ User: {$user->name} ({$user->email})\n";

// Create test notifications
// Get actual problem ID that exists
$problem = \App\Models\Problem::latest()->first();
$problemId = $problem ? $problem->id : 1;
$problemCode = $problem ? $problem->code : 'PRB-TEST';

$notifications = [
    [
        'event' => 'problem_created',
        'event_name' => 'Problem Baru Dibuat',
        'message' => "Problem {$problemCode} telah dibuat",
        'problem_code' => $problemCode,
        'problem_id' => $problemId,
        'link' => url("/problems/{$problemId}")
    ],
    [
        'event' => 'problem_submitted',
        'event_name' => 'Problem Diajukan',
        'message' => "Problem {$problemCode} telah diajukan dan menunggu penanganan teknisi",
        'problem_code' => $problemCode,
        'problem_id' => $problemId,
        'link' => url("/problems/{$problemId}")
    ],
    [
        'event' => 'problem_accepted',
        'event_name' => 'Problem Diterima Teknisi',
        'message' => "Problem {$problemCode} telah diterima oleh teknisi",
        'problem_code' => $problemCode,
        'problem_id' => $problemId,
        'link' => url("/problems/{$problemId}")
    ],
    [
        'event' => 'problem_finished',
        'event_name' => 'Problem Selesai Dikerjakan',
        'message' => "Problem {$problemCode} telah selesai dikerjakan",
        'problem_code' => $problemCode,
        'problem_id' => $problemId,
        'link' => url("/problems/{$problemId}")
    ]
];

foreach ($notifications as $notifData) {
    $notification = $user->notifications()->create([
        'id' => Illuminate\Support\Str::uuid(),
        'type' => 'App\Notifications\WorkflowNotification',
        'data' => json_encode($notifData),
        'read_at' => null,
    ]);
    echo "✅ Created: {$notifData['event_name']}\n";
}

// Check results
$totalNotifications = $user->notifications()->count();
$unreadNotifications = $user->unreadNotifications()->count();

echo "\n📊 SUMMARY:\n";
echo "Total notifications: {$totalNotifications}\n";
echo "Unread notifications: {$unreadNotifications}\n";

echo "\n🎯 TEST URLS:\n";
echo "Dashboard: " . url('/dashboard') . "\n";
echo "Notifications API: " . url('/api/notifications/unread') . "\n";
echo "Notifications Page: " . url('/notifications') . "\n";

echo "\n✅ Test notifications created successfully!\n";
?>