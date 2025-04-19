UPDATE sessions SET last_activity = NOW() WHERE token = :TOKEN;
