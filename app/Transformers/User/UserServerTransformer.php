<?php

namespace App\Transformers\User;

use App\Models\Server\Server;
use League\Fractal\TransformerAbstract;

class UserServerTransformer extends TransformerAbstract
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
    public function transform(Server $server)
    {
        return [
            "ip" => $server->ip,
            "port" => $server->client_port
        ];
    }
}
