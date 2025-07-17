<?php
namespace App\Repositories\Traits;

use App\Models\Server\Peer;
use App\Models\Server\Device;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Elyerr\Passport\Connect\Services\Passport;

trait Generic
{

    /**
     * generateNextSubnet
     * @param mixed $subnet
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return array
     */
    public function generateNextSubnet($subnet = null)
    {
        // Default safe private range base: 10.100.0.0/16
        $subnet_base = $subnet ?? "10.100.0.0/16";

        list($ip, $prefix) = explode('/', $subnet_base);

        // Validate IP format
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new ReportError(__('Invalid IP address'), 403);
        }

        $prefix = (int) $prefix;
        // Only /16 prefixes are allowed for consistency
        if ($prefix !== 16) {
            throw new ReportError(__('Only /16 prefixes are supported'), 403);
        }

        // Ensure the IP is within a private RFC1918 range
        if (!self::isPrivateIP($ip)) {
            throw new ReportError(__('Subnet must be within a private IP range (RFC1918)'), 403);
        }

        $ip_long = ip2long($ip);
        $increment = 1 << (32 - $prefix);
        $next_ip_long = $ip_long + $increment;

        // Ensure the next IP stays within the private range
        $private_limit = ip2long("10.255.255.255"); // assuming usage of 10.0.0.0/8
        if ($next_ip_long > $private_limit) {
            throw new ReportError(__('IP limit exceeded for the private range'), 403);
        }

        // Set gateway address to x.x.x.1
        $gateway_parts = explode('.', long2ip($next_ip_long));
        $gateway_parts[3] = 1;

        return [
            "subnet" => long2ip($next_ip_long),
            "gateway" => implode('.', $gateway_parts),
            "prefix" => $prefix
        ];
    }

    /**
     * Checks if an IP address is within a private RFC1918 range.
     * @param string $ip
     * @return bool
     */
    public static function isPrivateIP($ip)
    {
        $long = ip2long($ip);
        return (
            ($long >= ip2long('10.0.0.0') && $long <= ip2long('10.255.255.255')) ||
            ($long >= ip2long('172.16.0.0') && $long <= ip2long('172.31.255.255')) ||
            ($long >= ip2long('192.168.0.0') && $long <= ip2long('192.168.255.255'))
        );
    }

    /**
     * Summary of generateRandomIp
     * @param mixed $subnet
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return bool|string
     */
    public function generateRandomIp($subnet, $attempts = 10)
    {
        list($baseIp, $mask) = explode('/', $subnet);
        $mask = (int) $mask;

        $baseIpLong = ip2long($baseIp);
        $hostCount = 2 ** (32 - $mask) - 2;

        if ($hostCount < 1) {
            throw new ReportError(__("The specified subnet does not allow valid host addresses."), 422);
        }

        for ($i = 0; $i < $attempts; $i++) {
            $randomOffset = rand(1, $hostCount);
            $randomIpLong = $baseIpLong + $randomOffset;
            $randomIp = long2ip($randomIpLong);

            if (filter_var($randomIp, FILTER_VALIDATE_IP)) {
                $exists = $this->verifyIpExists($randomIp);

                if (!$exists) {
                    return $randomIp;
                }
            }
        }

        throw new ReportError(
            __("Unable to generate a valid IP address after :attempts attempts. The current server may be overloaded or unable to allocate new addresses. Please consider using a different server.", ['attempts' => $attempts]),
            422
        );
    }

    /**
     * Set the limit to add devices for the user
     * @param mixed $user
     * @return void
     */
    public function verifyPlan($user)
    {
        $passport = app(Passport::class);
        
        //Retrieve the all vpn device (Wireguard protocol)
        $wireguard = Peer::query();
        $wireguard->where('user_id', $user->id);

        //Retrieve the all Vpn device
        $devices = Device::query();
        $devices->where('user_id', $user->id);

        //Join the all devices
        $total_devices = $devices->count() + $wireguard->count();

        $access = collect($passport->access())->pluck('id');

        //available plans 
        $plans = [
            'commerce:vpn:advanced' => config('vpn.advanced'),
            'commerce:vpn:intermediate' => config('vpn.intermediate'),
            'commerce:vpn:basic' => config('vpn.basic'),
            'commerce:vpn:free' => config('vpn.free'),
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


    /**
     * Generate unique IP
     *
     * @param string $subnet
     * @return string
     */
    public function generateUniqueIp($subnet = "10.0.0.0/8")
    {
        list($baseIP, $prefix) = explode('/', $subnet);
        $baseIP = ip2long($baseIP);

        $totalIPs = pow(2, (32 - $prefix));

        $randomIP = $baseIP + rand(1, $totalIPs - 2);

        return long2ip($randomIP) . "/32";

    }

    /**
     * Check the ip address
     * @param mixed $ip
     * @return bool|Illuminate\Database\Eloquent\Model|object|Peer
     */
    public function verifyIpExists($ip)
    {
        $peer = Peer::where('allowed_ips', $ip)->first();
        return $peer ?? false;
    }

    /**
     * Retrieve user plan
     * @return array{user_plan: array{total_devices: mixed, used_devices: int}}
     */
    public function userPlan()
    {
        $passport = app(Passport::class);
        $user = $passport->user();
        
        $access = collect($passport->access())->pluck('id');

        //available plans 
        $plans = [
            'commerce:vpn:advanced' => config('vpn.advanced'),
            'commerce:vpn:intermediate' => config('vpn.intermediate'),
            'commerce:vpn:basic' => config('vpn.basic'),
            'commerce:vpn:free' => config('vpn.free'),
        ];

        //check user plan
        $amount = collect($plans)
            ->filter(fn($limit, $plan) => $access->contains($plan))
            ->first() ?? config('vpn.free');


        $count_devices = Device::where('user_id', $user->id)->count();
        $count_peer = Peer::where('user_id', $user->id)->count();

        return [
            'used_devices' => $count_devices + $count_peer,
            'total_devices' => $amount
        ];
    }

}
