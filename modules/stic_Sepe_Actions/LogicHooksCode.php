<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class stic_Sepe_ActionsLogicHooks {

    public function before_save(&$bean, $event, $arguments) {
        global $app_list_strings;
        // Create name if empty
        if (empty($bean->name)) {
            if ($bean->load_relationship('stic_sepe_actions_contacts')) {      
                $related_beans = $bean->stic_sepe_actions_contacts->getBeans();
                $related_bean = array_pop($related_beans);
                $contact_name = $related_bean->first_name .' '.$related_bean->last_name;
            }
            $action_type = $app_list_strings['stic_sepe_action_types_list'][$bean->type];           
            $bean->name = $contact_name .' - '.$action_type .' - '. $bean->start_date;
        }
    }
}