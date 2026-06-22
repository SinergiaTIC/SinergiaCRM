<?php
if (!defined('sugarEntry')) { define('sugarEntry', true); }
include 'include/entryPoint.php';
require_once 'SticInclude/Portal/ConfigUtils.php';
$all = SticPortalConfigUtils::getAll();
$raw = $all['PORTAL_APPS'] ?? 'MISSING';
echo 'RAW: [' . $raw . ']' . PHP_EOL;
echo 'HAS QUOT: ' . (strpos($raw, '&quot;') !== false ? 'yes' : 'no') . PHP_EOL;
$decoded = htmlspecialchars_decode($raw);
echo 'DECODED SAME: ' . ($decoded === $raw ? 'yes' : 'no') . PHP_EOL;
$arr = @unserialize($decoded, ['allowed_classes' => false]);
echo 'COUNT: ' . (is_array($arr) ? count($arr) : 'FAIL') . PHP_EOL;
