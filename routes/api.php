<?php

use App\Http\Controllers\Api\OrderSheetController;
use App\Http\Controllers\Api\WeightController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ordersheet routes
Route::get('/ordersheet', [OrderSheetController::class, 'getData'])->name('api.ordersheet');

// Current_id
Route::prefix('timbang')->group(function () {
    Route::post('/set-id', [WeightController::class, 'setCurrentId']);
    Route::get('/preview/{id}', [WeightController::class, 'getPreview']);
    Route::post('/preview', [WeightController::class, 'preview']);
    Route::post('/simpan', [WeightController::class, 'simpan']);
    Route::get('/riwayat/{id}', [WeightController::class, 'getRiwayat']);

    Route::prefix('esp32')->group(function () {
        Route::get('/cek-id', [WeightController::class, 'cekIdAktif']);
        Route::post('/kirim-berat', [WeightController::class, 'terimaBerat']);
    });
});
