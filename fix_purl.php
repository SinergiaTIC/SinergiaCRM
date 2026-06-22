<?php
if (!defined('sugarEntry')) { define('sugarEntry', true); }
include 'include/entryPoint.php';
require_once 'SticInclude/Portal/ConfigUtils.php';
$current = SticPortalConfigUtils::get('PORTAL_HOME_URL', '');
$fixed = rtrim($current, '/') . '/';
SticPortalConfigUtils::set('PORTAL_HOME_URL', $fixed);
echo 'Old: ' . $current . PHP_EOL;
echo 'New: ' . $fixed . PHP_EOL;
