<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| TEST Routes (Remove in production!)
|--------------------------------------------------------------------------
|
| Temporary routes for testing notification system without authentication
| WARNING: Remove this file before deploying to production!
|
*/

Route::get('/test-notifications', function(){
    return response()->json([
        'message' => 'Test route is working!',
        'status' => 'success',
        'routes' => [
            'notifications_unread' => route('api.notifications.unread'),
            'notifications_unread_count' => route('api.notifications.unread-count'),
        ]
    ]);
});

Route::get('/test-notification-bell', function(){
    $user = \App\Models\User::first();
    
    if (!$user) {
        return response()->json(['error' => 'No users found'], 404);
    }

    // Create a test notification
    $notification = $user->notifications()->create([
        'id' => \Illuminate\Support\Str::uuid(),
        'type' => 'App\\Notifications\\WorkflowNotification',
        'data' => [
            'event' => 'problem_created',
            'event_name' => 'Problem Baru Dibuat',
            'message' => 'Ini adalah test notification untuk sarana.test',
            'problem_code' => 'TEST-001',
            'problem_issue' => 'Test problem description',
            'problem_status' => 0,
            'link' => url('/problems/1'),
            'timestamp' => now()->toDateTimeString()
        ],
        'read_at' => null,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return response()->json([
        'message' => 'Test notification created!',
        'notification' => $notification,
        'user' => $user->name,
        'unread_count' => $user->unreadNotifications->count()
    ]);
});

Route::get('/test-clear-notifications', function(){
    $user = \App\Models\User::first();
    
    if (!$user) {
        return response()->json(['error' => 'No users found'], 404);
    }

    $deleted = $user->notifications()->delete();
    
    return response()->json([
        'message' => "Cleared {$deleted} notifications for user: {$user->name}"
    ]);
});