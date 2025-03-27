<?php

namespace App\Wrapper;

use GuzzleHttp\Client;
use Illuminate\Http\Response;
use App\Models\Security\KeyGenerator;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Log;

class Core
{
    /**
     * Client HTTP
     * @var Client
     */
    public $client;

    public function __construct($endpoint, $port = 8000)
    {
        $keyGenerator = app(KeyGenerator::class);
        $token = $keyGenerator->generateAndSignToken();

        $this->client = new Client([
            'base_uri' => "{$endpoint}:{$port}",
            'timeout' => 2.0,
            'verify' => false,
            'headers' => [
                'Authorization' => $token,
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
     * @param mixed $dns
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return Response|\Illuminate\Contracts\Routing\ResponseFactory|void
     */
    public function mountInterface($interface_name, $subnet, $gateway, $private_key, $physical_interface, $listen_port = 51820, $dns, $enable_dns)
    {
        try {
            $response = $this->client->request("POST", "/api/wireguard/mount", [
                'json' => [
                    'interface_name' => $interface_name,
                    'private_key' => $private_key,
                    'physical_interface' => $physical_interface,
                    'listen_port' => $listen_port,
                    'subnet' => $subnet,
                    'address' => $gateway,
                    'dns' => $dns,
                    'enable_dns' => $enable_dns
                ]
            ]);

            if ($response->getStatusCode() == 201) {
                return response(null, 201);
            }

        } catch (\Throwable $th) {
            $errorResponse = $th->getMessage();
            
            preg_match('/\{.*\}/s', $errorResponse, $matches);

            if (!empty($matches)) {
                $errorData = json_decode($matches[0], true);
                $message = $errorData['data'] ?? 'Unknown error';
            } else {
                $message = 'Unknown error';
            }

            throw new ReportError($message, $th->getCode());
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

        } catch (\Throwable $th) {
            $errorResponse = $th->getMessage();

            preg_match('/\{.*\}/s', $errorResponse, $matches);

            if (!empty($matches)) {
                $errorData = json_decode($matches[0], true);
                $message = $errorData['message'] ?? 'Unknown error';
            } else {
                $message = 'Unknown error';
            }

            throw new ReportError($message, $th->getCode());
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

        } catch (\Throwable $th) {
            $errorResponse = $th->getMessage();

            preg_match('/\{.*\}/s', $errorResponse, $matches);

            if (!empty($matches)) {
                $errorData = json_decode($matches[0], true);
                $message = $errorData['message'] ?? 'Unknown error';
            } else {
                $message = 'Unknown error';
            }

            throw new ReportError($message, $th->getCode());
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

        } catch (\Throwable $th) {
            $errorResponse = $th->getMessage();

            preg_match('/\{.*\}/s', $errorResponse, $matches);

            if (!empty($matches)) {
                $errorData = json_decode($matches[0], true);
                $message = $errorData['message'] ?? 'Unknown error';
            } else {
                $message = 'Unknown error';
            }

            throw new ReportError($message, $th->getCode());
        }
    }

    /**
     * Add new peer in the Wireguard Network Interface
     * @param mixed $userId
     * @param mixed $device_name
     * @param mixed $interface_name
     * @param mixed $public_key
     * @param mixed $allowed_ips
     * @param mixed $preshared_key
     * @param mixed $persistent_keepalive
     * @return Response|\Illuminate\Contracts\Routing\ResponseFactory|void
     */
    public function addPeer($userId, $device_name, $interface_name, $public_key, $allowed_ips, $endpoint, $preshared_key, $persistent_keepalive)
    {
        try {
            $response = $this->client->request("POST", "/api/wireguard/peer/add", [
                'json' => [
                    "user_id" => $userId,
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

        } catch (\Throwable $th) {
            $errorResponse = $th->getMessage();

            preg_match('/\{.*\}/s', $errorResponse, $matches);

            if (!empty($matches)) {
                $errorData = json_decode($matches[0], true);
                $message = $errorData['message'] ?? 'Unknown error';
            } else {
                $message = 'Unknown error';
            }

            throw new ReportError($message, $th->getCode());
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

        } catch (\Throwable $th) {
            $errorResponse = $th->getMessage();

            preg_match('/\{.*\}/s', $errorResponse, $matches);

            if (!empty($matches)) {
                $errorData = json_decode($matches[0], true);
                $message = $errorData['message'] ?? 'Unknown error';
            } else {
                $message = 'Unknown error';
            }

            throw new ReportError($message, $th->getCode());
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

        } catch (\Throwable $th) {
            $errorResponse = $th->getMessage();

            preg_match('/\{.*\}/s', $errorResponse, $matches);

            if (!empty($matches)) {
                $errorData = json_decode($matches[0], true);
                $message = $errorData['message'] ?? 'Unknown error';
            } else {
                $message = 'Unknown error';
            }

            throw new ReportError($message, $th->getCode());
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

        } catch (\Throwable $th) {
            $errorResponse = $th->getMessage();

            preg_match('/\{.*\}/s', $errorResponse, $matches);

            if (!empty($matches)) {
                $errorData = json_decode($matches[0], true);
                $message = $errorData['message'] ?? 'Unknown error';
            } else {
                $message = 'Unknown error';
            }

            throw new ReportError($message, $th->getCode());
        }
    }

    /**
     * update interface
     * @param mixed $interface_name
     * @param mixed $dns
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return Response|\Illuminate\Contracts\Routing\ResponseFactory|void
     */
    public function update($interface_name, $dns, $enable_dns)
    {
        try {
            $response = $this->client->request("PUT", "/api/wireguard/{$interface_name}", [
                'json' => [
                    "dns" => $dns,
                    "enable_dns" => $enable_dns
                ]
            ]);

            if ($response->getStatusCode() == 201) {
                return response(null, 201);
            }

        } catch (\Throwable $th) {
            $errorResponse = $th->getMessage();
            preg_match('/\{.*\}/s', $errorResponse, $matches);

            if (!empty($matches)) {
                $errorData = json_decode($matches[0], true);
                $message = $errorData['message'] ?? 'Unknown error';
            } else {
                $message = 'Unknown error';
            }

            throw new ReportError($message, $th->getCode());
        }
    }
}
