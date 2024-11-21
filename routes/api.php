<?php

use App\Http\Controllers\Peer\PeerController;
use App\Http\Controllers\Server\ServerController;
use App\Http\Controllers\Wg\WgController;
use Illuminate\Support\Facades\Route;

Route::put('servers/{server}/toggle', [ServerController::class, 'toggle'])->name('servers.toggle');
Route::resource('servers', ServerController::class)->except('edit', 'create');

Route::put('wgs/{wg}/toggle', [WgController::class, 'toggle'])->name('wgs.toggle');
Route::resource('wgs', WgController::class)->except('edit', 'create');

Route::put('peers/{peer}/toggle', [PeerController::class, 'toggle'])->name('peers.toggle');
Route::resource('peers', PeerController::class)->only('index', 'store', 'destroy');

Route::get('/interfaces/{ip}/{port}', [WgController::class, 'interfaces']);
