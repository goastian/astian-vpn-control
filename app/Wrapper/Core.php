<?php

namespace App\Wrapper;

use GuzzleHttp\Client;
use Illuminate\Http\Response;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
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
     * @param mixed $subnet
     * @param mixed $gateway
     * @param mixed $private_key
     * @param mixed $physical_interface
     * @param mixed $listen_port
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return Response|\Illuminate\Contracts\Routing\ResponseFactory|void
     */
    public function mountInterface($interface_name, $subnet, $gateway, $private_key, $physical_interface, $listen_port = 51820)
    {
        try {
            $response = $this->client->request("POST", "/api/wireguard/mount", [
                'json' => [
                    'interface_name' => $interface_name,
                    'private_key' => $private_key,
                    'physical_interface' => $physical_interface,
                    'listen_port' => $listen_port,
                    'subnet' => $subnet,
                    'address' => $gateway
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
        } catch (ServerException $th) {
            throw new ReportError(__('Connection to the server failed'), 500);
        }
    }

    /**
     * Remove the Wireguard Network Interface
     * @param mixed $interface_name
     * @return Response|\Illuminate\Contracts\Routing\ResponseFactory|void
     */
    public function removeInterface($interface_name)
    {
        try {
            $response = $this->client->request("DELETE", "/api/wireguard/umount", [
                'json' => [
                    "interface_name" => $interface_name
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
        } catch (ServerException $th) {
            throw new ReportError(__('Connection to the server failed'), 500);
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
            throw new ReportError(__('T'), $th->getCode());
        } catch (ServerException $th) {
            throw new ReportError(__('Connection to the server failed'), 500);
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
        } catch (ServerException $th) {
            throw new ReportError(__('Connection to the server failed'), 500);
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
            throw new ReportError(__('An error occurred while processing your request. This is an uncommon issue, and we apologize for the inconvenience caused. Our team is working diligently to ensure seamless operations for all users'), 500);

        } catch (ClientException $th) {
            throw new ReportError(__('An error occurred while processing your request. This is an uncommon issue, and we apologize for the inconvenience caused. Our team is working diligently to ensure seamless operations for all users'), $th->getCode());

        } catch (ServerException $th) {
            throw new ReportError(__('An error occurred while processing your request. This is an uncommon issue, and we apologize for the inconvenience caused. Our team is working diligently to ensure seamless operations for all users'), 500);
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
            throw new ReportError(__('An error occurred while processing your request. This is an uncommon issue, and we apologize for the inconvenience caused. Our team is working diligently to ensure seamless operations for all users'), 500);

        } catch (ClientException $th) {
            throw new ReportError(__('An error occurred while processing your request. This is an uncommon issue, and we apologize for the inconvenience caused. Our team is working diligently to ensure seamless operations for all users'), $th->getCode());

        } catch (ServerException $th) {
            throw new ReportError(__('An error occurred while processing your request. This is an uncommon issue, and we apologize for the inconvenience caused. Our team is working diligently to ensure seamless operations for all users'), 500);
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
        } catch (ServerException $th) {
            throw new ReportError(__('Connection to the server failed'), 500);
        }
    }

    /**
     * Force to reload the wireguard network interface using the config file
     * @param mixed $interface_name
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse|void
     */
    public function reloadNetwork($interface_name)
    {
        try {
            $response = $this->client->request(
                "POST",
                "/api/system/reload-networks",
                [
                    'json' => [
                        "name" => $interface_name,
                    ]
                ]
            );

            if ($response->getStatusCode() == 201) {
                return response()->json(__("Wireguard Network Interface started successfully"), 200);
            }

        } catch (ConnectException $th) {
            throw new ReportError(__('Connection to the server failed'), 500);
        } catch (ClientException $th) {
            if ($th->getCode() === 404) {
                throw new ReportError(__("Cannot find the interface on the server"), $th->getCode());
            }
            throw new ReportError($th->getResponse()->getReasonPhrase(), $th->getCode());
        } catch (ServerException $th) {
            throw new ReportError(__('Connection to the server failed'), 500);
        }
    }
}
