<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

if (!function_exists('getJWT')) {
    /**
     * Generates a JWT
     *
     * @param array $payload Data to include in the token
     * @param string|null $expiresIn Expiration time (default: '48h')
     * @return string Generated JWT
     */
    function getJWT($payload, $expiresIn = '2 days')
    {
        $key = getenv('JWT_SECRET'); // Define la clave secreta en el archivo .env
        $issuedAt = time();
        $expirationTime = strtotime('+' . $expiresIn, $issuedAt); // Tiempo de expiración

        $token = [
            'iat' => $issuedAt,          // Timestamp de creación
            'exp' => $expirationTime,    // Timestamp de expiración
            'data' => $payload,          // Datos que deseas incluir en el token
            //'IssuedAt: ' . date('Y-m-d H:i:s', $issuedAt),
            //'ExpiresAt: ' . date('Y-m-d H:i:s', $expirationTime),
        ];

        return JWT::encode($token, $key, 'HS256');
    }
}

if (!function_exists('validateJWT')) {
    /**
     * Validates a JWT
     *
     * @param string $token JWT to validate
     * @return mixed Decoded token if valid, false otherwise
     */
    function validateJWT($token)
    {
        try {
            $key = getenv('JWT_SECRET');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return (array) $decoded->data;
        } catch (\Exception $e) {
            return false; // Token inválido
        }
    }
}


if (!function_exists('encrypt_token')) {
    function encrypt_token($data, $method = 'aes-256-cbc') {
        $key = getenv('AUTH_SECRET');
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
        return openssl_encrypt($data, $method, $key, 0, $iv) . '::' . bin2hex($iv);
    }
}

if (!function_exists('decrypt_token')) {
    function decrypt_token($data, $method = 'aes-256-cbc') {
        $key = getenv('AUTH_SECRET');
        list($encrypted_data, $iv) = explode('::', $data);
        $iv = hex2bin($iv);
        return openssl_decrypt($encrypted_data, $method, $key, 0, $iv);
    }
}