<?php

class stic_Incorpora_LocationsUtils {
   /**
     * Retrieves the data from the related stic_Incorpora_Locations record and copy the values into the Incorpora location fields
     * of the calling module. 
     * 
     * If the modules are from SuiteCRM base modules, the fields are custom and should be added the string "_c". To check this
     * we test if the field 'inc_id' is a custom one.
     *
     * @param recordBean It can be a Contact, Accounts or stic_Job_Offers bean
     */
    public static function transferLocationData($recordBean) {
        $sufix = $recordBean->field_defs['inc_id_c'] ? '_c' : '';
        $inc_town_code = 'inc_town_code' .$sufix;
        $inc_town = 'inc_town' .$sufix;
        $inc_municipality_code = 'inc_municipality_code' .$sufix;
        $inc_municipality = 'inc_municipality' .$sufix;
        $inc_state_code = 'inc_state_code' .$sufix;
        $inc_state = 'inc_state' .$sufix;
        $stic_incorpora_locations_id = 'stic_incorpora_locations_id' .$sufix;

        $location_bean = BeanFactory::getBean('stic_Incorpora_Locations', $recordBean->$stic_incorpora_locations_id);
        $recordBean->$inc_town_code = $location_bean->town_code;
        $recordBean->$inc_town = $location_bean->town;
        $recordBean->$inc_municipality_code = $location_bean->municipality_code;
        $recordBean->$inc_municipality = $location_bean->municipality;
        $recordBean->$inc_state_code = $location_bean->state_code;
        $recordBean->$inc_state = $location_bean->state;
    }
}