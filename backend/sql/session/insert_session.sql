-- =============================================================================
-- 📄 File: /backend/sql/session/insert_session.sql
-- 🎯 Purpose: Insert a new session with token and expiration timestamps
-- =============================================================================

INSERT INTO sessions (token, created_at, expires_at, ip_address)
VALUES (:TOKEN, :NOW, :EXPIRES, :IP);

