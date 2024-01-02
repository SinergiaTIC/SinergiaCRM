<?php

class stic_Job_OffersLogicHooks
{
    public function before_save(&$bean, $event, $arguments)
    {
        // Bring Incorpora location data, if there is any
        if ($bean->fetched_row['stic_incorpora_locations_id'] != $bean->stic_incorpora_locations_id) {
            include_once 'modules/stic_Incorpora_Locations/Utils.php';
            stic_Incorpora_LocationsUtils::transferLocationData($bean);
        }
    }
}