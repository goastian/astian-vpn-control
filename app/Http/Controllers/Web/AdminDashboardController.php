<?php
namespace App\Http\Controllers\Web;

use Inertia\Inertia;
use App\Models\Setting\Menu;
use App\Http\Controllers\WebController;

class AdminDashboardController extends WebController
{
    /**
     * Dashboard routes for admins
     * @var array
     */
    public $admin_dashboard_routes = [];

    public function __construct()
    {
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_dashboard')->only('dashboard');
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_view')->except('dashboard');
        $this->admin_dashboard_routes = Menu::adminRoutes();
    }

    /**
     * Show the admin dashboard 
     * @return \Inertia\Response
     */
    public function dashboard()
    {
        return Inertia::render("Admin/Home/Index", [
            "admin_routes" => $this->admin_dashboard_routes
        ]);
    }

    /**
     * Show the server admin view
     * @return \Inertia\Response
     */
    public function servers()
    {
        return Inertia::render("Admin/Server/Index", [
            "admin_routes" => $this->admin_dashboard_routes,
            "links" => [
                'peers' => route("api.v1.admin.servers.index"),
            ]
        ]);
    }

    /**
     * Show the wireguard admin view
     * @return \Inertia\Response
     */
    public function wireguard()
    {
        return Inertia::render("Admin/Wireguard/Index", [
            "admin_routes" => $this->admin_dashboard_routes,
            "links" => [
                'wireguard' => route("api.v1.admin.wireguard.index"),
                'servers' => route('api.v1.admin.servers.index')
            ]
        ]);
    }
}
