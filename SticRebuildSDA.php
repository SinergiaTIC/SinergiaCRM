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
/**
 * SticRebuildSDA.php
 *
 * CLI entry point to run a SinergiaDA rebuild in background (spawned by the Rebuild
 * button/URL in async mode), with no web/gateway timeouts. The status/debug output
 * goes to cache/sda_rebuild_status.json and cache/sda_rebuild_debug.html for the UI.
 *
 * Usage: php -f SticRebuildSDA.php -- --filter=all|tables|views [--debug]
 */

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

if (substr(php_sapi_name(), 0, 3) !== 'cli') {
    die("SticRebuildSDA.php is CLI only.\n");
}

chdir(__DIR__);

require_once 'include/entryPoint.php';

$args = array_slice(isset($argv) ? $argv : [], 1);
$filter = 'all';
foreach ($args as $arg) {
    if (strpos($arg, '--filter=') === 0) {
        $filter = substr($arg, 9);
    }
}
if (!in_array($filter, ['all', 'tables', 'views'], true)) {
    $filter = 'all';
}

global $current_user;
$current_user = BeanFactory::newBean('Users');
$current_user->getSystemUser();

if (in_array('--debug', $args, true)) {
    $_REQUEST['print_debug'] = 1;
}
$_REQUEST['debug'] = 1;
$_REQUEST['rebuild_filter'] = $filter;

require_once 'SticInclude/SinergiaDARebuild.php';

$res = SinergiaDARebuild::rebuild(true, $filter, ['capture' => true]);

$GLOBALS['log']->stic('Line ' . __LINE__ . ': ' . __METHOD__ . ': SticRebuildSDA finished with [' . $res . ']');
exit(0);
