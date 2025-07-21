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

class stic_SignaturesUtils
{

/**
 * Retrieves the relationships and fields for a given SuiteCRM module.
 *
 * This function fetches a module's own reportable fields and also
 * identifies and lists reportable fields from related modules.
 * The field names are formatted as '$[table_name]:[field_name]' or
 * '$[relationship_name]:[field_name]' for clarity in reporting or display.
 *
 * @param string $moduleName The name of the module (e.g., 'Accounts', 'Contacts').
 * @param string $format The format of the output, either 'raw' or 'json'.
 * @return array An associative array or string containing module relationships and field options.
 */

    public static function getModuleRelationships($moduleName, $format = 'raw')
    {
        global $beanList;

        $options_array = ['' => ''];
        $mod_options_array = [];

        // Validate module existence
        if (!isset($beanList[$moduleName])) {
            return [];
        }

        // Instantiate the module
        $module = new $beanList[$moduleName]();

        // Process main module's fields
        foreach ($module->field_defs as $name => $arr) {
            if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || (isset($arr['type']) && $arr['type'] == 'id') || (isset($arr['type']) && $arr['type'] == 'link'))) {
                if (!isset($arr['reportable']) || $arr['reportable']) {
                    $options_array['$' . $module->table_name . ':' . $name] = translate($arr['vname'] ?? '', $module->module_dir);
                }
            }
        }

        $options = $options_array;

        // Agregar el módulo principal al array de opciones
        $mod_options_array[$module->module_dir] = translate('LBL_MODULE_NAME', $module->module_dir);

        $firstOptions = $options;

        $fmod_options_array = [];

        // Process related module fields
        foreach ($module->field_defs as $module_name => $module_arr) {
            if (isset($module_arr['type']) && $module_arr['type'] == 'relate' && isset($module_arr['source']) && $module_arr['source'] == 'non-db') {
                $options_array = ['' => ''];

                if (isset($module_arr['module']) && $module_arr['module'] != '' && $module_arr['module'] != 'EmailAddress') {
                    $relate_module_class_name = $beanList[$module_arr['module']];

                    if (!is_null($relate_module_class_name)) {
                        $relate_module = new $relate_module_class_name();

                    }

                    $options = $options_array;

                    if ($module_arr['vname'] != 'LBL_DELETED') {
                        $options_array['$' . $module->table_name . ':' . $name] = translate($module_arr['vname'], $module->module_dir);
                        $module_path = $module_arr['module'] . ':' . ($module_arr['id_name'] ?? $module_arr['link']);
                        $fmod_options_array[$module_path] = translate($relate_module->module_dir) . ' : ' . translate($module_arr['vname'], $module->module_dir);
                    }
                }
            }
        }

        // Finalize and sort module options
        array_multisort($fmod_options_array, SORT_ASC, $fmod_options_array);
        $mod_options_array = array_merge($mod_options_array, $fmod_options_array);

        $moduleOptions = $mod_options_array;

        if ($format == 'json') {
            // Convert to JSON format if requested
            $moduleOptions = json_encode($moduleOptions);
            echo $moduleOptions;
            die();
        } else {
            // Otherwise, return as an associative array
            return $moduleOptions;
        }

    }

    /* * Retrieves related modules that are emailable (i.e., modules that can have email addresses).
     *
     * @param string $mainModule The main module to check for related emailable modules.
     * @return array An associative array of related emailable modules with their labels.
     */
    public static function getRelatedEmailableModules($mainModule)
    {
        global $beanFiles, $beanList;
        $emailableModules = array();

        $relatedModules = self::getModuleRelationships($mainModule);
        foreach ($relatedModules as $beanPath => $beanLabel) {
            $beanName = explode(':', $beanPath)[0];
            if (isset($beanList[$beanName]) && isset($beanFiles[$beanList[$beanName]])) {
                require_once $beanFiles[$beanList[$beanName]];
                $obj = new $beanList[$beanName];
                if ($obj instanceof Person) {
                    $emailableModules[$beanPath] = $beanLabel;
                }
            }
        }
        asort($emailableModules);
        return $emailableModules;
    }

    public static function populateSignerPathListString($moduleName)
    {
        global $app_list_strings;
        // get emailable related modules and populate the dropdown
        // this is used to select the path for extracting email addresses or phone data for signers
        require_once 'modules/stic_Signatures/Utils.php';
        $relatedModules = stic_SignaturesUtils::getRelatedEmailableModules($moduleName);
        $app_list_strings['stic_signatures_signer_path_list'][''] = '';
        foreach ($relatedModules as $key => $value) {
            $app_list_strings['stic_signatures_signer_path_list'][$key] = $value;
        }
    }

    /**
     * Retrieves the signers for a given signature ID and main module IDs.
     *
     * @param string $signatureId The ID of the signature.
     * @param array|string $mainModuleIds An array or single value of main module IDs.
     * @return array An array of signer IDs.
     */
    public static function getSignatureSigners($signatureId, $mainModuleIds)
    {
        if (empty($signatureId) || empty($mainModuleIds)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Signature [{$signatureId}] or Main Module ID [{$mainModuleIds}] is empty.");
            return;
        }
        // Initialize an empty array to hold unique signer IDs
        $signersIdList = [];

        // Ensure $mainModuleIds is an array
        $mainModuleIds = is_array($mainModuleIds) ? $mainModuleIds : [$mainModuleIds];

        // Load the signature bean and check if it exists
        $signatureBean = BeanFactory::getBean('stic_Signatures', $signatureId);
        if (empty($signatureBean)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Signature Bean for ID [{$signatureId}] is empty.");
            return;
        }

        $mainModule = $signatureBean->main_module;
        $signerPath = $signatureBean->signer_path;
        $signerModule = explode(':', $signerPath)[0];
        $signerPath = explode(':', $signerPath)[1] ?? $signerPath;

        // Check if the main module and signer module are valid
        foreach ($mainModuleIds as $mainModuleId) {
            $mainModuleBean = BeanFactory::getBean($mainModule, $mainModuleId);
            if (empty($mainModuleBean)) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": Main Module Bean for ID [{$mainModuleId}] is empty.");
                continue;
            }

            if ($signerPath != $signerModule) {
                // try to retrieve the linked beans based on relatinships
                $signers = $mainModuleBean->get_linked_beans($signerPath, $signerModule);
            } else {
                // If the signerPath is the same as the signerModule, we can directly access the field
                $signers = [$mainModuleBean];
            }

            if (empty($signers)) {
                $relatedId = $mainModuleBean->$signerPath;
                // If no linked beans found, try to get the related ID directly (for relate fields)
                if (!empty($relatedId)) {
                    // Fallback to the main module bean if no linked beans found
                    $signers[] = BeanFactory::getBean($signerModule, $relatedId);
                }
            }
            if (empty($signers)) {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ": No signers found for Main Module ID [{$mainModuleId}] and Signer Path [{$signerPath}].");
                continue;
            }

            foreach ($signers as $signer) {
                if (!in_array($signer->id, $signersIdList)) {
                    $signersIdList[$signer->id] = [
                        'module' => $signerModule,
                        'sourceModule' => $mainModule,
                        'sourceId' => $mainModuleId,
                        'signerPath' => $signerPath,
                        'id' => $signer->id,
                        'name' => $signer->name,
                        'email' => $signer->email1 ?? '',
                        'phone' => $signer->phone ?? '',
                    ];
                }
            }

        }
        return $signersIdList;
    }

    /*     * Retrieves the parsed template for a given signer ID.
     *
     * @param string $signerId The ID of the signer.
     * @return string The parsed HTML template.
     */
    public static function getParsedTemplate($signerId)
    {
        
        require_once 'SticInclude/Utils.php';

        // Use common functions for PDF generation
        require_once 'custom/modules/AOS_PDF_Templates/SticGeneratePdfFunctions.php';
        
        $signerId = $signerId; // '00000b06-3aa0-2b29-db5c-6879efaf8c9d';
        $signerBean = BeanFactory::getBean('stic_Signers', $signerId);
        $signatureBean = SticUtils::getRelatedBeanObject($signerBean, 'stic_signatures_stic_signers');
        $pdfTemplateBean = BeanFactory::getBean('AOS_PDF_Templates', $signatureBean->pdftemplate_id_c);
        $sourceModuleBean = BeanFactory::getBean($signatureBean->main_module, $signerBean->record_id);
            
   
        require_once 'modules/AOS_PDF_Templates/templateParser.php';

        // Retrieving the record and template beans
        $bean = $sourceModuleBean;
        if (!$bean) {
            sugar_die("Invalid Record");
        }

        $templateBean = $pdfTemplateBean;
        if (!$templateBean) {
            sugar_die("Invalid Template");
        }

        // Define the patterns used to clean the template HTML code
        $search = array('/<script[^>]*?>.*?<\/script>/si', // Strip out javascript
            '/<[\/\!]*?[^<>]*?>/si', // Strip out HTML tags
            '/([\r\n])[\s]+/', // Strip out white space
            '/&(quot|#34);/i', // Replace HTML entities
            '/&(amp|#38);/i',
            '/&(lt|#60);/i',
            '/&(gt|#62);/i',
            '/&(nbsp|#160);/i',
            '/&(iexcl|#161);/i',
            '/<address[^>]*?>/si',
            '/&(apos|#0*39);/',
            '/&#(\d+);/',
        );

        $replace = array('',
            '',
            '\1',
            '"',
            '&',
            '<',
            '>',
            ' ',
            chr(161),
            '<br>',
            "'",
            'chr(%1)',
        );

        // Clean the template content
        $header = preg_replace($search, $replace, $templateBean->pdfheader);
        $footer = preg_replace($search, $replace, $templateBean->pdffooter);
        $text = preg_replace($search, $replace, $templateBean->description);
        $text = str_replace("<p><pagebreak /></p>", "<pagebreak />", $text);
        $text = preg_replace_callback(
            '/\{DATE\s+(.*?)\}/',
            function ($matches) {
                return date($matches[1]);
            },
            $text
        );

        if (str_starts_with($_REQUEST['module'], "AOS_")) {
            $variableName = strtolower($bean->module_dir);
            $lineItemsGroups = array();
            $lineItems = array();

            $sql = "SELECT pg.id, pg.product_id, pg.group_id FROM aos_products_quotes pg LEFT JOIN aos_line_item_groups lig ON pg.group_id = lig.id WHERE pg.parent_type = '" . $bean->object_name . "' AND pg.parent_id = '" . $bean->id . "' AND pg.deleted = 0 ORDER BY lig.number ASC, pg.number ASC";
            $res = $bean->db->query($sql);
            while ($row = $bean->db->fetchByAssoc($res)) {
                $lineItemsGroups[$row['group_id']][$row['id']] = $row['product_id'];
                $lineItems[$row['id']] = $row['product_id'];
            }

            //backward compatibility
            if (isset($bean->billing_account_id)) {
                $object_arr['Accounts'] = $bean->billing_account_id;
            }
            if (isset($bean->billing_contact_id)) {
                $object_arr['Contacts'] = $bean->billing_contact_id;
            }
            if (isset($bean->assigned_user_id)) {
                $object_arr['Users'] = $bean->assigned_user_id;
            }
            if (isset($bean->currency_id)) {
                $object_arr['Currencies'] = $bean->currency_id;
            }

            $text = str_replace("\$aos_quotes", "\$" . $variableName, $text);
            $text = str_replace("\$aos_invoices", "\$" . $variableName, $text);
            $text = str_replace("\$total_amt", "\$" . $variableName . "_total_amt", $text);
            $text = str_replace("\$discount_amount", "\$" . $variableName . "_discount_amount", $text);
            $text = str_replace("\$subtotal_amount", "\$" . $variableName . "_subtotal_amount", $text);
            $text = str_replace("\$tax_amount", "\$" . $variableName . "_tax_amount", $text);
            $text = str_replace("\$shipping_amount", "\$" . $variableName . "_shipping_amount", $text);
            $text = str_replace("\$total_amount", "\$" . $variableName . "_total_amount", $text);

            $text = populate_group_lines($text, $lineItemsGroups, $lineItems);
        }

        // The parse_template function requires an array of beans
        $beanArray = array();
        $beanArray[$bean->module_dir] = $bean->id;

        // Parse the template using the record's data
        $converted = templateParser::parse_template($text, $beanArray);
        $header = templateParser::parse_template($header, $beanArray);
        $footer = templateParser::parse_template($footer, $beanArray);

        // Replace last break lines by html tags
        $printable = str_replace("\n", "<br />", $converted);


        return "{$header}{$converted}{$footer}";

    }

}
