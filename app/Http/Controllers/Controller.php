<?php

namespace App\Http\Controllers;

use App\Models\Server\Peer;
use InvalidArgumentException;
use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\Passport\Connect\Traits\Passport;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as RoutingController;

abstract class Controller extends RoutingController
{
    use Asset, ValidatesRequests, Passport, JsonResponser;

    /**
     * generateNextSubnet
     * @param mixed $subnet
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return array
     */
    public function generateNextSubnet($subnet = null)
    {
        $subnet_base = $subnet ?? "192.10.0.0/16";

        list($ip, $prefix) = explode('/', $subnet_base);
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new ReportError(__('Invalid IP address'), 403);
        }

        $prefix = (int) $prefix;
        if ($prefix !== 16) {
            throw new ReportError(__('Invalid prefix, only /16 is supported'), 403);
        }

        $ip_long = ip2long($ip);

        $increment = 1 << (32 - $prefix);
        $next_ip_long = $ip_long + $increment;

        $max_ip_long = ip2long("192.255.255.255");
        if ($next_ip_long > $max_ip_long) {
            throw new ReportError(__('The limit has been exceeded for the /16 range'), 403);
        }

        $gateway = explode('.', long2ip($next_ip_long));
        $gateway[3] = 1;

        $data = [
            "subnet" => long2ip($next_ip_long),
            "gateway" => implode('.', $gateway),
            "prefix" => $prefix
        ];

        return $data;
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
     * Verify the 
     * @param mixed $ip
     * @return bool|TModel|TValue
     */
    public function verifyIpExists($ip)
    {
        $peer = Peer::where('allowed_ips', $ip)->first();
        return $peer ?? false;
    }
}
