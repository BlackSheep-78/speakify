<?php

// =============================================================================
// Project: Speakify
// File: /backend/classes/core/Input.php
// Description: Single gateway class for all incoming HTTP input (GET/POST/REQUEST/COOKIE)
// =============================================================================
// This class is the only entry point for all incoming client data.
// It sanitizes, validates, and controls all access to input variables.
// Optionally maps friendly aliases to filter constants.
// Supports JSON error responses for bad input.
// =============================================================================
// TODO: Add Input::header('X-Custom-Header') support later.

class Input
{
    protected static array $data = [];

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

    public static function get(string $key, string $type = 'raw')
    {
        return self::fetch($_GET, $key, $type);
    }

    public static function post(string $key, string $type = 'raw')
    {
        return self::fetch($_POST, $key, $type);
    }

    public static function request(string $key, string $type = 'raw')
    {
        return self::fetch($_REQUEST, $key, $type);
    }

    public static function cookie(string $key, string $type = 'raw')
    {
        return self::fetch($_COOKIE, $key, $type);
    }

    public static function all(): array
    {
        return array_merge($_REQUEST, $_COOKIE);
    }

    protected static function fetch(array $source, string $key, string $type)
    {
        if (!isset($source[$key])) 
        {
            self::jsonError("Missing parameter: $key");
        }

        $value = $source[$key];
        $filter = self::$filters[$type] ?? null;

        if (is_callable([self::class, $filter])) 
        {
            $value = self::$filter($value);
        } 
        elseif ($filter !== null) 
        {
            $value = filter_var($value, $filter);
        }

        if ($value === false || $value === null) 
        {
            self::jsonError("Invalid value for '$key' as type '$type'");
        }

        return $value;
    }

    protected static function sanitize_string($value): string
    {
        return trim(strip_tags($value));
    }

    protected static function sanitize_token($value): ?string
    {
        $value = preg_replace('/[^a-zA-Z0-9_-]/', '', $value);
        $len = strlen($value);
        return ($len >= 32 && $len <= 128) ? $value : null;
    }

    protected static function jsonError(string $message): void
    {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => $message
        ]);
        exit;
    }
}
