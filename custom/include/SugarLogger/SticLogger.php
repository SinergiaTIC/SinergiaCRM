<?php
/**
 * This file adds a custom STIC Logger that extends directly from SugarLogger. 
 * Only necessary variables/functions are overriden.
 * This logger will write messages in a custom file (sinergiacrm.log) with a custom level (stic) 
 * and same SugarLogger rolling functionality.
 */

require_once('include/SugarLogger/LoggerManager.php');
require_once('include/SugarLogger/SugarLogger.php');

class SticLogger extends SugarLogger
{
    // Setting configuration for SticLogger
    protected $logfile = 'sinergiacrm';
    protected $ext = '.log';
    protected $dateFormat = '%F %T';
    protected $logSize = '1MB';
    protected $maxLogs = 10;
    protected $filesuffix = "";
    protected $date_suffix = "";
    protected $log_dir = '.';
    protected $defaultPerms = 0664;

    public function __construct()
    {
        // Initializing the Logger, same as in SugarLogger __construct
        $this->log_dir = './';
        $this->_doInitialization();
        // Setting a new log level
        LoggerManager::setLevelMapping('stic', 12);
        // Setting SticLogger class as the default logger for the "stic" level
        LoggerManager::setLogger('stic', 'SticLogger');
    }
}
