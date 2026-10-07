<?php

namespace App\Utils;

use Exception;

class JWT
{
    private static function getSecret(): string
    {
        $settings = require __DIR__ . '/../../config/settings.php';
        return $settings['jwt']['secret'] ?? 'uiu-research-portal-secret-key';
    }

    private static function getDefaultExpire(): int
    {
        $settings = require __DIR__ . '/../../config/settings.php';
        return (int)($settings['jwt']['expire'] ?? 86400);
    }

    /**
     * Encode payload into JWT string
     */
    public static function encode(array $payload, ?int $expirySeconds = null): string
    {
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $now = time();
        $ttl = $expirySeconds ?? self::getDefaultExpire();

        $payload = array_merge([
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + $ttl,
        ], $payload);

        $base64UrlHeader = self::base64UrlEncode(json_encode($header));
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload));

        $signature = hash_hmac(
            'sha256',
            $base64UrlHeader . '.' . $base64UrlPayload,
            self::getSecret(),
            true
        );
        $base64UrlSignature = self::base64UrlEncode($signature);

        return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
    }

    /**
     * Decode and verify JWT token
     */
    public static function decode(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$headerB64, $payloadB64, $signatureB64] = $parts;

        // Verify signature
        $expectedSignature = hash_hmac(
            'sha256',
            $headerB64 . '.' . $payloadB64,
            self::getSecret(),
            true
        );
        $expectedSignatureB64 = self::base64UrlEncode($expectedSignature);

        if (!hash_equals($expectedSignatureB64, $signatureB64)) {
            return null;
        }

        // Decode payload
        $payload = json_decode(self::base64UrlDecode($payloadB64), true);
        if (!is_array($payload)) {
            return null;
        }

        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return null;
        }

        return $payload;
    }

    /**
     * Helper to base64url encode
     */
    public static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Helper to base64url decode
     */
    public static function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
