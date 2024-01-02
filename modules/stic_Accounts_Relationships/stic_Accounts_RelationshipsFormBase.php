<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_Accounts_RelationshipsFormBase extends FormBase
{
    public $moduleName = 'stic_Accounts_Relationships';
    public $objectName = 'stic_Accounts_Relationships';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Accounts_Relationships();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
