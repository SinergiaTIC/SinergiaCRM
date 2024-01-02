<?php
class stic_SessionsController extends SugarController {
    /**
     * This action is triggered when a record is created, added or unlinked from a subpanel.
     * If so, we will run the function updateFieldOnSubpanelChange to update the value of certain fields. 
     */ 
    public function action_SubPanelViewer() {
        require_once 'SticInclude/Utils.php';
        $fieldsToUpdate = array(
            'total_attendances' => array('type' => 'integer'),
            'validated_attendances' => array('type' => 'integer'),
        );
        SticUtils::updateFieldOnSubpanelChange('stic_Sessions', 'stic_attendances_stic_sessions', $fieldsToUpdate);
    }
}