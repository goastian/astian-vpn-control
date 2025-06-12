<?php

namespace App\Models\Server;

use App\Models\Master;
use App\Models\Server\Wg; 
use App\Transformers\User\PeerTransformer;

class Peer extends Master
{
    public $table = 'peers';

    /**
     * Transform class to output information to the client
     * @var
     */
    public $transformer = PeerTransformer::class;

    public $fillable = [
        'name',
        'public_key',
        'preshared_key',
        'allowed_ips',
        'persistent_keepalive',
        'active',
        'user_id',
        'wg_id',
        'stand_by'
    ];

    /**
     * Relationship belongs to
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wg()
    {
        return $this->belongsTo(Wg::class);
    }

    /**
     *  Generate a pair keys (private and public key) for the config file to the client
     * @return string[]
     */
    public function generatePairKeys()
    {
        $private_key = trim(shell_exec('wg genkey'));
        $public_key = trim(shell_exec("echo {$private_key} | wg pubkey"));
        return ['private_key' => $private_key, 'public_key' => $public_key];
    }

    /**
     * Generate a Preshared key for the config peer
     * @return string
     */
    public function generatePresharedkey()
    {
        return trim(shell_exec('wg genpsk'));
    }

    /**
     * Generate unique IP
     *
     * @param string $subnet
     * @return string
     */
    public function generateUniqueIp($subnet = "10.0.0.0/8")
    {
        list($baseIP, $prefix) = explode('/', $subnet);
        $baseIP = ip2long($baseIP);

        $totalIPs = pow(2, (32 - $prefix));

        $randomIP = $baseIP + rand(1, $totalIPs - 2);

        return long2ip($randomIP) . "/32";

    }
}
