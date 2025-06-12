<?php
namespace App\Http\Controllers\Api\V1\Admin\Wireguard;

use App\Wrapper\Core;
use App\Models\Server\Wg;
use App\Rules\BooleanRule;
use App\Models\Server\Peer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Elyerr\ApiResponse\Exceptions\ReportError;

class WireguardController extends ApiController
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
     * Show the all wireguard servers available
     * @param \App\Models\Server\Wg $wg
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Wg $wg)
    {
        $request->merge([
            'order_type' => 'desc' //set desc by default
        ]);

        $params = $this->filter_transform($wg->transformer);

        $data = $wg->query();

        $data = $this->searchByBuilder($data, $params);
        $data = $this->orderByBuilder($data, $wg->transformer);

        return $this->showAllByBuilder($data, $wg->transformer);
    }

    /**
     * Add new wireguard interface
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Wg $wg
     * @return mixed|\Illuminate\Http\JsonResponse
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
            'enable_dns' => ['required', new BooleanRule()],
            'dns' => [
                function ($attribute, $value, $fail) use ($request) {
                    if ((bool) $request->enable_dns && empty($value)) {
                        $fail("The field is required");
                    }
                },
                'max:190'
            ],
        ]);

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

            $core = new Core($wg->server->ip, $wg->server->port);
            $core->mountInterface(
                $wg->slug,
                $wg->subnet,
                $wg->gateway,
                $wg->private_key,
                $wg->interface,
                $wg->listen_port
            );
        });

        return $this->showOne($wg, $wg->transformer, 201);
    }

    /**
     * Show a one resource
     * @param \App\Models\Server\Wg $wg
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Wg $wg)
    {
        return $this->showOne($wg, $wg->transformer);
    }

    /**
     * Update specific resource
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Wg $wg
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Wg $wg)
    {
        //throw_if($wg->active, new ReportError(__('This action can be done, please stop the Wg Interface'), 422));

        $request->validate([
            'enable_dns' => ['required', new BooleanRule()],
            'dns' => [
                'nullable',
                'max:190',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->has('enable_dns') && empty($value)) {
                        $fail("The field is required");
                    }
                }
            ],
        ]);


        DB::transaction(function () use ($request, $wg) {

            $wg->dns = $request->dns;
            $wg->enable_dns = $request->enable_dns;

            $wg->push();
        });

        return $this->showOne($wg, $wg->transformer, 200);
    }

    /**
     * Destroy current resource
     * @param \App\Models\Server\Wg $wg
     * @param \App\Models\Server\Peer $peer
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Wg $wg, Peer $peer)
    {
        throw_if($wg->active, new ReportError(__('Unable to delete this resource because is active. Please shutdown and try again.'), 403));
        //throw_if($wg->peers()->count() > 0, new ReportError(__('Unable to delete this resource because it has assigned dependencies. Please remove any associated resources first.'), 403));

        DB::transaction(function () use ($wg, $peer) {

            $core = new Core($wg->server->ip, $wg->server->port);
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
     * Summary of toggle
     * @param \App\Models\Server\Wg $wg
     * @param \App\Models\Server\Peer $peer
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function toggle(Wg $wg, Peer $peer)
    {
        //open connection
        $core = new Core($wg->server->ip, $wg->server->port);

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

        return $this->showOne($wg, $wg->transformer, 200);
    }

    /**
     * Reload Wg Network using a config file
     * @param \App\Models\Server\Wg $wg
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function reload(Wg $wg)
    {
        DB::transaction(function () use ($wg) {
            $core = new Core($wg->server->ip, $wg->server->port);
            $core->reloadNetwork($wg->slug);
            $wg->active = true;
            $wg->push();
        });

        return $this->showOne($wg, $wg->transformer, 201);
    }
}
