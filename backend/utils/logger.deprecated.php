<?php
// =============================================================================
// File: backend/utils/logger.php
// Description: Logs error entries to the logs table using LoggerModel.
//              Falls back to /logs/error.log if DB logging fails.
// =============================================================================

error_log("***** yes we are here I");
trigger_error("***** yes we are here I");

function log_error_to_db($level, $message, $file = null, $line = null, $context = null)
{
    try 
    {
        error_log("***** yes we are here II");
        trigger_error("***** yes we are here II");


        $logger = new LoggerModel();
        $logger->insert($level, $message, $file, $line, $context);
    } 
    catch (Throwable $e) 
    {
        $fallback = [
            'level'     => $level,
            'message'   => $message,
            'file'      => $file,
            'line'      => $line,
            'context'   => $context,
            'timestamp' => date('Y-m-d H:i:s'),
            'error'     => $e->getMessage()
        ];

        $logPath = BASEPATH . '/logs/error.log';
        $logEntry = json_encode($fallback, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;

        file_put_contents($logPath, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
