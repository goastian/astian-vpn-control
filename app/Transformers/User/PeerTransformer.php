<?php

namespace App\Transformers\User;

use App\Models\Server\Peer;
use League\Fractal\TransformerAbstract;

class PeerTransformer extends TransformerAbstract
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
            'name' => $data->name,
            'active' => $data->active,
            'stand_by' => $data->stand_by,
            'created' => $data->created_at,
            'updated' => $data->updated_at,
            'config' => $data->config ?: null,
            'network' => [
                'name' => $data->wg->name,
                'listen_port' => $data->wg->listen_port,
                'server_name' => $data->wg->server->country,
            ],
            'links' => [
                'index' => route('api.v1.users.wireguard.peers.index'),
                'store' => route('api.v1.users.wireguard.peers.store'),
                'delete' => route('api.v1.users.wireguard.peers.destroy', ['peer' => $data->id]),
                'toggle' => route('api.v1.users.wireguard.peers.toggle', ['peer' => $data->id]),
            ],
        ];
    }

    /**
     * Retrieve Original Attributes to filter data
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'name' => 'name',
            'public_key' => 'public_key',
            'preshared_key' => 'preshared_key',
            'allowed_ips' => 'allowed_ips',
            'persistent_keepalive' => 'persisten_keepalive',
            'endpoint' => 'endpoint',
            'active' => 'active',
            'created' => 'created_at',
            'updated' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;

    }
}
