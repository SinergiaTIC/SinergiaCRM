<?php
// STIC-Custom AAES - 20261109 - Attendance ticker write actions (IMPL-AAES-001, Fase B)
// Generic version for any instance (base of SCRM_marianao's fmarAttendanceTicker).
// Every action: validations by raw SQL columns, writes ALWAYS by bean (the
// core hooks recalc registration totals and session counters on save), the
// registration status is NEVER touched (RF-006) and every request is logged.
// Creation follows the core stic_AttendancesUtils::createAttendances pattern
// (automaticCreate, name, raw start_date, formatted duration, session owner).
// Generic rules (IMPL-AAES-001):
//   - mark / mark_all: in-census registrations only ('confirmed','participates')
//   - outside_mark (DD-3): ANY other defined status; the registration stays
//     untouched (the generic counterpart of Marianao's waiting_mark)
//   - set_status: only the four default statuses (DD-2)
//   - undo: dual semantics (core-owned -> clear status; else logical delete)
// END STIC-Custom AAES

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once __DIR__ . '/AAES_AttendanceTickerUtils.php';
require_once __DIR__ . '/AAES_AttendanceTickerQueries.php';

class AAES_AttendanceTickerActions
{
    // ---------------------------------------------------------------- helpers

    private static function fail($labelKey, $logType, $detail, $context = array())
    {
        AAES_AttendanceTickerUtils::logAttempt($logType, $detail, $context);
        return array('ok' => false, 'msg' => AAES_AttendanceTickerUtils::t($labelKey));
    }

    /** Full status label for messages (from the fixed base status map). */
    public static function statusLabel($status)
    {
        $map = AAES_AttendanceTickerUtils::statusMap();
        return AAES_AttendanceTickerUtils::t(isset($map[$status]) ? $map[$status]['label'] : (string) $status);
    }

    /**
     * Session validation shared by every action: exists and is not future
     * (PD-10; raw start_date column, core date convention).
     */
    private static function validSession($sessionId)
    {
        $session = AAES_AttendanceTickerQueries::sessionRow($sessionId);
        if ($session === '') {
            return null;
        }
        if (date('Y-m-d', strtotime((string) $session['start_date'])) > AAES_AttendanceTickerUtils::today()) {
            return 'future';
        }
        return $session;
    }

    /**
     * Find-or-create the attendance of a (registration, session) pair with
     * status 'yes'. Idempotent: never duplicates (RF-005/PD-16). The creation
     * mirrors the core createAttendances pattern; the registration status is
     * never modified (RF-006).
     * @param array $registrationRow row from registrationRow()
     * @param array $sessionRow row from sessionRow()
     * @return string|null attendance id, null on failure
     */
    private static function ensureAttendanceYes($registrationRow, $sessionRow)
    {
        $log = 'AAES_AttendanceTickerUtils';

        $attendanceId = AAES_AttendanceTickerQueries::attendanceIdFor($registrationRow['id'], $sessionRow['id']);
        if ($attendanceId !== '') {
            $bean = BeanFactory::getBean('stic_Attendances', $attendanceId);
            if (!$bean || !empty($bean->deleted)) {
                return null;
            }
            if ((string) $bean->status !== 'yes') {
                $bean->status = 'yes';
                $bean->save();
            }
            return $bean->id;
        }

        // Name: registration name + cleaned session name (core pattern, so the
        // attendance name never repeats the event name twice)
        $duration = $sessionRow['duration'];
        if (is_null($duration)) {
            $duration = 0;
        }
        $decodedSessionName = html_entity_decode((string) $sessionRow['name'], ENT_QUOTES, 'UTF-8');
        $sessionNameStart = trim(explode('|', $decodedSessionName)[0]);
        if (mb_stripos((string) $registrationRow['name'], $sessionNameStart) !== false) {
            $cleanSessionName = str_replace($sessionNameStart, '', $decodedSessionName);
            $cleanSessionName = trim(str_replace('|', '', $cleanSessionName));
        } else {
            $cleanSessionName = (string) $sessionRow['name'];
        }

        $attendance = BeanFactory::newBean('stic_Attendances');
        $attendance->automaticCreate = 1; // all values are set here: hooks may skip
        $attendance->name = $registrationRow['name'] . ' | ' . $cleanSessionName;
        $attendance->status = 'yes'; // "Yes" (RF-005/RF-006)
        $attendance->start_date = $sessionRow['start_date']; // raw column value (GMT)
        $attendance->duration = formatDecimalInConfigSettings($duration, true);
        $attendance->assigned_user_id = $sessionRow['assigned_user_id'];
        $attendance->stic_attendances_stic_registrationsstic_registrations_ida = $registrationRow['id'];
        $attendance->stic_attendances_stic_sessionsstic_sessions_ida = $sessionRow['id'];
        $attendance->save(); // core hooks recalc registration totals + session counters
        // stic_Registrations.status is NEVER touched (RF-006)
        $log::logAttempt('attendance_created', 'reg:' . $registrationRow['id'] . ' session:' . $sessionRow['id']);
        return $attendance->id;
    }

    /** Response envelope with live counters for the chips. */
    private static function ok($msgKey, $msgArgs, $sessionId, $extra = array())
    {
        return array_merge(
            array('ok' => true, 'msg' => sprintf(AAES_AttendanceTickerUtils::t($msgKey), $msgArgs)),
            array('counters' => AAES_AttendanceTickerQueries::sessionCounters($sessionId)),
            $extra
        );
    }

    /**
     * Common in-census registration validation for the marking actions:
     * exists, belongs to the event of the session, registered on or before
     * the session date, holds one of the allowed statuses, and the session
     * weekday is not disabled for them (the same rule the core
     * createAttendances applies when generating attendances: a person with
     * this weekday disabled never gets an attendance for it, so the ticker
     * must not mark them either).
     * @return array|null|string registration row | null (not found) | 'status' | 'disabled'
     */
    private static function validRegistration($registrationId, $sessionRow, $allowedStatuses)
    {
        $registration = AAES_AttendanceTickerQueries::registrationRow($registrationId);
        if ($registration === '' || $registration['event_id'] !== $sessionRow['event_id']) {
            return null;
        }
        if (!in_array($registration['status'], $allowedStatuses, true)) {
            return 'status';
        }
        return self::validateTiming($registration, $sessionRow);
    }

    /**
     * Generic out-of-census validation (DD-3): any DEFINED status other than
     * the in-census ones ('confirmed','participates') is markable. Empty
     * statuses are never markable. Same timing/weekday rules as the census.
     * @return array|null|string registration row | null | 'status' | 'disabled'
     */
    private static function validOutsideRegistration($registrationId, $sessionRow)
    {
        $registration = AAES_AttendanceTickerQueries::registrationRow($registrationId);
        if ($registration === '' || $registration['event_id'] !== $sessionRow['event_id']) {
            return null;
        }
        $status = (string) $registration['status'];
        if ($status === '' || in_array($status, array('confirmed', 'participates'), true)) {
            return 'status';
        }
        return self::validateTiming($registration, $sessionRow);
    }

    /** Shared registration_date <= session date + weekday rule (pure checks). */
    private static function validateTiming($registration, $sessionRow)
    {
        if (!empty($registration['registration_date'])
            && date('Y-m-d', strtotime((string) $registration['registration_date'])) > date('Y-m-d', strtotime((string) $sessionRow['start_date']))
        ) {
            return 'status';
        }
        $weekday = date('w', strtotime((string) $sessionRow['start_date']));
        if ($weekday !== false
            && !empty($registration['disabled_weekdays'])
            && strpos((string) $registration['disabled_weekdays'], (string) $weekday) !== false
        ) {
            return 'disabled';
        }
        return $registration;
    }

    // ---------------------------------------------------------------- actions

    /**
     * Mark a person as present (RF-005): find-or-create the attendance with
     * status 'yes'. Covers pending rows (on-the-fly creation, PD-6) and
     * already-marked rows (idempotent update). Only in-census statuses
     * (confirmed/participates).
     */
    public static function mark($registrationId, $sessionId)
    {
        $session = self::validSession($sessionId);
        if ($session === null) {
            return self::fail('LBL_AAES_TICKER_NOT_FOUND', 'session_not_found', $sessionId);
        }
        if ($session === 'future') {
            return self::fail('LBL_AAES_TICKER_FUTURE', 'session_future', $sessionId);
        }
        $registration = self::validRegistration($registrationId, $session, array('confirmed', 'participates'));
        if ($registration === null) {
            return self::fail('LBL_AAES_TICKER_NOT_FOUND', 'registration_not_in_session', $registrationId);
        }
        if ($registration === 'status') {
            return self::fail('LBL_AAES_TICKER_NOT_FOUND', 'registration_status_not_markable', $registrationId);
        }
        if ($registration === 'disabled') {
            return self::fail('LBL_AAES_TICKER_DISABLED_DAY', 'registration_weekday_disabled', $registrationId);
        }
        $attendanceId = self::ensureAttendanceYes($registration, $session);
        if ($attendanceId === null) {
            return self::fail('LBL_AAES_TICKER_AJAX_ERROR', 'mark_failed', $registrationId);
        }
        return self::ok('LBL_AAES_TICKER_MARK_OK', '', $sessionId, array('attendance_id' => $attendanceId));
    }

    /**
     * Out-of-census person came (RF-006, PD-11 + DD-3): creates/updates the
     * attendance with status 'yes' WITHOUT touching the registration
     * (whatever its non-census custom status is, it stays). Generic
     * counterpart of Marianao's waiting_mark.
     */
    public static function outside_mark($registrationId, $sessionId)
    {
        $session = self::validSession($sessionId);
        if ($session === null) {
            return self::fail('LBL_AAES_TICKER_NOT_FOUND', 'session_not_found', $sessionId);
        }
        if ($session === 'future') {
            return self::fail('LBL_AAES_TICKER_FUTURE', 'session_future', $sessionId);
        }
        // Data driven (PD-11): any defined non-census status is markable
        $registration = self::validOutsideRegistration($registrationId, $session);
        if ($registration === null) {
            return self::fail('LBL_AAES_TICKER_NOT_FOUND', 'registration_not_in_session', $registrationId);
        }
        if ($registration === 'status') {
            return self::fail('LBL_AAES_TICKER_NOT_FOUND', 'registration_status_not_markable', $registrationId);
        }
        if ($registration === 'disabled') {
            return self::fail('LBL_AAES_TICKER_DISABLED_DAY', 'registration_weekday_disabled', $registrationId);
        }
        $attendanceId = self::ensureAttendanceYes($registration, $session);
        if ($attendanceId === null) {
            return self::fail('LBL_AAES_TICKER_AJAX_ERROR', 'outside_mark_failed', $registrationId);
        }
        return self::ok('LBL_AAES_TICKER_OUT_OK', '', $sessionId, array('attendance_id' => $attendanceId));
    }

    /**
     * Correct the status of an existing attendance (RF-005, PD-5/PD-9):
     * only the four default statuses (DD-2, AAES_AttendanceTickerUtils::statusMap).
     */
    public static function set_status($attendanceId, $status)
    {
        if (!isset(AAES_AttendanceTickerUtils::statusMap()[$status])) {
            return self::fail('LBL_AAES_TICKER_INVALID_STATUS', 'status_not_whitelisted', (string) $status);
        }
        if (!preg_match(AAES_AttendanceTickerQueries::UUID_RE, (string) $attendanceId)) {
            return self::fail('LBL_AAES_TICKER_NOT_FOUND', 'attendance_bad_uuid', (string) $attendanceId);
        }
        $bean = BeanFactory::getBean('stic_Attendances', $attendanceId);
        if (!$bean || !empty($bean->deleted)) {
            return self::fail('LBL_AAES_TICKER_NOT_FOUND', 'attendance_not_found', (string) $attendanceId);
        }
        $sessionId = self::attendanceSessionId($attendanceId);
        if ($sessionId === '') {
            return self::fail('LBL_AAES_TICKER_NOT_FOUND', 'attendance_no_session', (string) $attendanceId);
        }
        $session = self::validSession($sessionId);
        if ($session === 'future') {
            return self::fail('LBL_AAES_TICKER_FUTURE', 'session_future', $sessionId);
        }
        $bean->status = $status;
        $bean->save(); // core hooks recalc registration totals + session counters
        return self::ok('LBL_AAES_TICKER_STATUS_OK', self::statusLabel($status), $sessionId);
    }

    /**
     * Mark every pending person of the session as present (PD-14): one
     * request, N bean saves server-side. Only in-census pendings; the
     * out-of-census section is NEVER marked in bulk (each "came" is
     * individual and deliberate).
     */
    public static function mark_all($sessionId)
    {
        $session = self::validSession($sessionId);
        if ($session === null) {
            return self::fail('LBL_AAES_TICKER_NOT_FOUND', 'session_not_found', $sessionId);
        }
        if ($session === 'future') {
            return self::fail('LBL_AAES_TICKER_FUTURE', 'session_future', $sessionId);
        }
        $pendents = AAES_AttendanceTickerQueries::pending($sessionId);
        $marked = 0;
        foreach ($pendents as $p) {
            $registrationRow = array('id' => $p['registration_id'], 'name' => $p['registration_name']);
            if (self::ensureAttendanceYes($registrationRow, $session) !== null) {
                $marked++;
            }
        }
        return self::ok('LBL_AAES_TICKER_MARK_ALL_OK', $marked, $sessionId, array('marked' => $marked));
    }

    /**
     * Undo a marking (PD-15). Two semantics depending on who owns the record:
     * - In-census registrations meeting the core generation rules
     *   (confirmed/participates of the session's event, registered on or
     *   before the session date, session weekday enabled): the attendance
     *   belongs to the core model, so the marking is undone by CLEARING the
     *   status (the record stays, the person shows as pending again, exactly
     *   as the generator left it).
     * - Anything else (out-of-census people, or records the core would never
     *   generate): logically delete the record (mark_deleted cleans the bean
     *   row and its relationship rows), putting the person back on their
     *   section (generic DD-3 semantics; Marianao: back to waiting list).
     */
    public static function undo($attendanceId)
    {
        if (!preg_match(AAES_AttendanceTickerQueries::UUID_RE, (string) $attendanceId)) {
            return self::fail('LBL_AAES_TICKER_NOT_FOUND', 'attendance_bad_uuid', (string) $attendanceId);
        }
        $bean = BeanFactory::getBean('stic_Attendances', $attendanceId);
        if (!$bean || !empty($bean->deleted)) {
            return self::fail('LBL_AAES_TICKER_NOT_FOUND', 'attendance_not_found', (string) $attendanceId);
        }
        // Snapshot the related ids before touching anything
        $sessionId = self::attendanceSessionId($attendanceId);
        $registrationId = self::attendanceRegistrationId($attendanceId);
        if ($sessionId === '' || $registrationId === '') {
            return self::fail('LBL_AAES_TICKER_NOT_FOUND', 'attendance_no_relations', (string) $attendanceId);
        }

        // Does the core own this record? (same rules createAttendances applies)
        $session = AAES_AttendanceTickerQueries::sessionRow($sessionId);
        $registration = $session !== ''
            ? self::validRegistration($registrationId, $session, array('confirmed', 'participates'))
            : null;

        if (is_array($registration)) {
            // Core-owned: clear the status, keep the record
            if ((string) $bean->status !== '') {
                $bean->status = '';
                $bean->save(); // hooks recalc registration totals + session counters
                AAES_AttendanceTickerUtils::logAttempt('undo_clear', 'att:' . $attendanceId);
            }
            return self::ok('LBL_AAES_TICKER_UNDO_CLEAR_OK', '', $sessionId);
        }

        // Ticker-owned (out-of-census or non-generatable): logical delete
        $bean->mark_deleted($attendanceId);

        // Recalculate what no hook recalculates on delete
        require_once 'modules/stic_Attendances/Utils.php';
        require_once 'modules/stic_Sessions/Utils.php';
        stic_AttendancesUtils::setRegistrationTotalHoursAndPercentage($registrationId);
        stic_SessionsUtils::setSessionAttendancesCounters($sessionId);
        AAES_AttendanceTickerUtils::logAttempt('undo_delete', 'att:' . $attendanceId);

        return self::ok('LBL_AAES_TICKER_UNDO_OK', '', $sessionId);
    }

    // ---------------------------------------------------------- sql lookups

    private static function attendanceSessionId($attendanceId)
    {
        $db = DBManagerFactory::getInstance();
        return (string) $db->getOne(
            "SELECT asx.stic_attendances_stic_sessionsstic_sessions_ida
            FROM stic_attendances_stic_sessions_c asx
            WHERE asx.stic_attendances_stic_sessionsstic_attendances_idb = " . $db->quoted($attendanceId) . "
              AND asx.deleted = 0
            LIMIT 1"
        );
    }

    private static function attendanceRegistrationId($attendanceId)
    {
        $db = DBManagerFactory::getInstance();
        return (string) $db->getOne(
            "SELECT ar.stic_attendances_stic_registrationsstic_registrations_ida
            FROM stic_attendances_stic_registrations_c ar
            WHERE ar.stic_attendances_stic_registrationsstic_attendances_idb = " . $db->quoted($attendanceId) . "
              AND ar.deleted = 0
            LIMIT 1"
        );
    }
}
