SELECT * FROM sessions 
WHERE token = :TOKEN AND expires_at > NOW()
LIMIT 1;
