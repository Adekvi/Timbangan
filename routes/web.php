<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\OrderSheetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', [HomeController::class, 'index']);

// Login & Logout
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login/store', [LoginController::class, 'store'])->name('login.store');
Route::get('login/admin', [LoginController::class, 'admin'])->name('login.admin');

Route::get('lupa-password', [LoginController::class, 'lupa'])->name('lupa.password');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function(){
    Route::prefix('admin')->group(function(){
        Route::get('/dashboard',[DashboardController::class, 'index'])->name('admin.view');

        Route::get('/ordersheet-index',[OrderSheetController::class, 'index'])->name('admin.view.index');
    });
});

Route::middleware(['auth', 'role:user'])->group(function(){
    Route::prefix('user')->group(function(){
        Route::get('/home',[DashboardController::class, 'index'])->name('dashboard');

        Route::get('/ordersheet-view',[OrderSheetController::class, 'index'])->name('order.view');

        // tambah data ordersheet
        Route::get('/ordersheet-view/create', [OrderSheetController::class, 'create'])->name('ordersheet.create');
        Route::post('/ordersheet/store', [OrderSheetController::class, 'store'])->name('ordersheet.store');

        // cetak timbangan
        Route::get('/order/print', [OrderSheetController::class, 'print'])->name('order.print');
        Route::get('/print/{buyer}', [OrderSheetController::class, 'print'])->name('order.print.buyer');
    });
});