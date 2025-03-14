<?php

use App\Models\Setting\Setting;
use Illuminate\Database\QueryException;

if (!function_exists('settingLoad')) {
    /**
     * Add an item only if it does not exist
     * @param mixed $key
     * @param mixed $value
     * @param mixed $userId
     * @return void
     */
    function settingLoad($key, $value, $userId = null)
    {
        Setting::firstOrCreate(
            [
                'key' => $key,
                'user_id' => $userId
            ],
            [
                'key' => $key,
                'value' => $value
            ]
        );
    }
}


if (!function_exists('settingAdd')) {
    /**
     * Add an item only if it does not exist
     * @param mixed $key
     * @param mixed $value
     * @param mixed $userId
     * @return void
     */
    function settingAdd($key, $value, $userId = null)
    {
        Setting::updateOrCreate(
            [
                'key' => $key,
                'user_id' => $userId
            ],
            [
                'key' => $key,
                'value' => $value
            ]
        );
    }
}


if (!function_exists('settingItem')) {

    /**
     * Get the setting item
     * @param mixed $key
     * @param mixed $default
     * @param mixed $userId
     */
    function settingItem($key, $default = null, $userId = false)
    {
        try {

            $setting = Setting::where('key', $key)->first();

            return $setting ? $setting->value : $default;

        } catch (QueryException $e) {
            return $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}
