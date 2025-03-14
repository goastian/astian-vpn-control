<?php

namespace App\Models\Setting;

use App\Models\Master;
use Illuminate\Support\Facades\Config;
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
        'user_id',
        'group'
    ];

    /**
     * Load default settings
     * @return void
     */
    public static function defaultSetting()
    {
        $data = json_decode(file_get_contents(base_path('database/extra/settings.json')));

        foreach ($data as $setting) {
            settingLoad($setting->key, $setting->value, $setting->group);
        }
    }

    /**
     * Load default settings
     * @return void
     */
    public function loadSetting()
    {
        //
        Config::set('app.name', settingItem('app.name', 'VPN Server'));


        //Plan config
        Config::set('vpn.free', settingItem('vpn.free', 1));
        Config::set('vpn.basic', settingItem('vpn.basic', 2));
        Config::set('vpn.intermediate', settingItem('vpn.intermediate', 5));
        Config::set('vpn.advanced', settingItem('vpn.advanced', 10));
    }

}
