<?php

namespace App\Http\Controllers\Peer;

use App\Wrapper\Core;
use App\Models\Server\Wg;
use App\Models\Server\Peer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GlobalController as Controller;

class PeerController extends Controller
{
    public function __construct()
    {
        $this->middleware('server');
    }

    /**
     * Show all resources
     * @param \App\Models\Server\Peer $peer
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Peer $peer)
    {
        $this->checkMethod('get');

        $params = $this->filter_transform($peer->transformer);
        $data = $peer->query();
        $data = $data->where('user_id', $this->user()->id);

        $data = $this->searchByBuilder($data, $params);
        $data = $this->orderByBuilder($data, $peer->transformer);

        return $this->showAllByBuilder($data, $peer->transformer);
    }
    
    /**
     * Store a new resource
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Peer $peer
     * @param \App\Models\Server\Wg $wg
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Peer $peer, Wg $wg)
    {
        $message = __('You have exceeded the device limit. To add more devices, please upgrade to a higher plan.');
        $userId = $this->user()->id;
        $data = $peer->query();
        $count = $data->where('user_id', $userId)->count();

        //---------check plans --------------------------//
        if (!app()->environment(['local', 'dev'])) {
            //user access
            $access = collect($this->access())->pluck('id');

            //available plans 
            $plans = [
                'commerce_vpn_advanced' => config('vpn.advanced'),
                'commerce_vpn_intermediate' => config('vpn.intermediate'),
                'commerce_vpn_basic' => config('vpn.basic'),
                'commerce_vpn_free' => config('vpn.free'),
            ];

            //check user plan
            $userLimit = collect($plans)
                ->filter(fn($limit, $plan) => $access->contains($plan))
                ->first() ?? config('vpn.free');

            throw_if($count >= $userLimit, new ReportError($message, 403));

            //---------End check plans -------------------------- 
        }

        $request->validate([
            'name' => ['required'],
            'server_id' => [
                'required',
                'exists:wgs,id',
                function ($attribute, $value, $fail) use ($wg) {

                    $wg = $wg->where('id', $value)->first();

                    if (!$wg->active) {
                        $fail("The server is currently offline and cannot be used.");
                    }
                }
            ],
        ]);

        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

        //Wireguard interface
        $wg = $wg->findOrFail($request->server_id);

        DB::transaction(function () use ($request, $peer, $wg, $userId) {

            //Generate pair keys
            $keys = $peer->generatePairKeys();

            //Preshared key
            $preshared_key = $peer->generatePresharedkey();
            $dns = $wg->enable_dns ? $wg->dns : null;
            $ip_allowed = $this->generateRandomIp($wg->subnet);

            //peer
            $peer = $peer->fill($request->only('name'));
            $peer->public_key = $keys['public_key'];
            $peer->preshared_key = $preshared_key;
            $peer->allowed_ips = $ip_allowed;
            $peer->persistent_keepalive = 25;
            $peer->user_id = $userId;
            $peer->wg_id = $wg->id;
            $peer->save();

            //user config
            $config[] = "[Interface]";
            $config[] = "PrivateKey = {$keys['private_key']}";
            $config[] = "ListenPort = {$wg->listen_port}";
            $config[] = "Address =  {$ip_allowed}/32";
            if ($wg->enable_dns) {
                $config[] = "DNS =  {$dns}";
            }
            $config[] = "";
            $config[] = "[Peer]";
            $config[] = "PublicKey = {$wg->generatePubKey()}";
            $config[] = "Endpoint = {$wg->getEndpoint()}";
            $config[] = "AllowedIPs = 0.0.0.0/0, ::/0";
            $config[] = "PresharedKey = {$preshared_key}";
            $config[] = "PersistentKeepalive = {$peer->persistent_keepalive}";

            $peer->config = implode("\n", $config);
        });

        return $this->showOne($peer, $peer->transformer, 201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response|void
     */
    public function destroy(Peer $peer)
    {
        $this->checkMethod('delete');
        $this->checkContentType(null);

        throw_if($peer->active, new ReportError(__("This peer is active, please stop and try again"), 403));

        $peer->delete();

        $this->showOne($peer, $peer->transformer);
    }

    /**
     * On and off the current peer
     * @param \App\Models\Server\Peer $peer
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function toggle(Peer $peer)
    {
        $this->checkMethod('put');
        $this->checkContentType(null);

        $core = new Core($peer->wg->server->ip, $peer->wg->server->port);

        if ($peer->active) {

            $peer->active = !$peer->active;
            $core->deletePeer($peer->wg->slug, $peer->public_key);

        } else {
            $peer->active = !$peer->active;
            $core->addPeer(
                $this->user()->id,
                $peer->name,
                $peer->wg->slug,
                $peer->public_key,
                $peer->allowed_ips,
                $peer->wg->getEndpoint(),
                $peer->preshared_key,
                $peer->persistent_keepalive
            );
        }

        $peer->push();

        return $this->showOne($peer, $peer->transformer, 200);
    }
}
