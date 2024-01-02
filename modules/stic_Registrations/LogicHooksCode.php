<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class stic_RegistrationsLogicHooks {

    public function before_save(&$bean, $event, $arguments) {
        require_once 'SticInclude/Utils.php';

        // Set registration name
        if (empty($bean->name)) {
            $eventBean = SticUtils::getRelatedBeanObject($bean, 'stic_registrations_stic_events');
            $eventName = $eventBean->name;

            // Get the subject name
            $contactBean = SticUtils::getRelatedBeanObject($bean, 'stic_registrations_contacts');
            $subjectName .= !empty($bean->stic_registrations_contactscontacts_ida) ? $contactBean->first_name . ' ' . $contactBean->last_name : '';
            $subjectName .= !empty($bean->stic_registrations_accountsaccounts_ida) ? (!empty($subjectName) ? ' / ' : '') . SticUtils::getRelatedBeanObject($bean, 'stic_registrations_accounts')->name : '';
            $subjectName .= !empty($bean->stic_registrations_leadsleads_ida) ? SticUtils::getRelatedBeanObject($bean, 'stic_registrations_leads')->name : '';
            $bean->name = $subjectName . ' - ' . $eventName;
        }

    }

    public function after_save(&$bean, $event, $arguments) {
        include_once 'modules/stic_Registrations/Utils.php';

        // Recalculate ateendees totals, only if the status or attendees number change.
        if ($bean->status != $bean->fetched_row['status'] || $bean->attendees != $bean->fetched_row['attendees']) {
            stic_RegistrationsUtils::recalculateTotalAttendees($bean);
        }

        // Do only if status is "participates" or "confirmed"
        if ($bean->status == 'participates' || $bean->status == 'confirmed') {
            // The create attendances function is called in the following cases
            // 1) if it is a new registration
            // 2) if registration date has changed and it is not empty
            // 3) if registration status has changed
            if (empty($bean->fetched_row['id'])
                || ($bean->registration_date != $bean->fetched_row['registration_date'] && !empty($bean->registration_date))
                || $bean->fetched_row['status'] != $bean->status
            ) {
                // Try create attendances
                stic_RegistrationsUtils::createAttendancesForNewRegistration($bean);

            }
        }
    }

    public function manage_relationships(&$bean, $event, $arguments) {
        include_once 'modules/stic_Registrations/Utils.php';

        if ($arguments['related_module'] == 'stic_Events') {
            switch ($event) {

            case 'after_relationship_delete':
                // Recalculate totals attendes by status in the related event
                stic_RegistrationsUtils::recalculateTotalAttendees($bean, $arguments['related_id']);
                break;

            case 'after_relationship_add':
                // Recalculate totals attendes by status in the related event
                stic_RegistrationsUtils::recalculateTotalAttendees($bean, $arguments['related_id']);

                // Try create attendances
                stic_RegistrationsUtils::createAttendancesForNewRegistration($bean);
                break;

            default:
                break;
            }
        }

    }

}
