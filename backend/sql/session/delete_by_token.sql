-- =============================================================================
-- 📄 File: /backend/sql/session/delete_by_token.sql
-- 🎯 Purpose: Delete a session by token
-- =============================================================================

DELETE FROM sessions WHERE token = :TOKEN;
