<?php

use App\Http\Controllers\AppearanceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
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

Route::get('/', [AppearanceController::class, 'index'])->middleware('auth')->name('dashboard');

Route::get('/login',[LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'attempt'])->name('login.attempt')->middleware('guest');
Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout')->middleware('auth');

Route::post('update', [AppearanceController::class, 'update_absence'])->name('update.ajax');
