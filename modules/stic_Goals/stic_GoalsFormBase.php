<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_GoalsFormBase extends FormBase
{
    public $moduleName = 'stic_Goals';
    public $objectName = 'stic_Goals';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Goals();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
