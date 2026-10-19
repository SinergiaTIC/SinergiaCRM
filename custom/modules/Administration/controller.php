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

require_once 'modules/Administration/controller.php';
class CustomAdministrationController extends AdministrationController
{

    /**
     * Populate the database with example records. All records are assigned and created by the fake user
     * "SinergiaCRM-TEST", which is also created with a special id (9) that will be used
     * in case of later deletion.
     *
     * @return void
     */
    public function action_insertSticData()
    {

        global $mod_strings;

        $db = DBManagerFactory::getInstance();

        // Load from an external file the queries to run.
        $sqlPopulate = file_get_contents('SticInclude/data/InsertSticData.sql');

        // As DBManagerFactory does not allow more than one SQL sentence in the same query,
        // will execute them in a loop.
        $sqlPopulate = explode('REPLACE INTO', $sqlPopulate);

        $dbErrors = '';

        foreach ($sqlPopulate as $key => $value) {
            if (empty(trim($value))) {
                continue;
            }
            $sql = 'REPLACE INTO ' . $value;
            $db->query($sql);
            $dbErrors .= $db->last_error;
        }

        if (empty($dbErrors)) {
            SugarApplication::appendErrorMessage('<div class="alert alert-success">' . $mod_strings['LBL_STIC_TEST_DATA_INSERT_SUCCESS'] . '</div>');
        } else {
            SugarApplication::appendErrorMessage('<div class="alert alert-danger">' . $mod_strings['LBL_STIC_TEST_DATA_INSERT_ERROR'] . '</div>');
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error populating data: ' . $dbErrors);

        }
        SugarApplication::redirect('index.php?module=Administration&action=SticManageTestData');
    }

    /**
     * Remove test records (those created by user with id 9) and the fake user.
     *
     * @return void
     */
    public function action_removeSticData($showMessage = true)
    {
        global $mod_strings;
        $removeId = '9';
        $db = DBManagerFactory::getInstance();

        // Build an array with the database tables to be cleaned
        $tableListResult = $db->query("SELECT table_name FROM information_schema.COLUMNS where table_schema = database() and COLUMN_NAME = 'created_by';");

        $dbErrors = '';

        while ($row = $db->fetchByAssoc($tableListResult)) {
            $table = $row['table_name'];

            // 1) Remove main table records
            $db->query("DELETE FROM {$table} WHERE created_by='{$removeId}';");
            $dbErrors .= $db->last_error;

            // 2) Remove orphan record in _cstm table, if exists
            $cstmTableExists = $db->getOne("SELECT count(*) FROM information_schema.TABLES where table_schema=database() and TABLE_NAME='{$table}_cstm'");
            if ($cstmTableExists == 1) {
                $db->query("DELETE FROM `{$table}_cstm` WHERE id_c NOT IN (SELECT id FROM {$table});");
                $dbErrors .= $db->last_error;
            }
        }

        // Delete user with $removeId
        $db->query("DELETE FROM users WHERE id = '{$removeId}';");
        $dbErrors .= $db->last_error;

        if ($showMessage == true) {
            if (empty($dbErrors)) {
                SugarApplication::appendErrorMessage('<div class="alert alert-success">' . $mod_strings['LBL_STIC_TEST_DATA_REMOVE_SUCCESS'] . '</div>');
            } else {
                SugarApplication::appendErrorMessage('<div class="alert alert-danger">' . $mod_strings['LBL_STIC_TEST_DATA_REMOVE_ERROR'] . '</div>');
                $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ': Error removing test data: ' . $dbErrors);

            }
            SugarApplication::redirect('index.php?module=Administration&action=SticManageTestData');
        }
    }

    public function action_createReportingMySQLViews()
    {
        global $sugar_config;
        $sdaEnabled = $sugar_config['stic_sinergiada']['enabled'] ?? false;

        if (empty($sdaEnabled) || !$sdaEnabled) {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "SinergiaDA is disabled");
            die("Sinergia Data Analytics is disabled.");
        }

        global $current_user, $mod_strings;
        if (!is_admin($current_user)) {
            die('<h1>Operación restringida a administradores</h1>');
        }

        require_once 'SticInclude/SinergiaDARebuild.php';

        // -----------------------------------------------------------------
        // Polling endpoints used by the Rebuild button (debug panel) and by
        // the waiting page shown when the rebuild runs in background.
        // -----------------------------------------------------------------
        if (!empty($_REQUEST['check_status'])) {
            $this->sticRespondJson(SdaRebuildStatus::statusForClient());
            exit();
        }

        if (!empty($_REQUEST['fetch_debug'])) {
            header('Content-Type: text/html; charset=utf-8');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            echo SdaRebuildStatus::readDebug();
            exit();
        }

        $filter = $_REQUEST['rebuild_filter'] ?? 'all';
        if (!in_array($filter, ['all', 'tables', 'views'], true)) {
            $filter = 'all';
        }
        $isDebug = !empty($_REQUEST['print_debug']);

        // -----------------------------------------------------------------
        // Async launch: answer with JSON immediately and run the rebuild in
        // background (CLI subprocess when possible, otherwise this worker after
        // closing the HTTP response), so no gateway/proxy timeout can abort it.
        // -----------------------------------------------------------------
        if (!empty($_REQUEST['async'])) {
            $this->sticLaunchAsyncRebuild($isDebug, $filter);
            return;
        }

        // -----------------------------------------------------------------
        // Synchronous execution (legacy manual URL behaviour)
        // -----------------------------------------------------------------
        require_once 'SticInclude/SinergiaDARebuild.php';
        $res = SinergiaDARebuild::rebuild(true, $filter);

        if ($res === 'busy') {
            $alert = '<div class="container alert alert-warning"> <div class="col-md-1"><span style="font-size:xx-large" class="col-md-1 glyphicon glyphicon-time center"></span></div> <strong>' . ($mod_strings['LBL_STIC_RUN_SDA_BUSY_MSG'] ?? 'Busy') . '</strong></div>';
        } elseif ($res != 'ok') {
            $alert = '<div class="container alert alert-danger"> <div class="col-md-1"><span style="font-size:xx-large" class="col-md-1 glyphicon glyphicon-minus-sign center"></span></div> <strong>' . $mod_strings['LBL_STIC_RUN_SDA_ERROR_MSG'] . ':</strong><p>' . $res . '</p></div>';
        } else {
            $alert = '<div class="container alert alert-success"> <div class="col-md-1"><span style="font-size:xx-large" class="col-md-1 glyphicon glyphicon-check center"></span></div><div class="col-md-11"><strong>' . $mod_strings['LBL_STIC_RUN_SDA_SUCCESS_MSG'] . '</strong></div></div>';
        }

        // Debug panel mode (Rebuild button with "Enable debug mode" checked): the debug
        // output has already been echoed by createViews() when print_debug=1, so the
        // result must be appended inline and returned as-is. Redirecting here would
        // emit a SUGAR.ajaxUI.loadContent() script that replaces the page content and
        // wipes the debug panel, and the $(window).on('load') message would never run.
        if (!empty($_REQUEST['ajax_debug'])) {
            echo $alert;
            exit();
        }

        $tx = '<script>$(window).on(\'load\', function() {
                    $(\'#rebuild-feedback\').html(' . (json_encode($alert) ?: '""') . ');
                });</script>';

        SugarApplication::appendSuccessMessage($tx);
        SugarApplication::redirect("index.php?module=Administration&action=sticmanagesdaintegration");

        die();
    }

    /**
     * Launches a rebuild in background and answers with JSON immediately:
     *  1. CLI subprocess (SticRebuildSDA.php) - same conditions as the nightly cron
     *  2. this FPM worker, after closing the HTTP response (fastcgi_finish_request)
     *
     * @param bool   $isDebug whether to pass --debug (capture the debug report)
     * @param string $filter  all|tables|views
     * @return void
     */
    private function sticLaunchAsyncRebuild($isDebug, $filter)
    {
        if (SdaRebuildStatus::isBusy()) {
            $this->sticRespondJson(['state' => 'busy']);
            exit();
        }

        // Cover the gap between the launch and the moment the runner takes the lock
        SdaRebuildStatus::markRunning();

        $phpCli = SdaRebuildStatus::findPhpCli();
        if ($phpCli !== null && (function_exists('exec') || function_exists('popen') || function_exists('shell_exec'))) {
            if ($this->sticSpawnCliRebuild($phpCli, $isDebug, $filter)) {
                $this->sticRespondJson(['state' => 'running']);
                exit();
            }
            if ((SdaRebuildStatus::read()['state'] ?? '') === 'finished') {
                // Rebuild finished while still launching (very small instance)
                $this->sticRespondJson(SdaRebuildStatus::statusForClient());
                exit();
            }
        }

        // Fallback: run inside this worker, but only after answering the client,
        // so no gateway/proxy timeout can abort the rebuild
        set_time_limit(0);
        ignore_user_abort(true);
        $this->sticRespondJson(['state' => 'running']);
        while (ob_get_level() > 0) {
            @ob_end_flush();
        }
        @flush();
        if (function_exists('session_write_close')) {
            @session_write_close();
        }
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        require_once 'SticInclude/SinergiaDARebuild.php';
        SinergiaDARebuild::rebuild(true, $filter, ['capture' => true]);
        exit();
    }

    /**
     * Spawns SticRebuildSDA.php in background and verifies it booted.
     *
     * @param string $phpCli  path to a PHP CLI binary
     * @param bool   $isDebug whether to pass --debug (capture the debug report)
     * @param string $filter  all|tables|views
     * @return bool false when the process died before starting (caller falls back to FPM)
     */
    private function sticSpawnCliRebuild($phpCli, $isDebug, $filter)
    {
        $cmd = escapeshellarg($phpCli) . ' -f ' . escapeshellarg(SdaRebuildStatus::root() . '/SticRebuildSDA.php')
            . ' --' // PHP eats script args starting with "-" unless separated
            . ' --filter=' . escapeshellarg($filter)
            . ($isDebug ? ' --debug' : '')
            . ' >> ' . escapeshellarg(SdaRebuildStatus::root() . '/cache/sda_rebuild_cli.log') . ' 2>&1';

        $pid = 0;
        if (function_exists('exec')) {
            $out = [];
            $rc = 0;
            exec($cmd . ' & echo $!', $out, $rc);
            if ($rc === 0 && !empty($out)) {
                $pid = (int) end($out);
            }
        } elseif (function_exists('popen')) {
            $handle = popen($cmd . ' &', 'r');
            if (!is_resource($handle)) {
                return true;
            }
            pclose($handle);
        } else {
            shell_exec($cmd . ' &');
        }

        if ($pid <= 0) {
            // No pid available: trust the launch and let the polling detect problems
            return true;
        }
        // Give the process a moment to boot; if it dies immediately (bad binary,
        // fatal on startup...) the caller falls back to running inside this worker
        sleep(1);
        if (is_dir('/proc') && !file_exists('/proc/' . $pid)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': CLI rebuild process ' . $pid . ' died before starting, falling back to FPM worker');
            return false;
        }
        return true;
    }

    /**
     * Emits a JSON response (headers only; the caller exits or continues in background).
     *
     * @param array $payload
     * @return void
     */
    private function sticRespondJson(array $payload)
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        echo json_encode($payload, JSON_INVALID_UTF8_SUBSTITUTE | JSON_UNESCAPED_UNICODE);
    }


    public function action_sticSaveSdaConfig()
    {
        global $current_user, $mod_strings, $sugar_config;

        if (!is_admin($current_user)) {
            sugar_die("Unauthorized access to administration.");
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            SugarApplication::redirect("index.php?module=Administration&action=sticmanagesdaintegration");
            return;
        }

        require_once 'modules/Configurator/Configurator.php';
        require_once 'include/utils/array_utils.php';
        $configurator = new Configurator();

        $oldConfig = $sugar_config['stic_sinergiada'] ?? [];
        $oldPublicUrl = $sugar_config['stic_sinergiada_public']['url'] ?? '';

        // General settings
        $configurator->config['stic_sinergiada']['enabled'] = !empty($_POST['enabled']);
        $configurator->config['stic_sinergiada']['group_permissions_enabled'] = !empty($_POST['group_permissions_enabled']);
        $configurator->config['stic_sinergiada']['auto_rebuild_on_studio_events'] = !empty($_POST['auto_rebuild_on_studio_events']);
        $configurator->config['stic_sinergiada']['max_users_processed'] = ($_POST['max_users_processed'] ?? '') !== '' ? (int) $_POST['max_users_processed'] : '';

        // publish_as_table: handle modules selected via multi-select.
        // Always store an array (empty array when nothing is selected), so the value is
        // consistent with how SinergiaDA consumes it.
        $publish = $_POST['publish_as_table'] ?? [];
        if (!is_array($publish)) {
            $publish = [];
        }
        $configurator->config['stic_sinergiada']['publish_as_table'] = array_values($publish);

        // Cache settings (known fields)
        $knownCacheKeys = ['cache_enabled', 'cache_units', 'cache_quantity', 'cache_hours', 'cache_minutes'];
        $cacheUnits = $_POST['cache_units'] ?? '';
        $configurator->config['stic_sinergiada']['config']['cache_enabled'] = !empty($_POST['cache_enabled']);
        $configurator->config['stic_sinergiada']['config']['cache_units'] = in_array($cacheUnits, ['days', 'hours'], true) ? $cacheUnits : 'days';
        $configurator->config['stic_sinergiada']['config']['cache_quantity'] = ($_POST['cache_quantity'] ?? '') !== '' ? (int) $_POST['cache_quantity'] : '';
        $configurator->config['stic_sinergiada']['config']['cache_hours'] = str_pad((string) max(0, min(23, (int) ($_POST['cache_hours'] ?? 0))), 2, '0', STR_PAD_LEFT);
        $configurator->config['stic_sinergiada']['config']['cache_minutes'] = str_pad((string) max(0, min(59, (int) ($_POST['cache_minutes'] ?? 0))), 2, '0', STR_PAD_LEFT);

        // Extra / unknown config keys (from "Otras configuraciones" section)
        $extraConfig = $_POST['extra_config'] ?? [];
        if (is_array($extraConfig)) {
            foreach ($extraConfig as $ekey => $evalue) {
                if (!in_array($ekey, $knownCacheKeys)) {
                    $configurator->config['stic_sinergiada']['config'][$ekey] = $evalue;
                }
            }
        }

        // Public URL
        // Only persist the URL when it has a value. If left empty, the key is removed so that
        // no empty line is written to config_override.php and a URL defined elsewhere (config.php,
        // manual override, etc.) is not silently overwritten.
        $publicUrl = trim($_POST['public_url'] ?? '');
        if ($publicUrl !== '') {
            $configurator->config['stic_sinergiada_public']['url'] = $publicUrl;
        } else {
            unset($configurator->config['stic_sinergiada_public']['url']);
        }

        // Log changes
        $userId = $current_user->id;
        $changes = [];
        $newConfig = $configurator->config['stic_sinergiada'];
        foreach ($newConfig as $key => $newVal) {
            $oldVal = $oldConfig[$key] ?? '';
            if (json_encode($oldVal) !== json_encode($newVal)) {
                $changes[] = "$key: " . json_encode($oldVal) . " -> " . json_encode($newVal);
            }
        }
        $newPublicUrl = $configurator->config['stic_sinergiada_public']['url'] ?? '';
        if ($oldPublicUrl !== $newPublicUrl) {
            $changes[] = "public_url: $oldPublicUrl -> $newPublicUrl";
        }

        try {
            // Read existing override, then only update our specific keys
            $overrideArray = $configurator->readOverride();
            $sdaConfig = $configurator->config['stic_sinergiada'];
            $sdaPublicConfig = $configurator->config['stic_sinergiada_public'] ?? [];
            $overrideArray['stic_sinergiada'] = $sdaConfig;
            $overrideArray['stic_sinergiada_public'] = $sdaPublicConfig;
            $overrideString = "<?php\n/***CONFIGURATOR***/\n";
            foreach ($overrideArray as $key => $val) {
                $overrideString .= override_value_to_string_recursive2('sugar_config', $key, $val);
            }
            $overrideString .= '/***CONFIGURATOR***/';
            $configurator->saveOverride($overrideString);

            if (!empty($changes)) {
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "User $userId changed SinergiaDA config: " . implode('; ', $changes));
            } else {
                $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "User $userId saved SinergiaDA config (no changes detected)");
            }
            SugarApplication::appendErrorMessage('<div class="alert alert-success">' . $mod_strings['LBL_STIC_SINERGIADA_CONFIG_SAVE_SUCCESS'] . '</div>');
        } catch (Exception $e) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "User $userId error saving SinergiaDA config: " . $e->getMessage());
            SugarApplication::appendErrorMessage('<div class="alert alert-danger">' . $mod_strings['LBL_STIC_SINERGIADA_CONFIG_SAVE_ERROR'] . '</div>');
        }

        SugarApplication::redirect("index.php?module=Administration&action=sticmanagesdaintegration");
    }

    public function action_configureMainMenu(){
        // Add specific logic for manage main menu
        require_once('custom/modules/Administration/SticAdvancedMenu/SticAdvancedMenuEdit.php');

    } 

}
