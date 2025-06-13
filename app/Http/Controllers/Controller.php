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
     * Verify the 
     * @param mixed $ip
     * @return bool
     */
    public function verifyIpExists($ip)
    {
        $peer = Peer::where('allowed_ips', $ip)->first();
        return $peer ?? false;
    }
}
