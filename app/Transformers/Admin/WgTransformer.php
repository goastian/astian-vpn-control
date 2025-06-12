<?php

namespace App\Transformers\Admin;

use App\Models\Server\Wg;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class WgTransformer extends TransformerAbstract
{
    use Asset;
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
            'created' => $this->format_date($wg->created_at),
            'updated' => $this->format_date($wg->updated_at),
            'active' => $wg->active,
            'server_country' => $wg->server->country,
            'server_url' => $wg->server->url,
            'server_id' => $wg->server->id,
            "devices" => $wg->peers()->count(),
            'links' => [
                'index' => route('api.v1.admin.wireguard.index'),
                'store' => route('api.v1.admin.wireguard.store'),
                'show' => route('api.v1.admin.wireguard.show', ['wg' => $wg->id]),
                'update' => route('api.v1.admin.wireguard.show', ['wg' => $wg->id]),
                'delete' => route('api.v1.admin.wireguard.destroy', ['wg' => $wg->id]),
                'toggle' => route('api.v1.admin.wireguard.toggle', ['wg' => $wg->id]),
                'reload' => route('api.v1.admin.wireguard.reload', ['wg' => $wg->id]),
            ],
        ];
    }

    /**
     * Original attributes to filter
     * @param mixed $index
     * @return string|null
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
