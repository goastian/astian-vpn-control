<?php
namespace App\Http\Controllers\Web;

use App\Repositories\Traits\Generic;
use Inertia\Inertia;
use App\Models\Setting\Menu;
use App\Http\Controllers\WebController;

class DashboardController extends WebController
{
    use Generic;
    /**
     * Set the user routes for dashboard
     * @var array
     */
    public $user_dashboard_routes = [];

    public function __construct()
    {
        $this->middleware('server');
        $this->user_dashboard_routes = Menu::userDashboardRoutes();
    }

    /**
     * User dashboard
     * @return \Inertia\Response
     */
    public function dashboard()
    {
        return Inertia::render("Auth/Home/Dashboard", [
            'user_dashboard_routes' => $this->user_dashboard_routes,
            'user_plan' => $this->userPlan(),
            'links' => [
                "peers" => route('api.v1.users.wireguard.peers.index')
            ]
        ]);
    }

    /**
     * Show the vpn devices
     * @return \Inertia\Response
     */
    public function devices()
    {
        return Inertia::render(
            "Auth/Device/Index",
            [
                'user_dashboard_routes' => $this->user_dashboard_routes,
                'links' => [
                    'device' => route('api.v1.users.devices.index')
                ]
            ]
        );
    }

    /**
     * Wireguard configuration file generator
     * @return \Inertia\Response
     */
    public function wireguardGenerator()
    {
        return Inertia::render("Auth/Wireguard/Index", [
            'user_dashboard_routes' => $this->user_dashboard_routes,
            'links' => [
                "peers" => route('api.v1.users.wireguard.peers.index'),
                "servers" => route("api.v1.users.wireguard.servers.index"),
            ]
        ]);
    }

    /**
     * Instructions
     * @return \Inertia\Response
     */
    public function Instructions()
    {
        return Inertia::render("Auth/Instructions/Index", [
            'user_dashboard_routes' => $this->user_dashboard_routes,
        ]);
    }
}
