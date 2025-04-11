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
    public static function isAdmin(string $token): bool
    {
        Logger::debug("isAdmin ".$token, __FILE__, __LINE__);



        $session = SessionManager::getCurrentUser($token);

        $str = json_encode($session, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        Logger::debug($str, __FILE__, __LINE__);
        Logger::debug($session['email'], __FILE__, __LINE__);



        return isset($session['email']) && $session['email'] === 'jorge.mnf.alves@gmail.com';
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

        $scriptPath = BASEPATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'generate_file_structure.sh';
        $scriptPath = str_replace(['\\', '//'], '/', $scriptPath); // normalize for bash
        
        $cmd = "bash {$scriptPath} 2>&1";
        
        Logger::debug($cmd, __FILE__, __LINE__);
        
        exec($cmd, $output, $returnCode);

        return [
            'success' => $returnCode === 0,
            'output' => implode("\n", $output)
        ];
    }

    // 🔧 More admin tools can be defined here.
}
