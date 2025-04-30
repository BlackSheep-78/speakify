-- =============================================================================
-- File: backend/sql/users/select_by_email.sql
-- Project: Speakify
-- Description: Retrieve user by email for authentication, including name and password hash
-- =============================================================================

SELECT id, email, password_hash, name
FROM users
WHERE email = :EMAIL
LIMIT 1;
