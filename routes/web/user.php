<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Web\DashboardController;

Route::group([
    "prefix" => "user",
    "as" => "user."
], function () {
    Route::get("/dashboard", [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get("/devices", [DashboardController::class, 'devices'])->name('vpn.devices');
    Route::get("/wireguard/generator", [DashboardController::class, 'wireguardGenerator'])->name('wireguard.generator');
    Route::get("/wireguard/instruction", [DashboardController::class, 'Instructions'])->name('wireguard.instructions');

    Route::post('/logout', [ApiController::class, 'logout'])->name('logout');
});
