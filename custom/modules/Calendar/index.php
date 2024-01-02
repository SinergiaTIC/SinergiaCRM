<?php

/**
 * STIC 20210430 AAM - Custom index.php to load custom Classes.
 * See STIC comments
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

if (!ACLController::checkAccess('Calendar', 'list', true)) {
    ACLController::displayNoAccess(true);
}

// STIC changed requires to custom directory
require_once('custom/modules/Calendar/Calendar.php');
require_once('custom/modules/Calendar/CalendarDisplay.php');
// END STIC modification

// STIC 20210927 AAM - Added Shared Day option
$views = array("agendaDay" => array(),"basicDay" => array(), "basicWeek" => array(), "agendaWeek" => array(),"month" => array(), "sharedDay" => array(), "sharedMonth" => array(), "sharedWeek" => array());
// END STIC

global $cal_strings, $current_language;
$cal_strings = return_module_language($current_language, 'Calendar');

if (empty($_REQUEST['view'])) {
    if (isset($_SESSION['CALENDAR_VIEW']) && in_array($_SESSION['CALENDAR_VIEW'], $views)) {
        $_REQUEST['view'] = $_SESSION['CALENDAR_VIEW'];
    } else {
        $_REQUEST['view'] = SugarConfig::getInstance()->get('calendar.default_view', 'agendaWeek');
    }
}

    $_SESSION['CALENDAR_VIEW'] = $_REQUEST['view'];

// STIC Get Custom Class
$cal = new CustomCalendar($_REQUEST['view'], array(), $views);
// END STIC modification

// STIC 20210927 AAM - Added Shared Day option
if ($cal->view == "sharedDay" || $cal->view == "sharedMonth" || $cal->view == "sharedWeek") {
// END STIC
    $cal->init_shared();
    global $shared_user;
    $shared_user = BeanFactory::newBean('Users');
    foreach ($cal->shared_ids as $member) {
        $shared_user->retrieve($member);
        $cal->add_activities($shared_user);
    }
} else {
    if (array_key_exists($cal->view, $views)) {
        $cal->add_activities($GLOBALS['current_user']);
    }
}

if (array_key_exists($cal->view, $views)) {
    $cal->load_activities();
}

if (!empty($_REQUEST['print']) && $_REQUEST['print'] == 'true') {
    $cal->setPrint(true);
}

// STIC Get Custom Class
$display = new CustomCalendarDisplay($cal, "", $views);
// END STIC modification

$display->display_title();
// STIC 20210927 AAM - Added Shared Day option
if ($cal->view == "sharedDay" || $cal->view == "sharedMonth" || $cal->view == "sharedWeek") {
    $display->display_shared_html($cal->view);
}
$display->display_calendar_header();
$display->display();
$display->display_calendar_footer();
