<?php
namespace App\Http\Controllers\Api\V1\Gateway;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\Server\DeviceRepository;
use App\Repositories\Security\KeyGeneratorRepository;

class GatewayController extends ApiController
{
    /**
     * Generator repository
     */
    public $key_generator_repository;

    /**
     * Device repository
     * @var 
     */
    public $device_repository;

    /**
     * constructor
     * @param \App\Repositories\Security\KeyGeneratorRepository $keyGeneratorRepository
     * @param \App\Repositories\Server\DeviceRepository $deviceRepository
     */
    public function __construct(KeyGeneratorRepository $keyGeneratorRepository, DeviceRepository $deviceRepository)
    {
        $this->middleware('server')->except('checkCredentials');
        $this->key_generator_repository = $keyGeneratorRepository;
        $this->device_repository = $deviceRepository;
    }

    /**
     * Validate token authorization
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function checkCredentials(Request $request)
    {
        return $this->key_generator_repository->validateToken($request);
    }

    /**
     * Validate device
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function checkAuth(Request $request)
    {
        return $this->device_repository->validateDevice($request);
    }
}
