-- =============================================================================
-- File: backend/sql/playlists/get_user_playlists.sql
-- Project: Speakify
-- Description: Retrieve all playlists that belong to a specific user
-- =============================================================================

SELECT p.*
FROM playlists p
WHERE p.user_id = :USER_ID
ORDER BY p.name ASC;
