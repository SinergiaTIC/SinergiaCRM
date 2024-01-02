<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_AssessmentsFormBase extends FormBase
{
    public $moduleName = 'stic_Assessments';
    public $objectName = 'stic_Assessments';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Assessments();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
