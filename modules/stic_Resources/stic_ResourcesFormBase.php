<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_ResourcesFormBase extends FormBase
{
    public $moduleName = 'stic_Resources';
    public $objectName = 'stic_Resources';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Resources();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
