<?php

namespace App\Http\Controllers;

use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Illuminate\Routing\Controller as RoutingController;

abstract class Controller extends RoutingController
{
    use Asset, JsonResponser;
}
