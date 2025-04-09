<?php
/*
  ================================================================================
  📁 File: classes/AdminService.php
  📌 Project: Speakify
  🧰 Type: Backend Class
  📚 Description:
     - Provides super admin tools for system control and maintenance.
     - Requires admin session validation via SessionManager.
     - Executes CLI tasks like generating file structure.
  ================================================================================
*/

declare(strict_types=1);

require_once BASEPATH . '/backend/classes/SessionManager.php';

class AdminService
{
    /**
     * Checks if the current user has admin privileges.
     * You can replace this logic with a role/flag check later.
     */
    public static function isAdmin(): bool
    {
        $session = SessionManager::getCurrentUser();
        return isset($session['email']) && $session['email'] === 'admin@example.com';
    }

    /**
     * Executes the generate_file_structure.sh script.
     * Returns output and success flag.
     */
    public static function generateFileStructure(): array
    {
        $output = [];
        $returnCode = 0;

        // Run the script and capture output
        exec('bash generate_file_structure.sh 2>&1', $output, $returnCode);

        return [
            'success' => $returnCode === 0,
            'output' => implode("\n", $output)
        ];
    }

    // 🔧 More admin tools can be defined here.
}
