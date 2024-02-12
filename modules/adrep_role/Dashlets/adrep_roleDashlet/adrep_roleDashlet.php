<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/adrep_role/adrep_role.php');

class adrep_roleDashlet extends DashletGeneric { 
    function __construct($id, $def = null) {
		global $current_user, $app_strings;
		require('modules/adrep_role/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'adrep_role');

        $this->searchFields = $dashletData['adrep_roleDashlet']['searchFields'];
        $this->columns = $dashletData['adrep_roleDashlet']['columns'];

        $this->seedBean = new adrep_role();        
    }
}
