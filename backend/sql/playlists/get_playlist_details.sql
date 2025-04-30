-- =============================================================================
-- File: backend/sql/playlists/get_playlist_details.sql
-- Project: Speakify
-- Description: Get all details about a specific playlist including schema name
-- =============================================================================

SELECT p.*, s.name AS schema_name
FROM playlists p
LEFT JOIN `schemas` s ON p.schema_id = s.id
WHERE p.id = :PLAYLIST_ID;
