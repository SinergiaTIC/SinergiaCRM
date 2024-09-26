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

global $messageableModules;

// This array can be extended in custom folder to add new modules or change default fields
$messageableModules = array(
    'Contacts' => array('phoneField' => 'phone_mobile', 'name' => "concat(first_name, ' ', last_name)"),
    'Accounts' => array('phoneField' => 'phone_office', 'name' => 'name'),
    'Leads' => array('phoneField' => 'phone_mobile', 'name' => "concat(first_name, ' ', last_name)"),
    'Employees' => array('phoneField' => 'phone_mobile', 'name' => "concat(first_name, ' ', last_name)"),
    'Users' => array('phoneField' => 'phone_mobile', 'name' => "concat(first_name, ' ', last_name)"),
);


class stic_MessagesUtils {
    public static function getMessageableModules() {
        global $messageableModules;
        $modules = array_keys($messageableModules);
        asort($modules);
        return $modules;
    }

    public static function getRelatedMessageableFields($module) {
        global $beanList, $app_list_strings;
        $relPhoneFields = array();
        $checked_link = array();
        $msgModules = self::getMessageableModules();
        if ($module != '') {
            if (isset($beanList[$module]) && $beanList[$module]) {
                $mod = new $beanList[$module]();

                foreach ($mod->get_related_fields() as $field) {
                    if (isset($field['link'])) {
                        $checked_link[] = $field['link'];
                    }
                    if (!isset($field['module']) || !in_array($field['module'], $msgModules) || (isset($field['dbType']) && $field['dbType'] == "id")) {
                        continue;
                    }
                    $relPhoneFields[$field['link'] ? $field['link'] : $field['name']] = translate($field['module']) . ": "
                        . trim(translate($field['vname'], $mod->module_name), ":");
                }

                foreach ($mod->get_linked_fields() as $field) {
                    if (!in_array($field['name'], $checked_link) && !in_array($field['relationship'], $checked_link)) {
                        if (isset($field['module']) && $field['module'] != '') {
                            $rel_module = $field['module'];
                        } elseif ($mod->load_relationship($field['name'])) {
                            $relField = $field['name'];
                            $rel_module = $mod->$relField->getRelatedModuleName();
                        }

                        if (in_array($rel_module, $msgModules)) {
                            if (isset($field['vname']) && $field['vname'] != '') {
                                $relPhoneFields[$field['name']] = $app_list_strings['moduleList'][$rel_module] . ' : ' . translate($field['vname'], $mod->module_dir);
                            } else {
                                $relPhoneFields[$field['name']] = $app_list_strings['moduleList'][$rel_module] . ' : ' . $field['name'];
                            }
                        }
                    }
                }

                array_multisort($relPhoneFields, SORT_ASC, $relPhoneFields);
            }
        }
        return $relPhoneFields;
    }

    public static function getPhoneForMessage($bean) {
        global $messageableModules;
    
        $fieldName = $messageableModules[$bean->module_name]['phoneField'];
        if ($fieldName !== null){
            return $bean->$fieldName;
        }
        return '';
    }
    public static function getPhoneFieldNameForMessage($bean) {
        global $messageableModules;
    
        $fieldName = $messageableModules[$bean->module_name]['phoneField'];

        return $fieldName;
    }
    public static function getNameFieldNameForMessage($bean) {
        global $messageableModules;
    
        $fieldName = $messageableModules[$bean->module_name]['name'];

        return $fieldName;
    }
    public static function get_stic_messages($type) {
        $beanId = $_REQUEST['record'];
        $return_array['select'] = 'SELECT stic_messages.id ';
        $return_array['from'] = ' FROM stic_messages ';
        $return_array['where'] = " WHERE stic_messages.parent_id = '{$beanId}'";
    
        if (isset($type) && ! empty($type['return_as_array'])) {
            return $return_array;
        }
    
        return $return_array['select'] . $return_array['from'] . $return_array['where'];
    }
}
