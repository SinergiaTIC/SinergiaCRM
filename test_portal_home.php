<?php
if (!defined('sugarEntry')) { define('sugarEntry', true); }
include 'include/entryPoint.php';
require_once 'SticInclude/Portal/ConfigUtils.php';
$url = SticPortalConfigUtils::get('PORTAL_HOME_URL', 'DEFAULT_NOT_SET');
echo 'PORTAL_HOME_URL=[' . $url . ']' . PHP_EOL;
$site = $GLOBALS['sugar_config']['site_url'] ?? 'NO_SITE_URL';
echo 'site_url=[' . $site . ']' . PHP_EOL;
