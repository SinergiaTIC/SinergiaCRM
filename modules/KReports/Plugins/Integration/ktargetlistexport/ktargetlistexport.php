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

require_once('modules/KReports/Plugins/prototypes/kreportintegrationplugin.php');

class ktargetlistexport extends kreportintegrationplugin {

    public function __construct() {
        global $mod_strings;
        $this->pluginName = $mod_strings['LBL_EXPORT_TO_TARGETLIST_BUTTON_LABEL'];
    }

    public function checkAccess($thisReport){

        require_once('modules/ProspectLists/ProspectList.php');
        $newProspectList = new ProspectList();

        // fill with results:
        $newProspectList->load_relationships();

        $linkedFields = $newProspectList->get_linked_fields();

        $foundModule = false;

        foreach ($linkedFields as $linkedField => $linkedFieldData) {
            if ($newProspectList->$linkedField->_relationship->rhs_module == $thisReport->report_module) {
                $foundModule = true;
            }
        }

        return ($foundModule) ? true : false;
    }
    
    public function getMenuItem() {
        return array(
            'jsFile' => 'modules/KReports/Plugins/Integration/ktargetlistexport/ktargetlistexport.js',
            'menuItem' => array(
                'icon' => $this->wrapText('modules/KReports/images/targetlist.png'),
                'text' => $this->wrapText($this->pluginName),
                'handler' => $this->wrapFunction('ktargetlistexport')
                ));
    }

}

?>
