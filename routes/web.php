<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;
use Elyerr\Passport\Connect\Controllers\CodeController;

Route::get('/redirect', [CodeController::class, 'redirect'])->name('redirect');
Route::get('/callback', [CodeController::class, 'callback'])->name('callback');


Route::get("/", [WebController::class, 'home'])->name('home');
Route::get("/dashboard", [DashboardController::class, 'dashboard'])->name('dashboard');
