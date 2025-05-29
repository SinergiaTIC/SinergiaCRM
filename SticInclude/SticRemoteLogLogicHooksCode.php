<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class SticRemoteLogLogicHooks
{
    private $scriptDuration = -1;
    private $startLogTime = -1;

    public function server_round_trip($event, $arguments)
    {
        // Calculate execution time
        $this->startLogTime = microtime(true);
        $this->scriptDuration = $this->startLogTime - $_SERVER['REQUEST_TIME_FLOAT'];

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
            $this->sticShutdownHandler();
        }
    }

    /**
     * Handles logging to SuiteCRM and Loki, even when no errors occur.
     */
    protected function sticShutdownHandler() {
        global $current_user, $sugar_config;
        
        $site_url = $sugar_config['site_url'] ?? '';
        // Clean site URL (removing http/https)
        $instanceClean = preg_replace('/^https?:\/\//', '', $site_url);

        $hostname = $sugar_config['host_name'] ?? 'unknown';
        $full_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

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

        $messageType = $error ? $this->mapPhpErrorToMonologLevel($error['type']) : 'info';

        if (!class_exists(\Itspire\MonologLoki\Handler\LokiHandler::class)) {
            require_once 'vendor/autoload.php';
            require_once 'SticInclude/vendor/monolog-loki/src/main/php/Handler/LokiHandler.php';
            require_once 'SticInclude/vendor/monolog-loki/src/main/php/Formatter/LokiFormatter.php';
        }

        $logger = new \Monolog\Logger('loki-no-failure', [
            new \Monolog\Handler\WhatFailureGroupHandler([
                new \Itspire\MonologLoki\Handler\LokiHandler([
                    'entrypoint' => $sugar_config['stic_remote_monitor_url'],
                    'context' => [],
                    'labels' => [
                        'app' => 'SinergiaCRM',
                        'environment' => $sugar_config['stic_environment'] ?? 'production',
                        'host_name' => $hostname,
                        'error_type' => $error['type'] ?? null,
                        'detected_level' => $messageType,
                    ],
                    'curl_options' => [
                        CURLOPT_CONNECTTIMEOUT_MS => 500,
                        CURLOPT_TIMEOUT_MS => 600,
                    ]
                ])
            ])
        ]);

        // Send execution data and error (if any) to Loki
        $logger->$messageType($log_message, [
            '_module' => $_REQUEST['module'] ?? 'N/A',
            '_action' => $_REQUEST['action'] ?? 'N/A',
            '_record' => $_REQUEST['record'] ?? 'N/A',
            
            'error_message' => $error['message'] ?? null,
            'error_file_full' => $error['file'] ?? null,
            'error_file' => str_replace(dirname(__DIR__, 1). '/', '', $error['file'] ?? ''),
            'error_line' => $error['line'] ?? null,

            'memory_usage' => memory_get_usage(),
            'memory_peak_usage' => memory_get_peak_usage(),

            'url_string' => $_SERVER['QUERY_STRING'],
            'url_full' => $full_url,
            'url_site' => $site_url,
            'url_request_uri' => $_SERVER['REQUEST_URI'],

            'user_id' => $current_user->id ?? 'unknown',
            'user_name' => $current_user->user_name ?? 'unknown',

            'php_session_id' => session_id(),
            'php_pid' => getmypid(),

            'time_start' => $_SERVER['REQUEST_TIME_FLOAT'],
            'time_duration_script' => round($this->scriptDuration, 4),
            'time_duration_log' => round(microtime(true) - $this->startLogTime, 4),
            'time_sent_log' => microtime(true),
        ]);

    }

    private function mapPhpErrorToMonologLevel(int $errno) {
        switch ($errno) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_PARSE:
            case E_RECOVERABLE_ERROR:
                return 'critical';

            case E_USER_ERROR:
                return 'error';

            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
                return 'warning';

            case E_NOTICE:
            case E_USER_NOTICE:
                return 'notice';

            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                return 'info';

            default:
                return 'error'; // fallback
        }
    }
}


// Register shutdown function to log execution and errors
// register_shutdown_function('suitecrmShutdownHandler');