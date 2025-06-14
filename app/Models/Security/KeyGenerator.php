<?php

namespace App\Models\Security;

class KeyGenerator
{
    /**
     * Path for the key
     * @var string
     */
    public string $path;

    /**
     * Private key path
     * @var string
     */
    private string $privateKeyPath;

    /**
     * Public key path
     */
    private string $publicKeyPath;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->path = base_path(config("generator.path", "storage/keys"));
        $this->privateKeyPath = "{$this->path}/priv.pem";
        $this->publicKeyPath = "{$this->path}/pub.pem";

        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    /**
     * Generate pair keys
     * @param bool $overwrite
     * @return bool
     */
    public function generateKeys(bool $overwrite = false): bool
    {
        if (!$overwrite && file_exists($this->privateKeyPath) && file_exists($this->publicKeyPath)) {
            return false;
        }

        $config = [
            "private_key_bits" => config("generator.bits", 2048),
            "private_key_type" => config("generator.key_type", OPENSSL_KEYTYPE_RSA),
        ];

        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privateKey);
        $publicKey = openssl_pkey_get_details($res)['key'];

        file_put_contents($this->privateKeyPath, $privateKey);
        file_put_contents($this->publicKeyPath, $publicKey);

        return true;
    }

    /**
     * Encrypt data
     * @param string $data
     * @return bool|string
     */
    public function encrypt(string $data): string|false
    {
        if (!file_exists($this->publicKeyPath)) {
            return false;
        }

        $publicKey = file_get_contents($this->publicKeyPath);
        $key = openssl_pkey_get_public($publicKey);

        if (!$key)
            return false;

        if (openssl_public_encrypt($data, $encrypted, $key)) {
            return base64_encode($encrypted);
        }

        return false;
    }

    /**
     * Decrypt data
     * @param string $base64Data
     * @return bool|string
     */
    public function decrypt(string $base64Data): string|false
    {
        if (!file_exists($this->privateKeyPath)) {
            return false;
        }

        $privateKey = file_get_contents($this->privateKeyPath);
        $key = openssl_pkey_get_private($privateKey);

        if (!$key)
            return false;

        if (openssl_private_decrypt(base64_decode($base64Data), $decrypted, $key)) {
            return $decrypted;
        }

        return false;
    }

    /**
     * Generate a random token
     * @return bool|string
     */
    public function generateToken(): string|false
    {
        $data = json_encode(["iat" => time()]);
        return $this->encrypt($data);
    }

    /**
     * Validate token
     * @param string $encryptedToken
     * @return bool
     */
    public function validateToken(string $encryptedToken): bool
    {
        $decrypted = $this->decrypt($encryptedToken);

        if ($decrypted === false) {
            return false;
        }

        $data = json_decode($decrypted, true);

        if (!isset($data["iat"])) {
            return false;
        }

        $expires = config("generator.expires", 2); // minutes
        return (time() - $data["iat"]) <= ($expires * 60);
    }
}
