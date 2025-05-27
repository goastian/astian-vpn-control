<?php

namespace App\Transformers\Peer;

use App\Models\Server\Device;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class DeviceTransformer extends TransformerAbstract
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
    public function transform(Device $device)
    {
        return [
            'id' => $device->id,
            //'key' => $device->key,
            'ip' => $device->ip_address,
            'agent' => $device->agent,
            'name' => $device->name,
            'created' => $this->format_date($device->created_at),
            'updated' => $this->format_date($device->updated_at),
            'links' => [
                'index' => route('devices.index'),
                'store' => route('devices.store'),
                'destroy' => route('devices.destroy', ['device' => $device->id]),
            ]
        ];
    }

    /**
     * Summary of getOriginalAttributes
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'key' => "key",
            'ip' => "ip_address",
            'agent' => "agent",
            'name' => "name",
            'created' => "created_at",
            'updated' => "updated_at",
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
