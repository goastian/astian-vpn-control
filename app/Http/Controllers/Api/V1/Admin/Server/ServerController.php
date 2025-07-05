<?php
namespace App\Http\Controllers\Api\V1\Admin\Server;

use App\Http\Requests\Server\StoreRequest;
use App\Http\Requests\Server\UpdateRequest;
use Illuminate\Http\Request;
use App\Models\Server\Server;
use App\Http\Controllers\ApiController;
use App\Repositories\Server\ServerRepository;

class ServerController extends ApiController
{
    /**
     * Server repository
     */
    public $repository;

    /**
     * 
     */
    public function __construct(ServerRepository $serverRepository)
    {
        $this->repository = $serverRepository;
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:view')->only('index', 'interfaces');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:view')->only('index', 'interfaces');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:create')->only('store');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:show')->only('show');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:update')->only('update', 'toggle');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:destroy')->only('destroy');
    }

    /**
     * Display the all resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function index(Request $request)
    {
        return $this->repository->search($request);
    }

    /**
     * Create new resource
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function store(StoreRequest $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Show one resource
     * @param string $id
     * @return Server
     */
    public function show(string $id)
    {
        return $this->repository->find($id);
    }

    /**
     * Update resource information
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Server $server
     * @param \App\Repositories\Server\ServerRepository $serverRepository
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function update(UpdateRequest $request, string $id, ServerRepository $serverRepository)
    {
        return $serverRepository->update($id, $request->toArray());
    }

    /**
     * Destroy a specific resource
     * @param \App\Models\Server\Server $server
     * @param \App\Repositories\Server\ServerRepository $serverRepository
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(Server $server, ServerRepository $serverRepository)
    {
        return $serverRepository->delete($server->id);
    }

    /**
     * Show the all physical network interface of the server
     * @param mixed $id
     * @param \App\Models\Server\Server $server
     * @return array{interface: mixed[]}
     */
    public function interfaces($id)
    {
        return $this->repository->hostNetworkInterfaces($id);
    }
}
