<?php

class ContactsLogicHooks {
    public function before_save(&$bean, $event, $arguments) {
        // Calculate age
        if ($bean->birthdate != $bean->fetched_row['birthdate']) {
            include_once 'custom/modules/Contacts/SticUtils.php';
            $bean->stic_age_c = ContactsUtils::getAge($bean->birthdate);
        }

        // Bring Incorpora location data, if there is any
        if ($bean->fetched_row['stic_incorpora_locations_id_c'] != $bean->stic_incorpora_locations_id_c) {
            include_once 'modules/stic_Incorpora_Locations/Utils.php';
            stic_Incorpora_LocationsUtils::transferLocationData($bean);
        }
    }

    public function after_save(&$bean, $event, $arguments) {
        // This code is added due to an error detected in SuiteCRM code.
        // https://github.com/salesagility/SuiteCRM/issues/8765
        // When a new Contact Relationship is added, the Contact is saved and the stic_relationship_type_c field overwritten with the values
        // added with the SQL
        // Please, delete these lines when the issue is resolved
        if (isset($_REQUEST['child_field']) && $_REQUEST['child_field'] == 'stic_contacts_relationships_contacts') {
            include_once 'modules/stic_Contacts_Relationships/Utils.php';
            stic_Contacts_RelationshipsUtils::setRelationshipType($bean->id);
        }
        // End of Patch issue

        // Generate automatic Call
        if ($bean->stic_postal_mail_return_reason_c != $bean->fetched_row['stic_postal_mail_return_reason_c']) {
            include_once 'custom/modules/Contacts/SticUtils.php';
            ContactsUtils::generateCallFromReturnMailReason($bean);
        }
    }

    public function after_retrieve(&$bean, $event, $arguments) {
        // In order to avoid identification number loss due to GUI JS validation, set the right identification type
        // when this field is empty and the identification number is set.
        if (!empty($bean->stic_identification_number_c) && (empty($bean->stic_identification_type_c) || trim($bean->stic_identification_type_c) == '')) {
            include_once 'SticInclude/Utils.php';
            if (!SticUtils::isValidNIForNIE($bean->stic_identification_number_c)) {
                $bean->stic_identification_type_c = 'other';
            } else {
                $firstCharacter = strtoupper($bean->stic_identification_number_c[0]);
                if (is_numeric($firstCharacter) || in_array($firstCharacter, array('K', 'L', 'M'))) {
                    $bean->stic_identification_type_c = 'nif';
                } else {
                    $bean->stic_identification_type_c = 'nie';
                }
            };
            $bean->save();
        }
    }
}
