<?php
if (!defined('sugarEntry')) { define('sugarEntry', true); }
include 'include/entryPoint.php';
require_once 'SticInclude/Portal/ConfigUtils.php';
echo 'get(PORTAL_HOME_URL)=' . SticPortalConfigUtils::get('PORTAL_HOME_URL', 'DEFAULT_NOT_SET') . PHP_EOL;
echo 'getAll count=' . count(SticPortalConfigUtils::getAll()) . PHP_EOL;
echo 'site_url=' . $GLOBALS['sugar_config']['site_url'] . PHP_EOL;
