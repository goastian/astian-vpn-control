<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\Auth\LogoutController;

Route::group([
    "prefix" => "user",
    "as" => "user."
], function () {
    Route::get("/dashboard", [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get("/devices", [DashboardController::class, 'devices'])->name('vpn.devices');
    Route::get("/wireguard/generator", [DashboardController::class, 'wireguardGenerator'])->name('wireguard.generator');
    Route::get("/wireguard/instruction", [DashboardController::class, 'Instructions'])->name('wireguard.instructions');

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});
