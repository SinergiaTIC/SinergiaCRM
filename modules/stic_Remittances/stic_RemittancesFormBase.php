<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_RemittancesFormBase extends FormBase
{
    public $moduleName = 'stic_Remittances';
    public $objectName = 'stic_Remittances';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Remittances();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
