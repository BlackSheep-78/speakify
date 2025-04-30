<?php
// Project: Speakify
// File: /backend/classes/models/PlaylistModel.php
// Description: Data access layer for all playlist-related operations

class PlaylistModel {
  private $db;

  public function __construct(array $options = []) 
  {
      $this->db = $options['db'] ?? null;
  
      if (!$this->db instanceof Database) 
      {
          throw new Exception("PlaylistModel requires a valid 'db' instance.");
      }
  }

  public function getPlaylists($user_id = null) 
  {
      if ($user_id) 
      {
          return $this->db->file('/playlists/get_user_playlists.sql')
                          ->replace(':USER_ID', $user_id, 'i')
                          ->result();
      } 
      else 
      {
          return $this->db->file('/playlists/get_all_playlists.sql')
                          ->result();
      }
  }
  
  public function getPlaylistDetails($playlist_id) 
  {
      return $this->db->file('/playlists/get_playlist_details.sql')
                      ->replace(':PLAYLIST_ID', $playlist_id, 'i')
                      ->result(['fetch' => 'row']);
  }
  

}
