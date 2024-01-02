<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_Personal_EnvironmentFormBase extends FormBase
{
    public $moduleName = 'stic_Personal_Environment';
    public $objectName = 'stic_Personal_Environment';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Personal_Environment();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
