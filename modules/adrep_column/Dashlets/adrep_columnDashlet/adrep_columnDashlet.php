<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/adrep_column/adrep_column.php');

class adrep_columnDashlet extends DashletGeneric { 
    function __construct($id, $def = null) {
		global $current_user, $app_strings;
		require('modules/adrep_column/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'adrep_column');

        $this->searchFields = $dashletData['adrep_columnDashlet']['searchFields'];
        $this->columns = $dashletData['adrep_columnDashlet']['columns'];

        $this->seedBean = new adrep_column();        
    }
}
