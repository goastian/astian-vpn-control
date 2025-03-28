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
            'url' => $server->url,
            'port' => $server->port,
            'active' => $server->active,
            'ipv4' => $server->getIpAddress(),
            'ss_domain' => parse_url($server->url, PHP_URL_HOST),
            'ss_port' => $server->ss_port,
            'ss_method' => $server->ss_method,
            'ss_password' => $server->ss_password,
            'ss_over_https' => $server->ss_over_https,
            'updated' => $server->updated_at,
            'created' => $server->created_at,
            'links' => [
                'index' => route('servers.index'),
                'store' => route('servers.store'),
                'show' => route('servers.show', ['server' => $server->id]),
                'update' => route('servers.update', ['server' => $server->id]),
                'delete' => route('servers.destroy', ['server' => $server->id]),
                'add_config' => route('shadowsocks.add_config', ['server_id' => $server->id]),
                'show_config' => route('shadowsocks.show_config', ['server_id' => $server->id]),
                'start' => route('shadowsocks.start', ['server_id' => $server->id]),
                'restart' => route('shadowsocks.restart', ['server_id' => $server->id]),
                'stop' => route('shadowsocks.stop', ['server_id' => $server->id]),
                'status' => route('shadowsocks.status', ['server_id' => $server->id]),
                'delete_config' => route('shadowsocks.config_delete', ['server_id' => $server->id]),
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
            'url' => 'url',
            'port' => 'port',
            'ipv6' => 'ipv6',
            'uri' => 'uri',
            'active' => 'active',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
