<?php

namespace App\Models\Server;

use App\Models\Master;
use App\Models\Server\Peer; 
use App\Transformers\Admin\WgTransformer;
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
        'slug',
        'private_key',
        'listen_port',
        'subnet',
        'gateway',
        'dns',
        'active',
        'interface',
        'server_id',
        'enable_dns'
    ];

    public $cast = [
        'enable_dns' => 'bool'
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
        return "{$this->server->ip}:{$this->listen_port}";
    }

    /**
     * Get the endpoint server
     * @return string
     */
    public function getEndpoint()
    {
        $domain = $this->server->ip;
        return "{$domain}:{$this->listen_port}";
    }
}
