<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_Sepe_IncidentsFormBase extends FormBase
{
    public $moduleName = 'stic_Sepe_Incidents';
    public $objectName = 'stic_Sepe_Incidents';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Sepe_Incidents();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
