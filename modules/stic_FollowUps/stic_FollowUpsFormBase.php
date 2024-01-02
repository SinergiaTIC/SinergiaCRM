<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/forms/FormBase.php';

class stic_FollowUpsFormBase extends FormBase
{
    public $moduleName = 'stic_FollowUps';
    public $objectName = 'stic_FollowUps';
    public function handleSave($prefix, $redirect = true, $useRequired = false)
    {
        require_once 'include/formbase.php';
        $focus = new stic_FollowUps();
        $focus = populateFromPost($prefix, $focus);
        $focus->save();
    }
}
