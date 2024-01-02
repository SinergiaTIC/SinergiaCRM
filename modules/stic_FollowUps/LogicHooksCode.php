<?php
// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class stic_FollowUpsLogicHooks {

    public function before_save(&$bean, $event, $arguments) {

        if (empty($bean->name)) {
            global $app_list_strings;
            include_once 'SticInclude/Utils.php';
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Entry into LH');

            // Concatenate bean name using either the Family or the Contact name.
            $name = '';
            if (!empty($bean->stic_families_stic_followupsstic_families_ida)) {
                    $relatedBean = SticUtils::getRelatedBeanObject($bean, 'stic_families_stic_followups');
                    $name = $relatedBean->name;
            } elseif (!empty($bean->stic_followups_contactscontacts_ida)) {
                    $relatedBean = SticUtils::getRelatedBeanObject($bean, 'stic_followups_contacts');
                    $name = $relatedBean->first_name . ' ' . $relatedBean->last_name;
            }
            
            $start_date = date($GLOBALS["sugar_config"]["datef"], strtotime($bean->start_date));
            $bean->name = $name . ' - ' . $app_list_strings['stic_followups_types_list'][$bean->type] . ' - ' . $start_date;

        }
    }

}
