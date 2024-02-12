<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/adrep_chart/adrep_chart.php');

class adrep_chartDashlet extends DashletGeneric { 
    function __construct($id, $def = null) {
		global $current_user, $app_strings;
		require('modules/adrep_chart/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'adrep_chart');

        $this->searchFields = $dashletData['adrep_chartDashlet']['searchFields'];
        $this->columns = $dashletData['adrep_chartDashlet']['columns'];

        $this->seedBean = new adrep_chart();        
    }
}
