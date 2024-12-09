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
        parent::__construct();
    }

    /**
     * Show all resources
     * @param \App\Models\Server\Peer $peer
     * @return \Elyerr\ApiResponse\Assets\Json
     */
    public function index(Peer $peer)
    {
        $this->checkMethod('get');

        $params = $this->filter_transform($peer->transformer);
        $data = $this->search($peer->table, $params);

        return $this->showAll($data, $peer->transformer);
    }

    /**
     * Store a new resource
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Server\Peer $peer
     * @param \App\Models\Server\Wg $wg
     * @return \Elyerr\ApiResponse\Assets\Json
     */
    public function store(Request $request, Peer $peer, Wg $wg)
    {
        $this->checkMethod('post');

        $request->validate([
            'name' => ['required'],
            'wg_id' => [
                'required',
                'exists:wgs,id',
                function ($attribute, $value, $fail) use ($wg) {

                    $wg = $wg->where('id', $value)->first();

                    if (!$wg->active) {
                        $fail("The $wg->name is currently offline and cannot be used.");
                    }
                }
            ],
        ]);

        //Wireguar interface
        $wg = $wg->findOrFail($request->wg_id);

        DB::transaction(function () use ($request, $peer, $wg) {

            //Generate pair keys
            $keys = $peer->generatePairKeys();

            //Preshared key
            $preshared_key = $peer->generatePresharedkey();
            $ip_allowed = $peer->generateUniqueIp();

            //peer
            $peer = $peer->fill($request->only('name'));
            $peer->public_key = $keys['public_key'];
            $peer->preshared_key = $preshared_key;
            $peer->allowed_ips = $ip_allowed;
            $peer->persistent_keepalive = 25;
            $peer->user_id = $this->user()->id;
            $peer->wg_id = $wg->id;
            $peer->save();

            //user config
            $config = [
                "[Interface]",
                "PrivateKey = {$keys['private_key']}",
                "ListenPort = 21841",
                "",
                "[Peer]",
                "PublicKey = {$wg->generatePubKey()}",
                "Endpoint = {$wg->getServer()}",
                "AllowedIPs = 0.0.0.0/0",
                "PresharedKey = {$preshared_key}",
                "PersistentKeepalive = {$peer->persistent_keepalive}",
            ];

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

        throw_if($peer->active, new ReportError(__("This peer is active, please stop and try again"), 403));

        $peer->delete();

        $this->showOne($peer, $peer->transformer);
    }

    /**
     * On and off the current peer
     * @param \App\Models\Server\Peer $peer
     * @return \Elyerr\ApiResponse\Assets\Json
     */
    public function toggle(Peer $peer)
    {
        $this->checkMethod('get');

        $core = new Core($peer->wg->server->url, $peer->wg->server->port);

        if ($peer->active) {

            $peer->active = !$peer->active;
            $core->deletePeer($peer->wg->name, $peer->public_key);

        } else {
            $peer->active = !$peer->active;
            $core->addPeer(
                $peer->name,
                $peer->wg->name,
                $peer->public_key,
                $peer->allowed_ips,
                $peer->wg->getServer(),
                $peer->preshared_key,
                $peer->persistent_keepalive
            );
        }

        $peer->push();

        return $this->showOne($peer, $peer->transformer, 201);
    }
}
