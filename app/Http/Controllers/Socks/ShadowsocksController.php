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
        $this->middleware('auth:api')->only('index');
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

        $shadowsocks = new Shadowsocks($server->ip, $server->port);

        //$domain = parse_url($server->url, PHP_URL_HOST) ?? null;

        $response = $shadowsocks->createConfig(
            $server->ss_port,
            $server->ss_password,
            $server->ss_method,
            $server->dns
        );

        return $this->message($response, 200);
    }

    /**
     * Summary of start
     * @param \App\Models\Server\Server $server
     * @param mixed $server_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function serverStart(Server $server, $server_id)
    {
        $server = $server->find($server_id);
        $shadowsocks = new Shadowsocks($server->ip, $server->port);

        $response = $shadowsocks->serverStart();

        return $this->message($response, 200);
    }

    /**
     * Summary of stop
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Server $server
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function serverStop(Server $server, $server_id)
    {
        $server = $server->find($server_id);

        $shadowsocks = new Shadowsocks($server->ip, $server->port);

        $response = $shadowsocks->serverStop();

        return $this->message($response, 200);
    }

    /**
     * Summary of status
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Server $server
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function serverStatus(Server $server, $server_id)
    {
        $server = $server->find($server_id);

        $shadowsocks = new Shadowsocks($server->ip, $server->port);

        $response = $shadowsocks->serverStatus();

        return $this->message($response, 200);
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

        $shadowsocks = new Shadowsocks($server->ip, $server->port);

        $response = $shadowsocks->deleteConfig();

        return $this->message($response, 200);
    }

    public function clientStart(Server $server, $server_id)
    {
        $server = $server->find($server_id);
        $shadowsocks = new Shadowsocks($server->ip, $server->port);

        $response = $shadowsocks->clientStart();

        return $this->message($response, 200);
    }


    public function clientStop(Server $server, $server_id)
    {
        $server = $server->find($server_id);

        $shadowsocks = new Shadowsocks($server->ip, $server->port);

        $response = $shadowsocks->clientStop();

        return $this->message($response, 200);
    }

    public function clientStatus(Server $server, $server_id)
    {
        $server = $server->find($server_id);

        $shadowsocks = new Shadowsocks($server->ip, $server->port);

        $response = $shadowsocks->clientStatus();

        return $this->message($response, 200);
    }
}
