<?php
// STIC-Custom AAES - 20261109 - Attendance ticker integrated view (IMPL-AAES-001, Fase B)
// Generic version for any instance (base of SCRM_marianao's fmarAttendanceTicker).
// Registered through custom/Extension/modules/stic_Attendances/Ext/ActionFileMap/:
//   index.php?module=stic_Attendances&action=AAESAttendanceTicker (auth + ACL by MVC)
// Dispatch by aaes_op (custom param names, never reserved action/module/record):
//   - ''          (GET)  -> screen 1: project selector
//   - 'sessions'  (GET)  -> screen 2: today sessions + past session search
//   - 'pass'      (GET)  -> screen 3: session roster
//   - 'ajax'      (POST) -> JSON actions with CSRF:
//        ping (pipeline check), mark / set_status / mark_all / outside_mark / undo
// Writes always POST+CSRF; GET never writes; every request is logged.
// END STIC-Custom AAES

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once __DIR__ . '/AAES_AttendanceTickerUtils.php';
require_once __DIR__ . '/AAES_AttendanceTickerQueries.php';
require_once __DIR__ . '/AAES_AttendanceTickerActions.php';

/**
 * Screen 1: project selector (RF-002, PD-2/PD-7/PD-8).
 * Projects with events having sessions today or within the configured
 * window (AAES_TICKER_SEARCH_DAYS; direct stic_events_project only, DD-4);
 * single project auto-selects (PD-7) — EXCEPT when the user arrives
 * explicitly from the sessions screen ("Cambiar de proyecto" link sends
 * from=sessions: the selector is shown even with a single project so the
 * link is never a dead end). None shows the empty state with direct access
 * to the session searcher.
 * @param array|null $projects pre-fetched rows (null = query the DB)
 */
function aaesTickerRenderProjectSelector($projects = null)
{
    $u = 'AAES_AttendanceTickerUtils';
    $q = 'AAES_AttendanceTickerQueries';
    // The window comes from the AAES_TICKER_SEARCH_DAYS setting (entity rule:
    // every fixed last-N-days search uses the same configured window)
    $days = $u::searchDays();
    if ($projects === null) {
        $projects = $q::projectsWithSessions($days);
    }

    // Single project with running activity: auto-select it (PD-7), unless the
    // user asked for the selector explicitly (from=sessions navigation)
    $forceSelector = isset($_REQUEST['from']) && (string) $_REQUEST['from'] === 'sessions';
    if (count($projects) === 1 && !$forceSelector) {
        header('Location: ' . $u::url(array($u::OP_PARAM => 'sessions', 'project_id' => $projects[0]['id'])));
        exit;
    }

    if (empty($projects)) {
        $body = '<div class="warn">' . $u::e($u::t('LBL_AAES_TICKER_NO_PROJECTS')) . '</div>'
            . '<div class="btnrow"><a class="btn pri" href="' . $u::e($u::url(array($u::OP_PARAM => 'sessions'))) . '">'
            . $u::e($u::t('LBL_AAES_TICKER_SEARCH_SESSIONS')) . '</a></div>';
        $u::renderPage($u::t('LBL_AAES_TICKER_TITLE'), $body, sprintf($u::t('LBL_AAES_TICKER_S1_SUBTITLE'), $days));
        return;
    }

    $cards = '';
    foreach ($projects as $p) {
        $todayClass = $p['sessions_today'] > 0 ? 'badge ok' : 'badge mut';
        $cards .= '<a class="tap" href="' . $u::e($u::url(array($u::OP_PARAM => 'sessions', 'project_id' => $p['id']))) . '">'
            . '<span class="nm">' . $u::e($p['name']) . '</span>'
            . '<span class="chips">'
            . '<span class="' . $todayClass . '">' . $u::e($u::t('LBL_AAES_TICKER_TODAY')) . ': <b>' . (int) $p['sessions_today'] . '</b></span>'
            . '<span class="badge mut">' . $u::e(sprintf($u::t('LBL_AAES_TICKER_LAST_DAYS'), $days)) . ': ' . (int) $p['sessions_window'] . '</span>'
            . '</span></a>';
    }
    $u::renderPage(
        $u::t('LBL_AAES_TICKER_TITLE'),
        '<div class="rowlist">' . $cards . '</div>',
        sprintf($u::t('LBL_AAES_TICKER_S1_SUBTITLE'), $days)
    );
}

/**
 * Session row (today or search result) as a tappable card linking to the
 * roster screen. Shared by both screen 2 sections.
 */
function aaesTickerSessionRow($row, $projectId, $showDate)
{
    $u = 'AAES_AttendanceTickerUtils';
    $params = array($u::OP_PARAM => 'pass', 'session_id' => $row['id']);
    if ($projectId !== '') {
        $params['project_id'] = $projectId;
    }
    $sub = array();
    if ($showDate) {
        $sub[] = $u::e($row['date']);
    }
    $sub[] = $u::e($row['start_time']);
    if (!empty($row['duration']) && (float) $row['duration'] > 0) {
        $sub[] = $u::e(rtrim(rtrim(number_format((float) $row['duration'], 2, '.', ''), '0'), '.') . 'h');
    }
    $sub[] = $u::e($row['event_name']);
    $badgeClass = $row['marked'] > 0 ? 'badge ok' : 'badge mut';
    return '<a class="tap" href="' . $u::e($u::url($params)) . '">'
        . '<span class="nm">' . $u::e($row['name']) . '</span>'
        . '<span class="sub">' . implode(' · ', $sub) . '</span>'
        . '<span class="' . $badgeClass . '">' . $u::e(sprintf($u::t('LBL_AAES_TICKER_ATTENDEES'), $row['marked'], $row['roster'])) . '</span>'
        . '</a>';
}

/**
 * Screen 2: today sessions + past session search (RF-003/RF-004, PD-4).
 * project_id is optional: without it the scope is every project (the empty
 * state of screen 1 links here); with it, the project scope (PD-8).
 */
function aaesTickerRenderSessionsScreen()
{
    $u = 'AAES_AttendanceTickerUtils';
    $q = 'AAES_AttendanceTickerQueries';

    $projectId = isset($_REQUEST['project_id']) ? (string) $_REQUEST['project_id'] : '';
    if (!preg_match($q::UUID_RE, $projectId)) {
        $projectId = '';
    }
    if ($projectId !== '') {
        $projectName = $q::projectName($projectId);
        if ($projectName === '') {
            // Stale project link: back to the selector
            $u::logAttempt('project_not_found', $projectId);
            header('Location: ' . $u::url());
            exit;
        }
        $subtitle = sprintf($u::t('LBL_AAES_TICKER_S2_PROJECT'), $projectName);
    } else {
        $subtitle = $u::t('LBL_AAES_TICKER_ALL_PROJECTS');
    }

    $days = $u::searchDays();
    $filters = $q::normalizeSearchFilters($_REQUEST, $days);
    $todaySessions = $q::todaySessions($projectId);
    // The searcher is only rendered when it can offer something: any past
    // session in the configured window, or no today sessions at all (then it
    // is the only access to sessions). With today sessions and nothing else
    // to offer it stays hidden (entity rule 2026-10-22, refined the same day).
    // Note: decided over the default window, never over the active filters,
    // so submitting a filter with no results never hides the section.
    $showSearcher = $q::hasPastSessions($projectId, $days) || count($todaySessions) === 0;
    $search = $showSearcher
        ? $q::searchSessions($filters, $projectId)
        : array('rows' => array(), 'total' => 0, 'page' => 1, 'pages' => 1);
    $events = $showSearcher ? $q::scopeEvents($projectId, $filters['from'], $filters['to']) : array();

    $body = '';

    // Back to the project selector (from=sessions: the selector renders even
    // with a single project, otherwise auto-select would bounce back here)
    $body .= '<div class="hd"><a href="' . $u::e($u::url(array('from' => 'sessions'))) . '">&laquo; ' . $u::e($u::t('LBL_AAES_TICKER_CHANGE_PROJECT')) . '</a></div>';

    // Section: today sessions
    $body .= '<div class="sec-title">' . $u::e($u::t('LBL_AAES_TICKER_TODAY_SESSIONS')) . '</div>';
    if (empty($todaySessions)) {
        $body .= '<div class="note">' . $u::e($u::t('LBL_AAES_TICKER_NO_TODAY')) . '</div>';
    } else {
        $rows = '';
        foreach ($todaySessions as $row) {
            $rows .= aaesTickerSessionRow($row, $projectId, false);
        }
        $body .= '<div class="rowlist">' . $rows . '</div>';
    }

    // Section: searcher (past sessions only: today's never repeat here).
    // Hidden when there are today sessions and no past sessions in the
    // default window (entity UX rule 2026-10-22).
    if ($showSearcher) {
        // Title carries the configured window (entity request: one line, no note below)
        $body .= '<div class="sec-title">' . $u::e(sprintf($u::t('LBL_AAES_TICKER_SEARCHER'), $days)) . '</div>';
        // One wrapping row: single line on desktop, two on phones (dates + rest)
        $form = '<form class="filters" method="get" action="index.php">'
            . '<input type="hidden" name="module" value="' . $u::e($u::MODULE) . '">'
            . '<input type="hidden" name="action" value="' . $u::e($u::ACTION) . '">'
            . '<input type="hidden" name="' . $u::OP_PARAM . '" value="sessions">'
            . ($projectId !== '' ? '<input type="hidden" name="project_id" value="' . $u::e($projectId) . '">' : '')
            . '<div class="frow">'
            . '<input type="date" name="from" value="' . $u::e($filters['from']) . '" title="' . $u::e($u::t('LBL_AAES_TICKER_SEARCH_FROM')) . '">'
            . '<input type="date" name="to" value="' . $u::e($filters['to']) . '" title="' . $u::e($u::t('LBL_AAES_TICKER_SEARCH_TO')) . '">'
            . '<input type="text" name="q" value="' . $u::e($filters['q']) . '" placeholder="' . $u::e($u::t('LBL_AAES_TICKER_SEARCH_Q')) . '">'
            . '<select name="event_id" title="' . $u::e($u::t('LBL_AAES_TICKER_SEARCH_EVENT')) . '">'
            . '<option value="">' . $u::e($u::t('LBL_AAES_TICKER_SEARCH_ALL_EVENTS')) . '</option>';
        foreach ($events as $ev) {
            $sel = $ev['id'] === $filters['event_id'] ? ' selected' : '';
            $form .= '<option value="' . $u::e($ev['id']) . '"' . $sel . '>' . $u::e($ev['name']) . '</option>';
        }
        $form .= '</select>'
            . '<button type="submit">' . $u::e($u::t('LBL_AAES_TICKER_SEARCH_BUTTON')) . '</button>'
            . '</div></form>';
        $body .= $form;

        // Search results
        if ($search['total'] === 0) {
            $body .= '<div class="note">' . $u::e($u::t('LBL_AAES_TICKER_NO_RESULTS')) . '</div>';
        } else {
            $rows = '';
            foreach ($search['rows'] as $row) {
                $rows .= aaesTickerSessionRow($row, $projectId, true);
            }
            $body .= '<div class="rowlist">' . $rows . '</div>';

            if ($search['pages'] > 1) {
                $base = array($u::OP_PARAM => 'sessions', 'from' => $filters['from'], 'to' => $filters['to']);
                if ($projectId !== '') {
                    $base['project_id'] = $projectId;
                }
                if ($filters['q'] !== '') {
                    $base['q'] = $filters['q'];
                }
                if ($filters['event_id'] !== '') {
                    $base['event_id'] = $filters['event_id'];
                }
                $prev = $filters['page'] > 1
                    ? '<a class="btn sec" href="' . $u::e($u::url(array_merge($base, array('page' => $filters['page'] - 1)))) . '">' . $u::e($u::t('LBL_AAES_TICKER_PREV')) . '</a>'
                    : '';
                $next = $filters['page'] < $search['pages']
                    ? '<a class="btn sec" href="' . $u::e($u::url(array_merge($base, array('page' => $filters['page'] + 1)))) . '">' . $u::e($u::t('LBL_AAES_TICKER_NEXT')) . '</a>'
                    : '';
                $body .= '<div class="pager">' . $prev
                    . '<span>' . $u::e(sprintf($u::t('LBL_AAES_TICKER_PAGE_OF'), $search['page'], $search['pages'])) . '</span>'
                    . $next . '</div>';
            }
        }
    } // end searcher section

    $u::renderPage($u::t('LBL_AAES_TICKER_TITLE'), $body, $subtitle);
}

/**
 * Screen 3: session roster (RF-005/RF-006/RF-007, PD-5/6/14/15/16).
 * Header with live counters, local name filter, enrolled section (pending
 * tap-to-mark, marked rows with the status selector + undo, "all came"
 * bulk action) and the out-of-census section (DD-3: any defined non-census
 * registration status) with the deliberate per-person "came". Every write
 * goes through the AJAX pipeline (POST + CSRF).
 */
function aaesTickerRenderPassScreen()
{
    $u = 'AAES_AttendanceTickerUtils';
    $q = 'AAES_AttendanceTickerQueries';

    $sessionId = isset($_REQUEST['session_id']) ? (string) $_REQUEST['session_id'] : '';
    if (!preg_match($q::UUID_RE, $sessionId)) {
        $u::logAttempt('pass_bad_session', $sessionId);
        aaesTickerRenderPassError('LBL_AAES_TICKER_NOT_FOUND');
        return;
    }
    $session = $q::sessionRow($sessionId);
    if ($session === '') {
        $u::logAttempt('session_not_found', $sessionId);
        aaesTickerRenderPassError('LBL_AAES_TICKER_NOT_FOUND');
        return;
    }
    if (date('Y-m-d', strtotime((string) $session['start_date'])) > $u::today()) {
        // Future sessions can never be passed (PD-10)
        $u::logAttempt('session_future_view', $sessionId);
        aaesTickerRenderPassError('LBL_AAES_TICKER_FUTURE');
        return;
    }

    $projectId = isset($_REQUEST['project_id']) ? (string) $_REQUEST['project_id'] : '';
    if (!preg_match($q::UUID_RE, $projectId)) {
        $projectId = '';
    }
    $back = $u::url($projectId !== '' ? array($u::OP_PARAM => 'sessions', 'project_id' => $projectId) : array($u::OP_PARAM => 'sessions'));

    $roster = $q::rosterAttendances($sessionId);
    $pendents = $q::pending($sessionId);
    $out = $q::outsideCensus($sessionId);
    $counters = $q::sessionCounters($sessionId);

    $body = '';

    // Session header card
    $dur = (float) $session['duration'] > 0
        ? ' · ' . $u::e(rtrim(rtrim(number_format((float) $session['duration'], 2, '.', ''), '0'), '.') . 'h')
        : '';
    $body .= '<div class="card">'
        . '<div class="hdrrow"><span class="grow" style="font-weight:700;font-size:17px;margin-bottom:8px">' . $u::e($session['name']) . $u::e($dur) . '</span></div>'
        . '<div class="chips">'
        . '<span class="chip" id="aaesEnrolled" data-tpl="' . $u::e($u::t('LBL_AAES_TICKER_C_ENROLLED')) . '">' . $u::e(sprintf($u::t('LBL_AAES_TICKER_C_ENROLLED'), $counters['enrolled'])) . '</span>'
        . '<span class="chip" id="aaesMarked" data-tpl="' . $u::e($u::t('LBL_AAES_TICKER_C_MARKED')) . '">' . $u::e(sprintf($u::t('LBL_AAES_TICKER_C_MARKED'), $counters['marked'])) . '</span>'
        . '<span class="chip" id="aaesOut" data-tpl="' . $u::e($u::t('LBL_AAES_TICKER_C_OUT')) . '">' . $u::e(sprintf($u::t('LBL_AAES_TICKER_C_OUT'), $counters['out'])) . '</span>'
        . '</div></div>';

    $body .= '<div class="hd"><a href="' . $u::e($back) . '">&laquo; ' . $u::e($u::t('LBL_AAES_TICKER_BACK_SESSIONS')) . '</a></div>';

    // Everything below is refreshed in place by the JS after each action
    $body .= '<div id="aaesRoster">'
        . '<input type="hidden" id="aaesCsrf" value="' . $u::e($u::csrfToken()) . '">'
        . '<input type="hidden" id="aaesSessionId" value="' . $u::e($sessionId) . '">'
        . '<input type="text" class="searchbar-input" id="aaesFilter" placeholder="' . $u::e($u::t('LBL_AAES_TICKER_LOCAL_SEARCH')) . '"'
        . ' style="width:100%;padding:12px;font-size:16px;border:1px solid #ccd;border-radius:10px;background:#fff">';

    // Section: enrolled people (title + bulk action on the same line)
    $body .= '<div class="sec-head">'
        . '<span class="sec-title">' . $u::e($u::t('LBL_AAES_TICKER_ENROLLED_SECTION')) . '</span>'
        . '<button type="button" class="btn pri small" id="aaesMarkAll" data-action="markall"'
        . ($counters['pending'] === 0 ? ' style="display:none"' : '')
        . ' data-confirm="' . $u::e(sprintf($u::t('LBL_AAES_TICKER_MARK_ALL_CONFIRM'), $counters['pending'])) . '">'
        . $u::e($u::t('LBL_AAES_TICKER_MARK_ALL')) . '</button>'
        . '</div>';

    $rows = '';
    foreach ($pendents as $p) {
        $rows .= '<div class="prow" data-name="' . $u::e($p['name']) . '">'
            . '<div class="tap pend" role="button" data-action="mark" data-reg="' . $u::e($p['registration_id']) . '" data-name="' . $u::e($p['name']) . '">'
            . '<span class="nm">' . $u::e($p['name']) . '</span>'
            . '<span class="badge mut">' . $u::e($u::t('LBL_AAES_TICKER_PENDING')) . '</span>'
            . '</div></div>';
    }
    foreach ($roster as $r) {
        // Core-generated empty-status attendances (status NULL/'') are pending
        // marking: those people are already covered by the pending list (same
        // rendering, tap fills their existing attendance with 'yes'); only
        // real markings render as marked rows here
        if (empty($r['attendance_status'])) {
            continue;
        }
        // Out-of-census badge (DD-3): any defined status other than the
        // in-census ones is an out-of-census marking carried over
        $regStatus = (string) $r['registration_status'];
        $isOut = $regStatus !== '' && !in_array($regStatus, array('confirmed', 'participates'), true);
        // Status visuals (row class, badge class, labels) from the base map (DD-2)
        $statusMap = $u::statusMap();
        $statusKey = (string) $r['attendance_status'];
        $vis = isset($statusMap[$statusKey]) ? $statusMap[$statusKey] : null;
        $rowCls = $vis !== null ? $vis['row'] : 'pend';
        $statusLabel = $vis !== null ? $u::t($vis['label']) : $statusKey;
        $badgeCls = $vis !== null ? 'badge ' . $vis['badge'] : 'badge mut';
        $outBadge = $isOut ? ' <span class="badge warn">' . $u::e($u::t('LBL_AAES_TICKER_OUT_SHORT')) . '</span>' : '';

        // Status selector: one button per status of the base map (in map order)
        $menu = '';
        foreach ($statusMap as $statusValue => $visItem) {
            $menu .= '<button type="button" data-action="set_status" data-status="' . $u::e($statusValue) . '"'
                . ($statusValue === $statusKey ? ' class="sel"' : '') . '>'
                . $u::e($u::t($visItem['short'])) . '</button>';
        }
        $rows .= '<div class="prow" data-name="' . $u::e($r['name']) . '">'
            . '<div class="tap ' . $rowCls . '" role="button" data-action="menu" data-att="' . $u::e($r['attendance_id']) . '" data-name="' . $u::e($r['name']) . '">'
            . '<span class="nm">' . $u::e($r['name']) . $outBadge . '</span>'
            . '<span class="' . $badgeCls . '">' . $u::e($statusLabel) . '</span>'
            . '<button type="button" class="mini danger" data-action="undo" data-att="' . $u::e($r['attendance_id']) . '"'
            . ' data-confirm="' . $u::e(sprintf($u::t('LBL_AAES_TICKER_UNDO_CONFIRM'), $r['name'])) . '">'
            . $u::e($u::t('LBL_AAES_TICKER_UNDO')) . '</button>'
            . '</div>'
            . '<div class="minimenu">' . $menu . '</div>'
            . '</div>';
    }
    $body .= $rows !== '' ? '<div class="rowlist">' . $rows . '</div>'
        : '<div class="note">' . $u::e($u::t('LBL_AAES_TICKER_NO_RESULTS')) . '</div>';

    // Section: out of census (DD-3; only when somebody is there)
    if (!empty($out)) {
        $body .= '<div class="sec-title">' . $u::e($u::t('LBL_AAES_TICKER_OUT_SECTION')) . ' <span class="badge warn">' . count($out) . '</span></div>';
        $wrows = '';
        foreach ($out as $w) {
            $wrows .= '<div class="prow" data-name="' . $u::e($w['name']) . '">'
                . '<div class="tap pend" role="button" data-action="out" data-reg="' . $u::e($w['registration_id']) . '" data-name="' . $u::e($w['name']) . '">'
                . '<span class="nm">' . $u::e($w['name']) . '</span>'
                . '<button type="button" class="mini yes" data-action="out">' . $u::e($u::t('LBL_AAES_TICKER_CAME')) . '</button>'
                . '</div></div>';
        }
        $body .= '<div class="rowlist">' . $wrows . '</div>';
    }

    $body .= '</div>'; // #aaesRoster
    $body .= aaesTickerInlineJs();

    $u::renderPage($u::t('LBL_AAES_TICKER_TITLE'), $body, $u::t('LBL_AAES_TICKER_S3_TITLE'));
}

/** Error page for the pass screen (bad/unknown/future session). */
function aaesTickerRenderPassError($labelKey)
{
    $u = 'AAES_AttendanceTickerUtils';
    $body = '<div class="errbox">' . $u::e($u::t($labelKey)) . '</div>'
        . '<div class="btnrow"><a class="btn pri" href="' . $u::e($u::url()) . '">'
        . $u::e($u::t('LBL_AAES_TICKER_HOME')) . '</a></div>';
    $u::renderPage($u::t('LBL_AAES_TICKER_TITLE'), $body);
}

/**
 * Inline JS for the roster: every write goes through the AJAX pipeline
 * (POST + CSRF); one action saves immediately (PD-16); after each success
 * the roster section is re-rendered in place (no full reload) and the live
 * chips are updated. Event delegation survives the DOM replacement.
 */
function aaesTickerInlineJs()
{
    $u = 'AAES_AttendanceTickerUtils';
    $errLabel = json_encode($u::t('LBL_AAES_TICKER_AJAX_ERROR'));
    $js = <<<'EOT'
<script>
(function () {
  'use strict';
  var busy = false;
  var epUrl = __VIEW_URL__;
  var errLabel = __AJAX_ERROR__;

  function toast(msg, kind) {
    var t = document.getElementById('aaesToast');
    if (!t) { t = document.createElement('div'); t.id = 'aaesToast'; t.className = 'toast'; document.body.appendChild(t); }
    t.textContent = msg;
    t.className = 'toast show ' + (kind || 'ok');
    setTimeout(function () { t.className = 'toast ' + (kind || 'ok'); }, 2600);
  }

  // Live chips: update from the action response counters (data-tpl holds the
  // server-rendered label template, %s is replaced with the fresh value)
  function setChips(c) {
    if (!c) { return; }
    var defs = {aaesEnrolled: c.enrolled, aaesMarked: c.marked, aaesOut: c.out};
    Object.keys(defs).forEach(function (id) {
      var el = document.getElementById(id);
      if (el && el.getAttribute('data-tpl')) { el.textContent = el.getAttribute('data-tpl').replace('%s', defs[id]); }
    });
  }

  function post(action, params) {
    if (busy) { return; }
    busy = true;
    var data = 'aaes_op=ajax&aaes_action=' + encodeURIComponent(action) + '&csrf=' + encodeURIComponent(document.getElementById('aaesCsrf').value);
    for (var k in params) { if (params.hasOwnProperty(k)) { data += '&' + k + '=' + encodeURIComponent(params[k]); } }
    fetch(epUrl, {method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: data, credentials: 'same-origin'})
      .then(function (r) { return r.json(); })
      .then(function (j) {
        toast(j.msg, j.ok ? 'ok' : 'err');
        if (j.ok) { setChips(j.counters); refreshRoster(); }
      })
      .catch(function () { toast(errLabel, 'err'); })
      .finally(function () { busy = false; });
  }

  // Re-render the roster in place (no full page reload); keeps the local
  // filter text and the "all came" state fresh from the server
  function refreshRoster() {
    var old = document.getElementById('aaesFilter');
    var q = old ? old.value : '';
    fetch(window.location.href, {credentials: 'same-origin'})
      .then(function (r) { return r.text(); })
      .then(function (html) {
        var doc = new DOMParser().parseFromString(html, 'text/html');
        var fresh = doc.getElementById('aaesRoster');
        var cur = document.getElementById('aaesRoster');
        if (fresh && cur) { cur.replaceWith(fresh); }
        var f = document.getElementById('aaesFilter');
        if (f) { f.value = q; applyFilter(q); }
      })
      .catch(function () {});
  }

  function norm(s) {
    return (s || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  }

  function applyFilter(q) {
    q = norm(q);
    document.querySelectorAll('#aaesRoster .prow').forEach(function (row) {
      var name = norm(row.getAttribute('data-name'));
      row.style.display = (!q || name.indexOf(q) !== -1) ? '' : 'none';
    });
  }

  document.addEventListener('input', function (ev) {
    if (ev.target && ev.target.id === 'aaesFilter') { applyFilter(ev.target.value); }
  });

  // Delegation on document: survives the roster DOM replacement
  document.addEventListener('click', function (ev) {
    var el = ev.target.closest ? ev.target.closest('[data-action]') : null;
    if (!el) { return; }
    var action = el.getAttribute('data-action');
    var sessionId = (document.getElementById('aaesSessionId') || {}).value || '';

    if (action === 'menu') {
      var menu = el.parentElement && el.parentElement.querySelector('.minimenu');
      if (menu) { menu.classList.toggle('open'); }
      return;
    }
    ev.preventDefault();
    var confirmMsg = el.getAttribute('data-confirm');
    if (confirmMsg && !window.confirm(confirmMsg)) { return; }

    if (action === 'mark') {
      post('mark', {registration_id: el.getAttribute('data-reg'), session_id: sessionId});
    } else if (action === 'out') {
      // Client action 'out' maps to the server action 'outside_mark' (DD-3)
      post('outside_mark', {registration_id: el.getAttribute('data-reg'), session_id: sessionId});
    } else if (action === 'set_status') {
      var att = el.closest('.prow').querySelector('.tap[data-att]').getAttribute('data-att');
      post('set_status', {attendance_id: att, status: el.getAttribute('data-status')});
    } else if (action === 'undo') {
      post('undo', {attendance_id: el.getAttribute('data-att')});
    } else if (action === 'markall') {
      post('mark_all', {session_id: sessionId});
    }
  });
})();
</script>
EOT;
    return str_replace(
        array('__VIEW_URL__', '__AJAX_ERROR__'),
        array(json_encode($u::url(array($u::OP_PARAM => 'ajax'))), $errLabel),
        $js
    );
}

/**
 * Render the unknown screen error page.
 */
function aaesTickerRenderUnknownOp()
{
    $u = 'AAES_AttendanceTickerUtils';
    $body = '<div class="errbox">' . $u::e($u::t('LBL_AAES_TICKER_UNKNOWN_OP')) . '</div>'
        . '<div class="btnrow"><a class="btn pri" href="' . $u::e($u::url()) . '">'
        . $u::e($u::t('LBL_AAES_TICKER_HOME')) . '</a></div>';
    $u::renderPage($u::t('LBL_AAES_TICKER_TITLE'), $body);
}

/**
 * Handle AJAX actions: POST + CSRF always required, JSON response.
 */
function aaesTickerHandleAjax()
{
    $u = 'AAES_AttendanceTickerUtils';
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $u::logAttempt('ajax_bad_method', $_SERVER['REQUEST_METHOD']);
        $u::jsonResponse(array('ok' => false, 'msg' => $u::t('LBL_AAES_TICKER_AJAX_ERROR')));
    }

    if (!$u::checkCsrf(isset($_POST['csrf']) ? $_POST['csrf'] : '')) {
        $u::logAttempt('csrf_fail', 'ajax');
        $u::jsonResponse(array('ok' => false, 'msg' => $u::t('LBL_AAES_TICKER_CSRF')));
    }

    // NOTE: the action name uses a namespaced request param (aaes_action):
    // a plain 'action' would override $_REQUEST['action'] = AAESAttendanceTicker
    // and break the module MVC dispatch.
    $action = isset($_POST['aaes_action']) ? (string) $_POST['aaes_action'] : '';
    $req = function ($key) {
        return isset($_POST[$key]) ? (string) $_POST[$key] : '';
    };
    switch ($action) {
        // Pipeline health check (harmless, no writes)
        case 'ping':
            $u::logAttempt('ping', 'ok');
            $u::jsonResponse(array('ok' => true, 'msg' => $u::t('LBL_AAES_TICKER_PING_OK')));

        // Roster actions: every action validates ids and writes by bean
        case 'mark':
            AAES_AttendanceTickerUtils::jsonResponse(
                AAES_AttendanceTickerActions::mark($req('registration_id'), $req('session_id'))
            );

        case 'set_status':
            AAES_AttendanceTickerUtils::jsonResponse(
                AAES_AttendanceTickerActions::set_status($req('attendance_id'), $req('status'))
            );

        case 'mark_all':
            AAES_AttendanceTickerUtils::jsonResponse(
                AAES_AttendanceTickerActions::mark_all($req('session_id'))
            );

        case 'outside_mark':
            AAES_AttendanceTickerUtils::jsonResponse(
                AAES_AttendanceTickerActions::outside_mark($req('registration_id'), $req('session_id'))
            );

        case 'undo':
            AAES_AttendanceTickerUtils::jsonResponse(
                AAES_AttendanceTickerActions::undo($req('attendance_id'))
            );

        default:
            $u::logAttempt('unknown_action', $action);
            $u::jsonResponse(array('ok' => false, 'msg' => $u::t('LBL_AAES_TICKER_UNKNOWN_ACTION')));
    }
}

// --------------------------------------------------------------------- dispatch

$op = isset($_REQUEST['aaes_op']) ? (string) $_REQUEST['aaes_op'] : '';

if ($op === 'ajax') {
    aaesTickerHandleAjax();
}

switch ($op) {
    // Screen 1: project selector
    case '':
        aaesTickerRenderProjectSelector();
        break;

    // Screen 2: today sessions + past session search
    case 'sessions':
        aaesTickerRenderSessionsScreen();
        break;

    // Screen 3: session roster
    case 'pass':
        aaesTickerRenderPassScreen();
        break;

    default:
        AAES_AttendanceTickerUtils::logAttempt('unknown_op', $op);
        aaesTickerRenderUnknownOp();
        break;
}
