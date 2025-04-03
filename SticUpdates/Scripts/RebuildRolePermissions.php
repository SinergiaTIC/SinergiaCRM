<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

echo '<h3>Repairing roles</h3>';
include('modules/ACL/install_actions.php');
$GLOBALS['log']->info(__FILE__ . '(' . __LINE__ . ') >> Repairing roles');