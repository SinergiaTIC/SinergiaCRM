<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
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
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */


global $current_user, $sugar_config;
global $mod_strings;
global $app_list_strings;
global $app_strings;
global $theme;

if (!is_admin($current_user)) {
    sugar_die("Unauthorized access to administration.");
}

require_once('modules/Configurator/Configurator.php');


echo getClassicModuleTitle(
    "Administration",
    array(
        "<a href='index.php?module=Administration&action=index'>" . translate('LBL_MODULE_NAME', 'Administration') . "</a>",
        $mod_strings['LBL_AOS_ADMIN_MANAGE_AOS'],
    ),
    false
);

$cfg = new Configurator();
$sugar_smarty = new Sugar_Smarty();
$errors = array();

if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'save') {
    foreach ($_POST as $key => $value) {
        // STIC CUSTOM - JCH - 20251203 - Process other POST fields normally
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/870
        // Skip our custom series fields
        if (strpos($key, 'invoice_series_') === 0) {
            continue;
        }
        // END STIC CUSTOM
        if (strcmp((string)$value, 'true') == 0) {
            $value = true;
        }
        if (strcmp((string)$value, 'false') == 0) {
            $value = false;
        }
        $_POST[$key] = $value;
    }
    
    $cfg->saveConfig();

    // STIC CUSTOM - JCH - 20251203 - Process invoice series configuration separately to ensure complete replacement
    // https://github.com/SinergiaTIC/SinergiaCRM/pull/870
    if (isset($_POST['invoice_series_format']) && is_array($_POST['invoice_series_format'])) {
        // Build new series array from scratch
        $invoiceSeries = array();
        $validationErrors = array();
        $rectifiedSeriesIndex = isset($_POST['invoice_series_rectified']) ? $_POST['invoice_series_rectified'] : null;
        
        foreach ($_POST['invoice_series_format'] as $index => $format) {
            $format = trim($format);
            $initialNumber = isset($_POST['invoice_series_initial'][$index]) 
                           ? (int)$_POST['invoice_series_initial'][$index] 
                           : 1;
            $name = isset($_POST['invoice_series_name'][$index])
                  ? trim(substr($_POST['invoice_series_name'][$index], 0, 50))
                  : '';
            $isRectified = ($rectifiedSeriesIndex !== null && $rectifiedSeriesIndex == $index);
            
            // Validate format: only letters, 0, and symbols (no digits 1-9)
            if (!empty($format) && preg_match('/[1-9]/', $format)) {
                $validationErrors[] = "El formato '{$format}' contiene números del 1-9. Solo se permite usar el número 0.";
                continue; // Skip this series
            }
            
            // === Step 2.2: Validate series format characters ===
            // Allowed: A-Z, 0-9, hyphen (-), underscore (_), slash (/), dot (.), space, Y, 0
            // Valid placeholders: YYYY, YY, and sequences of 0s (0000, 000, 00)
            if (!empty($format)) {
                if (preg_match('/[a-z]/', $format)) {
                    $validationErrors[] = $mod_strings['LBL_AOS_SERIES_FORMAT_LOWERCASE'];
                    continue;
                }
                if (preg_match('/[^A-Z0-9\-_\/. ]/', $format)) {
                    $validationErrors[] = $mod_strings['LBL_AOS_SERIES_FORMAT_INVALID'];
                    continue;
                }
            }
            // === End Step 2.2 ===
            
            // Validate initial number: must be 1 or greater
            if ($initialNumber < 1) {
                $validationErrors[] = "El número inicial de la serie '{$name}' debe ser 1 o superior.";
                continue; // Skip this series
            }
            
            // === Step 2.6: Validate series name uniqueness (check before adding to array) ===
            if (!empty($name) && isset($invoiceSeries[$name])) {
                $validationErrors[] = $mod_strings['LBL_AOS_SERIES_DUPLICATE_NAME'];
                continue; // Skip this duplicate
            }
            // === End Step 2.6 ===
            
            // === Step 2.7: Check if format can be modified (block if accepted invoices exist) ===
            // Only validate if: name is not empty AND format changed AND series exists in config
            if (!empty($name) && !empty(trim($format)) && isset($sugar_config['aos']['invoices']['series'][$name])) {
                $currentFormat = trim($sugar_config['aos']['invoices']['series'][$name]['format'] ?? '');
                $newFormat = trim($format);
                // Only block if format actually changed
                if ($currentFormat !== '' && $currentFormat !== $newFormat) {
                    require_once 'custom/modules/AOS_Invoices/SticUtils.php';
                    if (!AOS_InvoicesUtils::canModifySeriesFormat($name)) {
                        $validationErrors[] = $mod_strings['LBL_AOS_SERIES_FORMAT_LOCKED'];
                        continue;
                    }
                }
            }
            // === End Step 2.7 ===
            
            // Only save non-empty formats and names that passed validation
            if (!empty($format) && !empty($name)) {
                $invoiceSeries[$name] = array(
                    'format' => $format,
                    'initialNumber' => $initialNumber,
                    'isRectified' => $isRectified
                );
            }
        }
        
        // Read current config_override.php
        $configFile = 'config_override.php';
        $configContent = file_get_contents($configFile);
        
        // Remove all existing aos.invoices.series lines
        $configContent = preg_replace(
            '/\$sugar_config\[\'aos\'\]\[\'invoices\'\]\[\'series\'\].*?;\n/s',
            '',
            $configContent
        );
        
        // === Step 2.7: Block series removal if has accepted invoices ===
        // Only check removal if there are series in the POST (user is actively saving changes)
        // If $invoiceSeries is empty, skip this check (no changes to validate)
        if (!empty($invoiceSeries)) {
            $existingSeries = $sugar_config['aos']['invoices']['series'] ?? array();
            $newSeriesNames = array_map('trim', array_keys($invoiceSeries));
            
            foreach ($existingSeries as $existingName => $existingData) {
                $trimmedName = trim($existingName);
                $isInNewList = in_array($trimmedName, $newSeriesNames);
                
                // Only block if series was explicitly removed from form (not present in POST at all)
                if (!$isInNewList) {
                    // Series is being removed - check if it has accepted invoices
                    require_once 'custom/modules/AOS_Invoices/SticUtils.php';
                    if (!AOS_InvoicesUtils::canModifySeriesFormat($existingName)) {
                        $validationErrors[] = $mod_strings['LBL_AOS_SERIES_FORMAT_LOCKED'];
                        // Restore config content for display
                        $configContent = file_get_contents($configFile);
                        break;
                    }
                }
            }
        }
        // === End Step 2.7 ===
        
        // If there are validation errors, show them and don't save
        if (!empty($validationErrors)) {
            foreach ($validationErrors as $error) {
                SugarApplication::appendErrorMessage($error);
            }
            SugarApplication::redirect('index.php?module=Administration&action=AOSAdmin');
            exit();
        }
        
        // Build new series configuration lines
        $newSeriesLines = '';
        foreach ($invoiceSeries as $seriesName => $seriesData) {
            $safeName = addslashes($seriesName);
            $safeFormat = addslashes($seriesData['format']);
            $isRectified = $seriesData['isRectified'] ? 'true' : 'false';
            $newSeriesLines .= "\$sugar_config['aos']['invoices']['series']['{$safeName}']['format'] = '{$safeFormat}';\n";
            $newSeriesLines .= "\$sugar_config['aos']['invoices']['series']['{$safeName}']['initialNumber'] = {$seriesData['initialNumber']};\n";
            $newSeriesLines .= "\$sugar_config['aos']['invoices']['series']['{$safeName}']['isRectified'] = {$isRectified};\n";
        }
        
        // Insert new lines before the closing /***CONFIGURATOR***/
        $configContent = str_replace(
            '/***CONFIGURATOR***/',
            $newSeriesLines . '/***CONFIGURATOR***/',
            $configContent
        );
        
        // Write back to file
        file_put_contents($configFile, $configContent);
    }
    // END STIC CUSTOM 

    // Stay on AOSAdmin page after save
    SugarApplication::redirect('index.php?module=Administration&action=AOSAdmin&saved=1');
    exit();
}

$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);
$sugar_smarty->assign('APP_LIST', $app_list_strings);
$sugar_smarty->assign('LANGUAGES', get_languages());
$sugar_smarty->assign("JAVASCRIPT", get_set_focus_js());
$sugar_smarty->assign('config', $sugar_config);
$sugar_smarty->assign('error', $errors);

// Get series that have accepted invoices in current year (to block edition/removal in UI)
$db = DBManagerFactory::getInstance();
$currentYear = date('Y');
$seriesWithInvoicesQuery = "SELECT DISTINCT cstm.stic_invoice_type_c 
                            FROM aos_invoices_cstm cstm
                            INNER JOIN aos_invoices inv ON inv.id = cstm.id_c
                            WHERE cstm.verifactu_aeat_status_c = 'accepted' 
                            AND cstm.stic_invoice_type_c IS NOT NULL 
                            AND cstm.stic_invoice_type_c != '' 
                            AND inv.deleted = 0
                            AND YEAR(inv.invoice_date) = " . (int)$currentYear;
$seriesWithInvoicesResult = $db->query($seriesWithInvoicesQuery);
$seriesWithInvoices = array();
while ($row = $db->fetchByAssoc($seriesWithInvoicesResult)) {
    if (!empty($row['stic_invoice_type_c'])) {
        $seriesWithInvoices[] = $row['stic_invoice_type_c'];
    }
}
$sugar_smarty->assign('series_with_invoices', $seriesWithInvoices);


$buttons = <<<EOQ
    <input title="{$app_strings['LBL_SAVE_BUTTON_TITLE']}"
                       accessKey="{$app_strings['LBL_SAVE_BUTTON_KEY']}"
                       class="button primary"
                       type="submit"
                       name="save"
                       onclick="return check_form('ConfigureSettings');"
                       value="  {$app_strings['LBL_SAVE_BUTTON_LABEL']}  " >
                &nbsp;<input title="{$mod_strings['LBL_CANCEL_BUTTON_TITLE']}"  onclick="document.location.href='index.php?module=Administration&action=index'" class="button"  type="button" name="cancel" value="  {$app_strings['LBL_CANCEL_BUTTON_LABEL']}  " >
EOQ;

$sugar_smarty->assign("BUTTONS", $buttons);

$sugar_smarty->display('modules/Administration/AOSAdmin.tpl');

$javascript = new javascript();
$javascript->setFormName('ConfigureSettings');
echo $javascript->getScript();
?>
<script language="Javascript" type="text/javascript">
    addToValidateLessThan('ConfigureSettings', 'aos_invoices_initialNumber', 'int', false, "", 9999999999,"Initial Invoice number cannot be bigger than 9999999999");
</script>

