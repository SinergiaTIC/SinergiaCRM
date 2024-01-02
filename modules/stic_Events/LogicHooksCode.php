<?php
// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class stic_EventsLogicHooks {

    public function after_save(&$bean, $event, $arguments) {

        // The percentage is only (re)calculated when total_hours changes its value
        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Total hours:' . $bean->total_hours);
        if ($bean->total_hours != $bean->fetched_row['total_hours']) {

            // Load the event bean with its related registrations
            $bean->load_relationship('stic_registrations_stic_events');

            // Calculate the attendance percentage for every related registration
            foreach ($bean->stic_registrations_stic_events->getBeans() as $registration) {
                if ($bean->total_hours != 0) {
                    $registration->attended_hours = empty($registration->attended_hours) ? 0 : $registration->attended_hours;
                    $registration->attendance_percentage = $registration->attended_hours / $bean->total_hours * 100;
                } else {
                    $registration->attendance_percentage = 0;
                }
                $registration->save();
            }
        }
    }
}
