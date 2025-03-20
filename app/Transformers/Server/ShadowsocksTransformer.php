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
            'port' => $data->port,
            'ss_port' => $data->ss_port,
            'ss_password' => $data->ss_password,
            'ss_method' => $data->ss_method,
            'links' => [
                'index' => route('shadowsocks.index'),
                'add_config' => route('shadowsocks.add_config', ['server_id' => $data->id]),
                'show_config' => route('shadowsocks.show_config', ['server_id' => $data->id]),
                'start' => route('shadowsocks.start', ['server_id' => $data->id]),
                'restart' => route('shadowsocks.restart', ['server_id' => $data->id]),
                'stop' => route('shadowsocks.stop', ['server_id' => $data->id]),
                'status' => route('shadowsocks.status', ['server_id' => $data->id]),
                'delete_config' => route('shadowsocks.config_delete', ['server_id' => $data->id]),
            ],
        ];
    }
}
