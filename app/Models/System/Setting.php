<?php

namespace App\Models\System;

use App\Models\Master;
use App\Transformers\Setting\SettingTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Master
{
    use HasFactory;

    public $table = "settings"; 
    
    public $transformer = SettingTransformer::class;

    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
        'system',
        'user_id',
    ];


    /**
     * Set default config
     * @return mixed
     */
    public static function defaultSetting()
    {
        return json_decode(file_get_contents(base_path('database/setting/setting.json')));
    }
}
