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
 * prototype class for an presentation item 
 * all presentation items should be based on this class and then extend the methods herein. 
 * KReporter expects these functions to be available
 */

class kreportpresentationplugin {
    var $canExport = false; 
    
    public function display(&$thisview){
        
    }
    // function that should be overwritten if the Presentation Plugin provides a separate export to XLS and PDF
    public function getExportData($thisReport, $dynamicols = '', $renderFields = true){
        return false;
    }
    
    public function buildFieldArray($thisReport){
        return array();
    }
    
    public function buildColumnArray($thisReport){
       return array();
    }
}
?>
