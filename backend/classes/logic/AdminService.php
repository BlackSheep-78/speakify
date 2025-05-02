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

//require_once BASEPATH . '/backend/classes/SessionManager.php';

class AdminService
{
    /**
     * Checks if the current user has admin privileges.
     * You can replace this logic with a role/flag check later.
     */
    public static function isAdmin(string $token,array $options = []): bool
    {

        $db = $options['db'] ?? null;

        if (!$db instanceof Database) 
        {
            throw new Exception(static::class . " requires a valid 'db' instance. ERROR_T_1525");
        }

        $sessionManager = new SessionManager(['db' => $db]);
        $session = $sessionManager->getCurrentUser($token);
    
        if (!$session || empty($session['email'])) 
        {
            Logger::debug("Admin check failed.");
            return false;
        }
    
        return $session['email'] === 'jorge.mnf.alves@gmail.com';
    }
    
    

    /**
     * Executes the generate_file_structure.sh script.
     * Returns output and success flag.
     */
    public static function generateFileStructure(?string $rootDir = null, ?string $outputFile = null): array
    {
        $output = [];
        $returnCode = 0;
    
        // Step 1: Define paths
        $bashExe = '"C:\\Program Files\\Git\\bin\\bash.exe"'; // Adjust if your Git is installed elsewhere
    
        $scriptPath = BASEPATH . '/resources/generate_file_structure.sh';
        $rootPath   = $rootDir ?? BASEPATH;
        $outputPath = $outputFile ?? BASEPATH . '/docs/file_structure.json';
    
        // Step 2: Convert to Git Bash–compatible paths
        $scriptBashPath = Utilities::toGitBashPath($scriptPath);
        $rootBashPath   = Utilities::toGitBashPath($rootPath);
        $outputBashPath = Utilities::toGitBashPath($outputPath);
    
        // Step 3: Build and run command
        $cmd = "$bashExe $scriptBashPath $rootBashPath $outputBashPath 2>&1";
        Logger::debug("Running file structure generator: $cmd");
    
        exec($cmd, $output, $returnCode);
    
        // Step 4: Return results
        return [
            'success'  => $returnCode === 0,
            'output'   => implode("\n", $output),
            'cmd'      => $cmd,
            'file'     => $outputPath
        ];
    }
    
    
    

    // 🔧 More admin tools can be defined here.
}
