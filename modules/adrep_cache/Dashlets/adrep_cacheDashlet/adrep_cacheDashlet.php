<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/adrep_cache/adrep_cache.php');

class adrep_cacheDashlet extends DashletGeneric { 
    function __construct($id, $def = null) {
		global $current_user, $app_strings;
		require('modules/adrep_cache/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'adrep_cache');

        $this->searchFields = $dashletData['adrep_cacheDashlet']['searchFields'];
        $this->columns = $dashletData['adrep_cacheDashlet']['columns'];

        $this->seedBean = new adrep_cache();        
    }
}
