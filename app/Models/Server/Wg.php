<?php

namespace App\Models\Server;

use App\Models\Master;
use App\Models\Server\Peer;
use App\Transformers\Wg\WgTransformer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wg extends Master
{
    public $table = "wgs";

    public $transformer = WgTransformer::class;

    public $fillable = [
        'name',
        'private_key',
        'listen_port',
        'dns_1',
        'dns_2',
        'server_id',
        'active',
    ];

    public $append = [
        'config',
    ];

    /**
     * @return BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * Peers
     *
     * @return HasMany
     */
    public function peers()
    {
        return $this->hasMany(Peer::class);
    }

    /**
     * Generate a private key
     */
    public function genratePrivKey()
    {
        return trim(shell_exec('wg genkey'));
    }

    /**
     * Generate public key
     */
    public function generatePubKey()
    {
        return trim(shell_exec("echo {$this->private_key} | wg pubkey"));
    }

    /**
     * Endpoint
     *
     * @return String
     */
    public function getEndpoint()
    {
        return "{$this->server->ipv4}:{$this->listen_port}";
    }
}
