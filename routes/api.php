<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\Server\ServerController;
use App\Http\Controllers\Api\Gateway\GatewayController;
use App\Http\Controllers\Api\Setting\SettingController;
use App\Http\Controllers\Api\Device\VpnDeviceController;
use App\Http\Controllers\Api\Wireguard\WireguardController;
use App\Http\Controllers\Api\Device\WireguardDeviceController;

/**
 * Route to authorize clients and users
 */
Route::group([
    'prefix' => 'gateway'
], function () {

    /**
     * Verify client (VPN Core)
     */
    Route::get('/authorization', [GatewayController::class, 'checkCredentials'])->name('gateway.authorization');

    /**
     * Verify users 
     * header Authorization
     * header X-Device-ID 
     */
    Route::get('/check-auth', [GatewayController::class, 'checkAuth'])->name('gateway.check-auth');
});

/**
 * Route to manage admin resources
 */
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => 'json.response'
], function () {

    /**
     * Route to manage servers configuration
     */
    Route::group([
        'prefix' => 'servers',
        'as' => 'servers.'
    ], function () {
        Route::put('/{server}/toggle', [ServerController::class, 'toggle'])->name('toggle');
        Route::resource('/', ServerController::class)->except('edit', 'create');
        Route::get('/interfaces/{id}', [ServerController::class, 'interfaces']);
    });

    /**
     * Endpoint to manage Wireguard VPN protocol
     */
    Route::group([
        'prefix' => 'wireguard',
        'as' => 'wireguard.'
    ], function () {
        Route::resource('/', WireguardController::class)->except('edit', 'create');
        Route::put('/{wg}/toggle', [WireguardController::class, 'toggle'])->name('toggle');
        Route::put('wireguard/{wg}/reload', [WireguardController::class, 'reload'])->name('reload');
    });

    /**
     * Route to manage and admin settings
     */
    Route::group([
        'prefix' => 'settings',
        'as' => 'settings.'
    ], function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/', [SettingController::class, 'store'])->name('store');
    });
});

/**
 * Manage use routes
 */
Route::group([
    'prefix' => 'users',
    'as' => 'users.',
    'middleware' => 'json.response'
], function () {

    Route::post('/logout', [ApiController::class, 'logout'])->name('logout');

    /**
     * Manga the all device proxy vpn
     */
    Route::group(
        [
            'prefix' => 'vpn',
            'as' => 'vpn.',
        ],
        function () {
            Route::resource('devices', VpnDeviceController::class)->except('show', 'edit', 'crate', 'update');
        }
    );

    /**
     * Manage device from to
     */
    Route::group(
        [
            'prefix' => 'wireguard',
            'as' => 'wireguard.',
        ],
        function () {
            Route::put('/peers/{peer}/toggle', [WireguardDeviceController::class, 'toggle'])->name('peers.toggle');
            Route::resource('/peers', WireguardDeviceController::class)->only('index', 'store', 'destroy');
        }
    );
});