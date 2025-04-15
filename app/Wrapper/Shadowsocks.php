<?php

namespace App\Wrapper;

use Proto\Shadowsocks\EmptyRequest;
use Proto\Shadowsocks\MountRequest;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Proto\Shadowsocks\ShadowsocksServiceClient;

class Shadowsocks extends System
{

    /**
     *  __construct
     * @param string $endpoint
     * @param int $port
     */
    public function __construct(string $endpoint, int $port = 50051)
    {
        parent::__construct(
            $endpoint,
            $port,
            ShadowsocksServiceClient::class
        );
    }

    /**
     * add new config
     * @param mixed $port
     * @param mixed $password
     * @param mixed $method
     * @param mixed $dns
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function createConfig(
        $port,
        $password,
        $method = "chacha20-ietf-poly1305",
        $dns = null
    ) {
        $mountRequest = new MountRequest();
        $mountRequest->setServerPort($port);
        $mountRequest->setPassword($password);
        $mountRequest->setMethod($method);

        if ($dns) {
            $mountRequest->setDns($dns);
        }

        list($response, $status) = $this->getClient()->mount(
            $mountRequest,
            $this->getMetadata()
        )->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();

    }

    /**
     * Start server
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function serverStart()
    {
        list($response, $status) = $this->getClient()->ServerStart(
            new EmptyRequest(),
            $this->getMetadata()
        )->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }

    /**
     * Stop the server
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function serverStop()
    {
        list($response, $status) = $this->getClient()->ServerStop(
            new EmptyRequest(),
            $this->getMetadata()
        )->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }

    /**
     * Show the status of the server
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function serverStatus()
    {
        list($response, $status) = $this->getClient()->ServerStatus(
            new EmptyRequest(),
            $this->getMetadata()
        )->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }

    /**
     * Start the server client
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function clientStart()
    {
        list($response, $status) = $this->getClient()->ClientStart(
            new EmptyRequest(),
            $this->getMetadata()
        )->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }

    /**
     * Stop the server client
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function clientStop()
    {
        list($response, $status) = $this->getClient()->ClientStop(
            new EmptyRequest(),
            $this->getMetadata()
        )->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }

    /**
     * Show the status of the server client
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function clientStatus()
    {
        list($response, $status) = $this->getClient()->ClientStatus(
            new EmptyRequest(),
            $this->getMetadata()
        )->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }

    /**
     * Delete configuration files
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function deleteConfig()
    {
        list($response, $status) = $this->getClient()->delete(
            new EmptyRequest(),
            $this->getMetadata()
        )->wait();

        if ($status->code !== \Grpc\STATUS_OK) {
            throw new ReportError("gRPC error: " . $status->details, $status->code);
        }

        return $response->getMessage();
    }
}
