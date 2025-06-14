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
}
