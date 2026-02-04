<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function unread()
    {
        if (!Auth::check()) {
            return response()->json([
                'notifications' => [],
                'unread_count' => 0,
                'authenticated' => false
            ]);
        }
        
        $notifications = $this->notificationService->getUnreadNotifications(Auth::user());
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unreadNotifications->count(),
            'authenticated' => true
        ]);
    }

    public function markAsRead($id)
    {
        $success = $this->notificationService->markAsRead(Auth::user(), $id);
        
        return response()->json([
            'success' => $success,
            'message' => $success ? 'Notification marked as read' : 'Failed to mark as read'
        ]);
    }

    public function markAllAsRead()
    {
        $success = $this->notificationService->markAllAsRead(Auth::user());
        
        return response()->json([
            'success' => $success,
            'message' => $success ? 'All notifications marked as read' : 'Failed to mark all as read'
        ]);
    }

    public function unreadCount()
    {
        if (!Auth::check()) {
            return response()->json([
                'unread_count' => 0,
                'authenticated' => false
            ]);
        }
        
        $count = Auth::user()->unreadNotifications->count();
        
        return response()->json([
            'unread_count' => $count,
            'authenticated' => true
        ]);
    }
}