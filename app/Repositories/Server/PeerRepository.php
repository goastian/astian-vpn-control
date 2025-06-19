<?php
namespace App\Repositories\Server;

use App\Wrapper\Core;
use App\Models\Server\Wg;
use App\Models\Server\Peer;
use Illuminate\Http\Request;
use App\Repositories\Traits\Generic;
use App\Repositories\Traits\Wireguard;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\Passport\Connect\Traits\Passport;
use Elyerr\ApiResponse\Exceptions\ReportError;

class PeerRepository implements Contracts
{
    use JsonResponser, Passport, Generic, Wireguard;

    /**
     * 
     * @var Peer $server
     */
    public $model;

    /**
     * Summary of __construct
     * @param  Peer $server
     */
    public function __construct(Peer $server)
    {
        $this->model = $server;
    }

    /**
     * Search data
     * @return mixed
     */
    public function search(Request $request)
    {
        $params = $this->filter_transform($this->model->transformer);

        $data = $this->model->query();

        $data = $data->with(['wg'])->where('user_id', $this->user()->id);

        $data = $this->searchByBuilder($data, $params);

        $data = $this->orderByBuilder($data, $this->model->transformer);

        return $this->showAllByBuilder($data, $this->model->transformer);
    }

    /**
     * Create new resource
     * @param array $data
     * @return JsonResponser
     */
    public function create(array $data)
    {
        /**
         * Retrieve the user
         */
        $user = $this->user();

        //---------check plans --------------------------//
        if (!app()->environment(['local', 'dev'])) {
            //user access
            $this->verifyPlan($user);
        }

        //Retrieve Wireguard server
        $wireguard_server = Wg::find($data['server_id']);

        //Generate pair keys to the client
        $keys = $this->generatePairKeys();

        //Preshared key to the client
        $preshared_key = $this->generatePresharedkey();
        $dns = $wireguard_server->enable_dns ? $wireguard_server->dns : null;

        //Generate new random ip
        $ip_allowed = $this->generateRandomIp($wireguard_server->subnet);

        //Create new peer
        $model = $this->model->create([
            'name' => $data['name'],
            'public_key' => $keys['public_key'],
            'preshared_key' => $preshared_key,
            'allowed_ips' => $ip_allowed,
            'persistent_keepalive' => 25,
            'user_id' => $user->id,
            'wg_id' => $wireguard_server->id,
            'active' => true
        ]);


        /**
         * Create peer configuration
         */
        $config[] = "[Interface]";
        $config[] = "PrivateKey = {$keys['private_key']}";
        /**
         * Note: The 'ListenPort' directive has been commented out because it is not supported on some platforms.
         * Certain systems do not allow explicitly setting this parameter in the WireGuard configuration.
         * Therefore, it is omitted to ensure broader compatibility.
         */
        // $config[] = "ListenPort = {$wireguard_server->listen_port}";

        $config[] = "Address =  {$ip_allowed}/32";
        if ($wireguard_server->enable_dns) {
            $config[] = "DNS =  {$dns}";
        }
        $config[] = "";
        $config[] = "[Peer]";
        $config[] = "PublicKey = {$this->generatePubKey($wireguard_server->private_key)}";
        $config[] = "Endpoint = {$wireguard_server->getEndpoint()}";
        $config[] = "AllowedIPs = 0.0.0.0/0, ::/0";
        $config[] = "PresharedKey = {$preshared_key}";
        $config[] = "PersistentKeepalive = {$model->persistent_keepalive}";

        // Add configuration to the model
        $model->config = implode("\n", $config);

        // Open connection with core
        $core = new Core($model->wg->server->ip, $model->wg->server->port);
        // Mount peer to start service
        $core->addPeer(
            $this->user()->id,
            $model->name,
            $model->wg->slug,
            $model->public_key,
            $model->allowed_ips,
            $model->wg->getEndpoint(),
            $model->preshared_key,
            $model->persistent_keepalive
        );

        return $this->showOne($model, $this->model->transformer, 201);
    }

    /**
     * Update new resource
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data)
    {

    }

    /**
     * Retrieve resource by $id
     * @param string $id
     * @return void
     */
    public function find(string $id)
    {

    }

    /**
     * Destroy resource by id
     * @param string $id
     * @return JsonResponser|ReportError
     */
    public function delete(string $id)
    {
        $model = $this->model->find($id);

        throw_if(
            $model->active,
            new ReportError(__("This peer is active, please stop and try again"), 403)
        );

        $model->delete();

        return $this->showOne($model, $this->model->transformer);
    }



    /**
     * Toggle peer (on and off)
     * @param string $id
     * @return JsonResponser
     */
    public function toggle(string $id)
    {
        //Search peer
        $model = $this->model->with(['wg', 'wg.server'])->find($id);

        $core = new Core($model->wg->server->ip, $model->wg->server->port);

        if ($model->active) {

            $model->active = !$model->active;

            $core->deletePeer($model->wg->slug, $model->public_key);

        } else {
            $model->active = !$model->active;
            $core->addPeer(
                $this->user()->id,
                $model->name,
                $model->wg->slug,
                $model->public_key,
                $model->allowed_ips,
                $model->wg->getEndpoint(),
                $model->preshared_key,
                $model->persistent_keepalive
            );
        }

        $model->push();

        return $this->showOne($model, $this->model->transformer, 200);
    }
}
