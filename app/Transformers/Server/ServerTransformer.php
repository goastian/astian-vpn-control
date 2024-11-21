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
            'ipv4' => $data->ipv4,
            'port' => $data->port,
            'active' => $data->active,
            'updated' => $data->updated_at,
            'created' => $data->created_at,
            'links' => [
                'index' => route('servers.index'),
                'store' => route('servers.store'),
                'show' => route('servers.show', ['server' => $data->id]),
                'update' => route('servers.update', ['server' => $data->id]),
                'delete' => route('servers.destroy', ['server' => $data->id]),
                'toggle' => route('servers.toggle', ['server' => $data->id]),
            ],
        ];
    }

    /**
     * Retrieve Original Attributes to filter data
     *
     * @param string $index
     * @return Array
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'country' => 'country',
            'ipv4' => 'ipv4',
            'port' => 'port',
            'ipv6' => 'ipv6',
            'uri' => 'uri',
            'active' => 'active',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
