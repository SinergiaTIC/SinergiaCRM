<?php
// STIC-Custom AAES - 20261109 - Attendance ticker shared utils (IMPL-AAES-001, Fase A)
// Generic version for any instance (base of SCRM_marianao's fmarAttendanceTicker).
// CSRF, escaping, labels, logging, dates and the responsive base page renderer
// (self-contained CSS, no CDN). Everything reachable through custom/include/AAES/;
// no field, status or core change is required (DD-2/DD-4/DD-5 of the IMPL doc).
// END STIC-Custom AAES

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class AAES_AttendanceTickerUtils
{
    /** Entry module of the integrated view: asistencias. */
    const MODULE = 'stic_Attendances';

    /** Custom module action name (the integrated view itself). */
    const ACTION = 'AAESAttendanceTicker';
    /** Custom request parameter for screens/ops (never reserved names). */
    const OP_PARAM = 'aaes_op';
    /** Search window setting in stic_Settings (optional, days; default 30). */
    const SEARCH_DAYS_SETTING = 'AAES_TICKER_SEARCH_DAYS';
    /** Session key for the per-session CSRF token. */
    const CSRF_SESSION_KEY = 'AAES_ticker_csrf';

    // ==================================================================== csrf

    /** Per-session CSRF token for all POST actions (PD: GET never writes). */
    public static function csrfToken()
    {
        if (empty($_SESSION[self::CSRF_SESSION_KEY])) {
            $_SESSION[self::CSRF_SESSION_KEY] = md5(uniqid('AAES_ticker', true));
        }
        return $_SESSION[self::CSRF_SESSION_KEY];
    }

    public static function checkCsrf($token)
    {
        return !empty($token) && !empty($_SESSION[self::CSRF_SESSION_KEY])
            && hash_equals($_SESSION[self::CSRF_SESSION_KEY], (string) $token);
    }

    // ================================================================== dates

    /**
     * Today as Y-m-d using the PHP server local date, the same convention the
     * core stic_AttendancesUtils::createAttendances() uses when matching
     * DATE(sessions.start_date). Keeping the same convention guarantees the
     * ticker lists exactly the sessions the core generated attendances for.
     */
    public static function today()
    {
        return date('Y-m-d');
    }

    /** Date N days before today (Y-m-d), same convention as today(). */
    public static function daysAgo($days)
    {
        return date('Y-m-d', strtotime('-' . (int) $days . ' days'));
    }

    // =============================================================== statuses

    /**
     * Single source of truth for the attendance statuses offered by the
     * ticker: the four default SinergiaCRM statuses (DD-2). The ticker does
     * NOT read the instance list and does NOT support instance custom
     * statuses beyond these: every instance runs the default list.
     * Key order drives the status menu. 'marked' counting (SQL) stays on
     * the core criterion ('yes','partial') in the queries.
     * @return array status => array(label key, short label key, row class, badge class)
     */
    public static function statusMap()
    {
        return array(
            'yes' => array(
                'label' => 'LBL_AAES_TICKER_ST_YES',
                'short' => 'LBL_AAES_TICKER_ST_YES',
                'row' => 'done',
                'badge' => 'ok',
            ),
            'partial' => array(
                'label' => 'LBL_AAES_TICKER_ST_PARTIAL',
                'short' => 'LBL_AAES_TICKER_ST_PARTIAL',
                'row' => 'part',
                'badge' => 'warn',
            ),
            'no_justified' => array(
                'label' => 'LBL_AAES_TICKER_ST_NO_JUST',
                'short' => 'LBL_AAES_TICKER_ST_NO_JUST_S',
                'row' => 'abs',
                'badge' => 'err',
            ),
            'no_unjustified' => array(
                'label' => 'LBL_AAES_TICKER_ST_NO_UNJUST',
                'short' => 'LBL_AAES_TICKER_ST_NO_UNJUST_S',
                'row' => 'abs',
                'badge' => 'err',
            ),
        );
    }

    // ================================================================ settings

    /**
     * Searcher window in days: the AAES_TICKER_SEARCH_DAYS setting from
     * stic_Settings (read with the core stic_SettingsUtils::getSetting, as
     * entities do). Defaults to 30 when the record is missing or holds an
     * invalid value, and is capped at 365 to keep the queries sane. Cached
     * per request. The entity can widen the window (60, 90...) by editing
     * the setting, without any code change.
     * @return int
     */
    public static function searchDays()
    {
        static $days = null;
        if ($days !== null) {
            return $days;
        }
        $days = 30;
        if (!class_exists('stic_SettingsUtils') && file_exists('modules/stic_Settings/Utils.php')) {
            require_once 'modules/stic_Settings/Utils.php';
        }
        if (class_exists('stic_SettingsUtils')) {
            $value = stic_SettingsUtils::getSetting(self::SEARCH_DAYS_SETTING);
        } else {
            $value = false; // no CRM bootstrap (bench/CLI): default window
        }
        if ($value !== false && $value !== null && $value !== '') {
            $candidate = (int) $value;
            if ($candidate >= 1 && $candidate <= 365) {
                $days = $candidate;
            } else {
                self::logAttempt('search_days_invalid', (string) $value);
            }
        }
        return $days;
    }

    // =============================================================== timezone

    /**
     * Display timezone: the logged-in user's preference (the CRM convention),
     * the instance default when unset, UTC as last resort. stic_Sessions
     * start_date is a GMT datetime column, so any displayed date/time must be
     * converted with dbToLocal().
     */
    public static function userTimeZone()
    {
        $tzName = '';
        if (!empty($GLOBALS['current_user']) && is_object($GLOBALS['current_user'])
            && method_exists($GLOBALS['current_user'], 'getPreference')) {
            $tzName = (string) $GLOBALS['current_user']->getPreference('timezone');
        }
        if ($tzName === '' && !empty($GLOBALS['sugar_config']['default_timezone'])) {
            $tzName = (string) $GLOBALS['sugar_config']['default_timezone'];
        }
        try {
            return new DateTimeZone($tzName !== '' ? $tzName : 'UTC');
        } catch (Exception $e) {
            return new DateTimeZone('UTC');
        }
    }

    /**
     * Page language code resolved for the <html lang> attribute: the user
     * preference when available, the instance default otherwise, 'es_ES'
     * as last resort (generic build; no Marianao hardcode).
     * @return string
     */
    public static function pageLang()
    {
        $lang = '';
        if (!empty($GLOBALS['current_user']) && is_object($GLOBALS['current_user'])
            && method_exists($GLOBALS['current_user'], 'getPreference')) {
            $lang = (string) $GLOBALS['current_user']->getPreference('language');
        }
        if ($lang === '' && !empty($GLOBALS['sugar_config']['default_language'])) {
            $lang = (string) $GLOBALS['sugar_config']['default_language'];
        }
        if ($lang === '') {
            $lang = 'es_ES';
        }
        // Normalize variants like ca_ES.UTF8?/en_us patterns to a lang tag
        $lang = preg_replace('/\.[^.]*$/', '', $lang);
        if (strcasecmp($lang, 'en_us') === 0) {
            $lang = 'en-US';
        } else {
            $parts = explode('_', $lang);
            if (count($parts) === 2) {
                $lang = strtolower($parts[0]) . '-' . strtoupper($parts[1]);
            }
        }
        return $lang;
    }

    /**
     * Convert a raw DB (GMT) datetime column value into a user-local string
     * for display. Listing filters keep using the raw columns (core
     * convention); only the rendered values go through this.
     */
    public static function dbToLocal($dbDateTime, $format = 'H:i')
    {
        if (empty($dbDateTime)) {
            return '';
        }
        try {
            $dt = new DateTime((string) $dbDateTime, new DateTimeZone('UTC'));
            $dt->setTimezone(self::userTimeZone());
            return $dt->format($format);
        } catch (Exception $e) {
            return (string) $dbDateTime;
        }
    }

    // ==================================================================== log

    /**
     * Log a ticker action or attempt for traceability (REQ RF-007/RNF-007).
     * @param string $type short tag (mark, outside_mark, undo, csrf_fail, ...)
     * @param string $detail
     * @param array $context
     */
    public static function logAttempt($type, $detail = '', $context = array())
    {
        if (isset($GLOBALS['log']) && is_object($GLOBALS['log'])) {
            $GLOBALS['log']->stic("AAES TICKER [$type] $detail " . json_encode($context));
        }
    }

    // =================================================================== html

    /** Escape a value for HTML output. */
    public static function e($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    /** Translate an app_strings label, falling back to the key itself. */
    public static function t($key)
    {
        $text = translate($key, '');
        return $text === $key ? $key : $text;
    }

    /** Integrated-view URL builder (GET screens, shareable links). */
    public static function url($params = array())
    {
        $params['module'] = self::MODULE;
        $params['action'] = self::ACTION;
        return 'index.php?' . http_build_query($params);
    }

    /** Send a JSON response for AJAX actions and stop. */
    public static function jsonResponse($res)
    {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($res);
        exit;
    }

    /**
     * Base responsive page (self-contained CSS, no CDN: kiosk/tablet friendly).
     * Shared chrome: title bar with "Back to the module" link and optional
     * subtitle. Language attribute resolved per user (DD-7).
     */
    public static function renderPage($title, $bodyHtml, $subtitle = '')
    {
        header('Content-Type: text/html; charset=UTF-8');
        $sub = $subtitle !== '' ? '<div class="sub">' . self::e($subtitle) . '</div>' : '';
        echo '<!DOCTYPE html><html lang="' . self::e(self::pageLang()) . '"><head><meta charset="UTF-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">'
            . '<meta name="robots" content="noindex"><title>' . self::e($title) . '</title>'
            . '<style>'
            . '*{box-sizing:border-box;margin:0;padding:0}'
            . 'body{font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;background:#f4f6f8;color:#223}'
            . '.wrap{max-width:640px;margin:0 auto;padding:16px;min-height:100vh;display:flex;flex-direction:column;gap:14px}'
            . '.hd{display:flex;justify-content:space-between;align-items:center;font-size:15px;color:#556}'
            . '.hd a{color:#0b57d0;text-decoration:none;font-weight:600}'
            . 'h1{font-size:21px;text-align:center;margin:2px 0}'
            . '.sub{font-size:14px;color:#556;text-align:center;margin-bottom:4px}'
            . '.card{background:#fff;border-radius:16px;padding:24px 16px;text-align:center;'
            . 'box-shadow:0 2px 10px rgba(0,0,0,.08)}'
            . '.sec-title{font-size:16px;font-weight:700;color:#334;margin-top:18px;margin-bottom:8px}'
            . '.sec-head{display:flex;align-items:center;justify-content:space-between;gap:10px;'
            . 'margin-top:18px;margin-bottom:8px}'
            . '.sec-head .sec-title{margin:0}'
            . '.sec-head + .rowlist,.sec-title + .rowlist{margin-top:10px}'
            . '.btn.small{padding:9px 14px;font-size:14px}'
            . '.note{background:#e8f0fe;color:#174ea6;border-radius:10px;padding:12px;font-size:14px;margin:12px 0;text-align:left}'
            . '.warn{background:#fef7e0;color:#8a6100;padding:14px;border-radius:10px;font-size:15px;text-align:center}'
            . '.errbox{background:#fdecea;color:#b3261e;padding:16px;border-radius:10px;font-size:15px;text-align:center}'
            . '.btnrow{display:flex;gap:10px;flex-wrap:wrap;justify-content:center}'
            . '.btn{display:inline-block;border:none;border-radius:12px;padding:13px 18px;font-size:15px;'
            . 'font-weight:700;text-decoration:none;cursor:pointer;-webkit-appearance:none;appearance:none}'
            . '.btn.pri{background:#0b57d0;color:#fff}.btn.sec{background:#e8eaed;color:#223}'
            . '.btn.danger{background:#c62828;color:#fff}'
            . '.tap{width:100%;text-align:left;background:#fff;color:#223;border:none;border-radius:14px;'
            . 'padding:14px 16px;-webkit-appearance:none;appearance:none;text-decoration:none;'
            . 'box-shadow:0 2px 10px rgba(0,0,0,.08);cursor:pointer;display:flex;gap:12px;align-items:center;'
            . 'min-height:64px}'
            . '.tap:active{background:#eef3fd}'
            . '.tap .nm{font-weight:700;font-size:16px;color:#223;flex:1}'
            . '.tap .sub{font-size:13px;color:#556}'
            . '.tap.pend{border-left:6px solid #c8ccd4}'
            . '.tap.done{border-left:6px solid #2e7d32}'
            . '.tap.part{border-left:6px solid #e09112}'
            . '.tap.abs{border-left:6px solid #c62828}'
            . '.tap .mini{border:none;border-radius:9px;padding:9px 13px;font-size:13px;font-weight:700;cursor:pointer;'
            . '-webkit-appearance:none;appearance:none}'
            . '.mini.yes{background:#2e7d32;color:#fff}.mini.sec{background:#e8eaed;color:#223}'
            . '.mini.danger{background:#c62828;color:#fff}'
            . '.badge{display:inline-block;font-size:11px;font-weight:700;border-radius:20px;padding:3px 9px}'
            . '.badge.ok{background:#e6f4ea;color:#1b5e20}.badge.err{background:#fdecea;color:#b3261e}'
            . '.badge.warn{background:#fef7e0;color:#8a6100}.badge.mut{background:#eef1f4;color:#556}'
            . '.chips{display:flex;gap:8px;flex-wrap:wrap;justify-content:center}'
            . '.chip{background:#fff;border-radius:20px;padding:8px 14px;font-size:13px;font-weight:700;'
            . 'color:#334;box-shadow:0 2px 8px rgba(0,0,0,.07)}'
            . '.chip b{color:#0b57d0}'
            . '.rowlist{display:flex;flex-direction:column;gap:9px}'
            . '.searchbar{display:flex;gap:8px}'
            . '.searchbar input,.searchbar select{flex:1;min-width:0;padding:12px;font-size:16px;'
            . 'border:1px solid #ccd;border-radius:10px;background:#fff}'
            . '.searchbar button{padding:12px 16px;font-size:15px;font-weight:700;border:none;border-radius:10px;'
            . 'background:#0b57d0;color:#fff;cursor:pointer}'
            . '.filters{display:flex;flex-direction:column;gap:10px;background:#fff;border-radius:14px;'
            . 'padding:14px;box-shadow:0 2px 10px rgba(0,0,0,.08)}'
            // One wrapping row: all filters fit a single line on desktop (~640px);
            // on phones they wrap into two (dates pair + text/event/button).
            . '.frow{display:flex;gap:8px;flex-wrap:wrap;align-items:stretch}'
            . '.frow input,.frow select{padding:12px;font-size:16px;border:1px solid #ccd;'
            . 'border-radius:10px;background:#fff;color:#223;min-width:0}'
            . '.frow input[type=date]{flex:1 1 95px}'
            . '.frow input[type=text]{flex:1 1 110px}'
            . '.frow select{flex:1 1 110px}'
            . '.frow button{flex:0 0 auto;min-width:64px;padding:10px 12px;font-size:14px;'
            . 'background:#0b57d0;color:#fff;font-weight:700;border:none;border-radius:10px;cursor:pointer}'
            . '.pager{display:flex;gap:10px;justify-content:center;align-items:center;font-size:14px;color:#556}'
            . '.prow{background:#fff;border-radius:14px;box-shadow:0 2px 10px rgba(0,0,0,.08);overflow:hidden}'
            . '.prow .tap{box-shadow:none;border-radius:0;min-height:56px}'
            . '.prow .minimenu{display:none;gap:8px;padding:0 12px 12px;flex-wrap:wrap}'
            . '.prow .minimenu.open{display:flex}'
            . '.prow .minimenu button{flex:1 1 90px;min-width:84px;padding:10px 8px;font-size:13px;font-weight:700;'
            . 'border:none;border-radius:9px;cursor:pointer;-webkit-appearance:none;appearance:none}'
            . '.prow .minimenu button.sel{background:#0b57d0;color:#fff}'
            . '.prow .minimenu button:not(.sel){background:#e8eaed;color:#223}'
            . '.hdrrow{display:flex;align-items:center;gap:8px;flex-wrap:wrap}'
            . '.hdrrow .grow{flex:1}'
            . '.toast{position:fixed;left:50%;bottom:24px;transform:translateX(-50%);max-width:92%;'
            . 'border-radius:12px;padding:14px 18px;font-size:15px;font-weight:700;color:#fff;'
            . 'box-shadow:0 4px 16px rgba(0,0,0,.25);opacity:0;transition:opacity .2s;z-index:50}'
            . '.toast.show{opacity:1}'
            . '.toast.ok{background:#2e7d32}.toast.err{background:#c62828}.toast.warn{background:#b26a00}'
            . '.hidden{display:none!important}'
            . '</style></head><body>'
            . '<div class="wrap">'
            . '<div class="hd"><a href="' . self::e('index.php?module=' . self::MODULE . '&action=index') . '">'
            . self::e(self::t('LBL_AAES_TICKER_BACK_MODULE')) . '</a>'
            . '<span>' . self::e(self::t('LBL_AAES_TICKER_HOME')) . '</span></div>'
            . '<h1>' . self::e($title) . '</h1>' . $sub
            . $bodyHtml
            . '</div></body></html>';
    }
}
