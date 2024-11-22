<?php

namespace App\Wrapper;

use GuzzleHttp\Client;
use Illuminate\Http\Response;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Elyerr\ApiResponse\Exceptions\ReportError;

final class Core
{
    /**
     * Client HTTP
     * @var Client
     */
    public $client;

    public function __construct($endpoint, $port = 8000)
    {
        $this->client = new Client([
            'base_uri' => "{$endpoint}:{$port}",
            'timeout' => 2.0,
            'verify' => false,
            'headers' => [
                'Authorization' => "Bearer " . request()->cookie(config('passport_connect.ids.jwt_token')),
            ],
        ]);
    }

    /**
     * Mount wireguard interface
     * @param mixed $interface_name
     * @param mixed $address
     * @param mixed $private_key
     * @param mixed $out_interface
     * @param mixed $listen_port
     * @return Response|\Illuminate\Contracts\Routing\ResponseFactory|void
     */
    public function mountInterface($interface_name, $address, $private_key, $out_interface, $listen_port = 51820)
    {
        try {
            $response = $this->client->request("POST", "/api/wireguard/mount", [
                'json' => [
                    'interface_name' => $interface_name,
                    'address' => $address,
                    'private_key' => $private_key,
                    'out_interface' => $out_interface,
                    'listen_port' => $listen_port
                ]
            ]);

            if ($response->getStatusCode() == 201) {
                return response(null, 201);
            }

        } catch (ConnectException $th) {
            throw new ReportError(__('Unable to connect with the server'), 500);

        } catch (ClientException $th) {
            if ($th->getCode() === 404) {
                throw new ReportError(__("Cannot find the interface on the server"), $th->getCode());
            }
            throw new ReportError($th->getResponse()->getReasonPhrase(), $th->getCode());
        }
    }

    /**
     * Remove the Wireguard Network Interface
     * @param mixed $interface_name
     * @param mixed $net
     * @return Response|\Illuminate\Contracts\Routing\ResponseFactory|void
     */
    public function removeInterface($interface_name, $net)
    {
        try {
            $response = $this->client->request("DELETE", "/api/wireguard/umount", [
                'json' => [
                    "interface_name" => $interface_name,
                    "out_interface" => $net
                ]
            ]);

            if ($response->getStatusCode() == 201) {
                return response(null, 201);
            }

        } catch (ConnectException $th) {
            throw new ReportError(__('Unable to connect with the server'), 500);

        } catch (ClientException $th) {
            if ($th->getCode() === 404) {
                throw new ReportError(__("Cannot find the interface on the server"), $th->getCode());
            }
            throw new ReportError($th->getResponse()->getReasonPhrase(), $th->getCode());
        }
    }

    /**
     * Shutdown the Wireguard Network Interface
     * @param mixed $interface_name
     * @return Response|\Illuminate\Contracts\Routing\ResponseFactory|void
     */
    public function shutdownInterface($interface_name)
    {
        try {
            $response = $this->client->request("GET", "/api/wireguard/down/$interface_name");

            if ($response->getStatusCode() == 200) {
                return response(null, 200);
            }

        } catch (ConnectException $th) {
            throw new ReportError(__('Unable to connect with the server'), 500);
        } catch (ClientException $th) {
            if ($th->getCode() === 404) {
                throw new ReportError(__("Cannot find the interface on the server"), $th->getCode());
            }
            throw new ReportError($th->getResponse()->getReasonPhrase(), $th->getCode());
        }
    }

    /**
     * Start the wireguard network interface
     * @param mixed $interface_name
     * @return Response|\Illuminate\Contracts\Routing\ResponseFactory|void
     */
    public function startInterface($interface_name)
    {
        try {
            $response = $this->client->request("GET", "/api/wireguard/up/$interface_name");

            if ($response->getStatusCode() == 200) {
                return response(null, 200);
            }

        } catch (ConnectException $th) {
            throw new ReportError(__('Unable to connect with the server'), 500);
        } catch (ClientException $th) {
            if ($th->getCode() === 404) {
                throw new ReportError(__("Cannot find the interface on the server"), $th->getCode());
            }
            throw new ReportError($th->getResponse()->getReasonPhrase(), $th->getCode());
        }
    }

    /**
     * Add new peer in the Wireguard Network Interface
     * @param mixed $device_name
     * @param mixed $interface_name
     * @param mixed $public_key
     * @param mixed $allowed_ips
     * @param mixed $preshared_key
     * @param mixed $persistent_keepalive
     * @return Response|\Illuminate\Contracts\Routing\ResponseFactory|void
     */
    public function addPeer($device_name, $interface_name, $public_key, $allowed_ips, $endpoint, $preshared_key, $persistent_keepalive)
    {
        try {
            $response = $this->client->request("POST", "/api/wireguard/peer/add", [
                'json' => [
                    "interface_name" => $interface_name,
                    "public_key" => $public_key,
                    "allowed_ips" => $allowed_ips,
                    "preshared_key" => $preshared_key,
                    "persistent_keepalive" => $persistent_keepalive,
                    "endpoint" => $endpoint,
                    "device_name" => $device_name
                ]
            ]);

            if ($response->getStatusCode() == 201) {
                return response(null, 201);
            }

        } catch (ConnectException $th) {
            throw new ReportError(__('Unable to connect with the server'), 500);

        } catch (ClientException $th) {
            if ($th->getCode() === 404) {
                throw new ReportError(__("Cannot find the interface on the server"), $th->getCode());
            }
            throw new ReportError($th->getResponse()->getReasonPhrase(), $th->getCode());
        }
    }

    /**
     * Delete peers from Wireguard Network Interface
     * @param mixed $interface_name
     * @param mixed $public_key
     * @return Response|\Illuminate\Contracts\Routing\ResponseFactory|void
     */
    public function deletePeer($interface_name, $public_key)
    {
        try {
            $response = $this->client->request("DELETE", "/api/wireguard/peer/delete", [
                'json' => [
                    "interface_name" => $interface_name,
                    "public_key" => $public_key
                ]
            ]);

            if ($response->getStatusCode() == 201) {
                return response(null, 201);
            }

        } catch (ConnectException $th) {
            throw new ReportError(__('Unable to connect with the server'), 500);

        } catch (ClientException $th) {
            if ($th->getCode() === 404) {
                throw new ReportError(__("Cannot find the interface on the server"), $th->getCode());
            }
            throw new ReportError($th->getResponse()->getReasonPhrase(), $th->getCode());
        }
    }

    /**
     * Get the all interface available on the server
     * @return mixed|\Illuminate\Http\JsonResponse|void
     */
    public function networkInterfaces()
    {
        try {
            $response = $this->client->request("GET", "/api/system/network-interfaces");

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                return response()->json($data, 200);
            }

        } catch (ConnectException $th) {
            throw new ReportError(__('Connection to the server failed'), 500);

        } catch (ClientException $th) {
            if ($th->getCode() === 404) {
                throw new ReportError(__("Cannot find the interface on the server"), $th->getCode());
            }
            throw new ReportError($th->getResponse()->getReasonPhrase(), $th->getCode());
        }
    }
}
