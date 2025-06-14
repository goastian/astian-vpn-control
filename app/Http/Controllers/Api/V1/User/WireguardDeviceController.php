<?php
namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Peer\StoreRequest;
use App\Repositories\Server\PeerRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;

class WireguardDeviceController extends ApiController
{

    /**
     * Repository
     * @var 
     */
    public $repository;

    /**
     * Constructor
     * @param PeerRepository $peerRepository
     */
    public function __construct(PeerRepository $peerRepository)
    {
        parent::__construct();
        $this->repository = $peerRepository;
    }

    /**
     * Show all resources
     * @param \App\Models\Server\Peer $peer
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->repository->search($request);
    }

    /**
     * Create a new resource
     * @param \App\Http\Requests\Peer\StoreRequest $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function store(StoreRequest $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return ReportError|\Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(string $id)
    {
        return $this->repository->delete($id);
    }

    /**
     * On and off the current peer
     * @param \App\Models\Server\Peer $peer
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function toggle(string $id)
    {
        return $this->repository->toggle($id);
    }
}
