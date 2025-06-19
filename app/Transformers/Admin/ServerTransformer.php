<?php

namespace App\Transformers\Admin;

use App\Models\Server\Server;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class ServerTransformer extends TransformerAbstract
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
    public function transform(Server $server)
    {
        return [
            'id' => $server->id,
            'country' => $server->country,
            'ip' => $server->ip,
            'port' => $server->port,
            'url' => $server->url,
            'client_port' => $server->client_port ?? 1080,
            'socks5_port' => $server->socks5_port ?? 1090,
            'created' => $this->format_date($server->created_at),
            'updated' => $this->format_date($server->updated_at),
            'links' => [
                'index' => route('api.v1.admin.servers.index'),
                'store' => route('api.v1.admin.servers.store'),
                'show' => route('api.v1.admin.servers.show', ['server' => $server->id]),
                'update' => route('api.v1.admin.servers.update', ['server' => $server->id]),
                'delete' => route('api.v1.admin.servers.destroy', ['server' => $server->id]),
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
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
