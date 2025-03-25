<?php
namespace App\Http\Controllers\Socks;

use App\Wrapper\Shadowsocks;
use App\Models\Server\Server;
use App\Http\Controllers\GlobalController;
use App\Transformers\Server\ShadowsocksTransformer;


class ShadowsocksController extends GlobalController
{

    public function __construct()
    {
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_view')->only('index');
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_create')->only('createConfig');
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_show')->only('showConfig', 'status');
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_update')->only('start', 'stop', 'restart');
        $this->middleware('scope:administrator_vpn_full,administrator_vpn_destroy')->only('deleteConfig');
    }

    /**
     * Summary of index
     * @param \App\Models\Server\Server $server
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Server $server)
    {
        $servers = $server->query();

        return $this->showAllByBuilder($servers, ShadowsocksTransformer::class);
    }

    /**
     * Summary of createConfig
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Server $server
     * @return mixed|\Illuminate\Http\JsonResponse 
     */
    public function createConfig(Server $server, $server_id)
    {
        $server = $server->find($server_id);

        $shadowsocks = new Shadowsocks($server->url, $server->port);

        $domain = parse_url($server->url, PHP_URL_HOST) ?? null;

        $response = $shadowsocks->createConfig(
            $server->ss_port,
            $server->ss_password,
            $server->ss_method,
            $server->ss_over_https ? $domain : null
        );


        $data = json_decode($response->getBody());
        return $this->message($data->message, $response->getStatusCode());
    }

    /**
     * showConfig
     * @param \App\Models\Server\Server $server
     * @param mixed $server_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function showConfig(Server $server, $server_id)
    {
        $server = $server->find($server_id);
        $shadowsocks = new Shadowsocks($server->url, $server->port);

        $response = $shadowsocks->showConfig();
        $data = json_decode($response->getBody());
        return $this->showOne($data->data, $response->getStatusCode());
    }

    /**
     * Summary of start
     * @param \App\Models\Server\Server $server
     * @param mixed $server_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function start(Server $server, $server_id)
    {
        $server = $server->find($server_id);
        $shadowsocks = new Shadowsocks($server->url, $server->port);

        try {

            $shadowsocks->start();

        } catch (\Throwable $th) {

            $this->createConfig($server, $server->id);
            $shadowsocks->start();

        }
        return $this->message("Server started successfully", 200);
    }

    /**
     * Summary of restart
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Server $server
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function restart(Server $server, $server_id)
    {
        $server = $server->find($server_id);

        $shadowsocks = new Shadowsocks($server->url, $server->port);

        $response = $shadowsocks->restart();
        $data = json_decode($response->getBody());
        return $this->message($data->message, $response->getStatusCode());
    }

    /**
     * Summary of stop
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Server $server
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function stop(Server $server, $server_id)
    {
        $server = $server->find($server_id);

        $shadowsocks = new Shadowsocks($server->url, $server->port);

        $response = $shadowsocks->stop();

        $data = json_decode($response->getBody());
        return $this->message($data->message, $response->getStatusCode());
    }

    /**
     * Summary of status
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Server $server
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function status(Server $server, $server_id)
    {
        $server = $server->find($server_id);

        $shadowsocks = new Shadowsocks($server->url, $server->port);

        $response = $shadowsocks->status();

        $data = json_decode($response->getBody());
        return $this->message($data->message, $response->getStatusCode());
    }

    /**
     * Summary of deleteConfig
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Server $server
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function deleteConfig(Server $server, $server_id)
    {
        $server = $server->find($server_id);

        $shadowsocks = new Shadowsocks($server->url, $server->port);

        $response = $shadowsocks->deleteConfig();

        $data = json_decode($response->getBody());
        return $this->message($data->message, $response->getStatusCode());
    }
}
