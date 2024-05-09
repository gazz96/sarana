<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProblemController;
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
    Route::post('/', [AuthController::class, 'login'])->name('auth.login');
});
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::resource('users', UserController::class);
Route::resource('goods', GoodController::class);
Route::resource('locations', LocationController::class);
Route::resource('problems', ProblemController::class);