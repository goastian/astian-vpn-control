<?php

namespace App\Models\Server;

use App\Models\Master;
use App\Transformers\User\DeviceTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Master
{
    use HasFactory;

    public $table = "devices";

    public $transformer = DeviceTransformer::class;

    protected $fillable = [
        "ip_address",
        "key",
        "agent",
        "name",
        "user_id"
    ];

}
