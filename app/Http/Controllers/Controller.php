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
    function generateNextSubnet($subnet = null)
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

}
