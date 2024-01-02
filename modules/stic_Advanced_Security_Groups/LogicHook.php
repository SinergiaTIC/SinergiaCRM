<?php

require_once('include/utils.php');
class stic_Advanced_Security_GroupsLogicHook
{
    public function saveLabel(&$bean, $event, $arguments){  
        $moduleName = $bean->module_name;
        $fieldValue= $bean->name;
        $selectedLabel= translate($fieldValue, $moduleName);
        $bean->name_lbl = $selectedLabel;         
    }
    
}
