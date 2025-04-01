<?php

namespace App\Wrapper;

use Throwable;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use App\Models\Security\KeyGenerator;
use Elyerr\ApiResponse\Exceptions\ReportError;

class Shadowsocks
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
     * create config
     * @param mixed $port
     * @param mixed $password
     * @param mixed $method
     * @param mixed $method
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return \Psr\Http\Message\ResponseInterface|void
     */
    public function createConfig($port, $password, $method = "chacha20-ietf-poly1305", $domain = null, $dns = null)
    {

        $data = [
            "server_port" => $port,
            "password" => $password,
            "method" => $method
        ];

        if (!empty($domain)) {
            $data["domain"] = $domain;
        }

        if (!empty($dns)) {
            $data["dns"] = $dns;
        }

        try {
            $response = $this->client->request("POST", "/api/shadowsocks/add", [
                'json' => $data
            ]);

            return $response;

        } catch (Throwable $th) {

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
     * showConfig
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showConfig()
    {
        try {
            $response = $this->client->request("GET", "/api/shadowsocks/config/show");

            return $response;

        } catch (Throwable $th) {
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
     * start
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError|void
     */
    public function start()
    {
        try {
            $response = $this->client->request("POST", "/api/shadowsocks/start");

            return $response;

        } catch (Throwable $th) {
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
     * restart
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function restart()
    {
        try {
            $response = $this->client->request("POST", "/api/shadowsocks/restart");

            return $response;

        } catch (Throwable $th) {
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
     * stop
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return \Psr\Http\Message\ResponseInterface|void
     */
    public function stop()
    {
        try {
            $response = $this->client->request("POST", "/api/shadowsocks/stop");

            return $response;

        } catch (Throwable $th) {
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
     * status
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function status()
    {
        try {
            $response = $this->client->request("GET", "/api/shadowsocks/status");

            return $response;

        } catch (Throwable $th) {
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
     * Summary of deleteConfig
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function deleteConfig()
    {
        try {
            $response = $this->client->request("DELETE", "/api/shadowsocks/config/delete");

            return $response;

        } catch (Throwable $th) {

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
