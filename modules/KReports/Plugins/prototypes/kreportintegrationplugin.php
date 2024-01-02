<?php
/* * *******************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed
 * by Christian Knoll. All rights are (c) 2012 by Christian Knoll
 *
 * This Version of the KReporter is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of Christian Knoll
 *
 * You can contact us at info@kreporter.org
 * ****************************************************************************** */
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


/*
 * prototype class for an integration item 
 * all integration items should be based on this class and then extend the methods herein. 
 * KReporter expects these functions to be available
 */

class kreportintegrationplugin {

    var $pluginName = 'prototype';

    public function __construct() {
        
    }

    public function checkAccess($thisReport) {
        return $thisReport->ACLAccess('export');
/*
        global $current_user;
        require_once('modules/ACL/ACLController.php');
        if (ACLController::checkAccess('KReports', 'export', false))
            return true;
        else
           return false;
*/
    }

    public function getMenuItem() {
        return array(
            'menuitem' => array(
                'xtype' => 'tbtext',
                'text' => $this->pluginName
            )
        );
    }

    public function wrapText($var) {
        return '\'' . $var . '\'';
    }

    public function wrapFunction($var) {
        return 'function(){' . $var . '();}';
    }

}

?>
