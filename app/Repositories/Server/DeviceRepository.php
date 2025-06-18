<?php
namespace App\Repositories\Server;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Server\Device;
use App\Repositories\Traits\Generic;
use Illuminate\Database\QueryException;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\Passport\Connect\Traits\Passport;
use Elyerr\ApiResponse\Exceptions\ReportError;

class DeviceRepository implements Contracts
{

    use JsonResponser, Passport, Generic;

    /**
     * 
     * @var \App\Models\Server\Device $server
     */
    public $model;


    /**
     * Constructor
     * @param \App\Models\Server\Device $device
     */
    public function __construct(Device $device)
    {
        $this->model = $device;
    }

    /**
     * Search data
     * @return JsonResponser
     */
    public function search(Request $request)
    {
        $data = $this->model->query();

        $user = $this->user();

        $data->where('user_id', $user->id);

        return $this->showAllByBuilder($data, $this->model->transformer);
    }

    /**
     * Create new resource
     * @param array $data
     * @return JsonResponser
     */
    public function create(array $data)
    {
        $user = $this->user();

        if (!app()->environment(['local', 'dev'])) {
            //user access
            $this->verifyPlan($user);
        }

        $id = $data['id'] ?? null;
        $key = Str::random(25);
        $user_agent = request()->header('user-agent');
        $user_ip = request()->ip();

        if (!empty($id)) {
            $device = $this->model->find($id);
            $device->agent = $user_agent;
            $device->ip_address = $user_ip;
            $device->name = $data['name'] ?? null;
            $device->key = $key;
            $device->push();

            return $this->showOne($device, $device->transformer, 200);
        }

        $device = $this->model->create([
            "ip_address" => $user_ip,
            "agent" => $user_agent,
            "name" => $data['name'] ?? null,
            "key" => $key,
            "user_id" => $user->id,
        ]);

        return $this->showOne($device, $device->transformer, 201);
    }

    /**
     * Update new resource
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data)
    {

    }

    /**
     * Retrieve resource by $id
     * @param string $id
     * @return void
     */
    public function find(string $id)
    {

    }

    /**
     * Destroy resource by id
     * @param string $id
     * @return JsonResponser
     */
    public function delete(string $id)
    {
        $device = $this->model->findOrFail($id);

        $device->delete();

        return $this->showOne($device, $device->transformer);
    }


    /**
     * Device verification by id
     * @param \Illuminate\Http\Request $request
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return JsonResponser
     */
    public function validateDevice(Request $request)
    {
        try {
            $device = $this->model->find($request->header('X-Device-ID') ?? null);

            if ($device && $device->id) {
                return $this->message(null, 200);
            }
        } catch (QueryException $th) {
        }

        throw new ReportError("Unauthorized", 401);
    }
}
