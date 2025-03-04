<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Dashboard\DashboardController;
use Elyerr\Passport\Connect\Controllers\CodeController;

Route::get('/redirect', [CodeController::class, 'redirect'])->name('redirect');
Route::get('/callback', [CodeController::class, 'callback'])->name('callback');

Route::get('/user', [GlobalController::class, 'user']);

Route::get("/{any}", [DashboardController::class, 'dashboard'])->where('any', '^(?!api).*$');
