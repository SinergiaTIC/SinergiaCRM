<?php
if (!defined('sugarEntry')) { define('sugarEntry', true); }
include 'include/entryPoint.php';
require_once 'SticInclude/Portal/ConfigUtils.php';
echo 'PORTAL_NOTIFY_NEW_LOGIN=' . SticPortalConfigUtils::get('PORTAL_NOTIFY_NEW_LOGIN', 'default') . PHP_EOL;
echo 'PORTAL_NOTIFY_ACCOUNT_LOCKED=' . SticPortalConfigUtils::get('PORTAL_NOTIFY_ACCOUNT_LOCKED', 'default') . PHP_EOL;
echo 'PORTAL_NOTIFY_RESET_REQUESTED=' . SticPortalConfigUtils::get('PORTAL_NOTIFY_RESET_REQUESTED', 'default') . PHP_EOL;
echo 'From address=' . (['sugar_config']['notify_fromaddress'] ?? 'NOT SET') . PHP_EOL;
echo 'mailer=' . (['sugar_config']['mailer'] ?? 'NOT SET') . PHP_EOL;
