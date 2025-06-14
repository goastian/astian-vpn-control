<?php
namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Server\DeviceRepository;

class VpnDeviceController extends ApiController
{

    /**
     * Repository
     * @var 
     */
    public $repository;

    public function __construct(DeviceRepository $deviceRepository)
    {
        parent::__construct();
        $this->repository = $deviceRepository;
    }

    /**
     * Show resources
     * @param \Illuminate\Http\Request $request
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
    public function store(Request $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Destroy devices
     * @param string $id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(string $id)
    {
        return $this->repository->delete($id);
    }
}
