<?php

namespace App\Models\Server;

use App\Models\Master;
use App\Models\Server\Wg;
use App\Transformers\Server\ServerTransformer;

class Server extends Master
{
    public $table = 'servers';

    public $transformer = ServerTransformer::class;

    public $fillable = [
        'country',
        'city',
        //  'ipv6',
        'ipv4',
        'uri',
        'active'
    ];

    /**
     * @return HasMany
     */
    public function wgs()
    {
        return $this->hasMany(Wg::class);
    }

}
