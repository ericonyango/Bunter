<?php

namespace App\Marketplace\Encryption;

use Random\RandomException;
use SodiumException;

class Cipher
{
    /**
     * @throws RandomException
     * @throws SodiumException
     */
    public function encryptMessage($message,$publicKey,$privateKey): array
    {
        $message_nonce = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);
        $keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey($privateKey,$publicKey);
        $ciphertext = sodium_crypto_box(
            $message,
            $message_nonce,
            $keypair
        );
        return  [
            'ciphertext'=> base64_encode($ciphertext),
            'message_nonce'=>base64_encode($message_nonce)
        ];
    }

    /**
     * @throws SodiumException
     */
    public static function decryptMessage($ciphertext,$message_nonce,$publicKey,$privateKey):bool|string
    {
        $ciphertext = base64_decode($ciphertext);
        $message_nonce = base64_decode($message_nonce);
        $keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey($privateKey,$publicKey);

        return  sodium_crypto_box_open($ciphertext,$message_nonce,$keypair);
    }

    /**
     * @throws SodiumException
     */
    public static function convertToHex($data): string
    {
        //return encrypted($data)
        return sodium_bin2hex($data);
    }

    /**
     * @throws SodiumException
     */
    public static function convertToBin($data): string
    {
        //return decrypt($data);
        return sodium_hex2bin($data);
    }
}
