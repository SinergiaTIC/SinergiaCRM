<?php

require_once 'include/MVC/View/views/view.list.php';
require_once 'SticInclude/Views.php';

class stic_Bookings_CalendarViewList extends ViewList
{
    public function __construct()
    {
        parent::__construct();

    }
    public function preDisplay()
    {
        parent::preDisplay();

        SticViews::preDisplay($this);

    }

    public function display()
    {
        global $mod_strings, $current_user, $app_strings, $sugar_config;
        SticViews::display($this);

        $initialCalendarDate = $_REQUEST['start_date'] ? $_REQUEST['start_date'] : '';
        $initialCalendarDate = json_encode($initialCalendarDate);
        echo <<<SCRIPT
        <script>initialCalendarDate = $initialCalendarDate;</script>
    SCRIPT;

        // Define the default color for Calendar items in the calendar
        $defaultCalendarObjectColor = $sugar_config['stic_bookings_calendar_default_event_color'];
        $defaultCalendarObjectColor = json_encode($defaultCalendarObjectColor);
        echo <<<SCRIPT
        <script>defaultCalendarObjectColor = $defaultCalendarObjectColor;</script>
    SCRIPT;

        // Define the language of the calendar
        $lang = $_SESSION['authenticated_user_language'];
        $lang = explode('_', $lang);
        $lang = json_encode($lang[0]);
        echo <<<SCRIPT
        <script>lang = $lang;</script>
    SCRIPT;

        // Retrieve user configuration for the availability mode
        $userBean = new UserPreference($current_user);
        $availabilityMode = $userBean->getPreference('stic_bookings_calendar_availability_mode') == "true" ? true : false;
        $availabilityModeJson = json_encode($availabilityMode);

        echo <<<SCRIPT
        <script>availabilityMode = $availabilityModeJson;</script>
    SCRIPT;

        // Retrieve user configuration for the calendar view
        $calendarView = $userBean->getPreference('stic_bookings_calendar_view');
        $calendarViewJson = json_encode($calendarView);

        echo <<<SCRIPT
        <script>calendarView = $calendarViewJson;</script>
        SCRIPT;

        // Pass the resources to the template and javascript, to be used in the CSS and in the filters
        require_once "modules/stic_Bookings_Calendar/Utils.php";
        $resources = stic_Bookings_CalendarUtils::getAllResources();
        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('RESOURCESGROUP', $resources['resourcesArrayByGroup']);
        $resourcesArrayJson = json_encode($resources['resourcesArray']);
        echo <<<SCRIPT
        <script>resourcesGroupArray = $resourcesArrayJson;</script>
    SCRIPT;

        echo '<link href="' . getJSPath('SticInclude/vendor/fullcalendar/lib/main.min.css') . '" rel="stylesheet"/>';
        echo getVersionedScript("SticInclude/vendor/fullcalendar/lib/main.min.js");
        echo getVersionedScript("SticInclude/vendor/fullcalendar/lib/locales-all.min.js");
        echo getVersionedScript("modules/stic_Bookings_Calendar/Utils.js");

        $this->ss->display("modules/stic_Bookings_Calendar/tpls/filter.tpl");
        $this->ss->display("modules/stic_Bookings_Calendar/tpls/calendar.tpl");

    }
}
