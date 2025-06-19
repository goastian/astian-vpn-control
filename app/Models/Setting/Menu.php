<?php

namespace App\Models\Setting;

class Menu
{
    /**
     * Global environment keys
     * @return array
     */
    public static function shareEnvironmentKeys()
    {
        return [
            "app_name" => config('app.name'),
            "routes" => [
                "redirect" => route('redirect'),
                "logout" => route("user.logout"),
                'home' => route('home'),
                'dashboard' => route('user.dashboard'),
                'wireguard_generator' => route('user.wireguard.generator')
            ],
        ];
    }

    /**
     * Dashboard usr routes
     * @return array{icon: string, name: string, route: string, show: bool[]}
     */
    public static function userDashboardRoutes()
    {
        return [
            static::addRoute("Dashboard", "mdi-home-automation", route('user.dashboard'), true),
            static::addRoute("Devices", "mdi-devices", route("user.vpn.devices"), true),
            static::addRoute("Wireguard Generator", "mdi-vpn", route("user.wireguard.generator"), true),
            static::addRoute("Instructions", "mdi-information-outline", route("user.wireguard.instructions"), true),
        ];
    }

    /**
     * Admin routes
     * @return array[]
     */
    public static function adminRoutes()
    {
        return [
            static::addRoute("Dashboard", "mdi-home-automation", route('admin.dashboard'), true),
            static::addRoute("Servers", "mdi-server-network", route("admin.servers"), true),
            static::addRoute("Wireguard", "mdi-vpn", route("admin.wireguard"), true),
            static::addRoute('Settings', 'mdi-shield', route('admin.settings.general'), true)
        ];
    }

    /**
     * Add new route 
     * @param string $name
     * @param string $icon
     * @param string $route
     * @param bool $visibility
     * @return array
     */
    public static function addRoute(string $name, string $icon, string $route, bool $visibility)
    {
        return [
            "name" => $name,
            "icon" => $icon,
            "route" => $route,
            "show" => $visibility
        ];
    }
}
