<?php
require_once 'modules/Calendar/controller.php';

class CustomCalendarController extends CalendarController
{
    /**
     * This action is triggered when new filters are added in the Calendar.
     * It saves the filters in the User's preferences
     */ 
    function action_SaveFilters()
    {
        global $current_user;
        $current_user->setPreference('calendar_stic_sessions_color', $_POST['stic_sessions_color']);
        $current_user->setPreference('calendar_stic_sessions_activity_type', $_POST['stic_sessions_activity_type']);
        $current_user->setPreference('calendar_stic_sessions_stic_events_type', $_POST['stic_sessions_stic_events_type']);
        $current_user->setPreference('calendar_stic_sessions_stic_events_id', $_POST['stic_sessions_stic_events_id']);
        $current_user->setPreference('calendar_stic_sessions_responsible_id', $_POST['stic_sessions_responsible_id']);
        $current_user->setPreference('calendar_stic_sessions_contacts_id', $_POST['stic_sessions_contacts_id']);
        $current_user->setPreference('calendar_stic_sessions_projects_id', $_POST['stic_sessions_projects_id']);
        $current_user->setPreference('calendar_stic_followups_color', $_POST['stic_followups_color']);
        $current_user->setPreference('calendar_stic_followups_type', $_POST['stic_followups_type']);
        $current_user->setPreference('calendar_stic_followups_contacts_id', $_POST['stic_followups_contacts_id']);
        $current_user->setPreference('calendar_stic_followups_projects_id', $_POST['stic_followups_projects_id']);
        if (isset($_REQUEST['day']) && !empty($_REQUEST['day'])) {
            header("Location: index.php?module=Calendar&action=index&view=".$_REQUEST['view']."&hour=0&day=".$_REQUEST['day']."&month=".$_REQUEST['month']."&year=".$_REQUEST['year']);
        } else {
            header("Location: index.php?module=Calendar&action=index");
        }

    }
}
