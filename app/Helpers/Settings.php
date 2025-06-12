<?php

use App\Models\Setting\Setting;
use Illuminate\Database\QueryException;

if (!function_exists('settingLoad')) {
    /**
     * Add an item only if it does not exist
     * @param mixed $key
     * @param mixed $value
     * @param mixed $group
     * @param mixed $userId
     * @return void
     */
    function settingLoad($key, $value, $group = null, $userId = null)
    {
        try {
            Setting::firstOrCreate(
                [
                    'key' => $key,
                    'user_id' => $userId,
                    'group' => $group
                ],
                [
                    'key' => $key,
                    'value' => $value,
                    'group' => $group ? Str::slug($group, '_') : null
                ]
            );
        } catch (\Throwable $th) {
        }
    }
}


if (!function_exists('settingAdd')) {
    /**
     * Add an item only if it does not exist
     * @param mixed $key
     * @param mixed $value
     * @param mixed $group
     * @param mixed $userId
     * @return void
     */
    function settingAdd($key, $value, $group = null, $userId = null)
    {
        try {
            Setting::updateOrCreate(
                [
                    'key' => $key,
                    'user_id' => $userId,
                    'group' => $group
                ],
                [
                    'key' => $key,
                    'value' => $value,
                    'group' => $group ? Str::slug($group, '_') : null
                ]
            );
        } catch (\Throwable $th) {
        }
    }
}


if (!function_exists('settingItem')) {

    /**
     * Get the setting item
     * @param mixed $key
     * @param mixed $default
     * @param mixed $group
     * @param mixed $userId
     */
    function settingItem($key, $default = null, $group = null, $userId = false)
    {
        try {

            $setting = Setting::where('key', $key)->where('group', $group)->first();

            return $setting ? $setting->value : $default;

        } catch (QueryException $e) {
            return $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}
