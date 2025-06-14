<?php
namespace App\Repositories\Traits;

trait Wireguard
{
    /**
     * Generate a PrivKey 
     * @return string
     */
    public function generatePrivKey()
    {
        return trim(shell_exec('wg genkey'));
    }

    /**
     * Generate a public key
     * @return string
     */
    public function generatePubKey(string $private_key)
    {
        return trim(shell_exec("echo {$private_key} | wg pubkey"));
    }


    /**
     *  Generate a pair keys (private and public key)  
     * @return string[]
     */
    public function generatePairKeys()
    {
        $private_key = trim(shell_exec('wg genkey'));
        $public_key = trim(shell_exec("echo {$private_key} | wg pubkey"));
        return ['private_key' => $private_key, 'public_key' => $public_key];
    }

    /**
     * Generate a Preshared key for the config peer
     * @return string
     */
    public function generatePresharedkey()
    {
        return trim(shell_exec('wg genpsk'));
    }
}
