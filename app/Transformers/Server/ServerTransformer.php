<?php

namespace App\Transformers\Server;

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
            'created' => $this->format_date($server->created_at),
            'updated' => $this->format_date($server->updated_at),
            'links' => [
                'index' => route('servers.index'),
                'store' => route('servers.store'),
                'show' => route('servers.show', ['server' => $server->id]),
                'update' => route('servers.update', ['server' => $server->id]),
                'delete' => route('servers.destroy', ['server' => $server->id]),
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
