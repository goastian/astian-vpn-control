<?php

namespace App\Transformers\User;

use App\Models\Server\Wg;
use League\Fractal\TransformerAbstract;

class UserWireguardTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Wg $wg)
    {
        return [
            'id' => $wg->id,
            'name' => $wg->name,
            'server_country' => $wg->server->country,
            'data' => locationData($wg->server->ip)
        ];
    }
}
