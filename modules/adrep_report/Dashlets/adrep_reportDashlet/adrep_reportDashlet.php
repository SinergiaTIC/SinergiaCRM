<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/adrep_report/adrep_report.php');

class adrep_reportDashlet extends DashletGeneric { 
    function __construct($id, $def = null) {
		global $current_user, $app_strings;
		require('modules/adrep_report/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'adrep_report');

        $this->searchFields = $dashletData['adrep_reportDashlet']['searchFields'];
        $this->columns = $dashletData['adrep_reportDashlet']['columns'];

        $this->seedBean = new adrep_report();        
    }
}
