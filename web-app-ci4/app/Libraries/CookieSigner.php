<?php

namespace App\Libraries;

class CookieSigner
{
    private static function getSecretKey(): string
    {
        $config = config('Encryption');
        $key = $config->key ?? '';
        if (!empty($key)) {
            return $key;
        }
        return 'StudentShield_Cookie_HMAC_Secret_Fallback_Key_2025';
    }

    /**
     * Signs raw payload (e.g., "userId:token") and returns base64 string containing payload + HMAC signature
     */
    public static function sign(string $payload): string
    {
        $hmac = hash_hmac('sha256', $payload, self::getSecretKey());
        return base64_encode($payload . '.' . $hmac);
    }

    /**
     * Verifies signed cookie string and returns raw payload ("userId:token") if valid, or null if invalid/tampered
     */
    public static function verify(string $cookieString): ?string
    {
        $decoded = base64_decode($cookieString, true);
        if ($decoded === false) {
            return null;
        }

        $lastDotPos = strrpos($decoded, '.');
        if ($lastDotPos === false) {
            return null;
        }

        $payload = substr($decoded, 0, $lastDotPos);
        $signature = substr($decoded, $lastDotPos + 1);

        if ($payload === '' || $signature === '') {
            return null;
        }

        $expectedHmac = hash_hmac('sha256', $payload, self::getSecretKey());

        if (hash_equals($expectedHmac, $signature)) {
            return $payload;
        }

        return null;
    }
}
