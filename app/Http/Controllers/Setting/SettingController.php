<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use App\Models\Setting\Setting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GlobalController as Controller;

class SettingController extends Controller
{

    public function __construct()
    {
        $this->middleware('scope:administrator_settings_full,administrator_settings_view')->only('index');
        $this->middleware('scope:administrator_settings_full,administrator_settings_update')->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Setting $setting)
    {
        $data = $setting->query();

        $params = $this->filter_transform($setting->transformer);

        $data = $this->searchByBuilder($data, $params);

        return $this->showAllByBuilder($data, $setting->transformer);
    }

    /**
     * Summary of store
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return void
     */
    public function store(Request $request, Setting $setting)
    {
        $data = $this->transformRequest($request->except('group'));
        $group = $request->group;

        DB::transaction(function () use ($data, $group) {

            foreach ($data as $key => $value) {
                settingAdd($key, $value, $group);
            }
        });

        return $this->message(__('Settings updated successfully'), 201);
    }
}
