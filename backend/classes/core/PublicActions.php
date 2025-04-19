<?php

class PublicActions
{
  public static function get(): array
  {
    return [
      'create_session',
      'validate_session',
      'register_user',
      'login',
      'logout',
      'playlists',
      'get_config'
    ];
  }
}
