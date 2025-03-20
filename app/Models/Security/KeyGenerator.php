<?php
namespace App\Models\Security;

class KeyGenerator
{
    /**
     * Path where keys are stored.
     * @var string
     */
    public $path;

    /**
     * Private key path.
     * @var string
     */
    private $privateKeyPath;

    /**
     * Public key path.
     * @var string
     */
    private $publicKeyPath;

    public function __construct()
    { 
        $this->path = base_path(config("generator.path"));
        $this->privateKeyPath = $this->path . "/priv.pem";
        $this->publicKeyPath = $this->path . "/pub.pem";

        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    /**
     * Generate RSA key pair.
     *
     * @param bool $overwrite If true, overwrite existing keys.
     * @return bool True if keys were generated successfully.
     */
    public function generateKeys(bool $overwrite = false): bool
    {
        if (!$overwrite && file_exists($this->privateKeyPath) && file_exists($this->publicKeyPath)) {
            return false;
        }

        $config = [
            "private_key_bits" => config("generator.bits"),
            "private_key_type" => config("generator.key_type"),
        ];

        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privateKey);
        $publicKey = openssl_pkey_get_details($res)['key'];

        file_put_contents($this->privateKeyPath, $privateKey);
        file_put_contents($this->publicKeyPath, $publicKey);

        return true;
    }

    /**
     * Encrypt data using the public key.
     *
     * @param string $data The data to encrypt.
     * @return string|false The encrypted data (base64 encoded) or false on failure.
     */
    public function encryptWithPublicKey(string $data): string|false
    {
        if (!file_exists($this->publicKeyPath)) {
            return false;
        }

        $publicKey = file_get_contents($this->publicKeyPath);
        $keyResource = openssl_pkey_get_public($publicKey);

        if (!$keyResource) {
            return false;
        }

        $encrypted = '';
        if (openssl_public_encrypt($data, $encrypted, $keyResource)) {
            return base64_encode($encrypted);
        }

        return false;
    }

    /**
     * Decrypt data using the private key.
     *
     * @param string $encryptedData The base64 encoded encrypted data.
     * @return string|false The decrypted data or false on failure.
     */
    public function decryptWithPrivateKey(string $encryptedData): string|false
    {
        if (!file_exists($this->privateKeyPath)) {
            return false;
        }

        $privateKey = file_get_contents($this->privateKeyPath);
        $keyResource = openssl_pkey_get_private($privateKey);

        if (!$keyResource) {
            return false;
        }

        $decrypted = '';
        if (openssl_private_decrypt(base64_decode($encryptedData), $decrypted, $keyResource)) {
            return $decrypted;
        }

        return false;
    }

    /**
     * Sign data using the private key.
     *
     * @param string $data The data to sign.
     * @return string|false The signature (base64 encoded) or false on failure.
     */
    public function signWithPrivateKey(string $data): string|false
    {
        if (!file_exists($this->privateKeyPath)) {
            return false;
        }
        
        $privateKey = file_get_contents($this->privateKeyPath);
        $keyResource = openssl_pkey_get_private($privateKey);

        if (!$keyResource) {
            return false;
        }

        $signature = '';
        if (openssl_sign($data, $signature, $keyResource, OPENSSL_ALGO_SHA256)) {
            return base64_encode($signature);
        }

        return false;
    }

    /**
     * Verify a signature using the public key.
     *
     * @param string $data The original data.
     * @param string $signature The base64 encoded signature.
     * @return bool True if the signature is valid, false otherwise.
     */
    public function verifyWithPublicKey(string $data, string $signature): bool
    {
        if (!file_exists($this->publicKeyPath)) {
            return false;
        }

        $publicKey = file_get_contents($this->publicKeyPath);
        $keyResource = openssl_pkey_get_public($publicKey);

        if (!$keyResource) {
            return false;
        }

        return openssl_verify($data, base64_decode($signature), $keyResource, OPENSSL_ALGO_SHA256) === 1;
    }

    /**
     * Generate a token, sign and encrypt it.
     *
     * @return array|false An array with the encrypted token and its signature, or false on failure.
     */
    public function generateAndSignToken(): string|false
    {
        $tokenData = [
            "iat" => time()
        ];
        // Json encode
        $tokenJson = json_encode($tokenData);
        
        // Sign token
        $signature = $this->signWithPrivateKey($tokenJson);
        if ($signature === false) {
            return false;
        }

        // encrypt token
        $encryptedToken = $this->encryptWithPublicKey($tokenJson);
        if ($encryptedToken === false) {
            return false;
        }

        //data
        return base64_encode(json_encode([
            "token" => base64_encode($encryptedToken),
            "signature" => base64_encode($signature)
        ]));
    }

    /**
     * Verify and decrypt a token.
     *
     * @param string $encryptedToken The base64 encoded encrypted token.
     * @param string $signature The base64 encoded signature.
     * @return array|false The decrypted token data or false on failure.
     */
    public function verifyAndDecryptToken(string $payload): bool
    {
        // Decode payload
        $decodedPayload = json_decode(base64_decode($payload), true);
        if (!$decodedPayload || !isset($decodedPayload["token"], $decodedPayload["signature"])) {
            return false;
        }

        // Decode token and signature
        $encryptedToken = base64_decode($decodedPayload["token"]);
        $signature = base64_decode($decodedPayload["signature"]);

        //Decrypt token
        $decryptedToken = $this->decryptWithPrivateKey($encryptedToken);
        if ($decryptedToken === false) {
            return false;
        }

        //Verify sign 
        if (!$this->verifyWithPublicKey($decryptedToken, $signature)) {
            return false;
        }

        // Decode json
        $tokenData = json_decode($decryptedToken, true);
        if (!$tokenData || !isset($tokenData["iat"])) {
            return false;
        }

        //Verify expiration time
        $expirationMinutes = config('generator.expires', 2);
        if (time() - $tokenData["iat"] > ($expirationMinutes * 60)) {
            return false;
        }

        return true;
    }

}

