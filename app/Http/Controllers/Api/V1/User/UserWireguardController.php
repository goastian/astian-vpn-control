<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\ApiController;
use App\Repositories\Server\WireguardRepository;

class UserWireguardController extends ApiController
{
    /**
     * Repository
     * @var 
     */
    public $repository;

    /**
     * Constructor
     * @param \App\Repositories\Server\WireguardRepository $wireguardRepository
     */
    public function __construct(WireguardRepository $wireguardRepository)
    {
        parent::__construct();
        $this->repository = $wireguardRepository;
    }

    /**
     * Show resources for users
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->repository->searchForUser();
    }
}
