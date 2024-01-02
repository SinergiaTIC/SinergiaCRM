<?php

class LeadsLogicHooks {

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
