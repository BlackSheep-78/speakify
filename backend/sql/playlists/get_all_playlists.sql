-- =============================================================================
-- File: backend/sql/playlists/get_all_playlists.sql
-- Project: Speakify
-- Description: Retrieve all playlists, including their associated schema names
-- =============================================================================

SELECT p.*, s.name AS schema_name
FROM playlists p
LEFT JOIN `schemas` s ON p.schema_id = s.id
ORDER BY p.name ASC;
