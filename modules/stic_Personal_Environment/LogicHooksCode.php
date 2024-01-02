<?php
// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class stic_Personal_EnvironmentLogicHooks {

    public function before_save(&$bean, $event, $arguments) {
        if (empty($bean->name)) {
            global $app_list_strings;
            include_once 'SticInclude/Utils.php';
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Entry into LH');

            // Concatenate bean name using either the Family or the Contact name.
            $name = '';
            if (!empty($bean->stic_families_stic_personal_environmentstic_families_ida)) {
                    $relatedBean = SticUtils::getRelatedBeanObject($bean, 'stic_families_stic_personal_environment');
                    $name = $relatedBean->name;
            } elseif (!empty($bean->stic_personal_environment_contactscontacts_ida)) {
                    $relatedBean = SticUtils::getRelatedBeanObject($bean, 'stic_personal_environment_contacts');
                    $name = $relatedBean->first_name . ' ' . $relatedBean->last_name;
            }

            if (!empty($bean->stic_personal_environment_contacts_1contacts_ida)) {
                $relatedContactBean = SticUtils::getRelatedBeanObject($bean, 'stic_personal_environment_contacts_1');
                $concat_field_1 = $relatedContactBean->first_name . " " . $relatedContactBean->last_name;
            } else if (!empty($bean->stic_personal_environment_accountsaccounts_ida)) {
                $relatedAccountBean = SticUtils::getRelatedBeanObject($bean, 'stic_personal_environment_accounts');
                $concat_field_1 = $relatedAccountBean->name;
            }

            $concat_field_2 = $app_list_strings['stic_personal_environment_relationships_list'][$bean->relationship_type];
            $bean->name = $concat_field_1 . " - " . $concat_field_2 . " - " . $name;
        }
    }
}
