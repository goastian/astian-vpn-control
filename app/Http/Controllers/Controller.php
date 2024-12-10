<?php

namespace App\Http\Controllers;

use Elyerr\ApiResponse\Exceptions\ReportError;
use InvalidArgumentException;
use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Routing\Controller as RoutingController;

abstract class Controller extends RoutingController
{
    use Asset, JsonResponser;


    /**
     * Summary of generateNextSubnet
     * @param mixed $subnet
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return string
     */
    public function generateNextSubnet($subnet = null)
    {
        $subnet_base = $subnet ?? "192.10.0.0/16";

        list($ip, $prefix) = explode('/', $subnet_base);

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new ReportError(__('Invalid IP address'), 403);
        }

        //split subnet
        throw_if($prefix != 16, new ReportError(__('Invalid prefix'), 403));

        $address = explode('.', $ip);
        $subnet_octet = $address[1] + 1;

        throw_if($subnet_octet > 254, new ReportError(__('The limit has been exceeded'), 403));
        $address[1] = $subnet_octet;

        return implode('.', $address) . "/" . $prefix;
    }

    /**
     * Summary of generateRandomIp
     * @param mixed $subnet
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return bool|string
     */
    public function generateRandomIp($subnet)
    {
        list($baseIp, $mask) = explode('/', $subnet);
        $mask = (int) $mask;

        $baseIpLong = ip2long($baseIp);
        $hostCount = 2 ** (32 - $mask) - 2;

        if ($hostCount < 1) {
            throw new ReportError(__("The specified subnet does not allow valid host addresses."), 422);
        }

        $attempts = 5;
        for ($i = 0; $i < $attempts; $i++) {
            $randomOffset = rand(1, $hostCount);
            $randomIpLong = $baseIpLong + $randomOffset;
            $randomIp = long2ip($randomIpLong);

            if (filter_var($randomIp, FILTER_VALIDATE_IP)) {
                return $randomIp;
            }
        }

        throw new ReportError(__("Failed to generate a valid IP address after :attempts attempts.", ['attempts' => $attempts]), 422);
    }
}
