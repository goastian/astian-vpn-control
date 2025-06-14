<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\VpnDeviceController;
use App\Http\Controllers\Api\V1\Gateway\GatewayController;
use App\Http\Controllers\Api\V1\User\UserServerController;
use App\Http\Controllers\Api\V1\Admin\Server\ServerController;
use App\Http\Controllers\Api\V1\User\WireguardDeviceController;
use App\Http\Controllers\Api\V1\Admin\Wireguard\WireguardController;

Route::group([
    'prefix' => 'v1',
    'as' => 'api.v1.',
    'middleware' => 'json.response'
], function () {

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

    ], function () {

        /**
         * Route to manage servers configuration
         */
        Route::get('servers/interfaces/{id}', [ServerController::class, 'interfaces'])->name('servers.interfaces');
        Route::put('servers/{server}/toggle', [ServerController::class, 'toggle'])->name('servers.toggle');
        Route::resource('servers', ServerController::class)->except('edit', 'create');


        /**
         * Endpoint to manage Wireguard VPN protocol
         */
        Route::put('wireguard/{wg}/toggle', [WireguardController::class, 'toggle'])->name('wireguard.toggle');
        Route::put('wireguard/{wg}/reload', [WireguardController::class, 'reload'])->name('wireguard.reload');
        Route::resource('wireguard', WireguardController::class)->except('edit', 'create')->parameters(['wireguard' => 'wg']);
    });

    /**
     * Manage use routes
     */
    Route::group([
        'prefix' => 'users',
        'as' => 'users.',
    ], function () {



        /**
         * Manga the all device proxy vpn
         */

        Route::resource('vpn/devices', VpnDeviceController::class)->except('show', 'edit', 'crate', 'update');

        /**
         * Manage device to the wireguard  protocol
         */
        Route::put('wireguard/peers/{peer}/toggle', [WireguardDeviceController::class, 'toggle'])->name('wireguard.peers.toggle');
        Route::get('wireguard/peers', [WireguardDeviceController::class, 'index'])->name('wireguard.peers.index');
        Route::post('wireguard/peers', [WireguardDeviceController::class, 'store'])->name('wireguard.peers.store');
        Route::delete('wireguard/peers/{peer}', [WireguardDeviceController::class, 'destroy'])->name('wireguard.peers.destroy');

        /**
         * Serves VPNs to the users
         */
        Route::get('vpn/servers', [UserServerController::class, 'index'])->name('vpn.servers.index');
        Route::get('wireguard/servers', [UserWireguardController::class, 'index'])->name('wireguard.servers.index');

    });

});