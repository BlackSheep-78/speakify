-- =============================================================================
-- 📄 File: /backend/sql/session/select_valid_by_token.sql
-- 🎯 Purpose: Fetch a valid session by token if not expired
-- =============================================================================

SELECT * FROM sessions
WHERE token = :TOKEN AND expires_at > NOW()
LIMIT 1;