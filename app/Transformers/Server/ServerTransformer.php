<?php

namespace App\Transformers\Server;

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
    public function transform($data)
    {
        return [
            'id' => $data->id,
            'country' => $data->country,
            'url' => $data->url,
            'port' => $data->port,
            'active' => $data->active,
            'ipv4' => $data->ip,
            'ss_domain' => parse_url($data->url, PHP_URL_HOST),
            'ss_port' => $data->ss_port,
            'ss_method' => $data->ss_method,
            'ss_password' => $data->ss_password,
            'updated' => $data->updated_at,
            'created' => $data->created_at,
            'links' => [
                'index' => route('servers.index'),
                'store' => route('servers.store'),
                'show' => route('servers.show', ['server' => $data->id]),
                'update' => route('servers.update', ['server' => $data->id]),
                'delete' => route('servers.destroy', ['server' => $data->id]),
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

    /**
     * Retrieve Original Attributes to filter data
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
