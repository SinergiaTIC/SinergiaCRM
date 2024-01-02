<?php
//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class stic_EventsController extends SugarController {

    /**
     * Show template view of periodic session generation assistant
     *
     * @return void
     */
    public function action_showSessionAssistant() {
        $this->view = "sessionassistant";
    }

    /**
     *  This action is invoker through periodic session wizard.
     *
     * @return void
     */
    public function action_createPeriodicSessions() {
        include_once 'modules/stic_Events/Utils.php';
        stic_EventsUtils::createPeriodicSessions();
    }

    /**
     * This action is invoked through the button "Register LPO members to event, using a popup to select the target list
     *
     * @return void
     */
    public function action_addTargetListToEventRegistrations() {
        include_once 'modules/stic_Events/Utils.php';
        stic_EventsUtils::addTargetListToEventRegistrations($_REQUEST["prospectListId"], $this->bean);
    }

    /**
     * This action is triggered when a record is created, added or unlinked from a subpanel.
     * If so, we will run the function updateFieldOnSubpanelChange to update the value of certain fields. 
     */ 
    public function action_SubPanelViewer() {
        require_once 'SticInclude/Utils.php';
        $fieldsToUpdateRegistrations = array(
            'status_confirmed' => array('type' => 'integer'),
            'status_not_invited' => array('type' => 'integer'),
            'status_took_part' => array('type' => 'integer'),
            'status_rejected' => array('type' => 'integer'),
            'status_invited' => array('type' => 'integer'),
            'status_maybe' => array('type' => 'integer'),
            'status_didnt_take_part' => array('type' => 'integer'),
            'status_drop_out' => array('type' => 'integer'),
        );
        SticUtils::updateFieldOnSubpanelChange('stic_Events', 'stic_registrations_stic_events', $fieldsToUpdateRegistrations);

        $fieldsToUpdateSessions = array(
            'total_hours' => array('type' => 'decimal'),
        );
        SticUtils::updateFieldOnSubpanelChange('stic_Events', 'stic_sessions_stic_events', $fieldsToUpdateSessions);
    }

}
