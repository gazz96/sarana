<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\LocationController;
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
    Route::post('/', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function(){

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('users', UserController::class);
    Route::resource('goods', GoodController::class);
    Route::resource('locations', LocationController::class);

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

});