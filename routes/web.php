<?php

use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;
use Elyerr\Passport\Connect\Controllers\CodeController;

Route::get('/redirect', [CodeController::class, 'redirect'])->name('redirect');
Route::get('/callback', [AuthController::class, 'callback'])->name('callback');


Route::get("/", [WebController::class, 'home'])->name('home');

require_once "web/user.php";

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {
    require_once "web/admin.php";
    require_once "web/settings.php";
});