<?php

use App\Http\Controllers\Peer\PeerController;
use App\Http\Controllers\Server\ServerController;
use App\Http\Controllers\System\SettingController;
use App\Http\Controllers\Wg\WgController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\geoController;

Route::put('servers/{server}/toggle', [ServerController::class, 'toggle'])->name('servers.toggle');
Route::resource('servers', ServerController::class)->except('edit', 'create');
Route::get('/interfaces/{id}', [ServerController::class, 'interfaces']);

Route::put('wgs/{wg}/toggle', [WgController::class, 'toggle'])->name('wgs.toggle');
Route::post('wg/{wg}/reload', [WgController::class, 'reload'])->name('wgs.reload');
Route::resource('wgs', WgController::class)->except('edit', 'create');

Route::put('peers/{peer}/toggle', [PeerController::class, 'toggle'])->name('peers.toggle');
Route::resource('peers', PeerController::class)->only('index', 'store', 'destroy');

Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::post('/', [SettingController::class, 'store'])->name('store');
});

Route::get('/endpoint', [App\Http\Controllers\geoController::class, 'geoController']);
