<?php

namespace App\Transformers\Server;

use League\Fractal\TransformerAbstract;

class ShadowsocksTransformer extends TransformerAbstract
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
    public function transform($data)
    {
        return [
            'id' => $data->id,
            'country' => $data->country,
            'ipv4' => $data->ip,
            'port' => 1080,
            'ss_port' => $data->ss_port,
            //'ss_password' => $data->ss_password,
            //'ss_method' => $data->ss_method,             
        ];
    }
}
