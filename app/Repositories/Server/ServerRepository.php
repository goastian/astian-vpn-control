<?php
namespace App\Repositories\Server;

use App\Wrapper\Core;
use Illuminate\Http\Request;
use App\Models\Server\Server;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Transformers\User\UserServerTransformer;

class ServerRepository implements Contracts
{

    use JsonResponser;

    /**
     * 
     * @var Server $server
     */
    public $model;

    /**
     * Summary of __construct
     * @param  Server $server
     */
    public function __construct(Server $server)
    {
        $this->model = $server;
    }

    /**
     * Searcher 
     * @param \Illuminate\Http\Request $request
     * @return JsonResponser
     */
    public function search(Request $request)
    {
        //filter params
        $params = $this->filter_transform($this->model->transformer);

        $data = $this->model->query();

        $data = $data->with(['wgs']);

        $data = $this->searchByBuilder($data, $params);

        $data = $this->orderByBuilder($data, $this->model->transformer);

        return $this->showAllByBuilder($data, $this->model->transformer);
    }


    /**
     * Create new resource
     * @param array $data
     * @return  JsonResponser
     */
    public function create(array $data)
    {
        $server = $this->model->create([
            'country' => $data['country'],
            'port' => $data['port'],
            'ip' => $data['ip'],
            'client_port' => $data['client_port'] ?? null,
            'socks5_port' => $data['socks5_port'] ?? null,
        ]);

        return $this->showOne($server, $server->transformer, 201);
    }

    /**
     * Update new resource
     * @param string $id
     * @param mixed $data
     * @return JsonResponser
     */
    public function update(string $id, $data)
    {
        $server = $this->model->find($id);

        if (!empty($data['ip'])) {
            $server->ip = $data['ip'];
        }

        if (!empty($data['country'])) {
            $server->country = $data['country'];
        }

        if (!empty($data['port'])) {
            $server->port = $data['port'];
        }

        if (!empty($data['client_port'])) {
            $server->client_port = $data['client_port'];
        }

        if (!empty($data['socks5_port'])) {
            $server->socks5_port = $data['socks5_port'];
        }

        $server->push();

        return $this->showOne($server, $server->transformer, 200);
    }

    /**
     * Retrieve resource by $id
     * @param string $id
     * @return Server
     */
    public function find(string $id)
    {
        return $this->model->find($id);
    }

    /**
     * Destroy resource by id
     * @param string $id
     * @return JsonResponser
     */
    public function delete(string $id)
    {
        $server = $this->model->with(['wgs'])->find($id);

        throw_if($server->wgs->count() > 0, new ReportError(__('Unable to delete this resource because it has assigned dependencies. Please remove any associated resources first.'), 403));

        $server->delete();

        return $this->showOne($server, $server->transformer);
    }

    /**
     * Retrieve the all physical network interfaces
     * @param mixed $id
     * @return array{interface: mixed[]}
     */
    public function hostNetworkInterfaces($id)
    {
        $host = $this->model->find($id);

        $core = new Core($host->ip, $host->port);

        $data = $core->networkInterfaces();

        return $data;
    }

    /**
     * Search server for users
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function searchForUser()
    {
        $data = $this->model->query();

        return $this->showAllByBuilder($data, UserServerTransformer::class);
    }
}
