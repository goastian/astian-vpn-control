<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

final class DashboardController extends Controller
{

    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }
}
