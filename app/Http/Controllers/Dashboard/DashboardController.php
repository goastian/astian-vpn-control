<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Elyerr\Passport\Connect\Middleware\Authorization;

final class DashboardController extends Controller
{

    public function __construct()
    {
        //$this->middleware(Authorization::class)->except('welcome');
    }

    public function dashboard()
    {
        return view('app');
    }
}
