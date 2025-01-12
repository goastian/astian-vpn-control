<?php

namespace App\Transformers\Peer;

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
        if (!($data instanceof Peer)) {
            $data = Peer::findOrFail($data->id);
        }

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
                'server' => $data->wg->server->url,
                'server_name' => $data->wg->server->country,
            ],
            'links' => [
                'index' => route('peers.index'),
                'store' => route('peers.store'),
                'delete' => route('peers.destroy', ['peer' => $data->id]),
                'toggle' => route('peers.toggle', ['peer' => $data->id]),
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
