<?php
namespace App\Http\Controllers\Web;

use Inertia\Inertia;
use App\Http\Controllers\WebController;

class DashboardController extends WebController
{

    public function __construct()
    {
        $this->middleware('server')->except('home');
    }

    /**
     * User dashboard
     * @return \Inertia\Response
     */
    public function dashboard()
    {
        return Inertia::render("Auth/Home/Dashboard", [
            "user" => $this->user()
        ]);
    }
}
