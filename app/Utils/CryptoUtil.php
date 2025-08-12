<?php

namespace App\Utils;

class CryptoUtil
{
    // The 32-byte (256-bit) key in binary, converted from hex string
    private static $key;

    public static function initKey()
    {
        if (self::$key === null) {
            self::$key = hex2bin('b2cf55f4bfcdf35d344124f2abf1c85377fe40a6109e28258ba7e51b06fcc4d6');
        }
    }

    public static function decrypt(string $encryptedData): ?string
    {
        self::initKey(); // <-- initialize key here

        try {
            // Base64 decode the payload (the btoa in JS)
            $jsonPayload = base64_decode($encryptedData);

            if (!$jsonPayload) {
                return null;
            }

            $payload = json_decode($jsonPayload, true);

            if (!isset($payload['iv']) || !isset($payload['value'])) {
                return null;
            }

            $iv = base64_decode($payload['iv']);
            $ciphertext = base64_decode($payload['value']);

            // AES-256-CBC decrypt
            $decrypted = openssl_decrypt(
                $ciphertext,
                'AES-256-CBC',
                self::$key,
                OPENSSL_RAW_DATA,
                $iv
            );

            return $decrypted === false ? null : $decrypted;
        } catch (\Exception $e) {
            return null;
        }
    }
}

