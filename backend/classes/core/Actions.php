<?php
// ============================================
// Project: Speakify
// File: /backend/classes/core/Actions.php
// Description: Static class to classify and validate API actions.
// ============================================

class Actions
{
    protected static array $map = [

        'get_config'        => ['public' => true,  'protected' => false],

        'login'             => ['public' => true,  'protected' => false],
        'logout'            => ['public' => true,  'protected' => false],
        'register_user'     => ['public' => true,  'protected' => false],
        'create_session'    => ['public' => true,  'protected' => false],
        'ping_session'      => ['public' => true,  'protected' => false],
        'validate_session'  => ['public' => true,  'protected' => false],

        'get_profile'       => ['public' => false, 'protected' => true],
        'check_schema'      => ['public' => false, 'protected' => true],
        'tts_generate'      => ['public' => false, 'protected' => true],
        'admin_tool'        => ['public' => false, 'protected' => true],
        'check_session'     => ['public' => false, 'protected' => true],
        'get_sentences'     => ['public' => true,  'protected' => false],
        'get_playlists'     => ['public' => true, 'protected' => false]
    ];

    public static function isPublic(string $action): bool
    {
        return self::$map[$action]['public'] ?? false;
    }

    public static function isProtected(string $action): bool
    {
        return self::$map[$action]['protected'] ?? false;
    }

    public static function isValid(string $action): bool
    {
        return array_key_exists($action, self::$map);
    }

    // Optional: future expansion idea
    public static function getFlags(string $action): array
    {
        return self::$map[$action] ?? [];
    }
}
