<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/adrep_parameter/adrep_parameter.php');

class adrep_parameterDashlet extends DashletGeneric { 
    function __construct($id, $def = null) {
		global $current_user, $app_strings;
		require('modules/adrep_parameter/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'adrep_parameter');

        $this->searchFields = $dashletData['adrep_parameterDashlet']['searchFields'];
        $this->columns = $dashletData['adrep_parameterDashlet']['columns'];

        $this->seedBean = new adrep_parameter();        
    }
}
