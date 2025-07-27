<?php
namespace App\Http\Controllers\Api\V1\Admin\Wireguard;

use Illuminate\Http\Request; 
use App\Http\Controllers\ApiController; 
use App\Http\Requests\Wireguard\StoreRequest;
use App\Http\Requests\Wireguard\UpdateRequest;
use App\Repositories\Server\WireguardRepository;  

class WireguardController extends ApiController
{

    /**
     * Repository
     * @var 
     */
    public $repository;

    public function __construct(WireguardRepository $wireguardRepository)
    {
        $this->repository = $wireguardRepository;

        $this->middleware('scope:administrator:vpn:full,administrator:vpn:view')->only('index', 'interfaces');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:create')->only('store');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:show')->only('show');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:update')->only('update', 'toggle', 'reload');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:destroy')->only('destroy');
    }

    /**
     * Show the all resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function index(Request $request)
    {
        return $this->repository->search($request);
    }

    /**
     * Create new resource
     * @param \App\Http\Requests\Wireguard\StoreRequest $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function store(StoreRequest $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Show specific resource
     * @param string $id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function show(string $id)
    {
        return $this->repository->find($id);
    }

    /**
     * Update specific resource
     * @param \App\Http\Requests\Wireguard\UpdateRequest $request
     * @param string $id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function update(UpdateRequest $request, string $id)
    {
        return $this->repository->update($id, $request->toArray());
    }

    /**
     * Destroy specific resource
     * @param string $id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(string $id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Toggle Interface (On and Off)
     * @param string $id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function toggle(string $id)
    {
        return $this->repository->toggle($id);
    }

    /**
     * Reload network interface
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function reload(string $id)
    {
        return $this->repository->reloadInterface($id);
    }
}
