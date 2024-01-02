<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_Job_ApplicationsFormBase extends FormBase
{
    public $moduleName = 'stic_Job_Applications';
    public $objectName = 'stic_Job_Applications';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Job_Applications();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
