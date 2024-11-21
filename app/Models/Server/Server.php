<?php

namespace App\Models\Server;

use App\Models\Master;
use App\Models\Server\Wg;
use App\Transformers\Server\ServerTransformer;

class Server extends Master
{
    /**
     * Name of table to the model
     * @var string
     */
    public $table = 'servers';

    /**
     * Transformer class to output information to the client
     * @var
     */
    public $transformer = ServerTransformer::class;

    public $fillable = [
        'country',
        'ipv4',
        'port',
        'active'
    ];

    /**
     * Relationship has many
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wgs()
    {
        return $this->hasMany(Wg::class);
    }

}
