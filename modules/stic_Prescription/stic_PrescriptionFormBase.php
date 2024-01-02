<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_PrescriptionFormBase extends FormBase
{
    public $moduleName = 'stic_Prescription';
    public $objectName = 'stic_Prescription';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Prescription();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
