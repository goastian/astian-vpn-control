<?php

namespace App\Http\Controllers\Peer;

use App\Http\Controllers\GlobalController as Controller;
use App\Models\Server\Peer;
use App\Models\Server\Wg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeerController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Peer $peer)
    {
        $params = $this->filter_transform($peer->tranformer);
        $data = $this->search($peer->table, $params);

        return $this->showAll($data, $peer->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Peer $peer, Wg $wg)
    {
        $request->validate([
            'name' => ['required'],
            'wg_id' => ['required', 'exists:wgs,id'],
        ]);

        //Wireguar interface
        $wg = $wg->findOrFail($request->wg_id);

        DB::transaction(function () use ($request, $peer, $wg) {

            //Generate pair keys
            $keys = $peer->generatePairKeys();

            //Preshared key
            $preshared_key = $peer->generatePresharedkey();

            //peer
            $peer = $peer->fill($request->only('name'));
            $peer->public_key = $keys['public_key'];
            $peer->preshared_key = $preshared_key;
            $peer->allowed_ips = $peer->generateUniqueIp();
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
                "Endpoint = {$wg->getEndpoint()}",
                "AllowedIPs = 0.0.0.0/0",
                "PresharedKey = {$preshared_key}",
                "PersistentKeepalive = {$peer->persistent_keepalive}",
            ];

            $peer->config = implode("\n", $config);
        });

        return $this->showOne($peer, $peer->transformer, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Peer $peer)
    {
        $peer->delete();

        $this->showOne($peer, $peer->transformer);
    }

    /**
     * On and off the current peer
     */
    public function toggle(Peer $peer)
    {
        $peer->active = !$peer->active ? now() : null;
        $peer->push();

        return $this->showOne($peer, $peer->transformer, 201);
    }
}
