<?php
// ============================================================================
// Project: Speakify
// File: tests.php
// Description: Dynamically lists all controllers and reports errors from logs.
// ============================================================================


$step = $_GET['step'] ?? '';
$token = $_GET['token'] ?? '';

$controllerDir = __DIR__; // 🔒 Defined earlier to avoid undefined variable

if ($step === 'scan') {
    $files = array_filter(scandir($controllerDir), function($f) use ($controllerDir) {
        return is_file("$controllerDir/$f") && substr($f, -4) === '.php';
    });

    $controllers = array_map(function($f) {
        return basename($f, '.php');
    }, $files);

        $viewDir = realpath(__DIR__ . '/../../public/views');
        $views = [];
        if ($viewDir && is_dir($viewDir)) 
        {
            $viewFiles = array_filter(scandir($viewDir), function($f) use ($viewDir) {
                return is_file("$viewDir/$f") && substr($f, -4) === '.php';
            });
            $views = array_map(function($f) {
                return basename($f, '.php');
            }, $viewFiles);
        }

    echo json_encode([
        'success' => true,
        'controllers' => array_values($controllers),
        'views' => array_values($views)
    ]);
    exit;
}

if ($step === 'report') {
    $errors = [];

    // 🔍 Scan PHP error log
    $logFile = __DIR__ . '/../../logs/php_error.log';
    if (file_exists($logFile)) {
        $lines = file($logFile);
        $errors = array_merge($errors, array_slice($lines, -20));
    }

    // 🔍 Scan logs DB if available
    $logDb = method_exists('Logger', 'lastErrors') ? Logger::lastErrors(20) : [];

    echo json_encode([
        'success' => true,
        'error_log' => $errors,
        'log_db' => $logDb
    ]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Unknown step']);
