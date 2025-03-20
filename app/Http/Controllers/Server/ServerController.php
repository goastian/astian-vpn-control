<?php

namespace App\Http\Controllers\Server;

use App\Rules\BooleanRule;
use App\Wrapper\Core;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Server\Server;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GlobalController;
use Elyerr\ApiResponse\Exceptions\ReportError;

class ServerController extends GlobalController
{
    public function __construct()
    {
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_view')->only('index', 'interfaces');
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_create')->only('store');
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_show')->only('show');
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_update')->only('update', 'toggle');
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Server $server)
    {
        $this->checkMethod('get');

        //filter params
        $params = $this->filter_transform($server->transformer);

        $data = $server->query();

        $data = $this->searchByBuilder($data, $params);

        $data = $this->orderByBuilder($data, $server->transformer);

        return $this->showAllByBuilder($data, $server->transformer);
    }

    /**
     * create a new resource
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Server $server
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Server $server)
    {
        $request->validate([
            'country' => ['string', 'max:190'],
            'url' => ['required', 'unique:servers,url', 'url:http,https'],
            'port' => ['required', 'max:6'],
            'ip' => ['required', 'unique:servers,ipv4', 'ipv4'],
            "ss_port" => ['nullable'],
            "ss_method" => ['nullable'],
        ]);

        if (!$request->has('method')) {
            $request->merge([
                'ss_method' => 'chacha20-ietf-poly1305',
                'ss_password' => Str::random(32)
            ]);
        }

        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

        DB::transaction(function () use ($request, $server) {
            $server = $server->fill($request->all());
            $server->save();
        });

        return $this->showOne($server, $server->transformer, 201);
    }

    /**
     * show details
     * @param \App\Models\Server\Server $server
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Server $server)
    {
        $this->checkMethod('get');
        $this->checkContentType(null);

        return $this->showOne($server);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Server $server)
    {
        $request->validate([
            'country' => ['string', 'max:190'],
            'url' => ['required', 'unique:servers,url,' . $server->id, 'url:http,https'],
            'port' => ['required', 'max:6'],
            "ss_port" => ['nullable'],
            "ss_method" => ['nullable'],
            "generate_password" => ['nullable', new BooleanRule()],
        ]);



        $this->checkMethod('put');
        $this->checkContentType($this->getUpdateHeader());

        DB::transaction(function () use ($request, $server) {

            $updated = false;

            if ($request->has('country') && $server->country != $request->country) {
                $updated = true;
                $server->country = $request->country;
            }

            if ($request->has('url') && $server->url != $request->url) {
                $updated = true;
                $server->url = $request->url;
            }

            if ($request->has('port') && $server->port != $request->port) {
                $updated = true;
                $server->port = $request->port;
            }

            if ($request->has('ss_port') && $server->ss_port != $request->ss_port) {
                $updated = true;
                $server->ss_port = $request->ss_port;
            }

            if ($request->has('ss_method') && $server->ss_method != $request->ss_method) {
                $updated = true;
                $server->ss_method = $request->ss_method;
            }

            if ($request->has('generate_password') && $request->generate_password) {
                $updated = true;

                $server->ss_password = Str::random(32);
            }

            if ($updated) {
                $server->push();
            }
        });

        return $this->showOne($server, $server->transformer, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Server $server)
    {
        $this->checkMethod('delete');
        $this->checkContentType(null);

        throw_if($server->wgs()->count() > 0, new ReportError(__('Unable to delete this resource because it has assigned dependencies. Please remove any associated resources first.'), 403));

        $server->delete();

        return $this->showOne($server, $server->transformer);
    }

    /**
     * On and Off Server
     * @param \App\Models\Server\Server $server
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function toggle(Server $server)
    {
        $this->checkMethod('put');
        $this->checkContentType(null);

        $server->active = !$server->active ? now() : null;
        $server->push();

        return $this->showOne($server, $server->transformer, 201);
    }


    /**
     * show interfaces of interfaces
     * @param mixed $ip
     * @param mixed $port
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function interfaces($id, Server $server)
    {
        $this->checkMethod('get');
        $this->checkContentType(null);

        $host = $server->findOrFail($id);
        $core = new Core($host->url, $host->port);
        return $core->networkInterfaces();
    }
}
