<?php
namespace App\Transformers\Setting;

use App\Models\Setting\Setting;
use League\Fractal\TransformerAbstract;

class SettingTransformer extends TransformerAbstract
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
    public function transform(Setting $data)
    {
        return [
            'id' => $data->id,
            'key' => $data->key,
            'value' => $data->value,
            'group' => $data->group
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
            'id' => 'id',
            'key' => 'key',
            'value' => 'value',
            'group' => 'group'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
