<?php

use App\Http\Controllers\Api\OrderSheetController;
use App\Http\Controllers\Api\WeightController;
use App\Http\Controllers\Ordersheet\OrderPackageController;
use App\Http\Controllers\Ordersheet\PackageController;
use App\Http\Controllers\Update\Admin\DeviceController;
use App\Http\Controllers\Update\User\SwitchController;
use App\Http\Controllers\Update\User\WifiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ordersheet routes
Route::get('/ordersheet', [OrderSheetController::class, 'getData'])->name('api.ordersheet');

// Login
Route::prefix('login')->group(function () {
    Route::post('/esp/heartbeat', [DeviceController::class, 'heartbeat']);
    
    // Wifi
    Route::get('/wifi/config', [WifiController::class, 'getWifiConfig']);
    Route::post('/wifi/update', [WifiController::class, 'updateWifi']);
    Route::get('/wifi/stream', [WifiController::class, 'stream']);
});

// Current_id Ordersheet
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

//  Package Current_id
Route::prefix('package')->middleware('web')->group(function () {
    Route::get('/timbangan/live', [OrderPackageController::class, 'getPreview']);
    Route::post('/timbangan/tare', [OrderPackageController::class, 'tare']);
    Route::post('/store', [PackageController::class, 'store']);
});

// Route khusus ESP (tanpa auth, tapi validasi esp_id)
Route::prefix('package/esp')->group(function () {
    Route::post('/timbangan/live', [OrderPackageController::class, 'terimaBerat']);
    Route::get('/timbangan/set', [OrderPackageController::class, 'setTimbanganCommand']); // ubah ke POST
});
