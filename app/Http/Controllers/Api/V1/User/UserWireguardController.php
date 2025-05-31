<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Models\Server\Wg;
use App\Http\Controllers\ApiController;
use App\Transformers\User\UserWireguardTransformer;

class UserWireguardController extends ApiController
{

    public function index(Wg $wg)
    {
        $data = $wg->query();

        return $this->showAllByBuilder($data, UserWireguardTransformer::class);
    }
}
