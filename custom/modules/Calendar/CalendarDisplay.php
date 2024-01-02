<?php

require_once 'modules/Calendar/CalendarDisplay.php';

class CustomCalendarDisplay extends CalendarDisplay
{
    /**
     * colors of items on calendar
     */
    public $activity_colors = array(
        'Meetings' => array(
            'border' => '87719C',
            'body' => '6B5171',
            'text' => 'E5E5E5',
        ),
        'Calls' => array(
            'border' => '487166',
            'body' => '72B3A1',
            'text' => 'E5E5E5',
        ),
        'Tasks' => array(
            'border' => '515A71',
            'body' => '707C9C',
            'text' => 'E5E5E5',
        ),
        'Project' => array(
            'border' => '699DC9',
            'body' => '557FA3',
            'text' => 'E5E5E5',
        ),
        'ProjectTask' => array(
            'border' => '83C489',
            'body' => '659769',
            'text' => 'E5E5E5',
        ),
        'stic_Sessions' => array(
            'border' => 'C29B8A',
            'body' => '7D6459',
            'text' => 'E5E5E5',
        ),
        'stic_FollowUps' => array(
            'border' => 'A29B8E',
            'body' => 'AD645E',
            'text' => 'E5E5EE',
        ),
    );

    // Overriding the array to add Shared Day option
    public function get_date_info($view, $date_time)
    {
        $str = "";

        global $current_user;
        $dateFormat = $current_user->getUserDateTimePreferences();

        if ($view == 'month' || $view == 'sharedMonth') {
            for ($i = 0; $i < strlen($dateFormat['date']); $i++) {
                switch ($dateFormat['date'][$i]) {
                    case "Y":
                        $str .= " " . $date_time->year;
                        break;
                    case "m":
                        $str .= " " . $date_time->get_month_name();
                        break;
                }
            }
        } elseif ($view == 'agendaWeek' || $view == 'sharedWeek') {
            $first_day = $date_time;

            $first_day = CalendarUtils::get_first_day_of_week($date_time);
            $last_day = $first_day->get("+6 days");

            for ($i = 0; $i < strlen($dateFormat['date']); $i++) {
                switch ($dateFormat['date'][$i]) {
                    case "Y":
                        $str .= " " . $first_day->year;
                        break;
                    case "m":
                        $str .= " " . $first_day->get_month_name();
                        break;
                    case "d":
                        $str .= " " . $first_day->get_day();
                        break;
                }
            }
            $str .= " - ";
            for ($i = 0; $i < strlen($dateFormat['date']); $i++) {
                switch ($dateFormat['date'][$i]) {
                    case "Y":
                        $str .= " " . $last_day->year;
                        break;
                    case "m":
                        $str .= " " . $last_day->get_month_name();
                        break;
                    case "d":
                        $str .= " " . $last_day->get_day();
                        break;
                }
            }
        // STIC 20210430 AAM - Add Shared day 
        // STIC#263
        // } elseif ($view == 'agendaDay') {
        } elseif ($view == 'agendaDay' || $view == 'sharedDay') {
            $str .= $date_time->get_day_of_week() . " ";

            for ($i = 0; $i < strlen($dateFormat['date']); $i++) {
                switch ($dateFormat['date'][$i]) {
                    case "Y":
                        $str .= " " . $date_time->year;
                        break;
                    case "m":
                        $str .= " " . $date_time->get_month_name();
                        break;
                    case "d":
                        $str .= " " . $date_time->get_day();
                        break;
                }
            }
        } elseif ($view == 'mobile') {
            $str .= $date_time->get_day_of_week() . " ";

            for ($i = 0; $i < strlen($dateFormat['date']); $i++) {
                switch ($dateFormat['date'][$i]) {
                    case "Y":
                        $str .= " " . $date_time->year;
                        break;
                    case "m":
                        $str .= " " . $date_time->get_month_name();
                        break;
                    case "d":
                        $str .= " " . $date_time->get_day();
                        break;
                }
            }
        } elseif ($view == 'year') {
            $str .= $date_time->year;
        } else {
            //could be a custom view.
            $first_day = $date_time;

            $first_day = CalendarUtils::get_first_day_of_week($date_time);
            $last_day = $first_day->get("+6 days");

            for ($i = 0; $i < strlen($dateFormat['date']); $i++) {
                switch ($dateFormat['date'][$i]) {
                    case "Y":
                        $str .= " " . $first_day->year;
                        break;
                    case "m":
                        $str .= " " . $first_day->get_month_name();
                        break;
                    case "d":
                        $str .= " " . $first_day->get_day();
                        break;
                }
            }
            $str .= " - ";
            for ($i = 0; $i < strlen($dateFormat['date']); $i++) {
                switch ($dateFormat['date'][$i]) {
                    case "Y":
                        $str .= " " . $last_day->year;
                        break;
                    case "m":
                        $str .= " " . $last_day->get_month_name();
                        break;
                    case "d":
                        $str .= " " . $last_day->get_day();
                        break;
                }
            }
        }
        return $str;
    }

    /**
     * We override this function to include parameters related to the filters function. Including the filters value and the filters TPL.
     *
     * @param SugarSmarty $ss
     * @return void
     */
    protected function load_settings_template(&$ss)
    {
        global $current_user, $app_list_strings, $dictionary;
        parent::load_settings_template($ss);
        $sticSessionsColor = $current_user->getPreference('calendar_stic_sessions_color');
        $sticSessionsActivityType = $current_user->getPreference('calendar_stic_sessions_activity_type');
        $sticSessionsSticEventsType = $current_user->getPreference('calendar_stic_sessions_stic_events_type');
        $sticSessionsSticEventId = $current_user->getPreference('calendar_stic_sessions_stic_events_id');
        $sticSessionsResponsibleId = $current_user->getPreference('calendar_stic_sessions_responsible_id');
        $sticSessionsContactId = $current_user->getPreference('calendar_stic_sessions_contacts_id');
        $sticSessionsProjectId = $current_user->getPreference('calendar_stic_sessions_projects_id');
        $sticFollowUpsColor = $current_user->getPreference('calendar_stic_followups_color');
        $sticFollowUpsType = $current_user->getPreference('calendar_stic_followups_type');
        $sticFollowUpsContactId = $current_user->getPreference('calendar_stic_followups_contacts_id');
        $sticFollowUpsProjectId = $current_user->getPreference('calendar_stic_followups_projects_id');

        $sticSessionsColorOptions = get_select_options_with_id($app_list_strings[$dictionary['stic_Sessions']['fields']['color']['options']], $sticSessionsColor);
        $ss->assign('stic_sessions_color', $sticSessionsColorOptions);

        $sticSessionsActivityTypeOptions = get_select_options_with_id($app_list_strings['stic_sessions_activity_types_list'], $sticSessionsActivityType);
        $ss->assign('stic_sessions_activity_type', $sticSessionsActivityTypeOptions);

        $sticSessionsSticEventsTypeOptions = get_select_options_with_id($app_list_strings['stic_events_types_list'], $sticSessionsSticEventsType);
        $ss->assign('stic_sessions_stic_events_type', $sticSessionsSticEventsTypeOptions);

        if ($sticSessionsSticEventId) {
            $eventBean = BeanFactory::getBean('stic_Events', $sticSessionsSticEventId);
            $ss->assign('stic_sessions_stic_events_name', $eventBean->name);
            $ss->assign('stic_sessions_stic_events_id', $sticSessionsSticEventId);
        }
        if ($sticSessionsResponsibleId) {
            $contactBean = BeanFactory::getBean('Contacts', $sticSessionsResponsibleId);
            $ss->assign('stic_sessions_responsible_name', $contactBean->name);
            $ss->assign('stic_sessions_responsible_id', $sticSessionsResponsibleId);
        }
        if ($sticSessionsContactId) {
            $contactBean = BeanFactory::getBean('Contacts', $sticSessionsContactId);
            $ss->assign('stic_sessions_contacts_name', $contactBean->full_name);
            $ss->assign('stic_sessions_contacts_id', $sticSessionsContactId);
        }
        if ($sticSessionsProjectId) {
            $projectBean = BeanFactory::getBean('Project', $sticSessionsProjectId);
            $ss->assign('stic_sessions_projects_name', $projectBean->name);
            $ss->assign('stic_sessions_projects_id', $sticSessionsProjectId);
        }

        $sticFollowUpsColorOptions = get_select_options_with_id($app_list_strings[$dictionary['stic_FollowUps']['fields']['color']['options']], $sticFollowUpsColor);
        $ss->assign('stic_followups_color', $sticFollowUpsColorOptions);

        $sticFollowUpsTypeOptions = get_select_options_with_id($app_list_strings['stic_followups_types_list'], $sticFollowUpsType);
        $ss->assign('stic_followups_type', $sticFollowUpsTypeOptions);

        if ($sticFollowUpsContactId) {
            $contactBean = BeanFactory::getBean('Contacts', $sticFollowUpsContactId);
            $ss->assign('stic_followups_contacts_name', $contactBean->full_name);
            $ss->assign('stic_followups_contacts_id', $sticFollowUpsContactId);
        }
        if ($sticFollowUpsProjectId) {
            $projectBean = BeanFactory::getBean('Project', $sticFollowUpsProjectId);
            $ss->assign('stic_followups_projects_name', $projectBean->name);
            $ss->assign('stic_followups_projects_id', $sticFollowUpsProjectId);
        }
        if (
            $sticSessionsSticEventsType || $sticSessionsSticEventId || $sticSessionsResponsibleId || $sticSessionsContactId || $sticSessionsProjectId ||
            $sticSessionsColor || $sticSessionsActivityType || $sticFollowUpsColor || $sticFollowUpsContactId || $sticFollowUpsProjectId || $sticFollowUpsType
        ) {
            $ss->assign('applied_filters', true);
        }
        $filters = get_custom_file_if_exists("modules/Calendar/tpls/filters.tpl");
        $ss->assign("filters", $filters);
    }
}
