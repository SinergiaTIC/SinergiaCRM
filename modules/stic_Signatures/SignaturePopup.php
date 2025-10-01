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
 * This class provides functionalities related to selecting signature templates.
 * It includes methods to generate HTML for displaying signature selection popups
 * in both List View (LV) and Detail View (DV) contexts, and to retrieve available
 * signature templates for a given module.
 */
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

#[\AllowDynamicProperties]
class SelectSignatureTemplate
{
    /**
     * Generates the Smarty script for displaying the "Add to Signature Process" button/link
     * based on the SugarCRM version.
     *
     * @return string The HTML string for the button or link.
     */
    public static function LVSmarty()
    {
        global $app_strings, $sugar_config;
        if (preg_match('/^6\./', (string) $sugar_config['sugar_version'])) {
            $script = '<a href="#" class="menuItem" onmouseover="hiliteItem(this,\'yes\');
" onmouseout="unhiliteItem(this);" onclick="showPopupSignatures()">' . $app_strings['LBL_ADD_TO_SIGNATURE_PROCESS'] . '</a>';
        } else {
            $script = ' <input class="button" type="button" value="' .
                $app_strings['LBL_ADD_TO_SIGNATURE_PROCESS'] . '" ' . 'onClick="showPopupSignatures();">';
        }

        return $script;
    }

    /**
     * Retrieves a list of open signature templates for a given module.
     *
     * @param string $module The name of the module.
     * @return array An associative array where keys are signature IDs and values are signature names.
     */
    public static function getModuleTemplates($module)
    {
        $db = DBManagerFactory::getInstance();
        $signatures = array();

        $sql = "SELECT id,name FROM stic_signatures WHERE main_module = '" . $module . "' AND deleted = 0 AND status='open' ORDER BY name";
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            $signatures[$row['id']] = $row['name'];
        }

        return $signatures;
    }

    /**
     * Generates the HTML for the signature selection popup in a List View context.
     *
     * @param string $module The name of the module from which the popup is initiated.
     * @return void Echoes the HTML directly.
     */
    public static function LVPopupHtml($module)
    {
        global $app_strings;

        $signatures = self::getModuleTemplates($module);

        if (!empty($signatures)) {
            echo '    
            
            <div id="popup-div-signature" class="modal fade" style="display: none;">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">' . $app_strings['LBL_SELECT_SIGNATURE'] . '</h4>('.
                        $app_strings['LBL_SELECT_SIGNATURE_INFO'].')

                     </div>
                     <div class="modal-body">
                        <div style="padding: 5px 5px; overflow: auto; height: auto;">
                              <table width="100%" class="list view table-responsive" cellspacing="0" cellpadding="0" border="0">
                                 <tbody>';
            $iOddEven = 1;
            foreach ($signatures as $signatureId => $signature) {
                $iOddEvenCls = 'oddListRowS1';
                if ($iOddEven % 2 == 0) {
                    $iOddEvenCls = 'evenListRowS1';
                }
                echo '<tr height="20" class="' . $iOddEvenCls . '" >
                                            <td width="17" valign="center"><a href="#" onclick="$(\'#popup-div-signature\').modal(\'hide\');sListView.send_form(true, \'' . $module .
                    '\', \'index.php?signature-id=' . $signatureId . '&entryPoint=sticSignatureSignersSelect\',\'' . $app_strings['LBL_LISTVIEW_NO_SELECTED'] . '\');"><img src="themes/SuiteP/images/insert-signature.png" width="16" height="16" /></a></td>
                                            <td scope="row" align="left"><b><a href="#" onclick="$(\'#popup-div-signature\').modal(\'hide\');sListView.send_form(true, \'' . $module .
                    '\', \'index.php?signature-id=' . $signatureId . '&entryPoint=sticSignatureSignersSelect\',\'' . $app_strings['LBL_LISTVIEW_NO_SELECTED'] . '\');">' . $signature . '</a></b></td></tr>';
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
                function showPopupSignatures(){
                    if(sugarListView.get_checks_count() < 1)
                    {
                        alert(\'' . $app_strings['LBL_LISTVIEW_NO_SELECTED'] . '\');
                    }
                    else
                    {
                        var ppd2=document.getElementById(\'popup-div-signature\');
                        if(ppd2!=null){
                            $("#popup-div-signature").modal("show",{backdrop: "static"});
                        }else{
                            alert(\'Error!\');
                        }
                    }
                }
            </script>';
        } else {
            echo '<script>
                function showPopupSignatures(){
                alert(\'' . $app_strings['LBL_NO_TEMPLATE'] . '\');        
                }
            </script>';
        }
    }

    /**
     * Generates the HTML for the signature selection popup in a Detail View context.
     *
     * @param string $module The name of the module from which the popup is initiated.
     * @return void Echoes the HTML directly.
     */
    public static function DVPopupHtml($module)
    {
        global $app_strings;

        $signatures = self::getModuleTemplates($module);

        if (!empty($signatures)) {
            echo '
            <div id="popup-div-signature" class="modal fade" style="display: none;">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">' . $app_strings['LBL_SELECT_SIGNATURE'] . '</h4>('.
                        $app_strings['LBL_SELECT_SIGNATURE_INFO'].')
                        
                     </div>
                     <div class="modal-body">
                        <div style="padding: 5px 5px; overflow: auto; height: auto;">
                           <form id="signatureForm" action="index.php?entryPoint=sticSignatureSignersSelect" method="post">
                              <table width="100%" class="list view table-responsive" cellspacing="0" cellpadding="0" border="0">
                                 <tbody>';
            $iOddEven = 1;
            foreach ($signatures as $signatureId => $signature) {
                $iOddEvenCls = 'oddListRowS1';
                if ($iOddEven % 2 == 0) {
                    $iOddEvenCls = 'evenListRowS1';
                }
                $js = "$('#popup-div-signature').modal('hide');var form=document.getElementById('signatureForm');if(form!=null){console.log(form);form['signature-id'].value='" . $signatureId . "';form.submit();}else{alert('Error!');}";
                echo '<tr height="20" class="' . $iOddEvenCls . '">
                                        <td width="17" valign="center"><a href="#" onclick="' . $js . '"><img src="themes/SuiteP/images/insert-signature.png" width="16" height="16" /></a></td>
                                        <td scope="row" align="left"><b><a href="#" onclick="' . $js . '">' . $signature . '</a></b></td></tr>';
                $iOddEven++;
            }
            echo '</tbody></table>
                              <input type="hidden" name="signature-id" value="" />
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
                function showPopupSignature(){
                    var ppd2=document.getElementById(\'popup-div-signature\');
                    if(ppd2!=null){
                        $("#popup-div-signature").modal("show",{backdrop: "static"});
                    }else{
                        alert(\'Error!\');
                    }
                }
            </script>';
        } else {
            echo '<script>
                function showPopupSignature(){
                alert(\'' . $app_strings['LBL_NO_TEMPLATE'] . '\');        
                }
            </script>';
        }
    }
}