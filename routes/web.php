<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Setting\SettingController;
use Elyerr\Passport\Connect\Controllers\CodeController;

Route::get('/redirect', [CodeController::class, 'redirect'])->name('login');
Route::get('/callback', [CodeController::class, 'callback'])->name('callback');
Route::get('/user', [GlobalController::class, 'user']);
Route::post('/logout', [GlobalController::class, 'logout']);

Route::get("/{any}", function () {
    return view('layouts.app');
})->where('any', '^(?!api).*$');
