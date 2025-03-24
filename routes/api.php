<?php

use App\Http\Controllers\Security\KeyGeneratorController;
use App\Http\Controllers\Socks\ShadowsocksController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Wg\WgController;
use App\Http\Controllers\Peer\PeerController;
use App\Http\Controllers\Server\ServerController;
use App\Http\Controllers\Setting\SettingController;

Route::get('/gateway/authorization', [KeyGeneratorController::class, 'checkCredentials'])->name('gateway.authorization');


Route::middleware(['json.response'])->group(function () {

    Route::put('servers/{server}/toggle', [ServerController::class, 'toggle'])->name('servers.toggle');
    Route::resource('servers', ServerController::class)->except('edit', 'create');
    Route::get('/interfaces/{id}', [ServerController::class, 'interfaces']);

    Route::put('wgs/{wg}/toggle', [WgController::class, 'toggle'])->name('wgs.toggle');
    Route::put('wg/{wg}/reload', [WgController::class, 'reload'])->name('wgs.reload');
    Route::resource('wgs', WgController::class)->except('edit', 'create');

    Route::put('peers/{peer}/toggle', [PeerController::class, 'toggle'])->name('peers.toggle');
    Route::resource('peers', PeerController::class)->only('index', 'store', 'destroy');

    Route::group([
        'prefix' => 'settings',
        'as' => 'settings.'
    ], function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/', [SettingController::class, 'store'])->name('store');
    });

    Route::get("/shadowsocks", [ShadowsocksController::class, 'index'])->name('shadowsocks.index');
    Route::post("/shadowsocks/{server_id}", [ShadowsocksController::class, 'createConfig'])->name('shadowsocks.add_config');
    Route::get("/shadowsocks/{server_id}/config", [ShadowsocksController::class, 'showConfig'])->name('shadowsocks.show_config');
    Route::post("/shadowsocks/{server_id}/start", [ShadowsocksController::class, 'start'])->name('shadowsocks.start');
    Route::post("/shadowsocks/{server_id}/restart", [ShadowsocksController::class, 'restart'])->name('shadowsocks.restart');
    Route::post("/shadowsocks/{server_id}/stop", [ShadowsocksController::class, 'stop'])->name('shadowsocks.stop');
    Route::get("/shadowsocks/{server_id}/status", [ShadowsocksController::class, 'status'])->name('shadowsocks.status');
    Route::delete("/shadowsocks/{server_id}/config/delete", [ShadowsocksController::class, 'deleteConfig'])->name('shadowsocks.config_delete');

});