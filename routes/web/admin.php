<?php

use App\Http\Controllers\Web\AdminDashboardController;


Route::get('dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
Route::get('servers', [AdminDashboardController::class, 'servers'])->name("servers");
Route::get('wireguard', [AdminDashboardController::class, 'wireguard'])->name('wireguard');
