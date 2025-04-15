<?php

namespace App\Transformers\Server;

use App\Models\Server\Server;
use League\Fractal\TransformerAbstract;

class ServerTransformer extends TransformerAbstract
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
            'id' => $server->id,
            'country' => $server->country,
            'ip' => $server->ip,
            'port' => $server->port,
            'active' => $server->active,
            'ss_port' => $server->ss_port,
            'ss_method' => $server->ss_method,
            'ss_password' => $server->ss_password, 
            'dns' => $server->dns,
            'updated' => $server->updated_at,
            'created' => $server->created_at,
            'links' => [
                'index' => route('servers.index'),
                'store' => route('servers.store'),
                'show' => route('servers.show', ['server' => $server->id]),
                'update' => route('servers.update', ['server' => $server->id]),
                'delete' => route('servers.destroy', ['server' => $server->id]),

                //'server_add_config' => route('shadowsocks.server.add_config', ['server_id' => $server->id]),
                
                'server_start' => route('shadowsocks.server.start', ['server_id' => $server->id]),
                'server_stop' => route('shadowsocks.server.stop', ['server_id' => $server->id]),
                'server_status' => route('shadowsocks.server.status', ['server_id' => $server->id]),

                'client_start' => route('shadowsocks.client.start', ['server_id' => $server->id]),
                'client_stop' => route('shadowsocks.client.stop', ['server_id' => $server->id]),
                'client_status' => route('shadowsocks.client.status', ['server_id' => $server->id]),
            ],
        ];
    }

    /**
     * Retrieve Original Attributes to filter server
     *
     * @param string $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'country' => 'country', 
            'port' => 'port',
            'ip' => 'ip',
            'uri' => 'uri',
            'active' => 'active',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
