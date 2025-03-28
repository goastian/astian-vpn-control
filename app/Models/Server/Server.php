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
        'url',
        'port',
        'active',
        'ip',
        'ss_port',
        'ss_password',
        'ss_method',
        'ss_over_https'
    ];

    /**
     * Relationship has many
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wgs()
    {
        return $this->hasMany(Wg::class);
    }

    /**
     * Show the IP Address 
     * @return string
     */
    public function getIpAddress()
    {
        $domain = parse_url($this->url, PHP_URL_HOST);
        return gethostbyname($domain);
    }
}
