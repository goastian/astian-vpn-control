<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Web\SettingController;


Route::group([
    'prefix' => 'settings',
    'as' => 'settings.',
], function () {
    Route::get('/', [SettingController::class, 'general'])->name('general');
    Route::get('/plans', [SettingController::class, 'plans'])->name('plans');
    Route::get('/user', [SettingController::class, 'user'])->name('user');
    Route::get('/routes', [SettingController::class, 'routes'])->name('routes');
    Route::get('/redis', [SettingController::class, 'redis'])->name('redis');
    Route::get('/queues', [SettingController::class, 'queues'])->name('queues');
    Route::get('/payment', [SettingController::class, 'payment'])->name('payment');
    Route::get('/session', [SettingController::class, 'session'])->name('session');

    Route::put('/', [SettingController::class, 'update'])->name('update');
});