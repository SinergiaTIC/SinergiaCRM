<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

/**
 * This Class is used to display the Run Flow in Record button in ListView and DetailView
 */
class AOW_WorkFlowForms {

    public static function getModuleWorkflows($module)
    {
        $db = DBManagerFactory::getInstance();
        $workflows = array();

        $sql = "SELECT id,name FROM aow_workflow WHERE flow_module = '" . $module . "' AND deleted = 0  AND status = 'Active' ORDER BY name";
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            $workflows[$row['id']] = $row['name'];
        }

        return $workflows;
    }

    public static function DVPopupHtml($module)
    {
        global $app_strings;

        $workflows = AOW_WorkFlowForms::getModuleWorkflows($module);

        if (!empty($workflows)) {
            echo '
            <div id="popupDivWorkflow_ara" class="modal fade" style="display: none;">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">' . $app_strings['LBL_SELECT_WORKFLOW'] . '</h4>
                     </div>
                     <div class="modal-body">
                        <div style="padding: 5px 5px; overflow: auto; height: auto;">
                           <form id="popupForm" action="index.php?return_action=DetailView&entryPoint=runFlowFromRecord" method="post">
                              <table width="100%" class="list view table-responsive" cellspacing="0" cellpadding="0" border="0">
                                 <tbody>';
            $iOddEven = 1;
            foreach ($workflows as $workflowId => $workflow) {
                $iOddEvenCls = 'oddListRowS1';
                if ($iOddEven % 2 == 0) {
                    $iOddEvenCls = 'evenListRowS1';
                }
                $js = "$('#popupDivWorkflow_ara').modal('hide');var form=document.getElementById('popupForm');if(form!=null){form.workflowID.value='" . $workflowId . "';form.submit();}else{alert('Error!');}";
                echo '<tr height="20" class="' . $iOddEvenCls . '">
                                        <td width="17" valign="center"><a href="#" onclick="' . $js . '"><img src="themes/default/images/icon_AOW_WorkFlow_32.gif" width="16" height="16" /></a></td>
                                        <td scope="row" align="left"><b><a href="#" onclick="' . $js . '">' . $workflow . '</a></b></td></tr>';
                $iOddEven++;
            }
            echo '</tbody></table>
                              <input type="hidden" name="workflowID" value="" />
                            <input type="hidden" name="module" value="' . $module . '" />
                            <input type="hidden" name="uid" value="' . clean_string($_REQUEST['record'],
                    'STANDARDSPACE') . '" />
                           </form>
                        </div>
                     </div>
                     <div class="modal-footer">&nbsp;<button type="button" class="btn btn-primary" data-dismiss="modal">' . $app_strings['LBL_CANCEL_BUTTON_LABEL'] . '</button></div>
                  </div>
               </div>
            </div>
            <script>
                function showPopupWorkflow(){
                    var ppd2=document.getElementById(\'popupDivWorkflow_ara\');
                    if(ppd2!=null){
                        $("#popupDivWorkflow_ara").modal("show",{backdrop: "static"});
                    }else{
                        alert(\'Error!\');
                    }
                }
            </script>';
        } else {
            echo '<script>
                function showPopupWorkflow(){
                alert("' . $app_strings['LBL_NO_WORKFLOW'] . '");        
                }
            </script>';
        }
    }

    public static function LVPopupHtml($module)
    {
        global $app_strings;

        $workflows = AOW_WorkFlowForms::getModuleWorkflows($module);

        if (!empty($workflows)) {
            echo '    
            
            <div id="popupDivWorkflow_ara" class="modal fade" style="display: none;">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">' . $app_strings['LBL_SELECT_WORKFLOW'] . '</h4>
                     </div>
                     <div class="modal-body">
                        <div style="padding: 5px 5px; overflow: auto; height: auto;">
                              <table width="100%" class="list view table-responsive" cellspacing="0" cellpadding="0" border="0">
                                 <tbody>';
            $iOddEven = 1;
            foreach ($workflows as $workflowid => $workflow) {
                $iOddEvenCls = 'oddListRowS1';
                if ($iOddEven % 2 == 0) {
                    $iOddEvenCls = 'evenListRowS1';
                }
                echo '<tr height="20" class="' . $iOddEvenCls . '" >
                                            <td width="17" valign="center"><a href="#" onclick="$(\'#popupDivWorkflow_ara\').modal(\'hide\');sListView.send_form(true, \'' . $module .
                    '\', \'index.php?return_action=ListView&workflowID=' . $workflowid . '&entryPoint=runFlowFromRecord\',\'' . $app_strings['LBL_LISTVIEW_NO_SELECTED'] . '\');"><img src="themes/default/images/icon_AOW_WorkFlow_32.gif" width="16" height="16" /></a></td>
                                            <td scope="row" align="left"><b><a href="#" onclick="$(\'#popupDivWorkflow_ara\').modal(\'hide\');sListView.send_form(true, \'' . $module .
                    '\', \'index.php?return_action=ListView&workflowID=' . $workflowid . '&entryPoint=runFlowFromRecord\',\'' . $app_strings['LBL_LISTVIEW_NO_SELECTED'] . '\');">' . $workflow . '</a></b></td></tr>';
                $iOddEven++;
            }
            echo '</tbody></table>
                        </div>
                     </div>
                     <div class="modal-footer">&nbsp;<button type="button" class="btn btn-primary" data-dismiss="modal">' . $app_strings['LBL_CANCEL_BUTTON_LABEL'] . '</button></div>
                  </div>
               </div>
            </div>
            <script>
                function showPopupWorkflow(){
                    if(sugarListView.get_checks_count() < 1)
                    {
                        alert(\'' . $app_strings['LBL_LISTVIEW_NO_SELECTED'] . '\');
                    }
                    else
                    {
                        var ppd2=document.getElementById(\'popupDivWorkflow_ara\');
                        if(ppd2!=null){
                            $("#popupDivWorkflow_ara").modal("show",{backdrop: "static"});
                        }else{
                            alert(\'Error!\');
                        }
                    }
                }
            </script>';
        } else {
            echo '<script>
                function showPopupWorkflow(){
                alert(\'' . $app_strings['LBL_NO_WORKFLOW'] . '\');        
                }
            </script>';
        }
    }

    public static function LVSmarty()
    {
        global $app_strings, $sugar_config;
        if (preg_match('/^6\./', $sugar_config['sugar_version'])) {
            $script = '<a href="#" class="menuItem" onmouseover="hiliteItem(this,\'yes\');
" onmouseout="unhiliteItem(this);" onclick="showPopupWorkflow()">' . $app_strings['LBL_RUN_WORKFLOW'] . '</a>';
        } else {
            $script = ' <input class="button" type="button" value="' .
                $app_strings['LBL_RUN_WORKFLOW'] . '" ' . 'onClick="showPopupWorkflow();">';
        }

        return $script;
    }
}