<?php
// ============================================================================
// File: speakify/backend/classes/SessionManager.php
// Description:
//     Manages user session lifecycle for Speakify, including:
//     - Creating anonymous sessions for every visitor (even without login)
//     - Validating session tokens and updating last_activity
//     - Upgrading to logged-in sessions by setting user_id
// ============================================================================
class SessionManager
{
    protected PDO $pdo;
    protected array $config;

    public function __construct(PDO $pdo, array $config)
    {
        $this->pdo = $pdo;
        $this->config = $config;
    }

    public function createAnonymous(): array
    {
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+7 days'));
        $now = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare("
            INSERT INTO sessions (token, user_id, expires_at, last_activity)
            VALUES (:token, NULL, :expires, :last_activity)
        ");
        $stmt->execute([
            'token' => $token,
            'expires' => $expires,
            'last_activity' => $now,
        ]);

        return [
            'token' => $token,
            'expires_at' => $expires,
            'user_id' => null,
        ];
    }

    public function validateToken(string $token, bool $touch = true): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM sessions WHERE token = :token AND expires_at > NOW()
        ");
        $stmt->execute(['token' => $token]);
        $session = $stmt->fetch();

        if (!$session) return null;
        if ($touch) $this->touch($token);

        return $session;
    }

    public function touch(string $token): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE sessions SET last_activity = NOW() WHERE token = :token
        ");
        $stmt->execute(['token' => $token]);
    }

    public function attachUser(string $token, int $userId): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE sessions SET user_id = :userId WHERE token = :token
        ");
        $stmt->execute([
            'userId' => $userId,
            'token' => $token
        ]);
    }

    public function getUserId(string $token): ?int
    {
        $stmt = $this->pdo->prepare("
            SELECT user_id FROM sessions WHERE token = :token
        ");
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch();

        return $row ? $row['user_id'] : null;
    }
}
