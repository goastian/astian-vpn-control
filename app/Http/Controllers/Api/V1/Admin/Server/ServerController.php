<?php
namespace App\Http\Controllers\Api\V1\Admin\Server;

use App\Wrapper\Core;
use Illuminate\Http\Request;
use App\Models\Server\Server;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Elyerr\ApiResponse\Exceptions\ReportError;

class ServerController extends ApiController
{
    public function __construct()
    {
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_view')->only('index', 'interfaces');
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
            'country' => ['required', 'max:190'],
            'ip' => ['required', 'ipv4'],
            'port' => ['required', 'max:6'],
            'client_port' => ['nullable', 'max:5'],
            'socks5_port' => ['nullable', 'max:5']
        ]);

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
        return $this->showOne($server, $server->transformer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Server $server)
    {
        $request->validate([
            'country' => ['nullable', 'max:190'],
            'ip' => ['required', 'ipv4'],
            'port' => ['nullable', 'max:6'],
            'client_port' => ['nullable', 'max:5'],
            'socks5_port' => ['nullable', 'max:5']
        ]);

        DB::transaction(function () use ($request, $server) {

            if (!empty($request->ip)) {
                $server->ip = $request->ip;
            }

            if (!empty($request->country)) {
                $server->country = $request->country;
            }

            if (!empty($request->port)) {
                $server->port = $request->port;
            }

            if (!empty($request->client_port)) {
                $server->client_port = $request->client_port;
            }

            if ($request->socks5_port) {
                $server->socks5_port = $request->socks5_port;
            }
            $server->push();
        });

        return $this->showOne($server, $server->transformer, 200);
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
     * @param mixed $id
     * @param \App\Models\Server\Server $server
     * @return array{interface: mixed[]}
     */
    public function interfaces($id, Server $server)
    {
        $host = $server->findOrFail($id);
        $core = new Core($host->ip, $host->port);

        $data = $core->networkInterfaces();
        return $data;
    }
}
