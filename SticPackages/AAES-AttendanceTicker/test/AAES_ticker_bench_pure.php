<?php
// STIC-Custom AAES - 20261109 - Bench test for pure logic of the AAES Attendance Ticker (IMPL-AAES-001, Fase E)
// No DB / no CRM bootstrap needed: defines sugarEntry, stubs the few global
// helpers the utils use, and exercises the pure parts (filters normalization,
// LIKE escaping, page language, URL building, status map, setting fallback).
// Usage: php SticPackages/AAES-AttendanceTicker/test/AAES_ticker_bench_pure.php
// END STIC-Custom AAES

define('sugarEntry', true);

$GLOBALS['log'] = new class {
    public function stic($msg) {}
};
$GLOBALS['current_user'] = null; // no user: pageLang falls back to default
$GLOBALS['sugar_config'] = array('default_language' => 'es_ES', 'default_timezone' => 'Europe/Madrid');
$GLOBALS['aaes_labels'] = array();

function translate($key, $module = '') { return isset($GLOBALS['aaes_labels'][$key]) ? $GLOBALS['aaes_labels'][$key] : $key; }

require_once __DIR__ . '/../../../custom/include/AAES/AttendanceTicker/AAES_AttendanceTickerUtils.php';
require_once __DIR__ . '/../../../custom/include/AAES/AttendanceTicker/AAES_AttendanceTickerQueries.php';

$ok = 0;
$fail = 0;
function check($name, $cond) {
    global $ok, $fail;
    if ($cond) { $ok++; } else { $fail++; echo "FAIL: $name\n"; }
}

// ---------------------------------------------------------------- status map
$map = AAES_AttendanceTickerUtils::statusMap();
check('statusMap: exactly the 4 default statuses',
    array_keys($map) === array('yes', 'partial', 'no_justified', 'no_unjustified'));
check('statusMap: no instance custom statuses (no "no", no "partial_justified")',
    !isset($map['no']) && !isset($map['partial_justified']));
$visuals = array('yes' => array('done', 'ok'), 'partial' => array('part', 'warn'), 'no_justified' => array('abs', 'err'), 'no_unjustified' => array('abs', 'err'));
foreach ($visuals as $st => $expect) {
    check("statusMap visuals $st", $map[$st]['row'] === $expect[0] && $map[$st]['badge'] === $expect[1] && !empty($map[$st]['label']) && !empty($map[$st]['short']));
}

// ---------------------------------------------------------------- like escaping
$q = 'AAES_AttendanceTickerQueries';
check('likePattern wraps with %', $q::likePattern('abc') === '%abc%');
check('likePattern escapes % _ \\', $q::likePattern('a%b_c\\d') === '%a\\%b\\_c\\\\d%');
check('likePattern attacks are literal',
    $q::likePattern('%%%') === '%\%\%\%%' && $q::likePattern('___') === '%\_\_\_%');
check('likePattern is UTF-8 safe', $q::likePattern('ÀÈÌ') === '%ÀÈÌ%');

// ------------------------------------------------------- normalizeSearchFilters
$u = 'AAES_AttendanceTickerUtils';
$today = $u::today();
$fromDef = $u::daysAgo(30);
$yest = $u::daysAgo(1);

$f = $q::normalizeSearchFilters(array());
check('filters: defaults (from=-30d, to clamped to yesterday)',
    $f['from'] === $fromDef && $f['to'] === $yest && $f['q'] === '' && $f['event_id'] === '' && $f['page'] === 1);

$f = $q::normalizeSearchFilters(array('from' => $u::daysAgo(400), 'to' => $u::daysAgo(0)), 30);
check('filters: window clamp (from forced to window start, to clamped to yesterday)',
    $f['from'] === $fromDef && $f['to'] === $yest);

$f = $q::normalizeSearchFilters(array('from' => $u::daysAgo(5)), 30);
check('filters: missing to defaults to today then clamps to yesterday', $f['to'] === $yest && $f['from'] === $u::daysAgo(5));

$f = $q::normalizeSearchFilters(array('from' => $u::daysAgo(1), 'to' => $u::daysAgo(10)), 30);
check('filters: swapped range normalized', $f['from'] === $u::daysAgo(10) && $f['to'] === $u::daysAgo(1));

$f = $q::normalizeSearchFilters(array('from' => 'not-a-date', 'to' => '2026-13-45'), 30);
check('filters: bogus dates fall back to defaults', $f['from'] === $fromDef && $f['to'] === $yest);

$f = $q::normalizeSearchFilters(array('q' => '  pep  ', 'event_id' => 'NOT-A-UUID', 'page' => '-3'));
check('filters: q trimmed, bad event_id dropped, page floor 1',
    $f['q'] === 'pep' && $f['event_id'] === '' && $f['page'] === 1);

$uuid = 'b10a46ce-9dbc-fa97-2355-5ca399094453';
$f = $q::normalizeSearchFilters(array('event_id' => strtoupper($uuid), 'page' => '2'));
check('filters: uppercase uuid accepted, page kept 2', $f['event_id'] === strtoupper($uuid) && $f['page'] === 2);

$f = $q::normalizeSearchFilters(array('page' => 99));
check('filters: page beyond range kept (clamped later against pages)', $f['page'] === 99);

// ---------------------------------------------------------------- pageLang
check('pageLang: falls back to default_language when no user',
    AAES_AttendanceTickerUtils::pageLang() === 'es-ES');

$GLOBALS['current_user'] = new class {
    public function getPreference($k) { return $k === 'language' ? 'ca_ES.UTF-8' : ''; }
};
check('pageLang: user preference wins and is normalized (strip .UTF-8)',
    AAES_AttendanceTickerUtils::pageLang() === 'ca-ES');

$GLOBALS['current_user'] = new class {
    public function getPreference($k) { return $k === 'language' ? 'en_us' : ''; }
};
check('pageLang: en_us -> en-US', AAES_AttendanceTickerUtils::pageLang() === 'en-US');

$GLOBALS['sugar_config'] = array(); // nothing at all
$GLOBALS['current_user'] = null;    // and no user preference either
check('pageLang: ultimate fallback es_ES', AAES_AttendanceTickerUtils::pageLang() === 'es-ES');

// ---------------------------------------------------------------- URL builder
$GLOBALS['current_user'] = null;
$url = 'AAES_AttendanceTickerUtils';
check('url: base builder (module+action)',
    $url::url() === 'index.php?module=stic_Attendances&action=AAESAttendanceTicker');
check('url: request params cannot override module/action',
    strpos($url::url(array('module' => 'Evil', 'action' => 'Evil')), '=Evil') === false);

// ---------------------------------------------------------------- constants
check('constants: setup', $url::MODULE === 'stic_Attendances' && $url::ACTION === 'AAESAttendanceTicker'
    && $url::OP_PARAM === 'aaes_op' && $url::SEARCH_DAYS_SETTING === 'AAES_TICKER_SEARCH_DAYS');
check('uuid regex: accepts a v4 uuid', preg_match($q::UUID_RE, $uuid) === 1
    && preg_match($q::UUID_RE, strtoupper($uuid)) === 1 && preg_match($q::UUID_RE, 'NOT-A-UUID') === 0);

echo "\nAAES TICKER BENCH (pure): $ok ok, $fail fail\n";
exit($fail === 0 ? 0 : 1);
