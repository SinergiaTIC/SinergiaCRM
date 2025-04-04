<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
require('SticInclude/vendor/monolog-loki/src/main/php/Handler/LokiHandler.php');
use Itspire\MonologLoki\Handler\LokiHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use Monolog\Logger;


class SticRemoteLogLogicHooks
{
    public function server_round_trip($event, $arguments)
    {
        global $sugar_config;
        if (
            isset($sugar_config['stic_remote_monitor_enabled']) && $sugar_config['stic_remote_monitor_enabled']
            && isset($sugar_config['stic_remote_monitor_url']) && $sugar_config['stic_remote_monitor_url']
            && (!isset($sugar_config['stic_remote_monitor_duration_threshold']) 
                || (isset($sugar_config['stic_remote_monitor_duration_threshold']) && $sugar_config['stic_remote_monitor_duration_threshold'] > (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'])))
            && (!isset($sugar_config['stic_remote_monitor_memory_threshold']) 
                || (isset($sugar_config['stic_remote_monitor_memory_threshold']) && $sugar_config['stic_remote_monitor_memory_threshold'] > memory_get_peak_usage()))
            && (!isset($_REQUEST['module']) || (isset($_REQUEST['module']) 
                && $_REQUEST['module'] != 'Alerts' && $_REQUEST['module'] != 'stic_Time_Tracker') && $_REQUEST['module'] != 'Favorites')
        ) {
            sticShutdownHandler();
        }
    }
}


/**
 * Handles logging to SuiteCRM and Loki, even when no errors occur.
 */
function sticShutdownHandler() {
    global $current_user, $sugar_config;
    
    // Clean site URL (removing http/https)
    $instanceClean = preg_replace('/^https?:\/\//', '', $sugar_config['site_url'] ?? '');
    $hostname = $sugar_config['host_name'] ?? 'unknown';
    
    // Get last error, if any
    $error = error_get_last();
    $log_message = "Script executed successfully."; // Default log message

    // If there's an error, extract details
    if ($error !== null) {
        [$errno, $errstr, $errfile, $errline] = [$error['type'], $error['message'], $error['file'], $error['line']];
        $log_message = "[SuiteCRM Error $errno] $errstr in $errfile on line $errline";
        
        // Log to SuiteCRM built-in logger
        $GLOBALS['log']->fatal($log_message);
    }

    // Calculate execution time

    $logger = new Logger('loki-no-failure', [
        new WhatFailureGroupHandler([
            new LokiHandler([
                'entrypoint' => $sugar_config['stic_remote_monitor_url'],
                'context' => ['environment' => 'production'],
                'labels' => [
                    'app' => 'SinergiaCRM',
                    'instance' => $instanceClean,
                ],
                'client_name' => $instanceClean,
                'tenant_id' => 'some-tenant',
                'auth' => ['basic' => ['user', 'password']],
                'contextPrefix' => '',
                'curl_options' => [
                    CURLOPT_CONNECTTIMEOUT_MS => 500,
                    CURLOPT_TIMEOUT_MS => 600,
                ]
            ])
        ])
    ]);

    // Send execution data and error (if any) to Loki
    $logger->info($log_message, [
        'error_type' => $error['type'] ?? null,
        'error_message' => $error['message'] ?? null,
        'error_file' => $error['file'] ?? null,
        'error_line' => $error['line'] ?? null,
        'memory_usage' => memory_get_usage(),
        'memory_peak_usage' => memory_get_peak_usage(),
        'module' => $_REQUEST['module'] ?? 'N/A',
        'action' => $_REQUEST['action'] ?? 'N/A',
        'record' => $_REQUEST['record'] ?? 'N/A',
        'url_string' => $_SERVER['QUERY_STRING'],
        'request_uri' => $_SERVER['REQUEST_URI'],
        'user_id' => $current_user->id ?? 'unknown',
        'user_name' => $current_user->user_name ?? 'unknown',
        'php_session_id' => session_id(),
        'php_pid' => getmypid(),
        'start_time' => $_SERVER['REQUEST_TIME_FLOAT'],
        'duration' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
        'site_url' => $instanceClean,
        'host_name' => $hostname,
    ]);

}

// Register shutdown function to log execution and errors
// register_shutdown_function('suitecrmShutdownHandler');
