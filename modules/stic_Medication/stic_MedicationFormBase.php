<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_MedicationFormBase extends FormBase
{
    public $moduleName = 'stic_Medication';
    public $objectName = 'stic_Medication';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Medication();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
