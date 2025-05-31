<?php

namespace App\Http\Controllers;

use App\Models\Server\Peer;
use App\Models\Server\Device;
use App\Http\Controllers\Controller;
use Elyerr\ApiResponse\Exceptions\ReportError;

class ApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('server');
    }

    /**
     * Set the limit to add devices for the user
     * @param mixed $user
     * @return void
     */
    public function verifyPlan($user)
    {
        //Retrieve the all vpn device (Wireguard protocol)
        $wireguard = Peer::query();
        $wireguard->where('user_id', $user->id);

        //Retrieve the all Vpn device
        $devices = Device::query();
        $devices->where('user_id', $user->id);

        //Join the all devices
        $total_devices = $devices->count() + $wireguard->count();

        $access = collect($this->access())->pluck('id');

        //available plans 
        $plans = [
            'commerce_vpn_advanced' => config('vpn.advanced'),
            'commerce_vpn_intermediate' => config('vpn.intermediate'),
            'commerce_vpn_basic' => config('vpn.basic'),
            'commerce_vpn_free' => config('vpn.free'),
        ];

        //check user plan
        $userLimit = collect($plans)
            ->filter(fn($limit, $plan) => $access->contains($plan))
            ->first() ?? config('vpn.free');

        throw_if(
            $total_devices >= $userLimit,
            new ReportError(
                __('You have exceeded the device limit. To add more devices, please upgrade to a higher plan.'),
                403
            )
        );

    }
}
