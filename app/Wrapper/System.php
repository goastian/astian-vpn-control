<?php

namespace App\Wrapper;

use Grpc\ChannelCredentials;
use App\Models\Security\KeyGenerator;

class System
{
    /**
     * gRPC client
     * @var mixed
     */
    protected $client;

    /**
     * Endpoint
     * @var string
     */
    protected string $endpoint;

    /**
     * Port
     * @var int
     */
    protected int $port;

    /**
     * Token JWT
     * @var string
     */
    protected string $token;

    /**
     * Clase del cliente gRPC
     * @var string
     */
    protected string $clientClass;

    public function __construct(string $endpoint, int $port = 50051, string $clientClass)
    {
        $this->endpoint = $endpoint;
        $this->port = $port;
        $this->clientClass = $clientClass;

        $this->initializeClient();
    }

    /**
     * initializeClient
     * @return void
     */
    private function initializeClient(): void
    {
        $target = "{$this->endpoint}:{$this->port}";

        $this->client = new $this->clientClass(
            $target,
            [
                'credentials' => ChannelCredentials::createInsecure(),
                'grpc.keepalive_timeout_ms' => 5000,  
                'grpc.keepalive_time_ms' => 10000,    
                'grpc.keepalive_permit_without_calls' => 1, 
            ]
        );
    }

    /**
     * Get client
     * @return object
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get metadata
     * @return string[][]
     */
    public function getMetadata(): array
    {
        $keyGenerator = app(KeyGenerator::class);
        $token = $keyGenerator->generateToken();

        return [
            'authorization' => [$token]
        ];
    }
}
