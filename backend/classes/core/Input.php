<?php
// ============================================
// Project: Speakify
// File: /backend/classes/core/Input.php
// Description: Centralized input handler for GET, POST, JSON, and COOKIE with validation and error feedback
// ============================================

class Input
{
    protected static ?array $jsonCache = null;

    protected static array $filters = [
        'int'    => FILTER_VALIDATE_INT,
        'float'  => FILTER_VALIDATE_FLOAT,
        'bool'   => FILTER_VALIDATE_BOOLEAN,
        'email'  => FILTER_VALIDATE_EMAIL,
        'url'    => FILTER_VALIDATE_URL,
        'ip'     => FILTER_VALIDATE_IP,
        'string' => 'sanitize_string',
        'token'  => 'sanitize_token',
        'raw'    => null
    ];

    public static function get(string $key, string $filter = 'raw', mixed $default = null): mixed
    {
        return self::filter($_GET[$key] ?? null, $filter ?? 'raw', $default);
    }

    public static function post(string $key, string $filter = 'raw', mixed $default = null): mixed
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
        return self::filter($_POST[$key] ?? null, $filter ?? 'raw', $default);
    }

    public static function request(string $key, string $filter = 'raw', mixed $default = null): mixed
    {
        return self::filter($_REQUEST[$key] ?? null, $filter ?? 'raw', $default);
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

    public static function json(string $key, string $filter = 'raw', mixed $default = null): mixed
    {
        static $json = null;
        if ($json === null) {
            $json = json_decode(file_get_contents('php://input'), true);
        }
        return self::filter($json[$key] ?? null, $filter ?? 'raw', $default);
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

    protected static function filter(mixed $value, string $filter, mixed $default): mixed
    {
        if ($value === null) return $default;

        if (isset(self::$filters[$filter])) 
        {
            $f = self::$filters[$filter];

            if (is_string($f) && is_callable([__CLASS__, $f])) 
            {
                return call_user_func([__CLASS__, $f], $value);
            }

            if (is_int($f)) 
            {
                return filter_var($value, $f) !== false ? $value : $default;
            }
        }

        return $default;
    }
    
    private static function isJsonRequest()
    {
        return isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }

    protected static function sanitize_string(string $value): string
    {
        return trim(strip_tags($value));
    }
    
    protected static function sanitize_token(string $value): string
    {
        return preg_replace('/[^a-f0-9]/i', '', $value);
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
