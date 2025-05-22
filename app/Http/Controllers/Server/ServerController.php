<?php

namespace App\Http\Controllers\Server;

use App\Http\Controllers\Socks\ShadowsocksController;
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
            'ip' => ['required', 'ipv4'],
            'port' => ['required', 'max:6'],
        ]);

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
        return $this->showOne($server);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Server $server)
    {
        $request->validate([
            'country' => ['string', 'max:190'],
            'ip' => ['required', 'ipv4'],
            'port' => ['required', 'max:6'],
        ]);

        DB::transaction(function () use ($request, $server) {

            $server->country = $request->country;

            $server->push();

        });

        return $this->showOne($server, $server->transformer, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Server $server)
    {

        throw_if($server->wgs()->count() > 0, new ReportError(__('Unable to delete this resource because it has assigned dependencies. Please remove any associated resources first.'), 403));

        $server->delete();

        return $this->showOne($server, $server->transformer);
    }

    /**
     * show interfaces of interfaces
     * @param mixed $ip
     * @param mixed $port
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function interfaces($id, Server $server)
    {
        $host = $server->findOrFail($id);
        $core = new Core($host->ip, $host->port);

        $data = $core->networkInterfaces();
        return $data;
    }
}
