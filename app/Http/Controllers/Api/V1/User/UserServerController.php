<?php
namespace App\Http\Controllers\Api\V1\User;

use App\Models\Server\Server;
use App\Http\Controllers\ApiController; 
use App\Transformers\User\UserServerTransformer;


class UserServerController extends ApiController
{
    public function index(Server $server)
    {
        $data = $server->query();

        return $this->showAllByBuilder($data, UserServerTransformer::class);
    }
}
