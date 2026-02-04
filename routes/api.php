<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// For testing with current session authentication
Route::middleware('web')->group(function(){
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    // Notification API routes - use web middleware for session auth
    Route::prefix('notifications')->group(function(){
        Route::get('/unread', [NotificationController::class, 'unread'])
            ->name('api.notifications.unread');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])
            ->name('api.notifications.unread-count');
    });
});
