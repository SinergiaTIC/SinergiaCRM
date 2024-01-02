<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_PaymentsFormBase extends FormBase
{
    public $moduleName = 'stic_Payments';
    public $objectName = 'stic_Payments';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Payments();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
