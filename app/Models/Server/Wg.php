<?php

namespace App\Models\Server;

use App\Models\Master;
use App\Models\Server\Peer;
use App\Transformers\Wg\WgTransformer;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wg extends Master
{
    /**
     * Name of table to the model
     * @var string
     */
    public $table = "wgs";

    /**
     * Transformer class to output information to the client
     * @var
     */
    public $transformer = WgTransformer::class;

    public $fillable = [
        'name',
        'private_key',
        'listen_port',
        'dns_1',
        'dns_2',
        'server_id',
        'active',
        'interface'
    ];

    public $append = [
        'config',
    ];

    /**
     * Relationship belongs to
     * @return BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * relationship has many
     *
     * @return HasMany
     */
    public function peers()
    {
        return $this->hasMany(Peer::class);
    }

    /**
     * Generate PrivKey
     * @return string
     */
    public function generatePrivKey()
    {
        return trim(shell_exec('wg genkey'));
    }

    /**
     * Generate a public key
     * @return string
     */
    public function generatePubKey()
    {
        return trim(shell_exec("echo {$this->private_key} | wg pubkey"));
    }

    /**
     * Get the full server
     *
     * @return string
     */
    public function getServer()
    {
        $url = str_replace(['https://', 'http://'], '', $this->server->url);
        return "{$url}:{$this->listen_port}";
    }
}
