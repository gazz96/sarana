<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoUploadController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('auth.index'));
});


Route::prefix('auth')->group(function(){
    Route::get('/', [AuthController::class, 'index'])->name('auth.index');
    Route::post('/', [AuthController::class, 'login'])->name('login')->middleware('rate.limit:5,1'); // 5 attempts per minute
});

Route::middleware('auth')->group(function(){

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('users', UserController::class);
    Route::resource('goods', GoodController::class);
    Route::resource('locations', LocationController::class);

    // Notification routes
    Route::prefix('notifications')->group(function(){
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
        Route::post('/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    });

    Route::prefix('photos')->group(function(){
        Route::post('/upload', [PhotoUploadController::class, 'upload'])->name('photos.upload')->middleware('rate.limit:20,1'); // 20 uploads per minute
        Route::delete('/delete', [PhotoUploadController::class, 'delete'])->name('photos.delete')->middleware('rate.limit:30,1');
        Route::get('/list/{problem_item_id}', [PhotoUploadController::class, 'list'])->name('photos.list');
    });

    Route::get('problems/submit/{problem}/management-approval', [ProblemController::class, 'managementApproval'])->name('problems.management-approval');
    Route::get('problems/submit/{problem}', [ProblemController::class, 'submitProblem'])->name('problems.submit');
    Route::get('problems/submit/{problem}/accept', [ProblemController::class, 'acceptProblem'])->name('problems.accept');
    Route::get('problems/submit/{problem}/cancel', [ProblemController::class, 'cancelProblem'])->name('problems.cancel');
    Route::get('problems/submit/{problem}/finish', [ProblemController::class, 'finishProblem'])->name('problems.finish');
    Route::get('problems/submit/{problem}/approve', [ProblemController::class, 'approveProblem'])->name('problems.approve');
    Route::get('problems/print/{problem}', [ProblemController::class, 'print'])->name('problems.print');
    Route::resource('problems', ProblemController::class);

    Route::prefix('settings')
        ->group(function(){
            Route::post('/', [SettingController::class, 'saveOptions'])->name('settings.save');
            Route::get('general', [SettingController::class, 'index'])->name('settings.general');
            Route::get('approval', [SettingController::class, 'approval'])->name('settings.approval');
        });

    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('logout', [DashboardController::class, 'logout'])->name('logout');

    Route::prefix('reports')
        ->group(function(){
            Route::get('problem', [ReportController::class, 'problem'])->name('reports.problem');
            Route::get('finance', [ReportController::class, 'finance'])->name('reports.finance');
        });

        // Monitoring Routes (Admin & Lembaga only)
        Route::prefix('monitoring')->name('monitoring.')->group(function(){
            Route::get('dashboard', [MonitoringController::class, 'dashboard'])->name('dashboard');
            Route::get('health', [MonitoringController::class, 'health'])->name('health');
            Route::get('errors', [MonitoringController::class, 'errors'])->name('errors');
            Route::get('error-stats', [MonitoringController::class, 'errorStats'])->name('error-stats');
            Route::get('activities', [MonitoringController::class, 'activities'])->name('activities');
            Route::get('performance', [MonitoringController::class, 'performance'])->name('performance');
            Route::post('health-check', [MonitoringController::class, 'runHealthCheck'])->name('run-health-check');
        });

        // Backup Routes (Admin & Lembaga only)
        Route::prefix('backup')->name('backup.')->group(function(){
            Route::get('dashboard', [BackupController::class, 'dashboard'])->name('dashboard');
            Route::post('create', [BackupController::class, 'createBackup'])->name('create');
            Route::post('restore', [BackupController::class, 'restoreBackup'])->name('restore');
            Route::delete('delete', [BackupController::class, 'deleteBackup'])->name('delete');
            Route::get('download/{fileName}', [BackupController::class, 'downloadBackup'])->name('download');
            Route::post('test', [BackupController::class, 'testBackup'])->name('test');
            Route::get('stats', [BackupController::class, 'stats'])->name('stats');
            Route::get('list', [BackupController::class, 'list'])->name('list');
        });

});

// AJAX Search Routes
Route::get('/api/goods/search', [GoodController::class, 'search'])->name('goods.search');