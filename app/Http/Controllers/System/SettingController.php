<?php
namespace App\Http\Controllers\System;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\System\Setting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{

    public function __construct()
    {
        $this->middleware('scope:vpn-full')->except('index');
    }

    /**
     * show the all key registered
     * @param \App\Models\System\Setting $setting
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Setting $setting)
    {
        $params = $this->filter_transform($setting->transformer);

        $data = $setting->query();

        $this->search($data, $params);

        $data = $data->get();

        return $this->showAll($data, $setting->transformer);
    }


    /**
     * Add new key 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\System\Setting $setting
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Setting $setting)
    {
        $this->validate($request, [
            'key' => ['required'],
        ]);

        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

        $request->merge([
            'key' => Str::slug($request->key, '.')
        ]);

        DB::transaction(function () use ($request, $setting) {

            $setting->updateOrCreate(
                [
                    'key' => $request->key
                ],
                [
                    'key' => $request->key,
                    'value' => $request->value,
                    'user_id' => $request->system === false ? $this->user()->id : null,
                    'system' => $request->system,
                ]
            );
        });

        return $this->message(__('New key create successfully'), 201);
    }
}
