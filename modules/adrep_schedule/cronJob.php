<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
$startTime = microtime(true);
ini_set('display_errors','On');
ini_set('error_reporting','E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE & ~E_WARNING & ~E_NOTICE');
set_time_limit(0);

require_once('include/entryPoint.php');

require_once "modules/adrep_schedule/adrep_schedule.php";

$focus = new adrep_schedule();
$cnt = $focus->run_reports();

echo "$cnt reports sent!\n";
