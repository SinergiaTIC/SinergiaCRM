<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_Contacts_RelationshipsFormBase extends FormBase
{
    public $moduleName = 'stic_Contacts_Relationships';
    public $objectName = 'stic_Contacts_Relationships';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Contacts_Relationships();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
