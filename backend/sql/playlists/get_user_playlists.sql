SELECT p.id, p.name, p.schema_id, s.name AS schema_name,
       (SELECT COUNT(*) FROM playlist_tb_link WHERE playlist_id = p.id) AS tb_count
FROM playlists p
LEFT JOIN schemas s ON p.schema_id = s.id
WHERE p.user_id = :USER_ID
ORDER BY p.name ASC;