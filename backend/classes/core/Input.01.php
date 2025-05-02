<?php
// ============================================
// Project: Speakify
// File: /backend/classes/core/Input.php
// Description: Centralized input handler for GET, POST, JSON, and COOKIE with validation and error feedback
// ============================================

class Input
{
    protected static ?array $jsonCache = null;

    public static function get(string $key, $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public static function post($key = null, $default = null)
    {
        static $jsonCache = null;
    
        if ($jsonCache === null && self::isJsonRequest()) {
            $raw = file_get_contents('php://input');
            $jsonCache = json_decode($raw, true) ?? [];
        }
    
        if (self::isJsonRequest()) {
            if ($key === null) return $jsonCache;
            return $jsonCache[$key] ?? $default;
        }
    
        if ($key === null) return $_POST;
        return $_POST[$key] ?? $default;
    }

    public static function cookie(string $key, $default = null): mixed
    {
        return $_COOKIE[$key] ?? $default;
    }

    public static function param(string $key, $default = null): mixed
    {
        return $_GET[$key]
            ?? $_POST[$key]
            ?? self::json($key, $default);
    }

    public static function json(?string $key = null, $default = null): mixed
    {
        if (self::$jsonCache === null) {
            $raw = file_get_contents('php://input');
            self::$jsonCache = json_decode($raw, true) ?? [];
        }

        if ($key === null) {
            return self::$jsonCache;
        }

        return self::$jsonCache[$key] ?? $default;
    }

    public static function require(string $key, string $scope = 'param'): mixed
    {
        $value = match ($scope) {
            'get' => self::get($key),
            'post' => self::post($key),
            'cookie' => self::cookie($key),
            'json' => self::json($key),
            default => self::param($key),
        };

        if ($value === null || $value === '') {
            self::fail("Missing required input: {$key}");
        }

        return $value;
    }

    public static function optional(string $key, string $scope = 'param', $default = null): mixed
    {
        return match ($scope) {
            'get' => self::get($key, $default),
            'post' => self::post($key, $default),
            'cookie' => self::cookie($key, $default),
            'json' => self::json($key, $default),
            default => self::param($key, $default),
        };
    }

    public static function token(): string
    {
        return self::get('token')
            ?? self::cookie('speakify_token')
            ?? '';
    }

    public static function action(): string
    {
        return self::get('action') ?? '';
    }

    private static function isJsonRequest()
    {
        return isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }
    

    protected static function fail(string $message): void
    {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => $message,
            'code' => 'ERROR 0002'
        ]);
        exit;
    }
}
