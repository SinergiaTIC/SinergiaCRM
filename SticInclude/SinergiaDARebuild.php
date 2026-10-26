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
 * Class SinergiaDARebuild
 *
 * This class is used to rebuild the Sinergia Data Analytics system.
 */
#[\AllowDynamicProperties]
class SinergiaDARebuild
{

    /**
     * Function rebuild
     *
     * Performs a rebuild operation if user is an administrator and SinnergiaDA is enabled.
     * Returns 'ok' on success, error messages on failure, 'busy' when another rebuild
     * already holds the lock, or a failure message for non-admin users.
     *
     * A flock (cache/sda_rebuild.lock) serializes all rebuild entry points (button/URL,
     * nightly cron, REST API, Quick Repair) so they never overlap, and the status file
     * (cache/sda_rebuild_status.json) is kept up to date for the polling UI.
     *
     * @param bool $callUpdateModel Whether to update the model.
     * @param string $rebuildFilter all|tables|views
     * @param array $context Optional, only 'capture': store the report returned by
     *     createViews() in cache/sda_rebuild_debug.html instead of sending it to the
     *     (already disconnected) client. Used by background runners.
     * @return string The result of the rebuild operation.
     */
    public static function rebuild($callUpdateModel = false, $rebuildFilter = 'all', $context = [])
    {
        global $current_user;

        if ($callUpdateModel) {
            $_REQUEST['update_model'] = 1;
        }

        // If it's a call that has been received via the API, the user is not validated through
        // username and password, so we assign the system user to execute the 'rebuild' method of SinergiaDA.
        if (!is_admin($current_user)) {
            $current_user->getSystemUser();
        }

        require_once 'modules/stic_Settings/Utils.php';
        
        // Check if SinergiaDA is enabledfem
        global $sugar_config;
        $sdaEnabled = $sugar_config['stic_sinergiada']['enabled'] ?? false;
        if (empty($sdaEnabled) || !$sdaEnabled) {
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "SinergiaDA setting is not = 1.");
            die("Sinergia Data Analytics is disabled.");
            return;
        }

        // Only proceed if the current user is an admin
        if (is_admin($current_user)) {
            // Only one rebuild at a time: if another process (button/URL, cron, REST...)
            // is already running one, report busy instead of overlapping.
            $lock = SdaRebuildStatus::acquireLock();
            if ($lock === null) {
                $GLOBALS['log']->stic('Line ' . __LINE__ . ': ' . __METHOD__ . ': rebuild skipped, another rebuild is already running');
                return 'busy';
            }

            try {
                SdaRebuildStatus::markRunning();

                require_once 'SticInclude/SinergiaDA.php';

                // Set the force request parameter
                $_REQUEST['force'] = ($_REQUEST['force'] ?? null) == 1 ? true : false;

                // Create views via the ExternalReporting class. In background mode the
                // print_debug flag must be off: createViews() ends with echo + die()
                // when it is set (legacy sync debug flow), and die() would skip the
                // finally/markFinished below, leaving the status stuck as "running".
                $capture = !empty($context['capture']);
                if ($capture) {
                    unset($_REQUEST['print_debug']);
                }
                $r = new ExternalReporting();
                $res = $r->createViews($callUpdateModel, $rebuildFilter);
                if ($capture) {
                    // createViews() returns the very report it would echo; store it for
                    // the polling UI (also clears a stale one)
                    SdaRebuildStatus::writeDebug($res);
                }

                // Pattern to match fatal errors
                $pattern = "/(\[FATAL:\s*.*?\])/i";

                preg_match_all($pattern, $res, $matches);
                $msg = '';

                // Build error message from fatal error matches
                foreach ($matches[1] as $match) {
                    $msg .= $match . "<br>";
                }

                if(file_exists('sdaRebuildError.txt'))
                {
                    unlink('sdaRebuildError.txt');
                }
                
                // Return 'ok' or the error message
                if (empty($msg)) {
                    SdaRebuildStatus::markFinished('ok');
                    return 'ok';
                } else {
                    sugar_file_put_contents('sdaRebuildError.txt', $msg);
                    SdaRebuildStatus::markFinished('error', $msg);
                    return $msg;
                }
            } finally {
                SdaRebuildStatus::releaseLock();
            }
        } else {
            // Non-admin users are not allowed to perform the operation
            die('<h1>Operación restringida a administradores</h1>');
        }

        return false;
    }

    /**
     * Calls the API to rebuild SinergiaDA.
     *
     * @param bool $callUpdateModel Indicates whether to update the model.
     * @return void
     */
    public static function callApiRebuildSDA($callUpdateModel = false, $rebuildFilter = 'all')
    {
        global $sugar_config;
        $GLOBALS['log']->stic('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Rebuilding SinergiaDA via API");

        $db_user_name = $sugar_config['dbconfig']['db_user_name'];

        // Obtain the valid token
        $validToken = md5($db_user_name . date("Ymd"));

        // API endpoint URL
        $url = "{$sugar_config['site_url']}/custom/service/v4_1_SticCustom/rest.php";

        // Data to send in the request
        $data = array(
            'debug' => '1',
            'update_model' => $callUpdateModel ? 1 : 0,
            'rebuild_filter' => $rebuildFilter,
            'method' => 'rebuild_sda',
            'input_type' => 'JSON',
            'response_type' => 'JSON',
            'token' => $validToken,
        );

        // Create a curl instance
        $ch = curl_init();

        // Set the URL and other necessary options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true); // Perform the request in the background
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1); // Ignore signals, allow the request to continue even if the user cancels

        // Execute the request and get the response
        $res = curl_exec($ch);

        // Check if any error occurred
        if (curl_errno($ch)) {
            $GLOBALS['log']->stic('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Rebuilding SinergiaDA via API. ERROR " . curl_error($ch));
        } else{
            $GLOBALS['log']->stic('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Rebuilding SinergiaDA via API. OK " );

        }

        // Close the curl instance
        curl_close($ch);

        $GLOBALS['log']->stic('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Rebuilding SinergiaDA via API. END");
    }

}

/**
 * Class SdaRebuildStatus
 *
 * Minimal status/lock/debug helpers so a SinergiaDA rebuild can run in background
 * (CLI subprocess or same FPM worker) and be observed by the UI via polling.
 * Files under cache/: sda_rebuild_status.json, sda_rebuild.lock, sda_rebuild_debug.html.
 * The lock (flock, released by the OS when the holder dies) doubles as the
 * "is the executor alive?" probe.
 */
#[\AllowDynamicProperties]
class SdaRebuildStatus
{
    /** Seconds a "running" status is trusted without lock (spawn window before the runner takes it). */
    const PENDING_GRACE = 30;

    /** @var resource|null flock handle held by this process (if any) */
    protected static $lockHandle = null;

    /**
     * @return string absolute path to the project root (this file lives in SticInclude/)
     */
    public static function root()
    {
        return dirname(__DIR__);
    }

    /**
     * @return array
     */
    public static function read()
    {
        $file = self::root() . '/cache/sda_rebuild_status.json';
        if (!is_file($file)) {
            return [];
        }
        $raw = @file_get_contents($file);
        $data = $raw ? json_decode($raw, true) : null;
        return is_array($data) ? $data : [];
    }

    /**
     * Atomic write (tmp + rename) so pollers never read a half-written JSON.
     *
     * @param array $data
     * @return bool
     */
    public static function write(array $data)
    {
        $file = self::root() . '/cache/sda_rebuild_status.json';
        $json = json_encode($data, JSON_INVALID_UTF8_SUBSTITUTE | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            return false;
        }
        $tmp = $file . '.' . getmypid() . '.tmp';
        if (@file_put_contents($tmp, $json) === false) {
            return false;
        }
        if (!@rename($tmp, $file)) {
            @unlink($tmp);
            return false;
        }
        return true;
    }

    /**
     * Marks the status as running. Called by the launcher before responding to the
     * client (covers the gap until the runner takes the lock) and again by rebuild()
     * once the lock has been acquired.
     *
     * @return void
     */
    public static function markRunning()
    {
        self::write([
            'state' => 'running',
            'started_at' => time(),
            'finished_at' => null,
            'result' => null,
            'message' => null,
        ]);
    }

    /**
     * @param string $result  ok|error
     * @param string $message error details (may contain HTML)
     * @return void
     */
    public static function markFinished($result, $message = '')
    {
        $prev = self::read();
        self::write([
            'state' => 'finished',
            'started_at' => $prev['started_at'] ?? time(),
            'finished_at' => time(),
            'result' => $result,
            'message' => (string) $message,
        ]);
    }

    /**
     * Tries to acquire the rebuild lock without blocking.
     *
     * @return resource|null the lock handle, or null when another process holds it
     */
    public static function acquireLock()
    {
        if (self::$lockHandle !== null) {
            return self::$lockHandle;
        }
        $fp = @fopen(self::root() . '/cache/sda_rebuild.lock', 'c');
        if ($fp === false) {
            return null;
        }
        if (!flock($fp, LOCK_EX | LOCK_NB)) {
            fclose($fp);
            return null;
        }
        self::$lockHandle = $fp;
        return $fp;
    }

    /**
     * Releases the lock held by this process (if any).
     *
     * @return void
     */
    public static function releaseLock()
    {
        if (self::$lockHandle !== null) {
            @flock(self::$lockHandle, LOCK_UN);
            @fclose(self::$lockHandle);
            self::$lockHandle = null;
        }
    }

    /**
     * Whether some process (this one or another) currently holds the rebuild lock.
     *
     * @return bool
     */
    public static function isLockHeld()
    {
        if (self::$lockHandle !== null) {
            return true;
        }
        $fp = @fopen(self::root() . '/cache/sda_rebuild.lock', 'c');
        if ($fp === false) {
            return false;
        }
        $acquired = flock($fp, LOCK_EX | LOCK_NB);
        if ($acquired) {
            flock($fp, LOCK_UN);
        }
        fclose($fp);
        return !$acquired;
    }

    /**
     * Effective status for the UI: "running" while the lock is held (e.g. nightly
     * cron or REST rebuilds not tracked by the status file), "idle" when there is
     * no status, "stalled" when a "running" status outlived its grace period
     * without a lock (the runner died), and the stored status otherwise.
     *
     * @return array
     */
    public static function statusForClient()
    {
        if (self::isLockHeld()) {
            $data = self::read();
            if (($data['state'] ?? '') !== 'running') {
                $data = ['state' => 'running', 'started_at' => time(), 'finished_at' => null, 'result' => null, 'message' => null];
            }
        } else {
            $data = self::read();
            if (empty($data)) {
                return ['state' => 'idle'];
            }
            if (($data['state'] ?? '') === 'running' && time() - (int) ($data['started_at'] ?? 0) >= self::PENDING_GRACE) {
                $data['state'] = 'stalled';
                $data['message'] = 'stalled';
                self::write($data);
            }
        }
        if (isset($data['message']) && is_string($data['message']) && strlen($data['message']) > 60000) {
            $data['message'] = substr($data['message'], 0, 60000);
        }
        return $data;
    }

    /**
     * Whether a rebuild should be considered already running.
     *
     * @return bool
     */
    public static function isBusy()
    {
        return (self::statusForClient()['state'] ?? '') === 'running';
    }

    /**
     * Stores the debug HTML produced during a background rebuild.
     *
     * @param string $html
     * @return void
     */
    public static function writeDebug($html)
    {
        @file_put_contents(self::root() . '/cache/sda_rebuild_debug.html', (string) $html);
    }

    /**
     * @return string
     */
    public static function readDebug()
    {
        $file = self::root() . '/cache/sda_rebuild_debug.html';
        if (!is_file($file)) {
            return '';
        }
        $html = @file_get_contents($file);
        return $html === false ? '' : $html;
    }

    /**
     * Locates a PHP CLI binary usable to spawn background rebuilds (under FPM,
     * PHP_BINARY points to php-fpm, which cannot run scripts with -f).
     *
     * @return string|null
     */
    public static function findPhpCli()
    {
        $candidates = [];
        if (defined('PHP_BINDIR') && PHP_BINDIR !== '') {
            $candidates[] = PHP_BINDIR . '/php';
        }
        $candidates[] = '/usr/bin/php';
        $candidates[] = '/usr/local/bin/php';
        foreach ($candidates as $candidate) {
            if (is_executable($candidate)) {
                return $candidate;
            }
        }
        return null;
    }
}
