<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_AttendancesFormBase extends FormBase
{
    public $moduleName = 'stic_Attendances';
    public $objectName = 'stic_Attendances';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Attendances();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
