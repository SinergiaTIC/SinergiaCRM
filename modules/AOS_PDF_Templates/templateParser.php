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
    // STIC-Custom - AAM - 20260519 - Subpanel and aggregate pattern constants for PDF templates
    const SUBPANEL_START_PATTERN = '/<!--\$subpanel:([a-z0-9_]+)(?::([a-z0-9_]+))?(?::([^>]+?))?-->/i';
    const SUBPANEL_END_PATTERN = '/<!--\/\$subpanel:([a-z0-9_]+)(?::([a-z0-9_]+))?-->/i';
    const AGGREGATE_PATTERN = '/\$(SUM|COUNT|AVG|MIN|MAX):([a-z0-9_]+):([a-z0-9_]+)/i';

    /** @var array Aggregate values keyed by "FUNC:table:field" */
    private static $aggregates = [];
    /** @var array All related beans grouped by table name for aggregate computation */
    private static $allBeansByTable = [];
    // END STIC-Custom

    public static function parse_template($string, $bean_arr)
    {
        foreach ($bean_arr as $bean_name => $bean_id) {
            $focus = BeanFactory::getBean($bean_name, $bean_id);

            // STIC-Custom 20260420 ART - Avoid "Attempt to read property 'field_defs' on null" when bean is not found
            // https://github.com/SinergiaTIC/SinergiaCRM/pull/1059
            if (!is_object($focus) || empty($focus->field_defs)) {
                continue;
            }
            // END STIC Custom

            $string = templateParser::parse_template_bean($string, $focus->table_name, $focus);

            foreach ($focus->field_defs as $focus_name => $focus_arr) {
                // STIC-Custom 20260420 ART - Avoid "Attempt to read property 'id_name' on null" when field definition is not correct
                // https://github.com/SinergiaTIC/SinergiaCRM/pull/1059
                // if ($focus_arr['type'] == 'relate') {
                //     if (isset($focus_arr['module']) && $focus_arr['module'] != '' && $focus_arr['module'] != 'EmailAddress') {
                //         $idName = $focus_arr['id_name'];
                //         $relate_focus = BeanFactory::getBean($focus_arr['module'], $focus->$idName);

                //         $string = templateParser::parse_template_bean($string, $focus_arr['name'], $relate_focus);
                //     }
                if (isset($focus_arr['type']) && $focus_arr['type'] == 'relate') {
                    if (isset($focus_arr['module']) && $focus_arr['module'] != '' && $focus_arr['module'] != 'EmailAddress') {
                        $idName = $focus_arr['id_name'] ?? '';

                        if ($idName === '' || !isset($focus->{$idName})) {
                            continue;
                        }

                        $relate_focus = BeanFactory::getBean($focus_arr['module'], $focus->{$idName});

                        if (!empty($focus_arr['name'])) {
                            $string = templateParser::parse_template_bean($string, $focus_arr['name'], $relate_focus);
                        }
                    }
                    // END STIC Custom
                }
            }
        }

        // STIC-Custom - AAM - 20260519 - Parse subpanel loops for related record data
        $string = templateParser::parseSubpanels($string, $bean_arr);
        // END STIC-Custom

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
                            // STIC-Custom 20260420 ART - Avoid translating full multienum list when value is empty
                            // https://github.com/SinergiaTIC/SinergiaCRM/pull/1059
                            // If $mVal is empty, translate() can return the full options array
                            if ($mVal === '' || $mVal === null) {
                                continue;
                            }
                            // END STIC Custom

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
    // END STIC

    // STIC-Custom - AAM - 20260519 - Get all subpanel (one-to-many) relationships for a bean
    public static function getSubpanelRelationships(SugarBean $bean): array
    {
        $relationships = array();
        if (!isset($bean->module_dir)) { return $relationships; }
        require_once 'include/SubPanel/SubPanelDefinitions.php';
        $subPanelDefinitions = new SubPanelDefinitions($bean);
        if (!isset($subPanelDefinitions->layout_defs['subpanel_setup'])) { return $relationships; }
        foreach ($subPanelDefinitions->layout_defs['subpanel_setup'] as $subpanelKey => $subpanelDef) {
            if (!isset($subpanelDef['module'])) { continue; }
            $module = $subpanelDef['module'];
            if (empty($module)) { continue; }
            $relatedBean = BeanFactory::newBean($module);
            if (!$relatedBean) { continue; }
            $fields = array();
            if (isset($relatedBean->field_defs) && is_array($relatedBean->field_defs)) {
                foreach ($relatedBean->field_defs as $relFieldName => $relFieldDef) {
                    if (isset($relFieldDef['name']) && $relFieldDef['name'] !== '') {
                        if (isset($relFieldDef['reportable']) && !$relFieldDef['reportable']) { continue; }
                        if (isset($relFieldDef['type']) && in_array($relFieldDef['type'], array('id', 'link'))) { continue; }
                        if (isset($relFieldDef['dbType']) && strtolower($relFieldDef['dbType']) === 'id') { continue; }
                        $fields[$relFieldName] = isset($relFieldDef['vname']) ? translate($relFieldDef['vname'], $module) : $relFieldName;
                    }
                }
            }
            if (!empty($fields)) {
                asort($fields);
                $subpanelTitleKey = isset($subpanelDef['title_key']) ? $subpanelDef['title_key'] : $subpanelKey;
                $subpanelTitle = translate($subpanelTitleKey, $bean->module_dir);
                $relationships[$subpanelKey] = array('module' => $module, 'table_name' => $relatedBean->table_name, 'fields' => $fields, 'relationship' => $subpanelKey, 'name' => $subpanelTitle);
            }
        }
        uasort($relationships, function($a, $b) { return strcasecmp($a['name'], $b['name']); });
        return $relationships;
    }

    // STIC-Custom - AAM - 20260519 - Parse subpanel loops in PDF template
    public static function parseSubpanels(string $template, array $beanArr): string
    {
        $GLOBALS['log']->fatal('PARSE_SUBPANELS_START: template length=' . strlen($template));
        $matches = array();
        if (!preg_match_all(self::SUBPANEL_START_PATTERN, $template, $matches, PREG_OFFSET_CAPTURE)) {
            $GLOBALS['log']->fatal('PARSE_SUBPANELS: no matches found');
            return $template;
        }
        $GLOBALS['log']->fatal('PARSE_SUBPANELS: found ' . count($matches[0]) . ' matches');
        self::$aggregates = [];
        self::$allBeansByTable = [];
        $result = $template;
        $subpanelStacks = array();
        foreach ($matches[0] as $index => $match) {
            $subpanelKey = $matches[1][$index][0];
            $parentKey = isset($matches[2][$index][0]) ? $matches[2][$index][0] : null;
            $optionsStr = isset($matches[3][$index][0]) ? $matches[3][$index][0] : null;
            if ($parentKey !== null && strpos($parentKey, '=') !== false) { $optionsStr = $parentKey; $parentKey = null; }
            $subpanelStacks[] = array('key' => $subpanelKey, 'parent' => $parentKey, 'options' => self::parseSubpanelOptions($optionsStr), 'offset' => $match[1], 'full_match' => $match[0], 'end_offset' => null);
        }
        $endMatches = array();
        if (preg_match_all(self::SUBPANEL_END_PATTERN, $template, $endMatches, PREG_OFFSET_CAPTURE)) {
            foreach ($endMatches[0] as $index => $match) {
                $endKey = $endMatches[1][$index][0];
                $endParent = isset($endMatches[2][$index][0]) ? $endMatches[2][$index][0] : null;
                foreach ($subpanelStacks as &$sp) {
                    if ($sp['key'] === $endKey && $sp['parent'] === $endParent && $sp['end_offset'] === null) { $sp['end_offset'] = $match[1] + strlen($match[0]); break; }
                }
            }
        }
        usort($subpanelStacks, function($a, $b) { return $b['offset'] - $a['offset']; });
        foreach ($subpanelStacks as $subpanel) {
            if ($subpanel['end_offset'] === null) { continue; }
            $fullMatch = $subpanel['full_match'];
            if (empty($fullMatch)) { continue; }
            $loopStart = $subpanel['offset'] + strlen($fullMatch);
            $endTagLen = strlen('<!--/$subpanel:' . $subpanel['key'] . '-->');
            $loopContentLength = $subpanel['end_offset'] - $subpanel['offset'] - strlen($fullMatch) - $endTagLen;
            if ($loopContentLength < 0) $loopContentLength = 0;
            $loopContent = substr($template, $loopStart, $loopContentLength);
            $options = $subpanel['options'];
            $parsedContent = '';
            if ($subpanel['parent'] && isset($beanArr[$subpanel['parent']])) {
                $parentBean = BeanFactory::getBean($subpanel['parent'], $beanArr[$subpanel['parent']]);
                if ($parentBean) {
                    $allBeans = self::getRelatedRecords($parentBean, $subpanel['key']);
                    self::collectBeansForAggregates($allBeans);
                    $parsedContent = self::parseNestedSubpanel($loopContent, $parentBean, $subpanel['key'], self::applySubpanelOptions($allBeans, $options));
                }
            } else {
                foreach ($beanArr as $beanName => $beanId) {
                    $bean = BeanFactory::getBean($beanName, $beanId);
                    if ($bean) {
                        $allBeans = self::getRelatedRecords($bean, $subpanel['key']);
                        self::collectBeansForAggregates($allBeans);
                        $parsedContent .= self::parseNestedSubpanel($loopContent, $bean, $subpanel['key'], self::applySubpanelOptions($allBeans, $options));
                    }
                }
            }
            $result = substr($result, 0, $subpanel['offset']) . $parsedContent . substr($result, $subpanel['end_offset']);
        }
        $result = self::computeAndReplaceAggregates($result);
        $GLOBALS['log']->fatal('PARSE_SUBPANELS_END: result length=' . strlen($result));
        return $result;
    }

    // STIC-Custom - AAM - 20260520 - Collect beans for deduplicated aggregate computation
    private static function collectBeansForAggregates(array $beans): void {
        foreach ($beans as $bean) {
            if (!empty($bean->table_name)) {
                if (!isset(self::$allBeansByTable[$bean->table_name])) { self::$allBeansByTable[$bean->table_name] = []; }
                self::$allBeansByTable[$bean->table_name][$bean->id] = $bean;
            }
        }
    }

    // STIC-Custom - AAM - 20260520 - Parse subpanel loop options string
    private static function parseSubpanelOptions(?string $optionsStr): array {
        $options = array('order' => null, 'dir' => 'ASC', 'limit' => 100, 'filters' => array());
        if (empty($optionsStr)) { return $options; }
        foreach (explode(';', $optionsStr) as $pair) {
            $pair = trim($pair); if (empty($pair)) continue;
            $eqPos = strpos($pair, '='); if ($eqPos === false) continue;
            $key = strtolower(trim(substr($pair, 0, $eqPos)));
            $value = trim(substr($pair, $eqPos + 1));
            switch ($key) {
                case 'order': $options['order'] = $value; break;
                case 'dir': $options['dir'] = strtoupper($value) === 'DESC' ? 'DESC' : 'ASC'; break;
                case 'limit': $options['limit'] = max(1, (int)$value); break;
                case 'filter': $parts = explode(':', $value, 3); if (count($parts) >= 2) { $options['filters'][] = array('field' => $parts[0], 'op' => $parts[1] ?? 'eq', 'value' => $parts[2] ?? ''); } break;
            }
        }
        return $options;
    }

    // STIC-Custom - AAM - 20260520 - Apply sorting, filtering, and limiting to beans
    private static function applySubpanelOptions(array $beans, array $options): array {
        if (empty($beans)) return $beans;
        if (!empty($options['filters'])) { $beans = self::applyFilters($beans, $options['filters']); }
        if ($options['order'] !== null && !empty($beans)) {
            $orderField = $options['order']; $direction = $options['dir'];
            usort($beans, function($a, $b) use ($orderField, $direction) {
                $va = $a->$orderField ?? ''; $vb = $b->$orderField ?? '';
                $cmp = (is_numeric($va) && is_numeric($vb)) ? ((float)$va <=> (float)$vb) : strcasecmp((string)$va, (string)$vb);
                return $direction === 'DESC' ? -$cmp : $cmp;
            });
        }
        if ($options['limit'] > 0 && count($beans) > $options['limit']) { $beans = array_slice($beans, 0, $options['limit']); }
        return $beans;
    }

    // STIC-Custom - AAM - 20260520 - Apply filter conditions
    private static function applyFilters(array $beans, array $filters): array {
        return array_filter($beans, function($bean) use ($filters) {
            foreach ($filters as $filter) {
                $beanValue = $bean->{$filter['field']} ?? '';
                if (isset($bean->field_defs[$filter['field']]) && in_array($bean->field_defs[$filter['field']]['type'], ['enum','radioenum','dynamicenum']) && isset($bean->field_defs[$filter['field']]['options'])) {
                    $beanValue = translate($bean->field_defs[$filter['field']]['options'], $bean->module_dir, $beanValue);
                }
                if (!self::compareValues($beanValue, $filter['op'], $filter['value'])) { return false; }
            }
            return true;
        });
    }

    // STIC-Custom - AAM - 20260520 - Compare two values with operator
    private static function compareValues($beanValue, string $op, string $filterValue): bool {
        $op = strtolower($op);
        $bothNumeric = is_numeric($beanValue) && is_numeric($filterValue);
        switch ($op) {
            case 'eq': case '=': return $bothNumeric ? (float)$beanValue == (float)$filterValue : strcasecmp((string)$beanValue, $filterValue) === 0;
            case 'neq': case '!=': case '<>': return $bothNumeric ? (float)$beanValue != (float)$filterValue : strcasecmp((string)$beanValue, $filterValue) !== 0;
            case 'gt': case '>': return $bothNumeric ? (float)$beanValue > (float)$filterValue : strcasecmp((string)$beanValue, $filterValue) > 0;
            case 'gte': case '>=': return $bothNumeric ? (float)$beanValue >= (float)$filterValue : strcasecmp((string)$beanValue, $filterValue) >= 0;
            case 'lt': case '<': return $bothNumeric ? (float)$beanValue < (float)$filterValue : strcasecmp((string)$beanValue, $filterValue) < 0;
            case 'lte': case '<=': return $bothNumeric ? (float)$beanValue <= (float)$filterValue : strcasecmp((string)$beanValue, $filterValue) <= 0;
            case 'like': case 'contains': return stripos((string)$beanValue, $filterValue) !== false;
            case 'in': foreach (array_map('trim', explode(',', $filterValue)) as $item) { if (($bothNumeric && (float)$beanValue == (float)$item) || strcasecmp((string)$beanValue, $item) === 0) return true; } return false;
            default: return strcasecmp((string)$beanValue, $filterValue) === 0;
        }
    }

    // STIC-Custom - AAM - 20260520 - Compute aggregates and replace placeholders in template
    private static function computeAndReplaceAggregates(string $template): string {
        if (empty(self::$allBeansByTable)) { return $template; }
        if (!preg_match_all(self::AGGREGATE_PATTERN, $template, $aggMatches)) { return $template; }
        $replacements = [];
        foreach ($aggMatches[0] as $idx => $fullMatch) {
            $func = strtoupper($aggMatches[1][$idx]); $table = $aggMatches[2][$idx]; $field = $aggMatches[3][$idx];
            if (isset($replacements[$fullMatch])) continue;
            $beans = self::$allBeansByTable[$table] ?? [];
            $values = array();
            foreach ($beans as $bean) { if ($func === 'COUNT') { $values[] = 1; } else { $val = $bean->$field ?? null; if ($val !== null && $val !== '') { $values[] = is_numeric($val) ? (float)$val : $val; } } }
            if (empty($values)) { $replacements[$fullMatch] = ($func === 'COUNT' ? '0' : ''); continue; }
            $allNumeric = array_reduce($values, function($c, $v) { return $c && is_numeric($v); }, true);
            switch ($func) {
                case 'SUM': $replacements[$fullMatch] = $allNumeric ? number_format(array_sum($values), 2, ',', '.') : ''; break;
                case 'COUNT': $replacements[$fullMatch] = (string)count($values); break;
                case 'AVG': $replacements[$fullMatch] = ($allNumeric && count($values) > 0) ? number_format(array_sum($values) / count($values), 2, ',', '.') : ''; break;
                case 'MIN': $replacements[$fullMatch] = $allNumeric ? number_format(min($values), 2, ',', '.') : (is_string($values[0]) ? min($values) : ''); break;
                case 'MAX': $replacements[$fullMatch] = $allNumeric ? number_format(max($values), 2, ',', '.') : (is_string($values[0]) ? max($values) : ''); break;
            }
        }
        foreach ($replacements as $search => $replacement) { $template = str_replace($search, $replacement, $template); }
        return $template;
    }

    // STIC-Custom - AAM - 20260519 - Handle nested subpanel loop rendering
    public static function parseNestedSubpanel(string $template, SugarBean $parentBean, string $relationshipName, array $relatedBeans = null): string {
        $GLOBALS['log']->fatal('NESTED_DEBUG: relationship=' . $relationshipName);
        if ($relatedBeans === null) { $relatedBeans = self::getRelatedRecords($parentBean, $relationshipName); }
        $GLOBALS['log']->fatal('NESTED_DEBUG: found ' . count($relatedBeans) . ' related beans');
        if (empty($relatedBeans)) { return ''; }
        $hasNested = preg_match(self::SUBPANEL_START_PATTERN, $template);
        $result = '';
        foreach ($relatedBeans as $relatedBean) {
            // STIC-Custom - AAM - 20260520 - Fully load bean to populate all fields
            if (method_exists($relatedBean, 'retrieve') && !empty($relatedBean->id)) { $relatedBean->retrieve($relatedBean->id); }
            $rowContent = $template;
            if ($hasNested) {
                $subpanelRelationships = self::getSubpanelRelationships($relatedBean);
                preg_match_all(self::SUBPANEL_START_PATTERN, $rowContent, $nestedMatches, PREG_OFFSET_CAPTURE);
                $nestedSubpanels = array();
                foreach ($nestedMatches[0] as $ni => $nm) { $nestedSubpanels[] = array('key' => $nestedMatches[1][$ni][0], 'offset' => $nm[1]); }
                usort($nestedSubpanels, function($a, $b) { return $b['offset'] - $a['offset']; });
                foreach ($nestedSubpanels as $nested) {
                    if (isset($subpanelRelationships[$nested['key']])) {
                        $nestedContent = self::parseNestedSubpanel($rowContent, $relatedBean, $nested['key']);
                        $pat = '/<!--\\$subpanel:' . $nested['key'] . '-->(.*?)<!--\\\/\\$subpanel:' . $nested['key'] . '-->/is';
                        if (preg_match($pat, $rowContent, $nm)) { $rowContent = str_replace($nm[0], $nestedContent, $rowContent); }
                    }
                }
            }
            $rowContent = self::parse_template_bean($rowContent, $relatedBean->table_name, $relatedBean);
            $result .= $rowContent;
        }
        return $result;
    }

    // STIC-Custom - AAM - 20260519 - Get related records via relationship link field
    public static function getRelatedRecords(SugarBean $bean, string $relationship): array {
        $relatedBeans = array();
        if (!isset($bean->field_defs[$relationship])) { return $relatedBeans; }
        $fieldDef = $bean->field_defs[$relationship];
        if (isset($fieldDef['type']) && $fieldDef['type'] === 'link' && method_exists($bean, 'get_linked_beans')) {
            $beanName = isset($fieldDef['bean_name']) ? $fieldDef['bean_name'] : (isset($fieldDef['module']) ? BeanFactory::getBeanName($fieldDef['module']) : null);
            if ($beanName) { $relatedBeans = $bean->get_linked_beans($relationship, $beanName, array(), 0, 9999); }
        }
        return $relatedBeans;
    }

    // STIC-Custom - AAM - 20260519 - Get available subpanel fields for template editor UI
    public static function getSubpanelFieldsForModule(string $moduleName): array {
        $bean = BeanFactory::getBean($moduleName);
        return $bean ? self::getSubpanelRelationships($bean) : array();
    }
    // END STIC-Custom

}