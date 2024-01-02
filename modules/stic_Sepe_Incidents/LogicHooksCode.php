<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class stic_Sepe_IncidentsLogicHooks {

    public function before_save(&$bean, $event, $arguments) {
        global $app_list_strings;
        // Create name if empty
        if (empty($bean->name)) {
            if ($bean->load_relationship('stic_sepe_incidents_contacts')) {      
                $relatedBeans = $bean->stic_sepe_incidents_contacts->getBeans();
                $relatedBean = array_pop($relatedBeans);
                $contactName = $relatedBean->first_name .' '.$relatedBean->last_name;
            }
            $incidentType = $app_list_strings['stic_sepe_incident_types_list'][$bean->type];
            $bean->name = $contactName .' - '.$incidentType .' - '. $bean->incident_date;
        }
    }
}
