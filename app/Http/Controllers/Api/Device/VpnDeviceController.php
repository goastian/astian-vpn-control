<?php
namespace App\Http\Controllers\Api\Device;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Server\Device;
use App\Http\Controllers\ApiController;

class VpnDeviceController extends ApiController
{
    /**
     * Show devices
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Device $device
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Device $device)
    {
        $data = $device->query();
        $user = $this->user();
        $data->where('user_id', $user->id);

        return $this->showAllByBuilder($data, $device->transformer);
    }

    /**
     * Generate device
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "ip_address" => ['required'],
            "agent" => ['required'],
            "name" => ['nullable'],
        ]);

        $user = $this->user();
        $id = $request->id;
        $key = Str::random(25);

        if (!empty($id)) {
            $device = Device::find($id);
            $device->agent = $request->header('user-agent');
            $device->ip_address = $request->ip();
            $device->name = $request->name;
            $device->key = $key;
            $device->push();

            return $this->showOne($device, $device->transformer, 201);
        }

        $device = Device::create([
            "ip_address" => $request->ip(),
            "agent" => $request->header('user-agent'),
            "name" => $request->name,
            "key" => $key,
            "user_id" => $user->id,
        ]);

        return $this->showOne($device, $device->transformer, 201);
    }


    /**
     * Destroy device
     * @param string $uuid
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $uuid)
    {
        $device = Device::findOrFail($uuid);

        $device->delete();

        return $this->showOne($device, $device->transformer);
    }
}
