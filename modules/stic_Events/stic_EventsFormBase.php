<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_EventsFormBase extends FormBase
{
    public $moduleName = 'stic_Events';
    public $objectName = 'stic_Events';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_Events();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
