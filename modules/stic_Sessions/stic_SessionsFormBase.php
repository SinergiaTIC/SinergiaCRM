<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_SessionsFormBase extends FormBase
{
    public $moduleName = 'stic_Sessions';
    public $objectName = 'stic_Sessions';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Sessions();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
