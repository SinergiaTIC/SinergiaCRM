<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_Payment_CommitmentsFormBase extends FormBase
{
    public $moduleName = 'stic_Payment_Commitments';
    public $objectName = 'stic_Payment_Commitments';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Payment_Commitments();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
