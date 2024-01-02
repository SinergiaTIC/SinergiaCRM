<?php

class AccountsLogicHooks
{

    public function after_save(&$bean, $event, $arguments)
    {
        // This code is added due to an error detected in SuiteCRM code.
        // https://github.com/salesagility/SuiteCRM/issues/8765
        // When a new Contact Relationship is added, the Contact is saved and the stic_relationship_type_c field overwritten with the values 
        // added with the SQL
        // Please, delete these lines when the issue is resolved
        if ($_REQUEST['child_field'] == 'stic_accounts_relationships_accounts') {
            include_once 'modules/stic_Accounts_Relationships/Utils.php';
            stic_Accounts_RelationshipsUtils::setRelationshipType($bean->id);
        }
        // End of Patch issue

        // Generate automatic Call
        if ($bean->stic_postal_mail_return_reason_c != $bean->fetched_row['stic_postal_mail_return_reason_c']) {
            include_once 'custom/modules/Accounts/SticUtils.php';
            AccountsUtils::generateCallFromReturnMailReason($bean);
        }
    }

    public function before_save(&$bean, $event, $arguments)
    {
        // Bring Incorpora location data, if there is any
        if ($bean->fetched_row['stic_incorpora_locations_id_c'] != $bean->stic_incorpora_locations_id_c) {
            include_once 'modules/stic_Incorpora_Locations/Utils.php';
            stic_Incorpora_LocationsUtils::transferLocationData($bean);
        }
    }
}
