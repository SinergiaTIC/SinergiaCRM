<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/adrep_menu_link/adrep_menu_link.php');

class adrep_menu_linkDashlet extends DashletGeneric { 
    function __construct($id, $def = null) {
		global $current_user, $app_strings;
		require('modules/adrep_menu_link/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'adrep_menu_link');

        $this->searchFields = $dashletData['adrep_menu_linkDashlet']['searchFields'];
        $this->columns = $dashletData['adrep_menu_linkDashlet']['columns'];

        $this->seedBean = new adrep_menu_link();        
    }
}
