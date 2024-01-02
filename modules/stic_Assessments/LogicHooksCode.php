<?php
// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class stic_AssessmentsLogicHooks {

    public function before_save(&$bean, $event, $arguments) {
        if (empty($bean->name)) {
            global $app_list_strings;
            include_once 'SticInclude/Utils.php';

            // Concatenate bean name using either the Family or the Contact name.
            $name = '';
            if (!empty($bean->stic_families_stic_assessmentsstic_families_ida)) {
                    $relatedBean = SticUtils::getRelatedBeanObject($bean, 'stic_families_stic_assessments');
                    $name = $relatedBean->name;
            } elseif (!empty($bean->stic_assessments_contactscontacts_ida)) {
                    $relatedBean = SticUtils::getRelatedBeanObject($bean, 'stic_assessments_contacts');
                    $name = $relatedBean->first_name . ' ' . $relatedBean->last_name;
            }
            $assessmentDate = date($GLOBALS["sugar_config"]["datef"], strtotime($bean->assessment_date));
            $bean->name = $name . ' - ' . $app_list_strings['stic_assessments_moments_list'][$bean->moment] . ' - ' . $assessmentDate;
        }
    }
}
