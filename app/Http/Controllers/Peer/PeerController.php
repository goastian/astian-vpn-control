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
     * @return \Elyerr\ApiResponse\Assets\Json
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
     * @return \Elyerr\ApiResponse\Assets\Json
     */
    public function store(Request $request, Peer $peer, Wg $wg)
    {
        $message = __('You have exceeded the device limit');
        $data = $peer->query();
        $data->where('user_id', $this->user()->id);

        //check if the app is on dev mode 
        if (config('app.test')) {
            throw_if($data->count() >= 5, new ReportError($message, 403));
        }

        //limit peer for admin
        if ($this->userCan('admin')) {
            throw_if($data->count() >= 10, new ReportError($message, 403));
        }

        //check scope in production mode         
        if (!config('app.test') && !$this->userCan('admin')) {
            if (!$this->userCan('vpn-free')) {
                throw new ReportError(__('These features are not yet available to the general public. Please stay tuned for future updates and announcements regarding their release.'), 403);
            }

            throw_if($data->count() >= 2, new ReportError($message, 403));
        }

        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

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

        //Wireguard interface
        $wg = $wg->findOrFail($request->wg_id);

        DB::transaction(function () use ($request, $peer, $wg) {

            //Generate pair keys
            $keys = $peer->generatePairKeys();

            //Preshared key
            $preshared_key = $peer->generatePresharedkey();
            $dns = str_replace('/16', '', $wg->subnet);
            $ip_allowed = $this->generateRandomIp($wg->subnet);

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
                "ListenPort = {$wg->listen_port}",
                "Address =  {$ip_allowed}/32",
                "",
                "[Peer]",
                "PublicKey = {$wg->generatePubKey()}",
                "Endpoint = {$wg->getEndpoint()}",
                "AllowedIPs = 0.0.0.0/0, ::/0",
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
        $this->checkContentType(null);

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
        $this->checkMethod('put');
        $this->checkContentType($this->getJsonHeader());

        $core = new Core($peer->wg->server->url, $peer->wg->server->port);

        if ($peer->active) {

            $peer->active = !$peer->active;
            $core->deletePeer($peer->wg->slug, $peer->public_key);

        } else {
            $peer->active = !$peer->active;
            $core->addPeer(
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

        return $this->showOne($peer, $peer->transformer, 201);
    }
}
