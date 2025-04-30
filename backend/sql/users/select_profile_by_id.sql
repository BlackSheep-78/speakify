-- =============================================================================
-- Project: Speakify
-- File: /backend/sql/users/select_profile_by_id.sql
-- Description: Retrieves full profile details for a user by ID
-- =============================================================================

SELECT id, email, name, last_login
FROM users
WHERE id = :USER_ID;
