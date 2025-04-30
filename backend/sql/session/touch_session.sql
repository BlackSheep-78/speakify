-- =============================================================================
-- Project: Speakify
-- File: /backend/sql/session/touch_session.sql
-- Description: Updates last_activity and extends expires_at by 8 hours
-- =============================================================================

UPDATE sessions
SET 
  last_activity = NOW(),
  expires_at = DATE_ADD(NOW(), INTERVAL 8 HOUR)
WHERE token = :TOKEN;
