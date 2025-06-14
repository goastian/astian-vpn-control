<?php

namespace App\Repositories\Server;

use App\Wrapper\Core;
use App\Models\Server\Wg;
use App\Models\Server\Peer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Traits\Generic;
use App\Repositories\Traits\Wireguard;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Transformers\User\UserWireguardTransformer;


class WireguardRepository implements Contracts
{
    use JsonResponser, Generic, Wireguard;

    /**
     * Wireguard
     * @var 
     */
    public $model;

    /**
     * Summary of __construct
     * @param   \App\Models\Server\Wg $wg
     */
    public function __construct(Wg $wg)
    {
        $this->model = $wg;
    }
    /**
     * Search data
     * @return JsonResponser
     */
    public function search(Request $request)
    {
        $request->merge([
            'order_type' => 'desc'
        ]);

        $params = $this->filter_transform($this->model->transformer);

        $data = $this->model->query();
        $data = $data->with(['server', 'peers']);
        $data = $this->searchByBuilder($data, $params);
        $data = $this->orderByBuilder($data, $this->model->transformer);

        return $this->showAllByBuilder($data, $this->model->transformer);
    }

    /**
     * Create new resource
     * @param array $data
     * @return JsonResponser
     */
    public function create(array $data)
    {
        $last_subnet = $this->model->latest()->first();

        $network = $this->generateNextSubnet($last_subnet ? $last_subnet->subnet : null);
        $subnet = "{$network['subnet']}/{$network['prefix']}";
        $gateway = "{$network['gateway']}/{$network['prefix']}";

        throw_if($this->model->count() >= 10, new ReportError(__('The limit has been exceeded'), 403));

        $model = $this->model->create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name'], "-"),
            'private_key' => $this->generatePrivKey(),
            'listen_port' => $data['listen_port'],
            'subnet' => $subnet,
            'gateway' => $gateway,
            'dns' => $data['dns'],
            'active' => true,
            'interface' => $data['interface'],
            'server_id' => $data['server_id'],
            'enable_dns' => $data['enable_dns'],
        ]);

        $core = new Core($model->server->ip, $model->server->port);
        $core->mountInterface(
            $model->slug,
            $model->subnet,
            $model->gateway,
            $model->private_key,
            $model->interface,
            $model->listen_port
        );


        return $this->showOne($model, $this->model->transformer, 201);
    }

    /**
     * Update new resource
     * @param string $id
     * @param mixed $data
     * @return JsonResponser
     */
    public function update(string $id, $data)
    {
        $model = $this->model->find($id);
        $model->dns = $data['dns'];
        $model->enable_dns = $data['enable_dns'];

        $model->push();

        return $this->showOne($model, $model->transformer, 200);
    }

    /**
     * Retrieve resource by $id
     * @param string $id
     * @return JsonResponser
     */
    public function find(string $id)
    {
        $model = $this->model->find($id);

        return $this->showOne($model, $this->model->transformer);
    }

    /**
     * Destroy resource by id
     * @param string $id
     * @return JsonResponser
     */
    public function delete(string $id)
    {

        $model = $this->model->with(['server', 'peers'])->find($id);

        throw_if(
            $model->active,
            new ReportError(__('Unable to delete this resource because is active. Please shutdown and try again.'), 403)
        );

        //throw_if($wg->peers->count() > 0, new ReportError(__('Unable to delete this resource because it has assigned dependencies. Please remove any associated resources first.'), 403));

        DB::transaction(function () use ($model) {

            $core = new Core($model->server->ip, $model->server->port);
            $core->removeInterface($model->slug);

            $status = $model->delete();

            if ($status) {
                Peer::where('wg_id', $model->id)->delete();
            }
        });

        return $this->showOne($model, $this->model->transformer);
    }

    /**
     * Toggle wireguard network interface
     * @param string $id
     * @return JsonResponser
     */
    public function toggle(string $id)
    {
        $model = $this->model->find($id);

        //open connection
        $core = new Core($model->server->ip, $model->server->port);

        DB::transaction(function () use ($model, $core) {

            $peers = Peer::query();
            $peers = $peers->where('wg_id', $model->id);


            if ($model->active) {//shutdown interface
                $core->shutdownInterface($model->slug);
                $model->active = !$model->active;
                $status = $model->push();

                if ($status) {
                    $peers = $peers->where('active', 1);
                    $peers->update(['active' => 0, 'stand_by' => 1]);
                }
            } else {//start interface
                $core->startInterface($model->slug);
                $model->active = !$model->active;
                $status = $model->push();
                if ($status) {
                    $peers = $peers->where('stand_by', 1);
                    $peers->update(['active' => 1, 'stand_by' => 0]);
                }
            }
        });

        return $this->showOne($model, $model->transformer, 200);
    }

    /**
     * Reload the wireguard network interface
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function reloadInterface($id)
    {
        $model = $this->model->find($id);

        $core = new Core($model->server->ip, $model->server->port);
        $core->reloadNetwork($model->slug);
        $model->active = true;
        $model->push();

        return $this->showOne($model, $model->transformer, 200);
    }

    /**
     * Show server to the user
     * @return JsonResponser
     */
    public function searchForUser()
    {
        $data = $this->model->query();

        //Retrieve the active interfaces
        $data = $this->model->with(['server', 'peers'])
            ->where('active', true);

        return $this->showAllByBuilder($data, UserWireguardTransformer::class);
    }
}
