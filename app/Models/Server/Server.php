<?php

namespace App\Models\Server;

use App\Models\Master;
use App\Models\Server\Wg;
use App\Transformers\Admin\ServerTransformer;

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
        'port',
        'ip',
        'client_port',
        'socks5_port',
        'url'
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
