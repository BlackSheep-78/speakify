<?php
// =============================================================================
// 📦 Class: LoginService
// 📁 Location: backend/classes/auth/LoginService.php
// 🎯 Purpose: Handles user authentication and session management logic
// =============================================================================

class LoginService 
{
  private UserModel $userModel;
  private SessionModel $sessionModel;
  private LoggerModel $loggerModel;

  public function __construct(array $options = []) 
  {
    $this->userModel    = new UserModel($options);
    $this->sessionModel = new SessionModel($options);
    $this->loggerModel  = new LoggerModel($options);
  }

  public function authenticate(string $email, string $password, ?string $existingToken = null): array 
  {
    $user = $this->userModel->findByEmail($email);

    if (!$user || !password_verify($password, $user['password_hash'])) 
    {
      return ['error' => 'Invalid credentials'];
    }

    $session = $this->sessionModel->validateToken($existingToken);

    if ($session && !$session['user_id']) 
    {
      $this->sessionModel->upgradeUserSession($existingToken, $user['id']);
      $token = $existingToken;
    } 
    else 
    {
      error_log("i'm here");

      $newSession = $this->sessionModel->create();
      $token = $newSession['token'] ;
      $this->sessionModel->upgradeUserSession($token, $user['id']);

      error_log("create() returned: " . json_encode($newSession));
    }

    return [
      'success'   => true,
      'logged_in' => true,
      'token'     => $token,
      'user_id'   => $user['id'],
      'name'      => $user['name'],
      'email'     => $user['email'] ?? null
    ];
  }

  public function validate(string $token): array 
  {
    if (!$token) return ['error' => 'Missing session token','code' => 'ERROR 0001'];

    $session = $this->sessionModel->validateToken($token);
    if (!$session) return ['error' => 'Invalid or expired session'];

    $isLoggedIn = isset($session['user_id']) && $session['user_id'] > 0;

    $result = [
      'success'   => true,
      'logged_in' => $isLoggedIn,
      'token' => $session['token'] ?? $token
    ];

    if ($isLoggedIn) 
    {
      $user = $this->userModel->getProfileById($session['user_id']);
      if ($user) 
      {
        $result['user_id'] = $session['user_id'];
        $result['name']    = $user['name'];
        $result['email']   = $user['email'] ?? null;
      }
    }

    return $result;
  }

  public function deleteOldestLogs(): void 
  {
    $total = $this->loggerModel->countAllLogs();
    if ($total <= 0) return;

    $limit = max(1, (int) ceil($total * 0.10));
    $ids = $this->loggerModel->selectOldestLogIds($limit);

    if (count($ids) > 0) {
      $this->loggerModel->deleteLogsByIds($ids);
      Logger::info("🗑️ Deleted {$limit} oldest logs (IDs: " . implode(',', $ids) . ').');
    }
  }

  public function upgrade(string $token, int $user_id): void {
    $session = $this->sessionModel->getSessionUserId($token);
    if ($session && !$session['user_id']) {
      $this->sessionModel->upgradeUserSession($token, $user_id);
      Logger::info("Session upgraded for token: {$token}");
    } else {
      Logger::info("Session already upgraded or not found for token: {$token}");
    }
  }
}
