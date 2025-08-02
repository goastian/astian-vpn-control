<?php

namespace App\Http\Middleware;

use Exception;
use Inertia\Middleware;
use App\Models\Setting\Menu;
use Illuminate\Http\Request;
use Elyerr\Passport\Connect\Services\Passport;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = null;

        try {
            $passport = app(Passport::class);
            $user = $passport->user();
        } catch (Exception $th) {
        }

        $environment_keys = Menu::shareEnvironmentKeys();
        $environment_keys['user'] = $user;

        //Dashboard admin route
        $environment_keys["admin_dashboard"] = Menu::addRoute(
            "Dashboard",
            "mdi-shield-account-outline",
            route('admin.dashboard'),
            $this->checkGroup($user, "administrator")
        );

        return [
            ...parent::share($request),
            ...$environment_keys
        ];
    }

    /**
     * Check user group
     * @param mixed $user
     * @param string $name
     * @return bool
     */
    public function checkGroup($user, string $name)
    {
        if (!$user || !isset($user->groups)) {
            return false;
        }

        return collect($user->groups)->contains(function ($item) use ($name) {
            return $item['slug'] === $name;
        });
    }
}
