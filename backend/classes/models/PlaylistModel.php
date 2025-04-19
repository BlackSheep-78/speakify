<?php
// file: backend/classes/models/PlaylistModel.php

class PlaylistModel {
  private $db;

  public function __construct($db) {
    $this->db = $db;
  }

  public function getPlaylists($user_id = null) 
  {

    Logger::debug("getPlaylists($user_id)");

    $sql = "SELECT p.id, p.name, p.schema_id, s.name AS schema_name,
                   (SELECT COUNT(*) FROM playlist_tb_link WHERE playlist_id = p.id) AS tb_count
            FROM playlists p
            LEFT JOIN `schemas` s ON p.schema_id = s.id";

    if ($user_id) 
    {
      Logger::debug("user id = ".$user_id);

      $sql .= " WHERE p.user_id = :user_id";
    }

    $stmt = $this->db->prepare($sql);
    if ($user_id) $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getPlaylistDetails($playlist_id) 
  {
    $sql = "SELECT * FROM playlists WHERE id = :playlist_id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':playlist_id', $playlist_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
