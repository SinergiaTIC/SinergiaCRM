<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_RegistrationsFormBase extends FormBase
{
    public $moduleName = 'stic_Registrations';
    public $objectName = 'stic_Registrations';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Registrations();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
