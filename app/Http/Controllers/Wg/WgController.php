<?php

namespace App\Http\Controllers\Wg;

use App\Wrapper\Core;
use App\Models\Server\Wg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Http\Controllers\GlobalController as Controller;

class WgController extends Controller
{
    public function __construct()
    {
        parent::__construct();
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

        if (count($params) > 0) {
            foreach ($params as $key => $value) {
                $data = $data->where($key, 'like', "%" . $value . "%");
            }
        }

        $data = $data->get();

        return $this->showAll($data, $wg->transformer);
    }

    /**
     * Create a new resource
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Wg $wg
     * @return \Elyerr\ApiResponse\Assets\Json
     */
    public function store(Request $request, Wg $wg)
    {
        $this->checkMethod('post');

        $last_subnet = $wg->latest()->first();

        $subnet = $this->generateNextSubnet($last_subnet ? $last_subnet->subnet : null);

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
            'dns_1' => ['nullable', 'max:190'],
            'dns_2' => ['nullable', 'max:190'],
        ]);


        DB::transaction(function () use ($request, $wg, $subnet) {

            $wg = $wg->fill($request->except('private_key'));
            $wg->private_key = $wg->generatePrivKey();
            $wg->active = true;
            $wg->subnet = $subnet;
            $wg->save();
            $core = new Core($wg->server->url, $wg->server->port);
            $core->mountInterface(
                $request->name,
                $subnet,
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
        $this->checkMethod("put");

        throw_if($wg->active, new ReportError(__('This action can be done, please stop the Wireguard Interface'), 422));

        $request->validate([
            'name' => ['nullable', 'max:20'],
            'dns_1' => ['nullable', 'max:190'],
            'dns_2' => ['nullable', 'max:190'],
        ]);

        DB::transaction(function () use ($request, $wg) {

            $updated = false;

            if ($this->is_different($wg->dns_1, $request->dns_1)) {
                $updated = true;
                $wg->dns_1 = $request->dns_1;
            }

            if ($this->is_different($wg->dns_2, $request->dns_2)) {
                $updated = true;
                $wg->dns_2 = $request->dns_2;
            }

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
    public function destroy(Wg $wg)
    {
        $this->checkMethod("delete");

        throw_if($wg->active, new ReportError(__('Unable to delete this resource because is active. Please shutdown and try again.'), 403));
        throw_if($wg->peers()->count() > 0, new ReportError(__('Unable to delete this resource because it has assigned dependencies. Please remove any associated resources first.'), 403));

        DB::transaction(function () use ($wg) {

            $core = new Core($wg->server->url, $wg->server->port);
            $core->removeInterface($wg->name, $wg->interface);

            $wg->delete();
        });

        return $this->showOne($wg, $wg->transformer);
    }

    /**
     * On and off network interface
     *
     * @param Wg $wg
     */
    public function toggle(Wg $wg)
    {
        $this->checkMethod('put');

        if ($wg->active) {
            $core = new Core($wg->server->url, $wg->server->port);
            $core->shutdownInterface($wg->name);
            $wg->active = !$wg->active;
            $wg->push();
        } else {
            $core = new Core($wg->server->url, $wg->server->port);
            $core->startInterface($wg->name);
            $wg->active = !$wg->active;
            $wg->push();
        }

        return $this->showOne($wg, $wg->transformer, 201);
    }

    /**
     * Reload Wireguard Network using a config file
     * @param \App\Models\Server\Wg $wg
     * @return \Elyerr\ApiResponse\Assets\Json
     */
    public function reload(Wg $wg)
    {
        $this->checkMethod('get');

        $core = new Core($wg->server->url, $wg->server->port);
        $core->reloadNetwork($wg->name);
        return $this->showOne($wg, $wg->transformer, 201);
    }
}
