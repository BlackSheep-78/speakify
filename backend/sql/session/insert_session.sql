-- =============================================================================
-- 📄 File: /backend/sql/session/insert_session.sql
-- 🎯 Purpose: Insert a new session with token and expiration timestamps
-- =============================================================================

INSERT INTO sessions (token, last_activity, expires_at)
VALUES (:TOKEN, :NOW, :EXPIRES);
