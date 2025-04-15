<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Wg\WgController;
use App\Http\Controllers\Peer\PeerController;
use App\Http\Controllers\Server\ServerController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\Socks\ShadowsocksController;
use App\Http\Controllers\Security\KeyGeneratorController;

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

    Route::group([
        'prefix' => 'shadowsocks',
        'as' => "shadowsocks."
    ], function () {
        
        Route::get("/", [ShadowsocksController::class, 'index'])->name('shadowsocks.index');
        
       // Route::post("/{server_id}", [ShadowsocksController::class, 'createConfig'])->name('server.add_config');
        
        Route::get("/{server_id}/server/start", [ShadowsocksController::class, 'serverStart'])->name('server.start');
        Route::get("/{server_id}/server/stop", [ShadowsocksController::class, 'serverStop'])->name('server.stop');
        Route::get("/{server_id}/server/status", [ShadowsocksController::class, 'serverStatus'])->name('server.status');

        Route::get("/{server_id}/client/start", [ShadowsocksController::class, 'clientStart'])->name('client.start');
        Route::get("/{server_id}/client/stop", [ShadowsocksController::class, 'clientStop'])->name('client.stop');
        Route::get("/{server_id}/client/status", [ShadowsocksController::class, 'clientStatus'])->name('client.status');
    });
});