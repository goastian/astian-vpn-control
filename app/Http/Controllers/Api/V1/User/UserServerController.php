<?php
namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\ApiController;
use App\Repositories\Server\ServerRepository;


class UserServerController extends ApiController
{

    /**
     * Repository
     * @var 
     */
    public $repository;

    /**
     * constructor
     * @param \App\Repositories\Server\ServerRepository $serverRepository
     */
    public function __construct(ServerRepository $serverRepository)
    {
        parent::__construct();
        $this->repository = $serverRepository;
    }

    /**
     * Search server for current user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->repository->searchForUser();
    }
}
