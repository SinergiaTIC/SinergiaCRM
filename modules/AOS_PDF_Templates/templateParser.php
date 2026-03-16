<?php

/**
 * Products, Quotations & Invoices modules.
 * Extensions to SugarCRM
 * @package Advanced OpenSales for SugarCRM
 * @subpackage Products
 * @copyright SalesAgility Ltd http://www.salesagility.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author SalesAgility Ltd <support@salesagility.com>
 */

use SuiteCRM\Utility\SuiteValidator as SuiteValidator;

#[\AllowDynamicProperties]
class templateParser
{
    const SUBPANEL_START_PATTERN = '/<!--\$subpanel:([a-z0-9_]+)(:([a-z0-9_]+))?-->/i';
    const SUBPANEL_END_PATTERN = '/<!--\/\$subpanel:([a-z0-9_]+)(:([a-z0-9_]+))?-->/i';
    const SUBPANEL_OPTIONS_PATTERN = '/(\w+):(\w+)/';

    public static function parse_template($string, $bean_arr)
    {
        $GLOBALS['log']->fatal('PARSE_TEMPLATE_START: string length=' . strlen($string));
        $GLOBALS['log']->fatal('PARSE_TEMPLATE_START: bean_arr=' . json_encode(array_keys($bean_arr)));
        
        foreach ($bean_arr as $bean_name => $bean_id) {
            $focus = BeanFactory::getBean($bean_name, $bean_id);
            $string = templateParser::parse_template_bean($string, $focus->table_name, $focus);

            foreach ($focus->field_defs as $focus_name => $focus_arr) {
                if (isset($focus_arr['type']) && $focus_arr['type'] == 'relate') {
                    if (isset($focus_arr['module']) && $focus_arr['module'] != '' && $focus_arr['module'] != 'EmailAddress') {
                        $idName = isset($focus_arr['id_name']) ? $focus_arr['id_name'] : null;
                        if (empty($idName) || !isset($focus->$idName) || empty($focus->$idName)) {
                            continue;
                        }
                        $relate_focus = BeanFactory::getBean($focus_arr['module'], $focus->$idName);
                        $string = templateParser::parse_template_bean($string, $focus_arr['name'], $relate_focus);
                    }
                }
            }
        }
        
        $string = templateParser::parseSubpanels($string, $bean_arr);
        
        $GLOBALS['log']->fatal('PARSE_TEMPLATE_END: string length=' . strlen($string));
        
        return $string;
    }

    /**
     * @param $string
     * @param $key
     * @param $focus
     * @return mixed
     * @throws Exception
     */
    public static function parse_template_bean($string, $key, &$focus)
    {
        global $app_strings, $sugar_config, $locale, $current_user;
        $repl_arr = array();
        $isValidator = new SuiteValidator();
        // STIC Custom 20250723 JCH - Attempt to read property "field_defs" on false
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/726 
        if (is_object($focus) && isset($focus->field_defs)) {
        // END STIC Custom
            foreach ($focus->field_defs as $field_def) {
                if (isset($field_def['name']) && $field_def['name'] != '') {
                    $fieldName = $field_def['name'];

                    // STIC Custom - JCH - 202210006 - Check if field is really empty
                    // STIC#880
                    // if (empty($focus->$fieldName)) {
                    if (!isset($focus->$fieldName) || $focus->$fieldName == '' ) {
                    // END STIC-Custom
                        $repl_arr[$key . '_' . $fieldName] = '';
                        continue;
                    }

                    if ($field_def['type'] == 'currency') {
                        // STIC-Custom 20250131 ART - Distinguish Type of Discount
                        // https://github.com/SinergiaTIC/SinergiaCRM/pull/575
                        // $repl_arr[$key . "_" . $fieldName] = currency_format_number($focus->$fieldName, $params = array('currency_symbol' => false));
                        // If it comes from aos_products_quotes and is product_discount
                        if ($key == 'aos_products_quotes' && ($field_def["name"] == 'product_discount' || $field_def["name"] ==  'service_discount')) { 
                            if ($focus->discount == 'Percentage') {
                                $repl_arr[$key . "_" . $fieldName] = currency_format_number($focus->$fieldName, $params = array('currency_symbol' => false)) . $app_strings['LBL_PERCENTAGE_SYMBOL'];
                            } else {
                                $repl_arr[$key . "_" . $fieldName] = currency_format_number($focus->$fieldName, $params = array('currency_symbol' => false)) . '€';
                            }
                        } else {
                            $repl_arr[$key . "_" . $fieldName] = currency_format_number($focus->$fieldName, $params = array('currency_symbol' => false));
                        }
                        // END STIC-Custom
                    } elseif (($field_def['type'] == 'radioenum' || $field_def['type'] == 'enum' || $field_def['type'] == 'dynamicenum') && isset($field_def['options'])) {
                        $repl_arr[$key . "_" . $fieldName] = translate($field_def['options'], $focus->module_dir, $focus->$fieldName);
                    } elseif ($field_def['type'] == 'multienum' && isset($field_def['options'])) {
                        $mVals = unencodeMultienum($focus->{$fieldName});
                        $translatedVals = array();

                        foreach ($mVals as $mVal) {
                            // STIC Custom 20250312 JBL - Avoid Warning: Array to string conversion
                            // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
                            // $translatedVals[] = translate($field_def['options'], $focus->module_dir, $mVal);
                            $translated = translate($field_def['options'], $focus->module_dir, $mVal);
                            if (is_array($translated)) {
                                $translated = implode(", ", $translated);
                            }
                            $translatedVals[] = $translated;
                            // END STIC Custom
                        }

                        $repl_arr[$key . "_" . $fieldName] = implode(", ", $translatedVals);
                    } //Fix for Windows Server as it needed to be converted to a string.
                    elseif ($field_def['type'] == 'int') {
                        $repl_arr[$key . "_" . $fieldName] = (string)$focus->$fieldName;
                    } elseif ($field_def['type'] == 'bool') {
                        if ($focus->{$fieldName} == "1") {
                            // STIC-Custom 20241125 ART - Translated checkbox values in PDF Templates
                            // https://github.com/SinergiaTIC/SinergiaCRM/pull/486
                            // $repl_arr[$key . "_" . $fieldName] = "true";
                            $repl_arr[$key . "_" . $fieldName] = translate('LBL_CHECKBOX_TRUE', 'AOS_PDF_Templates');
                        } else {
                            // STIC-Custom 20241125 ART - Translated checkbox values in PDF Templates - 
                            // https://github.com/SinergiaTIC/SinergiaCRM/pull/486
                            // $repl_arr[$key . "_" . $fieldName] = "false";
                            $repl_arr[$key . "_" . $fieldName] = translate('LBL_CHECKBOX_FALSE', 'AOS_PDF_Templates');
                            // END STIC-Custom
                        }
                    } elseif ($field_def['type'] == 'image') {
                        $secureLink = $sugar_config['site_url'] . '/' . "public/" . $focus->id . '_' . $fieldName;
                        $file_location = $sugar_config['upload_dir'] . '/' . $focus->id . '_' . $fieldName;
                        // create a copy with correct extension by mime type
                        if (!file_exists('public')) {
                            sugar_mkdir('public', 0777);
                        }
                        if (!copy($file_location, "public/{$focus->id}".  '_' . $fieldName)) {
                            $secureLink = $sugar_config['site_url'] . '/'. $file_location;
                        }

                        if (empty($focus->{$fieldName})) {
                            $repl_arr[$key . "_" . $fieldName] = "";
                        } else {
                            $link = $secureLink;
                            $repl_arr[$key . "_" . $fieldName] = '<img src="' . $link . '" width="' . $field_def['width'] . '" height="' . $field_def['height'] . '"/>';
                        }
                    } elseif ($field_def['type'] == 'wysiwyg') {
                        $repl_arr[$key . "_" . $field_def['name']] = html_entity_decode((string) $focus->$field_def['name'],
                            ENT_COMPAT, 'UTF-8');
                        $repl_arr[$key . "_" . $fieldName] = html_entity_decode((string) $focus->{$fieldName},
                            ENT_COMPAT, 'UTF-8');
                    } elseif ($field_def['type'] == 'decimal' || $field_def['type'] == 'float') {
                        // STIC Custom 20250414 ART - SticUtils function for UserPreferences decimals formatting
                        // https://github.com/SinergiaTIC/SinergiaCRM/pull/315
                        require_once 'SticInclude/Utils.php';
                        // END STIC Custom

                        // STIC Custom 20250215 JBL - Remove Warning: Undefined array key access
                        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
                        // if ($_REQUEST['entryPoint'] == 'formLetter') {
                        if (!empty($_REQUEST['entryPoint']) && $_REQUEST['entryPoint'] == 'formLetter') {
                        // END STIC Custom
                        
                        // STIC Custom 20250414 ART - SticUtils function for UserPreferences decimals formatting
                        // https://github.com/SinergiaTIC/SinergiaCRM/pull/315
                        //     $value = formatDecimalInConfigSettings($focus->$fieldName, true);
                        // } else {
                        //     $value = formatDecimalInConfigSettings($focus->$fieldName, false);
                        // }
                            $value = SticUtils::formatDecimalInConfigSettings($focus->$fieldName, true);
                        } else {
                            $value = SticUtils::formatDecimalInConfigSettings($focus->$fieldName, false);
                        }
                        // END STIC Custom
                        $repl_arr[$key . "_" . $fieldName] = $value;
                    // STIC Custom 20250424 JBL - Añadimos funcionalidad Addon campo de Firma
                    // https://github.com/SinergiaTIC/SinergiaCRM/pull/315
                    } elseif ($field_def['type'] == 'Signature') {
                        $repl_arr[$key . "_" . $fieldName] = '<img src="' . $focus->$fieldName . '" width="'.$field_def['width'].'" height="'.$field_def['height'].'">';
                    // END STIC Custom
                    // STIC-Custom 20221013 AAM - Parsing date/datetime fields when the bean is being modified
                    // STIC#883
                    } elseif ((isset($field_def['dbType']) && $field_def['dbType'] == 'date') || 
                            (isset($field_def['dbType']) && $field_def['dbType'] == 'datetime') || 
                            (!isset($field_def['dbType']) && isset($field_def['type']) &&  ($field_def['type'] == 'date' || $field_def['type'] == 'datetime'))) {                    
                        global $disable_date_format;
                        if($focus->$fieldName && ($focus->fetched_row || $disable_date_format)) {
                            $oldValueDisableDateFormat = $disable_date_format;
                            $disable_date_format = false;
                            $value = self::getUserDateDatetimeFormat($focus->$fieldName);
                            $repl_arr[$key . "_" . $fieldName] = $value; 
                            $disable_date_format = $oldValueDisableDateFormat;
                        } else {
                            $repl_arr[$key . "_" . $fieldName] = $focus->{$fieldName};
                        }
                    // END STIC
                    } else {
                        $repl_arr[$key . "_" . $fieldName] = $focus->{$fieldName};
                    }
                }
            } // end foreach()
        // STIC Custom 20250723 JCH - Attempt to read property "field_defs" on false
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/726 
        }
        // END STIC Custom
        krsort($repl_arr);
        reset($repl_arr);

        foreach ($repl_arr as $name => $value) {
            if ((strpos($name, 'product_discount') !== false || strpos($name, 'quotes_discount') !== false) && strpos($name, '_amount') === false) {
                if ($value !== '' && isset($repl_arr['aos_products_quotes_discount'])) {
                    if ($isValidator->isPercentageField($repl_arr['aos_products_quotes_discount'])) {
                        $sep = get_number_separators();
                        $value = rtrim(
                            rtrim(format_number($value), '0'),
                            $sep[1]
                        ) . $app_strings['LBL_PERCENTAGE_SYMBOL'];
                    }
                } else {
                    $value = '';
                }
            }

            if ($name === 'aos_products_product_image' && !empty($value)) {
                $value = '<img src="' . $value . '" class="img-responsive"/>';
            }

            if ($name === 'aos_products_quotes_product_qty') {
                $sep = get_number_separators();
                // STIC-Custom 20230623 AAM - Allowing decimals in the product_qty field
                // STIC#1144
                // $value = rtrim(rtrim(format_number($value), '0'), $sep[1]);
                // First, standarizing decimal separator
                $value = str_replace(',', '.', $value); 
                // Making sure there are two decimals in the value

                // STIC Custom 20250206 JBL - Avoid Uncaught TypeError in number_format
                // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
                // $value = number_format($value, 2, $sep[1], $sep[0]);
                $value = number_format((float) $value, 2, $sep[1], $sep[0]);
                // End STIC Custom
                // END STIC-Custom
            }

            if ($isValidator->isPercentageField($name)) {
                $sep = get_number_separators();

                $precision = $locale->getPrecision($current_user);

                if ($precision === '0') {
                    $params = [
                        'percentage' => true,
                    ];
                    $value = format_number($value, $precision, $precision, $params);
                } else {
                    $value = rtrim(rtrim(format_number($value), '0'), $sep[1]) . $app_strings['LBL_PERCENTAGE_SYMBOL'];
                }
            }
            if (!empty($focus->field_defs[$name]['dbType'])
                && $focus->field_defs[$name]['dbType'] === 'datetime'
                && (strpos($name, 'date') > 0 || strpos($name, 'expiration') > 0)
            ) {
                if ($value != '') {
                    $dt = explode(' ', $value);
                    $value = $dt[0];
                    if (isset($dt[1]) && $dt[1] != '') {
                        if (strpos($dt[1], 'am') > 0 || strpos($dt[1], 'pm') > 0) {
                            $value = $dt[0] . ' ' . $dt[1];
                        }
                    }
                }
            }
            if ($value != '' && is_string($value)) {
                $string = str_replace("\$$name", $value, (string) $string);
            } elseif (strpos($name, 'address') > 0) {
                $string = str_replace("\$$name<br />", '', (string) $string);
                $string = str_replace("\$$name <br />", '', $string);
                $string = str_replace("\$$name", '', $string);
            } else {
                $string = str_replace("\$$name", '&nbsp;', (string) $string);
            }
        }

        return $string;
    }

    /**
     * STIC-Custom AAM 20221013 - Function use to format the date/datetime field into user format
     * STIC#883
     * Some date field definition have the wrong "type" property in their vardef. Such as the field last_rev_create_date from Documents.
     * Therefore we check the field format before formatting
     * STIC#916
     *
     * @param String $date
     * @return String
     */
    private static function getUserDateDatetimeFormat($date) {
        $formatDate = 'Y-m-d';
        $validDate = DateTime::createFromFormat($formatDate, $date);
        $formatDateTime = 'Y-m-d H:i:s';
        $validDateTime = DateTime::createFromFormat($formatDateTime, $date);
        if ($validDate && $validDate->format($formatDate) === $date) {
            // Date field
            global $current_user, $timedate;
            $date = $timedate->fromDbDate($date);
            return $timedate->asUserDate($date, true, $current_user);
        } elseif ($validDateTime && $validDateTime->format($formatDateTime) === $date) {
            // Datetime field
            global $current_user, $timedate;
            $date = $timedate->fromDB($date);
            return $timedate->asUser($date, $current_user);
        } else { 
            return $date;
        }
    }

    /**
     * Get all subpanel (one-to-many) relationships for a bean
     *
     * @param SugarBean $bean
     * @return array Relationship definitions
     */
    public static function getSubpanelRelationships(SugarBean $bean): array
    {
        $relationships = array();

        if (!isset($bean->module_dir)) {
            return $relationships;
        }

        require_once 'include/SubPanel/SubPanelDefinitions.php';
        $subPanelDefinitions = new SubPanelDefinitions($bean);

        if (!isset($subPanelDefinitions->layout_defs['subpanel_setup'])) {
            return $relationships;
        }

        foreach ($subPanelDefinitions->layout_defs['subpanel_setup'] as $subpanelKey => $subpanelDef) {
            if (!isset($subpanelDef['module'])) {
                continue;
            }

            $module = $subpanelDef['module'];
            if (empty($module)) {
                continue;
            }

            $relatedBean = BeanFactory::newBean($module);
            if (!$relatedBean) {
                continue;
            }

            $fields = array();
            if (isset($relatedBean->field_defs) && is_array($relatedBean->field_defs)) {
                foreach ($relatedBean->field_defs as $relFieldName => $relFieldDef) {
                    if (isset($relFieldDef['name']) && $relFieldDef['name'] !== '') {
                        if (isset($relFieldDef['reportable']) && !$relFieldDef['reportable']) {
                            continue;
                        }
                        if (isset($relFieldDef['type']) && in_array($relFieldDef['type'], array('id', 'link'))) {
                            continue;
                        }
                        if (isset($relFieldDef['dbType']) && strtolower($relFieldDef['dbType']) === 'id') {
                            continue;
                        }

                        $fields[$relFieldName] = isset($relFieldDef['vname']) 
                            ? translate($relFieldDef['vname'], $module) 
                            : $relFieldName;
                    }
                }
            }

            if (!empty($fields)) {
                // Sort fields alphabetically by translated label
                asort($fields);
                
                $subpanelTitleKey = isset($subpanelDef['title_key']) ? $subpanelDef['title_key'] : $subpanelKey;
                $subpanelTitle = translate($subpanelTitleKey, $bean->module_dir);
                $relationships[$subpanelKey] = array(
                    'module' => $module,
                    'table_name' => $relatedBean->table_name,
                    'fields' => $fields,
                    'relationship' => $subpanelKey,
                    'name' => $subpanelTitle
                );
            }
        }
        
        // Sort subpanels alphabetically by name
        uasort($relationships, function($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });
        
        return $relationships;
    }

    /**
     * Parse subpanel/related records loops in template
     *
     * @param string $template
     * @param array $beanArr
     * @return string
     */
    public static function parseSubpanels(string $template, array $beanArr): string
    {
        $GLOBALS['log']->fatal('PARSE_SUBPANELS_START: template length=' . strlen($template));
        $GLOBALS['log']->fatal('PARSE_SUBPANELS_START: pattern=' . self::SUBPANEL_START_PATTERN);
        
        $matches = array();
        
        if (!preg_match_all(self::SUBPANEL_START_PATTERN, $template, $matches, PREG_OFFSET_CAPTURE)) {
            $GLOBALS['log']->fatal('PARSE_SUBPANELS: no matches found');
            return $template;
        }

        $GLOBALS['log']->fatal('PARSE_SUBPANELS: found ' . count($matches[0]) . ' matches');
        
        $result = $template;
        $offset = 0;
        
        $subpanelStacks = array();
        
        foreach ($matches[0] as $index => $match) {
            $fullMatch = $match[0];
            $matchOffset = $match[1];
            
            $subpanelKey = $matches[1][$index][0];
            $parentKey = isset($matches[3][$index][0]) ? $matches[3][$index][0] : null;
            
            $GLOBALS['log']->fatal('START_MATCH: key=' . $subpanelKey . ' parent=' . $parentKey . ' offset=' . $matchOffset . ' match=' . $fullMatch);
            
            $subpanelStacks[] = array(
                'key' => $subpanelKey,
                'parent' => $parentKey,
                'offset' => $matchOffset,
                'full_match' => $fullMatch,
                'end_offset' => null
            );
        }
        
        $endMatches = array();
        $GLOBALS['log']->fatal('END_PATTERN: ' . self::SUBPANEL_END_PATTERN);
        if (preg_match_all(self::SUBPANEL_END_PATTERN, $template, $endMatches, PREG_OFFSET_CAPTURE)) {
            $GLOBALS['log']->fatal('END_MATCHES: found ' . count($endMatches[0]));
            foreach ($endMatches[0] as $index => $match) {
                $endKey = $endMatches[1][$index][0];
                $endParent = isset($endMatches[3][$index][0]) ? $endMatches[3][$index][0] : null;
                $endOffset = $match[1];
                
                $GLOBALS['log']->fatal('END_MATCH: key=' . $endKey . ' parent=' . $endParent . ' offset=' . $endOffset . ' match=' . $match[0]);
                
                foreach ($subpanelStacks as &$sp) {
                    if ($sp['key'] === $endKey && $sp['parent'] === $endParent && $sp['end_offset'] === null) {
                        $sp['end_offset'] = $endOffset + strlen($match[0]);
                        $GLOBALS['log']->fatal('MATCHED_END: set end_offset=' . $sp['end_offset']);
                        break;
                    }
                }
            }
        }
        
        usort($subpanelStacks, function($a, $b) {
            return $b['offset'] - $a['offset'];
        });

        $GLOBALS['log']->fatal('SUBPANEL_STACKS_COUNT: ' . count($subpanelStacks));
        
        foreach ($subpanelStacks as $subpanel) {
            $GLOBALS['log']->fatal('PROCESS_SUBPANEL: key=' . $subpanel['key'] . ' end_offset=' . $subpanel['end_offset']);
            if ($subpanel['end_offset'] === null) {
                $GLOBALS['log']->fatal('PROCESS_SUBPANEL: skipping - no end_offset');
                continue;
            }
            
            $fullMatch = is_array($subpanel['full_match']) ? ($subpanel['full_match'][0] ?? '') : $subpanel['full_match'];
            if (empty($fullMatch)) {
                continue;
            }
            
            $endMatchStr = is_array($endMatches[0][0]) ? ($endMatches[0][0][0] ?? '') : $endMatches[0][0];
            
            $GLOBALS['log']->fatal('SUBPANEL_CALC: fullMatch=' . $fullMatch . ' endMatchStr=' . $endMatchStr . ' startOffset=' . $subpanel['offset'] . ' endOffset=' . $subpanel['end_offset']);
            
            $loopStart = $subpanel['offset'] + strlen($fullMatch);
            $loopContentLength = $subpanel['end_offset'] - $subpanel['offset'] - strlen($fullMatch) - strlen($endMatchStr);
            $loopContent = substr($template, $loopStart, $loopContentLength);
            
            $parentKey = $subpanel['parent'];
            $subpanelKey = $subpanel['key'];
            
            $GLOBALS['log']->fatal('SUBPANEL_DEBUG: key=' . $subpanelKey . ' parent=' . $parentKey . ' beanArr=' . json_encode(array_keys($beanArr)));
            
            if ($parentKey && isset($beanArr[$parentKey])) {
                $parentBean = BeanFactory::getBean($parentKey, $beanArr[$parentKey]);
                if ($parentBean) {
                    $parsedContent = self::parseNestedSubpanel($loopContent, $parentBean, $subpanelKey);
                } else {
                    $parsedContent = '';
                }
            } else {
                $parsedContent = '';
                
                foreach ($beanArr as $beanName => $beanId) {
                    $bean = BeanFactory::getBean($beanName, $beanId);
                    $GLOBALS['log']->fatal('SUBPANEL_DEBUG: bean=' . $beanName . ' id=' . $beanId . ' beanObj=' . (is_object($bean) ? 'yes' : 'no'));
                    if ($bean) {
                        $parsedContent .= self::parseNestedSubpanel($loopContent, $bean, $subpanelKey);
                    }
                }
            }
            
            $fullLoopLength = $subpanel['end_offset'] - $subpanel['offset'];
            $result = substr($result, 0, $subpanel['offset']) . $parsedContent . substr($result, $subpanel['end_offset']);
        }

        $GLOBALS['log']->fatal('PARSE_SUBPANELS_END: result length=' . strlen($result));
        
        return $result;
    }

    /**
     * Handle nested subpanel loops
     *
     * @param string $template
     * @param SugarBean $parentBean
     * @param string $relationshipName
     * @return string
     */
    public static function parseNestedSubpanel(
        string $template, 
        SugarBean $parentBean, 
        string $relationshipName
    ): string {
        $GLOBALS['log']->fatal('NESTED_DEBUG: relationship=' . $relationshipName . ' parentBean=' . get_class($parentBean) . ' id=' . $parentBean->id);
        
        $relatedBeans = self::getRelatedRecords($parentBean, $relationshipName);
        
        $GLOBALS['log']->fatal('NESTED_DEBUG: found ' . count($relatedBeans) . ' related beans');
        
        if (empty($relatedBeans)) {
            return '';
        }

        $nestedPattern = self::SUBPANEL_START_PATTERN;
        $hasNested = preg_match($nestedPattern, $template);
        
        $result = '';
        
        foreach ($relatedBeans as $relatedBean) {
            $rowContent = $template;
            
            if ($hasNested) {
                $subpanelRelationships = self::getSubpanelRelationships($relatedBean);
                
                preg_match_all(self::SUBPANEL_START_PATTERN, $rowContent, $nestedMatches, PREG_OFFSET_CAPTURE);
                
                $nestedSubpanels = array();
                foreach ($nestedMatches[0] as $index => $match) {
                    $nestedSubpanels[] = array(
                        'key' => $nestedMatches[1][$index][0],
                        'offset' => $match[1]
                    );
                }
                
                usort($nestedSubpanels, function($a, $b) {
                    return $b['offset'] - $a['offset'];
                });
                
                foreach ($nestedSubpanels as $nested) {
                    $nestedRelName = $nested['key'];
                    if (isset($subpanelRelationships[$nestedRelName])) {
                        $nestedContent = self::parseNestedSubpanel($rowContent, $relatedBean, $nestedRelName);
                        
                        if (preg_match('/<!--\$subpanel:' . $nestedRelName . '-->(.*?)<!--\/\$subpanel:' . $nestedRelName . '-->/is', $rowContent, $nestedMatch)) {
                            $rowContent = str_replace($nestedMatch[0], $nestedContent, $rowContent);
                        }
                    }
                }
            }
            
            $tableName = $relatedBean->table_name;
            $GLOBALS['log']->fatal('NESTED: parsing with tableName=' . $tableName . ' template=' . substr($rowContent, 0, 200));
            $rowContent = self::parse_template_bean($rowContent, $tableName, $relatedBean);
            $GLOBALS['log']->fatal('NESTED: after parse_template_bean=' . substr($rowContent, 0, 200));
            
            $result .= $rowContent;
        }

        $GLOBALS['log']->fatal('NESTED_END: result=' . substr($result, 0, 200));
        
        return $result;
    }

    /**
     * Get related records via a relationship
     *
     * @param SugarBean $bean
     * @param string $relationship
     * @return array SugarBean[]
     */
    public static function getRelatedRecords(SugarBean $bean, string $relationship): array
    {
        $relatedBeans = array();
        
        $GLOBALS['log']->fatal('GETRELATED_DEBUG: bean=' . get_class($bean) . ' relationship=' . $relationship);
        
        if (!isset($bean->field_defs[$relationship])) {
            $GLOBALS['log']->fatal('GETRELATED_DEBUG: relationship not in field_defs');
            return $relatedBeans;
        }
        
        $fieldDef = $bean->field_defs[$relationship];
        $GLOBALS['log']->fatal('GETRELATED_DEBUG: fieldDef type=' . ($fieldDef['type'] ?? 'none') . ' bean_name=' . ($fieldDef['bean_name'] ?? 'none') . ' module=' . ($fieldDef['module'] ?? 'none'));
        
        if (isset($fieldDef['type']) && $fieldDef['type'] === 'link') {
            if (method_exists($bean, 'get_linked_beans')) {
                $beanName = isset($fieldDef['bean_name']) ? $fieldDef['bean_name'] : (isset($fieldDef['module']) ? BeanFactory::getBeanName($fieldDef['module']) : null);
                $GLOBALS['log']->fatal('GETRELATED_DEBUG: calling get_linked_beans beanName=' . $beanName);
                if ($beanName) {
                    $relatedBeans = $bean->get_linked_beans($relationship, $beanName, array(), 0, 100);
                    $GLOBALS['log']->fatal('GETRELATED_DEBUG: got ' . count($relatedBeans) . ' beans');
                }
            }
        }
        
        return $relatedBeans;
    }

    /**
     * Get available subpanel fields for template editor
     *
     * @param string $moduleName
     * @return array
     */
    public static function getSubpanelFieldsForModule(string $moduleName): array
    {
        $bean = BeanFactory::getBean($moduleName);
        
        if (!$bean) {
            return array();
        }
        
        return self::getSubpanelRelationships($bean);
    }
}
