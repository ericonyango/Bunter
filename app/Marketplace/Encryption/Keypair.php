<?php

namespace App\Marketplace\Encryption;

class Keypair
{
    private string $keys;

    /**
     * @throws \SodiumException
     */
    public function __construct()
    {
        $this->keys = sodium_crypto_box_keypair();
    }

    public function getKeyPair(): string
    {
        return $this->keys;
    }

    /**
     * @throws \SodiumException
     */
    public function getPublicKey(): string
    {
        return sodium_crypto_box_publickey($this->keys);
    }

    /**
     * @throws \SodiumException
     */
    public function getPrivateKey(): string
    {
        return sodium_crypto_box_secretkey($this->keys);
    }
}
