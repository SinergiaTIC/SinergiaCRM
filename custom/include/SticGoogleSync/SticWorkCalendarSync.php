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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/GoogleSync/GoogleSyncExceptions.php';

use SuiteCRM\Utility\SuiteValidator;

/**
 * One-way push of stic_Work_Calendar records to the user's Google Calendar primary.
 *
 * Scope (types): 'holiday', 'vacation' (all-day native) and 'working' (timed).
 * Matching: stic_gsync_id / stic_gsync_lastsync columns (Vardefs ext).
 * Window: -30 days / +3 months (no backfill). Incremental via date_modified.
 *
 * @license https://raw.githubusercontent.com/salesagility/SuiteCRM/master/LICENSE.txt
 * GNU Affero General Public License version 3
 */
#[\AllowDynamicProperties]
class SticWorkCalendarSync
{
    /** @var string Google Calendar ID for each user (primary calendar) */
    protected $calendarId = 'primary';

    /** @var array Auth JSON config (decoded from $sugar_config['google_auth_json']) */
    protected $authJson = array();

    /** @var object Logger instance */
    protected $logger;

    /** @var object Database instance */
    protected $db;

    /** @var User current working user bean */
    protected $workingUser;

    /** @var \Google\Client current Google client */
    protected $gClient;

    /** @var \Google\Service\Calendar current Calendar service */
    protected $gService;

    /** @var string User timezone (from preference, default UTC) */
    protected $timezone;

    /** @var string Preference category where the OAuth tokens live */
    protected const PREF_CATEGORY = 'GoogleSync';

    /** @var array Syncable types (only these are published) */
    protected const SYNC_TYPES = array('holiday', 'vacation', 'working');

    /** @var array All-day types within the module (personal/sick/leave are NOT pushed) */
    protected const ALL_DAY_SYNC_TYPES = array('holiday', 'vacation');

    /** @var string Extended property keys used for matching (same as legacy engine) */
    protected const EXT_SUITECRM_ID = 'suitecrm_id';
    protected const EXT_SUITECRM_TYPE = 'suitecrm_type';

    /** All-day color (tomato) */
    protected const COLOR_ALL_DAY = '11';
    /** Timed color (blueberry) */
    protected const COLOR_TIMED = '9';

    /** Working events are shown as "Free" so they do not block the user's availability */
    protected const TRANSPARENCY_WORKING = 'transparent';

    /** No popup/email reminders: the calendar is a source of truth, not an alarm generator */
    protected const REMINDERS_USE_DEFAULT = false;

    /**
     * Constructor
     */
    public function __construct($sugarConfig = null)
    {
        global $sugar_config;
        $sugarConfig = $sugarConfig ?: $sugar_config;

        $this->logger = LoggerManager::getLogger();
        $this->db = DBManagerFactory::getInstance();
        $this->authJson = $this->getAuthJson($sugarConfig);
        $this->logger->debug(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . '__construct');
    }

    /**
     * Gets the google_auth_json from the system config.
     *
     * @param array $sugarConfig
     * @return array Decoded credentials
     * @throws GoogleSyncException if missing or corrupt
     */
    protected function getAuthJson($sugarConfig)
    {
        if (empty($sugarConfig['google_auth_json'])) {
            throw new GoogleSyncException('google_auth_json not set', GoogleSyncException::GOOGLE_RECORD_PARSE_FAILURE);
        }
        $json = @base64_decode($sugarConfig['google_auth_json']);
        $auth = @json_decode($json, true);
        if (!$auth || empty($auth['web']['client_id']) || empty($auth['web']['client_secret'])) {
            throw new GoogleSyncException('google_auth_json is invalid or missing keys', GoogleSyncException::INVALID_CLIENT_ID);
        }
        return $auth;
    }

    /**
     * Runs the full sync for all users with the syncGCal preference enabled.
     *
     * @return bool true when no unrecoverable per-user errors blocked the pass
     */
    public function pushAllUsers()
    {
        $this->logger->debug(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . 'pushAllUsers start');

        if (empty($this->authJson)) {
            $this->logger->fatal(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . 'No google_auth_json configured');
            return false;
        }

        // Users authorised for GoogleSync (syncGCal = 1) and holding a token
        try {
            $result = $this->getEnabledUsers();
        } catch (Exception $e) {
            $this->logger->fatal(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . 'Failed to fetch enabled users: ' . $e->getMessage());
            return false;
        }

        $success = true;
        foreach ($result as $row) {
            $userId = $row['id'];
            try {
                $this->pushUser($userId);
                $this->logger->info(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . "Push OK for user {$userId}");
            } catch (Exception $e) {
                $success = false;
                $this->logger->fatal(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . "Push failed for user {$userId}: " . $e->getMessage());
            }
        }

        $this->logger->debug(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . 'pushAllUsers end');
        return $success;
    }

    /**
     * SQL for users with GoogleSync enabled and tokens persisted.
     *
     * @return mysqli_result|resource|array
     */
    protected function getEnabledUsers()
    {
        // Contents hold base64_encode(serialize(...)): decode and filter in PHP.
        $result = array();
        $rows = $this->db->query("SELECT assigned_user_id, contents
                                    FROM user_preferences
                                   WHERE category = '" . self::PREF_CATEGORY . "'
                                     AND deleted = 0");
        while ($row = $this->db->fetchByAssoc($rows)) {
            $prefs = @unserialize(@base64_decode($row['contents']));
            if (!is_array($prefs)) {
                continue;
            }
            $enabled = isset($prefs['syncGCal']) && (int)$prefs['syncGCal'] === 1;
            $hasToken = !empty($prefs['GoogleApiToken']) || !empty($prefs['GoogleApiRefreshToken']);
            if ($enabled && $hasToken) {
                $result[] = array('id' => $row['assigned_user_id']);
            }
        }
        return $result;
    }

    /**
     * Pushes incremental changes + sweep for one user.
     *
     * @param string $userId
     * @throws Exception re-raised to let pushAllUsers isolate per user
     */
    public function pushUser($userId)
    {
        $this->logger->debug(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . "pushUser {$userId}");

        $hasValidator = class_exists('SuiteCRM\Utility\SuiteValidator');
        if ($hasValidator) {
            $validator = new SuiteValidator();
            if (!$validator->isValidId($userId)) {
                throw new GoogleSyncException('User id is invalid: ' . $userId, GoogleSyncException::INVALID_USER_ID);
            }
        }

        $this->workingUser = BeanFactory::getBean('Users', $userId);
        if (empty($this->workingUser->id)) {
            throw new GoogleSyncException('Unable to retrieve user: ' . $userId, GoogleSyncException::UNABLE_TO_RETRIEVE_USER);
        }

        // Timezone from user preference (default UTC)
        $this->timezone = $this->workingUser->getPreference('timezone', 'global');
        if (empty($this->timezone)) {
            $this->timezone = 'UTC';
        }

        $this->gClient = $this->getGoogleClient();
        $this->gService = new \Google\Service\Calendar($this->gClient);

        // 1) Sweep: records that left scope or were deleted
        $this->sweepDeletedOrOutOfScope($userId);

        // 2) Incremental upsert (window -30d / +3m, only modified since last sync)
        $this->processWindowChanges($userId);

        $this->logger->info(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . "Push done for user {$userId}");
    }

    /**
     * Builds a Google Client and refreshes the token if needed (legacy pattern).
     *
     * @return \Google\Client
     * @throws GoogleSyncException
     */
    protected function getGoogleClient()
    {
        if (empty($this->workingUser->id)) {
            throw new GoogleSyncException('Working user not set', GoogleSyncException::INVALID_USER_ID);
        }

        // Token from the same preference category used by the legacy engine
        $tokenB64 = $this->workingUser->getPreference('GoogleApiToken', self::PREF_CATEGORY);
        $refreshB64 = $this->workingUser->getPreference('GoogleApiRefreshToken', self::PREF_CATEGORY);

        if (empty($tokenB64)) {
            throw new GoogleSyncException('No access token for user', GoogleSyncException::ACCSESS_TOKEN_PARAMETER_MISSING);
        }

        $token = @json_decode(@base64_decode($tokenB64), true);
        if (empty($token['access_token'])) {
            throw new GoogleSyncException('No access token for user', GoogleSyncException::ACCSESS_TOKEN_PARAMETER_MISSING);
        }

        // The refresh token is only provided once, on first authentication. It must be added afterwards.
        if (!array_key_exists('refresh_token', $token) && !empty($refreshB64)) {
            $token['refresh_token'] = @base64_decode($refreshB64);
        }

        $client = new \Google\Client();
        $client->setApplicationName('SinergiaCRM');
        $client->setScopes(Google\Service\Calendar::CALENDAR);
        $client->setAccessType('offline');
        $client->setAuthConfig($this->authJson);
        $client->setAccessToken($token);

        // Refresh the token if needed
        if ($client->isAccessTokenExpired()) {
            $this->logger->info(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . 'Refreshing Access Token');
            $refreshToken = $client->getRefreshToken();
            if (!empty($refreshToken)) {
                $client->fetchAccessTokenWithRefreshToken($refreshToken);
                if ($client->getAccessToken() !== false) {
                    // Persist the refreshed token back to the preference
                    $this->workingUser->setPreference('GoogleApiToken', base64_encode(json_encode($client->getAccessToken())), false, self::PREF_CATEGORY);
                    $this->workingUser->savePreferencesToDB();
                }
            } else {
                throw new GoogleSyncException('Refresh token is missing', GoogleSyncException::NO_REFRESH_TOKEN);
            }
        }

        return $client;
    }

    /**
     * Fetches the incremental window and upserts each record.
     *
     * @param string $userId
     */
    protected function processWindowChanges($userId)
    {
        $sql = "SELECT id
                  FROM stic_work_calendar
                 WHERE assigned_user_id = " . $this->db->quoted($userId) . "
                   AND deleted = 0
                   AND type IN ('" . implode("','", self::SYNC_TYPES) . "')
                   AND start_date BETWEEN (UTC_TIMESTAMP() - INTERVAL 30 DAY)
                                   AND (UTC_TIMESTAMP() + INTERVAL 3 MONTH)
                   AND date_modified > COALESCE(stic_gsync_lastsync, 0)
                 ORDER BY start_date";

        $result = $this->db->query($sql);

        while ($row = $this->db->fetchByAssoc($result)) {
            try {
                $bean = BeanFactory::getBean('stic_Work_Calendar', $row['id']);
                if (empty($bean->id)) {
                    continue;
                }
                $this->upsert($bean);
            } catch (Exception $e) {
                $this->logger->fatal(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . "Upsert failed for record {$row['id']}: " . $e->getMessage());
            }
        }
    }

    /**
     * Creates or updates the remote event for a single bean.
     *
     * @param stic_Work_Calendar $bean
     */
    protected function upsert($bean)
    {
        $remoteId = $bean->stic_gsync_id;

        if (empty($remoteId)) {
            // Insert
            $event = $this->buildEvent($bean);
            $created = $this->gService->events->insert($this->calendarId, $event);
            $this->saveMatching($bean, $created);
            $this->logger->info(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . "Inserted event {$created->getId()} for record {$bean->id}");
            return;
        }

        // Update
        try {
            $event = $this->buildEvent($bean);
            $updated = $this->gService->events->update($this->calendarId, $remoteId, $event);
            $this->saveMatching($bean, $updated);
            $this->logger->info(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . "Updated event {$remoteId} for record {$bean->id}");
        } catch (\Google\Service\Exception $e) {
            // 404/410 => the remote event was permanently purged: recreate (defensive fallback)
            if (in_array($e->getCode(), array(404, 410))) {
                $this->logger->warn(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . "Event {$remoteId} no longer exists, recreating for record {$bean->id}");
                // Direct SQL to avoid hook recursion and preserve date_modified
                $this->db->query("UPDATE stic_work_calendar SET stic_gsync_id = '' WHERE id = " . $this->db->quoted($bean->id));
                $this->upsert($bean);
                return;
            }
            throw $e;
        }
    }

    /**
     * Builds the Google Event from a Work_Calendar bean.
     *
     * @param stic_Work_Calendar $bean
     * @return \Google\Service\Calendar\Event
     */
    protected function buildEvent($bean)
    {
        $event = new \Google\Service\Calendar\Event();
        $event->setSummary($bean->name);
        $event->setDescription($bean->description);

        // No alarms: useDefault=false + no overrides (create/update of a cancelled
        // event must not inherit the calendar's default reminders either).
        $reminders = new \Google\Service\Calendar\EventReminders();
        $reminders->setUseDefault(self::REMINDERS_USE_DEFAULT);
        $reminders->setOverrides(array());
        $event->setReminders($reminders);

        $extendedProperties = new \Google\Service\Calendar\EventExtendedProperties();
        $extendedProperties->setPrivate(array(
            self::EXT_SUITECRM_ID => $bean->id,
            self::EXT_SUITECRM_TYPE => 'stic_Work_Calendar',
        ));
        $event->setExtendedProperties($extendedProperties);

        if (in_array($bean->type, self::ALL_DAY_SYNC_TYPES)) {
            $event->setColorId(self::COLOR_ALL_DAY);
            $this->setAllDayDates($event, $bean);
        } else {
            $event->setColorId(self::COLOR_TIMED);
            // Working periods mark the user as available (free), not busy
            $event->setTransparency(self::TRANSPARENCY_WORKING);
            $this->setTimedDates($event, $bean);
        }

        return $event;
    }

    /**
     * Maps all-day dates converting from DB UTC to the user's timezone first.
     *
     * @param \Google\Service\Calendar\Event $event
     * @param stic_Work_Calendar $bean
     */
    protected function setAllDayDates($event, $bean)
    {
        // Bean datetime properties are localized to the user's display format on retrieve();
        // fetched_row holds the raw DB (UTC) values the module stored for all-day records.
        $startUtc = !empty($bean->fetched_row['start_date']) ? $bean->fetched_row['start_date'] : $bean->start_date;
        $endUtc = !empty($bean->fetched_row['end_date']) ? $bean->fetched_row['end_date'] : $bean->end_date;

        // Module saves all-day start as 00:00 user-TZ converted to UTC
        // (stic_Work_Calendar.php:103-120). Parse the DB UTC value explicitly
        // and convert back to the user's TZ to recover the local day.
        $startUser = new \DateTime($startUtc, new \DateTimeZone('UTC'));
        $startUser->setTimezone(new \DateTimeZone($this->timezone));
        $endUser = new \DateTime($endUtc, new \DateTimeZone('UTC'));
        $endUser->setTimezone(new \DateTimeZone($this->timezone));

        $startDateTime = new \Google\Service\Calendar\EventDateTime();
        $startDateTime->setDate($startUser->format('Y-m-d'));
        $event->setStart($startDateTime);

        $endDateTime = new \Google\Service\Calendar\EventDateTime();
        $endDateTime->setDate($endUser->format('Y-m-d'));
        $event->setEnd($endDateTime);
    }

    /**
     * Maps a timed (working) record: UTC datetimes + user timezone.
     *
     * @param \Google\Service\Calendar\Event $event
     * @param stic_Work_Calendar $bean
     */
    protected function setTimedDates($event, $bean)
    {
        // Same raw-DB (UTC) source as setAllDayDates(); user display format is unsuitable.
        $startUtc = !empty($bean->fetched_row['start_date']) ? $bean->fetched_row['start_date'] : $bean->start_date;
        $endUtc = !empty($bean->fetched_row['end_date']) ? $bean->fetched_row['end_date'] : $bean->end_date;

        $startDateTime = new \Google\Service\Calendar\EventDateTime();
        $startDateTime->setDateTime(date(DATE_ATOM, strtotime($startUtc . ' UTC')));
        $startDateTime->setTimeZone($this->timezone);
        $event->setStart($startDateTime);

        $endDateTime = new \Google\Service\Calendar\EventDateTime();
        $endDateTime->setDateTime(date(DATE_ATOM, strtotime($endUtc . ' UTC')));
        $endDateTime->setTimeZone($this->timezone);
        $event->setEnd($endDateTime);
    }

    /**
     * Persists matching columns after a successful insert/update and updates lastsync.
     *
     * @param stic_Work_Calendar $bean
     * @param \Google\Service\Calendar\Event $event
     */
    protected function saveMatching($bean, $event)
    {
        $sql = "UPDATE stic_work_calendar
                   SET stic_gsync_id = " . $this->db->quoted($event->getId()) . ",
                       stic_gsync_lastsync = " . self::sticNow() . "
                 WHERE id = " . $this->db->quoted($bean->id);
        $this->db->query($sql);
    }

    /**
     * Removes remote events for records that were deleted or left the sync scope.
     *
     * @param string $userId
     */
    protected function sweepDeletedOrOutOfScope($userId)
    {
        // Direct SQL: beans hide soft-deleted rows, and scope filters are DB-side only.
        $sql = "SELECT id, stic_gsync_id
                  FROM stic_work_calendar
                 WHERE assigned_user_id = " . $this->db->quoted($userId) . "
                   AND stic_gsync_id != ''
                   AND (deleted = 1
                        OR type NOT IN ('" . implode("','", self::SYNC_TYPES) . "')
                        OR type = 'canceled')";
        $result = $this->db->query($sql);

        while ($row = $this->db->fetchByAssoc($result)) {
            try {
                $this->gService->events->delete($this->calendarId, $row['stic_gsync_id']);
                $this->clearMatching($row['id']);
                $this->logger->info(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . "Removed remote event {$row['stic_gsync_id']} for record {$row['id']}");
            } catch (\Google\Service\Exception $e) {
                // 404/410 = already gone remotely; clear local matching anyway
                if (in_array($e->getCode(), array(404, 410))) {
                    $this->clearMatching($row['id']);
                    $this->logger->info(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . "Remote event {$row['stic_gsync_id']} already gone, cleared matching for {$row['id']}");
                } else {
                    $this->logger->fatal(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . "Failed to remove remote event {$row['stic_gsync_id']}: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Clears matching columns for a record.
     *
     * @param string $recordId
     */
    protected function clearMatching($recordId)
    {
        $sql = "UPDATE stic_work_calendar
                   SET stic_gsync_id = NULL,
                       stic_gsync_lastsync = NULL
                 WHERE id = " . $this->db->quoted($recordId);
        $this->db->query($sql);
    }

    /**
     * Unix timestamp for last-sync bookkeeping.
     *
     * @return int
     */
    protected static function sticNow()
    {
        return time();
    }

    /**
     * Recovery: relink events whose matching columns were lost (e.g. partial DB restore).
     * Lists primary events filtered server-side by suitecrm_type, then relinks or cleans.
     *
     * @param string $userId
     * @return int number of recovered records
     */
    public function recoverLostMatching($userId)
    {
        $this->workingUser = BeanFactory::getBean('Users', $userId);
        $this->gClient = $this->getGoogleClient();
        $this->gService = new \Google\Service\Calendar($this->gClient);

        $optParams = array(
            'maxResults' => 250,
            'privateExtendedProperty' => self::EXT_SUITECRM_TYPE . '=' . 'stic_Work_Calendar',
            'singleEvents' => true,
        );

        $recovered = 0;
        $token = null;
        do {
            if (!empty($token)) {
                $optParams['pageToken'] = $token;
            }
            $events = $this->gService->events->listEvents($this->calendarId, $optParams);
            foreach ($events->getItems() as $ev) {
                $priv = $ev->getExtendedProperties() ? $ev->getExtendedProperties()->getPrivate() : array();
                $crmId = isset($priv[self::EXT_SUITECRM_ID]) ? $priv[self::EXT_SUITECRM_ID] : '';
                if (empty($crmId)) {
                    continue;
                }
                // Update matching only if the local record still exists and is in scope
                $bean = BeanFactory::getBean('stic_Work_Calendar', $crmId);
                if (empty($bean->id) || empty($bean->stic_gsync_id)) {
                    $sql = "UPDATE stic_work_calendar
                               SET stic_gsync_id = " . $this->db->quoted($ev->getId()) . ",
                                   stic_gsync_lastsync = " . self::sticNow() . "
                             WHERE id = " . $this->db->quoted($crmId);
                    $this->db->query($sql);
                    $recovered++;
                    $this->logger->info(__FILE__ . ':' . __LINE__ . ' ' . __METHOD__ . ' - ' . "Recovered matching for record {$crmId} -> {$ev->getId()}");
                }
            }
            $token = $events->getNextPageToken();
        } while ($token);

        return $recovered;
    }
}