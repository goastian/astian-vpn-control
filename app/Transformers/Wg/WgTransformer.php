<?php

namespace App\Transformers\Wg;

use App\Models\Server\Wg;
use App\Wrapper\Core;
use League\Fractal\TransformerAbstract;

class WgTransformer extends TransformerAbstract
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
            'subnet' => $data->subnet,
            'listen_port' => $data->listen_port,
            'interface' => $data->interface,
            'gateway' => $data->gateway,
            'dns' => $data->dns,
            'created' => $data->created_at,
            'updated' => $data->updated_at,
            'active' => $data->active,
            'server_country' => $data->server->country,
            'server_url' => $data->server->url,
            'server_id' => $data->server->id,
            'links' => [
                'index' => route('wgs.index'),
                'store' => route('wgs.store'),
                'show' => route('wgs.show', ['wg' => $data->id]),
                'delete' => route('wgs.destroy', ['wg' => $data->id]),
                'toggle' => route('wgs.toggle', ['wg' => $data->id]),
                'reload' => route('wgs.reload', ['wg' => $data->id]),
            ],
        ];
    }

    /**
     * Retrieve Original Attributes to filter data
     *
     * @param string $index
     * @return Array|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'name' => 'name',
            'subnet' => 'subnet',
            'private_key' => 'private_key',
            'listen_port' => 'listen_port',
            'gateway' => 'gateway',
            'dns' => 'dns',
            'active' => 'active',
            'created' => 'created_at',
            'updated' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
