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
    public function transform(Wg $wg)
    {
        return [
            'id' => $wg->id,
            'name' => $wg->name,
            'subnet' => $wg->subnet,
            'listen_port' => $wg->listen_port,
            'interface' => $wg->interface,
            'gateway' => $wg->gateway,
            'dns' => $wg->dns,
            'enable_dns' => $wg->enable_dns ? true : false,
            'created' => $wg->created_at,
            'updated' => $wg->updated_at,
            'active' => $wg->active,
            'server_country' => $wg->server->country,
            'server_url' => $wg->server->url,
            'server_id' => $wg->server->id,
            "devices" => $wg->peers()->count(),
            'links' => [
                'index' => route('wgs.index'),
                'store' => route('wgs.store'),
                'show' => route('wgs.show', ['wg' => $wg->id]),
                'update' => route('wgs.show', ['wg' => $wg->id]),
                'delete' => route('wgs.destroy', ['wg' => $wg->id]),
                'toggle' => route('wgs.toggle', ['wg' => $wg->id]),
                'reload' => route('wgs.reload', ['wg' => $wg->id]),
            ],
        ];
    }

    /**
     * Retrieve Original Attributes to filter wg
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
