SELECT users.name, users.email, MAX(sessions.last_activity) AS last_login
FROM sessions
JOIN users ON users.id = sessions.user_id
WHERE sessions.token = :TOKEN
GROUP BY users.id;
