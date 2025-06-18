<?php
namespace App\Repositories;

use App\Models\Server\Wg;
use App\Models\Server\Peer;
use Illuminate\Http\Request;
use App\Models\Server\Device;
use App\Models\Server\Server;
use App\Transformers\Admin\WgTransformer;
use App\Transformers\User\DeviceTransformer;
use Elyerr\ApiResponse\Assets\JsonResponser;

class DashboardRepository
{
    use JsonResponser;

    /**
     * Data for admin dashboard 
     * @param Request $request
     * @return JsonResponser
     */
    public function admin(Request $request)
    {
        $server = Server::query();
        $nets = Wg::query();
        $peer = Peer::query();
        $browser_devices = Device::query();

        $last_interfaces = $nets->latest()->take(10)->get();
        $last_devices = $browser_devices->latest()->take(10)->get();

        return $this->data([
            'data' => [
                'total_servers' => $server->count(),
                'total_nets' => $nets->count(),
                'total_peers' => $peer->count(),
                'total_devices' => $browser_devices->count(),
                'last_nets' => fractal($last_interfaces, WgTransformer::class)->toArray()['data'],
                'last_devices' => fractal($last_devices, DeviceTransformer::class)->toArray()['data']
            ]
        ], 200);

    }

}
