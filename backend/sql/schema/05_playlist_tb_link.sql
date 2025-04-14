-- ✅ TABLE: playlist_tb_link

CREATE TABLE IF NOT EXISTS playlist_tb_link (
  id INT AUTO_INCREMENT PRIMARY KEY,
  playlist_id INT NOT NULL COMMENT 'Reference to the parent playlist',
  tb_id INT NOT NULL COMMENT 'Reference to translation_pairs.pair_id',
  order_index INT DEFAULT 0 COMMENT 'Defines the playback order within the playlist',

  FOREIGN KEY (playlist_id) REFERENCES playlists(id) ON DELETE CASCADE,
  FOREIGN KEY (tb_id) REFERENCES translation_pairs(pair_id) ON DELETE CASCADE
);
