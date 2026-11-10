<?php
// STIC-Custom AAES - 20261109 - Attendance ticker SQL listings (IMPL-AAES-001, Fase A)
// Generic version for any instance (base of SCRM_marianao's fmarAttendanceTicker).
// Read-only queries; every parameter is db-quoted. Dates follow the core
// convention (PHP server local date) of stic_AttendancesUtils::createAttendances.
// Instance-independent rules (DD-3/DD-4 of the IMPL doc):
//   - "Out of census" registrations (DD-3): any defined status NOT in
//     ('confirmed','participates') works as Marianao's waiting list, so any
//     entity with custom registration statuses keeps working.
//   - Project events: direct relationship only (stic_events_project); the
//     parent/child event chain is NOT supported (not a base feature).
// END STIC-Custom AAES

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class AAES_AttendanceTickerQueries
{
    // ---------------------------------------------------------------- helpers

    /** UUID pattern used for every id received from the request. */
    const UUID_RE = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

    /** Registration statuses owned by the core attendance model (censo). */
    const STATUS_IN_CENSO = "('confirmed','participates')";

    /**
     * Projects with activity in progress (screen 1): projects whose events
     * have sessions today or within the configured window (the caller passes
     * the AAES_TICKER_SEARCH_DAYS setting; PD-2). Events belong to a project
     * through the CORE relationship stic_events_project (direct only, DD-4:
     * no parent/child events). Future sessions never match (window ends at
     * today).
     * @param int $days window length, default 30
     * @return array rows: id, name, sessions_today, sessions_window
     */
    public static function projectsWithSessions($days = 30)
    {
        $db = DBManagerFactory::getInstance();
        $today = $db->quoted(AAES_AttendanceTickerUtils::today());
        $from = $db->quoted(AAES_AttendanceTickerUtils::daysAgo($days));

        $sql = "
            SELECT p.id, p.name,
                   COUNT(DISTINCT s.id) sessions_window,
                   COUNT(DISTINCT CASE WHEN DATE(s.start_date) = {$today} THEN s.id END) sessions_today
            FROM project p
            JOIN stic_events_project_c pe ON pe.stic_events_projectproject_ida = p.id AND pe.deleted = 0
            JOIN stic_events e ON e.id = pe.stic_events_projectstic_events_idb AND e.deleted = 0
            JOIN stic_sessions_stic_events_c se ON se.stic_sessions_stic_eventsstic_events_ida = e.id AND se.deleted = 0
            JOIN stic_sessions s ON s.id = se.stic_sessions_stic_eventsstic_sessions_idb AND s.deleted = 0
            WHERE p.deleted = 0
              AND DATE(s.start_date) BETWEEN {$from} AND {$today}
            GROUP BY p.id, p.name
            ORDER BY p.name";

        $rows = array();
        $res = $db->query($sql);
        while ($row = $db->fetchByAssoc($res)) {
            $rows[] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'sessions_today' => (int) $row['sessions_today'],
                'sessions_window' => (int) $row['sessions_window'],
            );
        }
        return $rows;
    }

    /**
     * Project name for the screen chrome, '' when not found or invalid.
     */
    public static function projectName($projectId)
    {
        if (!preg_match(self::UUID_RE, (string) $projectId)) {
            return '';
        }
        $db = DBManagerFactory::getInstance();
        return (string) $db->getOne(
            "SELECT name FROM project WHERE id = " . $db->quoted($projectId) . " AND deleted = 0"
        );
    }

    /**
     * LIKE pattern for a contains search, with %, _ and \ escaped so user
     * input never alters the pattern shape (pure, bench-testable).
     */
    public static function likePattern($text)
    {
        return '%' . str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), (string) $text) . '%';
    }

    /**
     * Normalize the session searcher filters (pure, bench-testable):
     * - the window is limited to the last $days days (entity rule: the
     *   searcher covers a bounded window, 30 by default, AAES_TICKER_SEARCH_DAYS
     *   in stic_Settings): 'from' is clamped to today-$days
     * - 'to' clamps to yesterday (today's sessions live in the "today"
     *   section, never repeated here)
     * - from > to: swapped; degenerate ranges after clamping collapse onto 'to'
     * - q: trimmed text; event_id: UUID or '' (anything else dropped)
     * - page: >= 1
     */
    public static function normalizeSearchFilters($raw, $days = 30)
    {
        $today = AAES_AttendanceTickerUtils::today();
        $yesterday = AAES_AttendanceTickerUtils::daysAgo(1);
        $windowFrom = AAES_AttendanceTickerUtils::daysAgo($days);
        $from = isset($raw['from']) ? (string) $raw['from'] : '';
        $to = isset($raw['to']) ? (string) $raw['to'] : '';
        $from = self::validYmd($from) ? $from : $windowFrom;
        $to = self::validYmd($to) ? $to : $today;
        if ($from > $to) {
            $tmp = $from;
            $from = $to;
            $to = $tmp;
        }
        if ($to > $yesterday) {
            $to = $yesterday;
        }
        if ($from < $windowFrom) {
            $from = $windowFrom;
        }
        if ($from > $to) {
            $from = $to;
        }
        $q = isset($raw['q']) ? trim((string) $raw['q']) : '';
        $eventId = isset($raw['event_id']) ? (string) $raw['event_id'] : '';
        if (!preg_match(self::UUID_RE, $eventId)) {
            $eventId = '';
        }
        $page = isset($raw['page']) ? (int) $raw['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        return array('from' => $from, 'to' => $to, 'q' => $q, 'event_id' => $eventId, 'page' => $page);
    }

    /** Real calendar date in Y-m-d format (pure). */
    private static function validYmd($value)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $value)) {
            return false;
        }
        $ts = strtotime($value);
        return $ts !== false && date('Y-m-d', $ts) === $value;
    }

    /**
     * Project scope condition over the events of a session query (direct
     * relationship only, DD-4). Requires the e alias of sessionBaseSql().
     */
    private static function scopeWhere($projectId)
    {
        if (!preg_match(self::UUID_RE, (string) $projectId)) {
            return '';
        }
        $db = DBManagerFactory::getInstance();
        $pid = $db->quoted($projectId);
        $direct = "SELECT stic_events_projectstic_events_idb FROM stic_events_project_c
                    WHERE stic_events_projectproject_ida = {$pid} AND deleted = 0";
        return " AND e.id IN ({$direct})";
    }

    /** Shared FROM/WHERE for session listings. */
    private static function sessionBaseSql()
    {
        return " FROM stic_sessions s
            JOIN stic_sessions_stic_events_c se ON se.stic_sessions_stic_eventsstic_sessions_idb = s.id AND se.deleted = 0
            JOIN stic_events e ON e.id = se.stic_sessions_stic_eventsstic_events_ida AND e.deleted = 0
            WHERE s.deleted = 0";
    }

    // ------------------------------------------------- screen 2 listings

    /**
     * Live counter subqueries shared by both session listings (screen 2).
     * Enrolled people only (RF-005): out-of-census registrations (DD-3) never
     * count here (they are a separate concept); an out-of-census marked
     * person does not inflate the assistant count either (their attendance
     * belongs to that flow, not to the enrolled roster). The session weekday
     * rule applies too (same as the core generator), so the badge
     * denominator matches the pass screen 'Enrolled'.
     */
    private static function counterSubqueries()
    {
        $marked = "(SELECT COUNT(*) FROM stic_attendances a
                      JOIN stic_attendances_stic_sessions_c asx ON asx.stic_attendances_stic_sessionsstic_attendances_idb = a.id AND asx.deleted = 0
                      JOIN stic_attendances_stic_registrations_c ar ON ar.stic_attendances_stic_registrationsstic_attendances_idb = a.id AND ar.deleted = 0
                      JOIN stic_registrations i2 ON i2.id = ar.stic_attendances_stic_registrationsstic_registrations_ida AND i2.deleted = 0
                     WHERE asx.stic_attendances_stic_sessionsstic_sessions_ida = s.id
                       AND a.deleted = 0 AND a.status IN ('yes','partial')
                       AND i2.status IN ('confirmed','participates')
                       AND (i2.disabled_weekdays IS NULL
                            OR INSTR(i2.disabled_weekdays, CONCAT(DAYOFWEEK(s.start_date) - 1)) = 0))";
        $roster = "(SELECT COUNT(*) FROM stic_registrations i
                      JOIN stic_registrations_stic_events_c ie ON ie.stic_registrations_stic_eventsstic_registrations_idb = i.id AND ie.deleted = 0
                     WHERE ie.stic_registrations_stic_eventsstic_events_ida = e.id AND i.deleted = 0
                       AND i.status IN ('confirmed','participates')
                       AND (DATE(i.registration_date) <= DATE(s.start_date) OR i.registration_date IS NULL)
                       AND (i.disabled_weekdays IS NULL
                            OR INSTR(i.disabled_weekdays, CONCAT(DAYOFWEEK(s.start_date) - 1)) = 0))";
        return array($marked, $roster);
    }

    /**
     * Today's sessions of the scope (RF-003), with live counters:
     * marked = attendances yes/partial of the session; roster = in-census
     * registrations of the event (registration_date <= session date, same
     * rule as the roster listing).
     */
    public static function todaySessions($projectId)
    {
        $db = DBManagerFactory::getInstance();
        $today = $db->quoted(AAES_AttendanceTickerUtils::today());
        list($marked, $roster) = self::counterSubqueries();
        $sql = "SELECT s.id, s.name, s.start_date, s.duration,
                       e.id event_id, e.name event_name,
                       {$marked} marked, {$roster} roster"
            . self::sessionBaseSql()
            . " AND DATE(s.start_date) = {$today}"
            . self::scopeWhere($projectId)
            . " ORDER BY s.start_date, s.name";

        $rows = array();
        $res = $db->query($sql);
        while ($row = $db->fetchByAssoc($res)) {
            $rows[] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                // Display in the user's timezone; filters stay on raw columns
                'start_time' => AAES_AttendanceTickerUtils::dbToLocal($row['start_date'], 'H:i'),
                'duration' => $row['duration'],
                'event_id' => $row['event_id'],
                'event_name' => $row['event_name'],
                'marked' => (int) $row['marked'],
                'roster' => (int) $row['roster'],
            );
        }
        return $rows;
    }

    /**
     * Past session search (RF-004, PD-4) with the normalized filters:
     * date range, free text over session/event name, event dropdown,
     * never future sessions, newest first, paginated.
     * @return array rows, total, page, pages
     */
    public static function searchSessions($f, $projectId, $pageSize = 25)
    {
        $db = DBManagerFactory::getInstance();
        list($marked, $roster) = self::counterSubqueries();
        $where = self::sessionBaseSql()
            . " AND DATE(s.start_date) BETWEEN " . $db->quoted($f['from']) . " AND " . $db->quoted($f['to'])
            . self::scopeWhere($projectId);
        if ($f['q'] !== '') {
            $like = $db->quoted(self::likePattern($f['q']));
            $where .= " AND (s.name LIKE {$like} OR e.name LIKE {$like})";
        }
        if ($f['event_id'] !== '') {
            $where .= " AND e.id = " . $db->quoted($f['event_id']);
        }
        $total = (int) $db->getOne("SELECT COUNT(DISTINCT s.id)" . $where);
        $pages = max(1, (int) ceil($total / $pageSize));
        $page = min($f['page'], $pages); // bogus page numbers fall back to the last one
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT DISTINCT s.id, s.name, s.start_date, s.duration,
                       e.id event_id, e.name event_name,
                       {$marked} marked, {$roster} roster"
            . $where
            . " ORDER BY s.start_date DESC, s.name"
            . " LIMIT {$pageSize} OFFSET {$offset}";

        $rows = array();
        $res = $db->query($sql);
        while ($row = $db->fetchByAssoc($res)) {
            $rows[] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                // Display in the user's timezone; filters stay on raw columns
                'date' => AAES_AttendanceTickerUtils::dbToLocal($row['start_date'], 'd/m/Y'),
                'start_time' => AAES_AttendanceTickerUtils::dbToLocal($row['start_date'], 'H:i'),
                'duration' => $row['duration'],
                'event_id' => $row['event_id'],
                'event_name' => $row['event_name'],
                'marked' => (int) $row['marked'],
                'roster' => (int) $row['roster'],
            );
        }
        return array(
            'rows' => $rows,
            'total' => $total,
            'page' => $page,
            'pages' => $pages,
        );
    }

    /**
     * Event dropdown options for the searcher: events of the scope having
     * sessions within the (already normalized) range, ordered by name.
     */
    public static function scopeEvents($projectId, $from, $to)
    {
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT DISTINCT e.id, e.name
            FROM stic_events e
            JOIN stic_sessions_stic_events_c se ON se.stic_sessions_stic_eventsstic_events_ida = e.id AND se.deleted = 0
            JOIN stic_sessions s ON s.id = se.stic_sessions_stic_eventsstic_sessions_idb AND s.deleted = 0
            WHERE e.deleted = 0
              AND DATE(s.start_date) BETWEEN " . $db->quoted($from) . " AND " . $db->quoted($to)
            . self::scopeWhere($projectId)
            . " ORDER BY e.name";
        $rows = array();
        $res = $db->query($sql);
        while ($row = $db->fetchByAssoc($res)) {
            $rows[] = array('id' => $row['id'], 'name' => $row['name']);
        }
        return $rows;
    }

    /**
     * Does the scope have any PAST session within the last N days (default
     * window, no text/event filters)? Drives whether the searcher section is
     * worth rendering: it is only superfluous when there are today sessions
     * AND nothing else to offer (entity rule 2026-10-22).
     */
    public static function hasPastSessions($projectId, $days = 30)
    {
        $db = DBManagerFactory::getInstance();
        $from = $db->quoted(AAES_AttendanceTickerUtils::daysAgo($days));
        $to = $db->quoted(AAES_AttendanceTickerUtils::daysAgo(1));
        $sql = "SELECT 1" . self::sessionBaseSql()
            . " AND DATE(s.start_date) BETWEEN {$from} AND {$to}"
            . self::scopeWhere($projectId)
            . " LIMIT 1";
        return (bool) $db->getOne($sql);
    }

    // ------------------------------------------------- screen 3 listings

    /**
     * Session row with its event, read by raw SQL columns (never from the
     * bean: bean dates may arrive user-formatted). '' when not found.
     * Used by the pass screen and by every action validation.
     */
    public static function sessionRow($sessionId)
    {
        if (!preg_match(self::UUID_RE, (string) $sessionId)) {
            return '';
        }
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT s.id, s.name, s.start_date, s.duration, s.assigned_user_id,
                       e.id event_id, e.name event_name
            FROM stic_sessions s
            JOIN stic_sessions_stic_events_c se ON se.stic_sessions_stic_eventsstic_sessions_idb = s.id AND se.deleted = 0
            JOIN stic_events e ON e.id = se.stic_sessions_stic_eventsstic_events_ida AND e.deleted = 0
            WHERE s.id = " . $db->quoted($sessionId) . " AND s.deleted = 0
            GROUP BY s.id";
        $row = $db->fetchByAssoc($db->query($sql));
        return $row ? $row : '';
    }

    /**
     * Registration row with its event, by raw SQL columns (action validations).
     * '' when not found.
     */
    public static function registrationRow($registrationId)
    {
        if (!preg_match(self::UUID_RE, (string) $registrationId)) {
            return '';
        }
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT i.id, i.name, i.status, i.registration_date, i.disabled_weekdays,
                       ie.stic_registrations_stic_eventsstic_events_ida event_id
            FROM stic_registrations i
            LEFT JOIN stic_registrations_stic_events_c ie ON ie.stic_registrations_stic_eventsstic_registrations_idb = i.id AND ie.deleted = 0
            WHERE i.id = " . $db->quoted($registrationId) . " AND i.deleted = 0
            GROUP BY i.id";
        $row = $db->fetchByAssoc($db->query($sql));
        return $row ? $row : '';
    }

    /**
     * Existing attendance id for a (registration, session) pair, '' when none.
     * Idempotency basis for every marking action (never duplicate).
     */
    public static function attendanceIdFor($registrationId, $sessionId)
    {
        $db = DBManagerFactory::getInstance();
        return (string) $db->getOne(
            "SELECT a.id
            FROM stic_attendances a
            JOIN stic_attendances_stic_registrations_c ar ON ar.stic_attendances_stic_registrationsstic_attendances_idb = a.id AND ar.deleted = 0
            JOIN stic_attendances_stic_sessions_c asx ON asx.stic_attendances_stic_sessionsstic_attendances_idb = a.id AND asx.deleted = 0
            WHERE ar.stic_attendances_stic_registrationsstic_registrations_ida = " . $db->quoted($registrationId) . "
              AND asx.stic_attendances_stic_sessionsstic_sessions_ida = " . $db->quoted($sessionId) . "
              AND a.deleted = 0
            LIMIT 1"
        );
    }

    /** Contact ids related to a registration (usually exactly one). */
    private static function registrationContactSql()
    {
        return " JOIN stic_registrations_contacts_c ic ON ic.stic_registrations_contactsstic_registrations_idb = i.id AND ic.deleted = 0
            JOIN contacts c ON c.id = ic.stic_registrations_contactscontacts_ida AND c.deleted = 0";
    }

    /**
     * Roster: every attendance of the session (whatever the registration
     * status: out-of-census-marked people included, carrying their
     * registration status so the view can badge them).
     */
    public static function rosterAttendances($sessionId)
    {
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT a.id attendance_id, a.status attendance_status, a.duration,
                       i.id registration_id, i.status registration_status,
                       c.id contact_id, c.first_name, c.last_name
            FROM stic_attendances a
            JOIN stic_attendances_stic_sessions_c asx ON asx.stic_attendances_stic_sessionsstic_attendances_idb = a.id AND asx.deleted = 0
            JOIN stic_attendances_stic_registrations_c ar ON ar.stic_attendances_stic_registrationsstic_attendances_idb = a.id AND ar.deleted = 0
            JOIN stic_registrations i ON i.id = ar.stic_attendances_stic_registrationsstic_registrations_ida AND i.deleted = 0
            JOIN stic_sessions s ON s.id = asx.stic_attendances_stic_sessionsstic_sessions_ida AND s.deleted = 0" . self::registrationContactSql() . "
            WHERE asx.stic_attendances_stic_sessionsstic_sessions_ida = " . $db->quoted($sessionId) . "
              AND a.deleted = 0
            ORDER BY c.last_name, c.first_name";
        $rows = array();
        $res = $db->query($sql);
        while ($row = $db->fetchByAssoc($res)) {
            $rows[] = array(
                'attendance_id' => $row['attendance_id'],
                'attendance_status' => $row['attendance_status'],
                'registration_id' => $row['registration_id'],
                'registration_status' => $row['registration_status'],
                'name' => trim(($row['first_name'] ? $row['first_name'] . ' ' : '') . $row['last_name']),
            );
        }
        return $rows;
    }

    /**
     * In-census registrations of the session's event pending to be marked
     * (RF-005): status IN ('confirmed','participates'), registered on or
     * before the session date, the session weekday NOT disabled for them
     * (the same rule the core createAttendances applies when generating the
     * attendances), and no status-set attendance for that session yet.
     * Covers both people with no attendance at all (registered after the
     * generator ran) and people whose core-generated attendance is still
     * empty-status (pending marking): tapping either creates or fills the
     * attendance with 'yes'.
     */
    public static function pending($sessionId)
    {
        return self::registrationsWithoutAttendance(
            $sessionId,
            "i.status IN " . self::STATUS_IN_CENSO
        );
    }

    /**
     * Out-of-census section (RF-006 + DD-3): registrations of the session's
     * event with ANY defined status other than the in-census ones
     * ('confirmed'/'participates') and no attendance for that session
     * (the generic counterpart of Marianao's waiting list; works for any
     * entity with custom registration statuses).
     */
    public static function outsideCensus($sessionId)
    {
        return self::registrationsWithoutAttendance(
            $sessionId,
            "i.status IS NOT NULL AND i.status != '' AND i.status NOT IN " . self::STATUS_IN_CENSO
        );
    }

    /**
     * Generic in-census/out-of-census listing driver: registrations of the
     * session's event matching the given status SQL condition, =before-the-
     * session-date rule, weekday rule, and no status-set attendance yet.
     * @param string $sessionId validated session id
     * @param string $statusWhere raw SQL condition over alias i.status
     */
    private static function registrationsWithoutAttendance($sessionId, $statusWhere)
    {
        $db = DBManagerFactory::getInstance();
        $sid = $db->quoted($sessionId);
        $sql = "SELECT i.id registration_id, i.name registration_name,
                       c.id contact_id, c.first_name, c.last_name
            FROM stic_registrations i
            JOIN stic_registrations_stic_events_c ie ON ie.stic_registrations_stic_eventsstic_registrations_idb = i.id AND ie.deleted = 0
            JOIN stic_sessions_stic_events_c se ON se.stic_sessions_stic_eventsstic_events_ida = ie.stic_registrations_stic_eventsstic_events_ida AND se.deleted = 0
            JOIN stic_sessions s ON s.id = se.stic_sessions_stic_eventsstic_sessions_idb AND s.deleted = 0 AND s.id = {$sid}" . self::registrationContactSql() . "
            WHERE i.deleted = 0
              AND {$statusWhere}
              AND (DATE(i.registration_date) <= DATE(s.start_date) OR i.registration_date IS NULL)
              AND (i.disabled_weekdays IS NULL
                   OR INSTR(i.disabled_weekdays, CONCAT(DAYOFWEEK(s.start_date) - 1)) = 0)
              AND NOT EXISTS (SELECT 1 FROM stic_attendances a
                    JOIN stic_attendances_stic_registrations_c ar ON ar.stic_attendances_stic_registrationsstic_attendances_idb = a.id AND ar.deleted = 0
                    JOIN stic_attendances_stic_sessions_c asx ON asx.stic_attendances_stic_sessionsstic_attendances_idb = a.id AND asx.deleted = 0
                   WHERE ar.stic_attendances_stic_registrationsstic_registrations_ida = i.id
                     AND asx.stic_attendances_stic_sessionsstic_sessions_ida = {$sid}
                     AND a.deleted = 0
                     AND a.status IS NOT NULL
                     AND a.status != '')
            ORDER BY c.last_name, c.first_name";
        $rows = array();
        $res = $db->query($sql);
        while ($row = $db->fetchByAssoc($res)) {
            $rows[] = array(
                'registration_id' => $row['registration_id'],
                'registration_name' => $row['registration_name'],
                'name' => trim(($row['first_name'] ? $row['first_name'] . ' ' : '') . $row['last_name']),
            );
        }
        return $rows;
    }

    /**
     * Live session counters for the header chips and the action responses:
     * enrolled = rows shown in the enrolled section (pending + status-set
     * attendances, out-of-census-marked people included as they are displayed
     * there), marked = yes/partial, out = out-of-census people without
     * attendance, pending = in-census people without a status-set attendance.
     */
    public static function sessionCounters($sessionId)
    {
        if (!preg_match(self::UUID_RE, (string) $sessionId)) {
            return array('enrolled' => 0, 'marked' => 0, 'out' => 0, 'pending' => 0, 'attendances' => 0);
        }
        $roster = self::rosterAttendances($sessionId);
        $markedRows = 0;
        $marked = 0;
        $att = 0;
        foreach ($roster as $r) {
            $att++;
            // Core-generated attendances carry a NULL/'' status: pending marking
            if (!empty($r['attendance_status'])) {
                $markedRows++;
                if (in_array($r['attendance_status'], array('yes', 'partial'), true)) {
                    $marked++;
                }
            }
        }
        $pending = count(self::pending($sessionId));
        return array(
            'enrolled' => $markedRows + $pending,
            'marked' => $marked,
            'out' => count(self::outsideCensus($sessionId)),
            'pending' => $pending,
            'attendances' => $att,
        );
    }
}
