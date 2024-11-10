<?php

use App\Http\Controllers\Dashboard\DashboardController;
use Elyerr\Passport\Connect\Controllers\CodeController;
use Illuminate\Support\Facades\Route;

Route::get('/redirect', [CodeController::class, 'redirect'])->name('redirect');
Route::get('/callback', [CodeController::class, 'callback'])->name('callback');
Route::get("/{any}", [DashboardController::class, 'dashboard'])->where('any', '^(?!api).*$');
