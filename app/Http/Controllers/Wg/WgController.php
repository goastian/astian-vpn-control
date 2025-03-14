<?php

namespace App\Http\Controllers\Wg;

use App\Wrapper\Core;
use App\Models\Server\Wg;
use App\Models\Server\Peer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Http\Controllers\GlobalController as Controller;

class WgController extends Controller
{
    public function __construct()
    {
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_view')->only('index', 'interfaces');
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_create')->only('store');
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_show')->only('show');
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_update')->only('update', 'toggle', 'reload');
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_destroy')->only('destroy');
    }

    /**
     * Show the all resources
     * @param \App\Models\Server\Wg $wg
     * @return \Elyerr\ApiResponse\Assets\Json
     */
    public function index(Wg $wg)
    {
        $this->checkMethod('get');

        $params = $this->filter_transform($wg->transformer);
        $data = $wg->query();

        $data = $this->searchByBuilder($data, $params);

        return $this->showAllByBuilder($data, $wg->transformer);
    }

    /**
     * Create a new resource
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Wg $wg
     * @return \Elyerr\ApiResponse\Assets\Json
     */
    public function store(Request $request, Wg $wg)
    {
        $last_subnet = $wg->latest()->first();

        $network = $this->generateNextSubnet($last_subnet ? $last_subnet->subnet : null);
        $subnet = "{$network['subnet']}/{$network['prefix']}";
        $gateway = "{$network['gateway']}/{$network['prefix']}";

        throw_if($wg->count() >= 10, new ReportError(__('The limit has been exceeded'), 403));

        $request->validate([
            'name' => [
                'required',
                'max:20',
                function ($attribute, $value, $fail) use ($request) {
                    $wg = Wg::where('name', $value)->first();

                    if ($wg && $request->server_id && $wg->server_id == $request->server_id) {
                        $fail("The $value has already been registered on this server.");
                    }
                }
            ],
            'listen_port' => ['required', 'max:10', 'unique:wgs,listen_port'],
            'interface' => ['required', 'max:50'],
            'server_id' => ['required', 'exists:servers,id'],
            'dns' => ['nullable', 'max:190'],
        ]);

        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

        $request->merge([
            'slug' => Str::slug($request->name, "-")
        ]);

        DB::transaction(function () use ($request, $wg, $subnet, $gateway) {

            $wg = $wg->fill($request->except('private_key'));
            $wg->private_key = $wg->generatePrivKey();
            $wg->active = true;
            $wg->subnet = $subnet;
            $wg->gateway = $gateway;
            $wg->save();

            $core = new Core($wg->server->url, $wg->server->port);
            $core->mountInterface(
                $request->slug,
                $subnet,
                $gateway,
                $wg->private_key,
                $wg->interface,
                $wg->listen_port
            );
        });

        return $this->showOne($wg, $wg->transformer, 201);
    }

    /**
     * Show a resource
     * @param \App\Models\Server\Wg $server
     * @return \Elyerr\ApiResponse\Assets\Json
     */
    public function show(Wg $server)
    {
        $this->checkMethod('get');
        $this->checkContentType(null);

        return $this->showOne($server, $server->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wg $wg)
    {
        throw_if($wg->active, new ReportError(__('This action can be done, please stop the Wireguard Interface'), 422));

        $request->validate([
            'name' => ['nullable', 'max:20'],
            'gateway' => ['nullable', 'max:190'],
            'dns' => ['nullable', 'max:190'],
        ]);

        $this->checkMethod('put');
        $this->checkContentType($this->getUpdateHeader());

        DB::transaction(function () use ($request, $wg) {

            $updated = false;

            if ($request->has('gateway') && $wg->gateway != $request->gateway) {
                $updated = true;
                $wg->gateway = $request->gateway;
            }

            /* if ($request->has('dns') && $wg->dns != $request->dns) {
                 $updated = true;
                 $wg->dns = $request->dns;
             }*/

            if ($updated) {
                $wg->push();
            }
        });

        return $this->showOne($wg, $wg->transformer, 201);
    }

    /**
     * Destroy current resource
     * @param \App\Models\Server\Wg $wg
     * @return \Elyerr\ApiResponse\Assets\Json
     */
    public function destroy(Wg $wg, Peer $peer)
    {
        $this->checkMethod('delete');
        $this->checkContentType(null);

        throw_if($wg->active, new ReportError(__('Unable to delete this resource because is active. Please shutdown and try again.'), 403));
        //throw_if($wg->peers()->count() > 0, new ReportError(__('Unable to delete this resource because it has assigned dependencies. Please remove any associated resources first.'), 403));

        DB::transaction(function () use ($wg, $peer) {

            $core = new Core($wg->server->url, $wg->server->port);
            $core->removeInterface($wg->slug);

            $status = $wg->delete();

            if ($status) {
                $peers = $peer->query();
                $peers = $peers->where('wg_id', $wg->id);
                $peers->delete();
            }
        });

        return $this->showOne($wg, $wg->transformer);
    }

    /**
     * On and off network interface
     *
     * @param Wg $wg
     */
    public function toggle(Wg $wg, Peer $peer)
    {
        $this->checkMethod('put');
        $this->checkContentType(null);

        //open connection
        $core = new Core($wg->server->url, $wg->server->port);

        DB::transaction(function () use ($wg, $peer, $core) {

            $peers = $peer->query();
            $peers = $peers->where('wg_id', $wg->id);


            if ($wg->active) {//shutdown interface
                $core->shutdownInterface($wg->slug);
                $wg->active = !$wg->active;
                $status = $wg->push();

                if ($status) {
                    $peers = $peers->where('active', 1);
                    $peers->update(['active' => 0, 'stand_by' => 1]);
                }
            } else {//start interface
                $core->startInterface($wg->slug);
                $wg->active = !$wg->active;
                $status = $wg->push();
                if ($status) {
                    $peers = $peers->where('stand_by', 1);
                    $peers->update(['active' => 1, 'stand_by' => 0]);
                }
            }
        });

        return $this->showOne($wg, $wg->transformer, 201);
    }

    /**
     * Reload Wireguard Network using a config file
     * @param \App\Models\Server\Wg $wg
     * @return \Elyerr\ApiResponse\Assets\Json
     */
    public function reload(Wg $wg)
    {
        $this->checkMethod('put');
        $this->checkContentType(null);

        DB::transaction(function () use ($wg) {
            $core = new Core($wg->server->url, $wg->server->port);
            $core->reloadNetwork($wg->slug);
            $wg->active = true;
            $wg->push();
        });

        return $this->showOne($wg, $wg->transformer, 201);
    }
}
