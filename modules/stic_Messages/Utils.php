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

// global $messageableModules;

// This array can be extended in custom folder to add new modules or change default fields


class stic_MessagesUtils {
    /* List of modules from which messages can be sent.
     * If new modules must be included, this list can be modified from a custom file
     */
    public static $messageableModules = array(
        'Contacts' => array('phoneField' => 'phone_mobile', 'name' => "concat(first_name, ' ', last_name)", 'dbTable' => 'contacts' ),
        'Accounts' => array('phoneField' => 'phone_office', 'name' => 'name', 'dbTable' => 'accounts'),
        'Leads' => array('phoneField' => 'phone_mobile', 'name' => "concat(first_name, ' ', last_name)", 'dbTable' => 'leads'),
        'Employees' => array('phoneField' => 'phone_mobile', 'name' => "concat(first_name, ' ', last_name)", 'dbTable' => 'users'),
        'Users' => array('phoneField' => 'phone_mobile', 'name' => "concat(first_name, ' ', last_name)", 'dbTable' => 'users'),
    );

    /** 
     * Returns the list of modules from which messages can be sent
     * @return array Modules enabled
     */
    public static function getMessageableModules() {
        $modules = array_keys(self::$messageableModules);
        asort($modules);
        return $modules;
    }

    /**
     * Generates the list of possible fields used on the sendMessage action for the module indicated
     * @param string Module name 
     * @return array List of fields to be shown on the action
     */
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

    /**
     * Return the default phone to be used to send messages to bean received
     * @param object The bean
     * @return string The phone number
     */
    public static function getPhoneForMessage($bean) {
    
        $fieldName = self::$messageableModules[$bean->module_name]['phoneField'];
        if ($fieldName !== null){
            return $bean->$fieldName;
        }
        return '';
    }

    /**
     * Return the default phone field to be used to send messages to the module indicated
     * @param string Module name
     * @return string The field name
     */
    public static function getPhoneFieldNameForMessage($moduleName) {
    
        $fieldName = self::$messageableModules[$moduleName]['phoneField'];

        return $fieldName;
    }

    /** 
     * Gets the field to be used on a SQL query to retrieve the name, depending on the module 
     * @param string Module name
     * @return string The filed or function to be used in a SQL query
     */
    public static function getNameFieldNameForMessage($moduleName) {
    
        $fieldName = self::$messageableModules[$moduleName]['name'];

        return $fieldName;
    }
        /** 
     * Gets the table name to be used on a SQL query to retrieve data from the module
     * @param string Module name
     * @return string The table name
     */
    public static function getTableNameForMessage($moduleName) {
    
        $tableName = self::$messageableModules[$moduleName]['dbTable'];

        return $tableName;
    }

    /** 
     * Function used to retrieve the messages subpanel data related to the bean being displayed.
     * @param array $params An array of parameters used to generate the query.
     * @return array|string The SQL query as an array if 'return_as_array' is true, or as a string otherwise.
     *
     */
    public static function get_stic_messages($type = null) {
        $beanId = $_REQUEST['record'];
        $statusList = $type['status']??'';
        $statusCond = empty($statusList)? '' : " and stic_messages.status IN ({$statusList})";
        $return_array['select'] = 'SELECT stic_messages.id ';
        $return_array['from'] = ' FROM stic_messages ';
        $return_array['where'] = " WHERE stic_messages.parent_id = '{$beanId}' {$statusCond}";
    
        if (isset($type) && ! empty($type['return_as_array'])) {
            return $return_array;
        }
    
        return $return_array['select'] . $return_array['from'] . $return_array['where'];
    }
    
    public static function get_stic_messages_summary($type = null) {
        $beanId = $_REQUEST['record'];
        $statusList = $type['status']??'';
        $statusCond = empty($statusList)? '' : " and stic_messages.status IN ({$statusList})";
        $return_array['select'] = 'SELECT * ';
        $return_array['from'] = ' FROM stic_messages ';
        $return_array['where'] = " WHERE stic_messages.parent_id = '{$beanId}' {$statusCond}";
    
        if (isset($type) && ! empty($type['return_as_array'])) {
            return $return_array;
        }
    
        return $return_array['select'] . $return_array['from'] . $return_array['where'];
    }
    
    /**
     * Adds a JS function to the output which indicates if the module stic_Messages is active.
     *
     */
    public static function echoIsMessagesModuleActive() {
        require_once('modules/stic_Settings/Utils.php');
        require_once 'modules/MySettings/TabController.php';
        $controller = new TabController();
        $currentTabs = $controller->get_system_tabs();
        if (!($currentTabs['stic_Messages'] ?? false)){
            $active = 'false';
        }
        else {
            $active = 'true';
        }

        $messagesLimit = stic_SettingsUtils::getSetting('MESSAGES_LIMIT');

        echo "<script type='text/javascript'>function getMessagesActive() {return {$active};} function getMessagesLimit() {return {$messagesLimit};} </script>";
        echo getVersionedScript("modules/stic_Messages/stic_Messages.js");
    }

    public static function fillDynamicListMessageTemplate()
    {
        $emailTemplatesFocus = BeanFactory::newBean('EmailTemplates');
        $emailTemplates = $emailTemplatesFocus->get_list("name", "email_templates.type='sms'", 0, -99, -99);

        $dynamic_email_template_list = array("" => translate("LBL_NONE", "app_strings"));

        foreach ($emailTemplates['list'] as $emailTemplate) {
            $dynamic_email_template_list[$emailTemplate->id] = $emailTemplate->name;
        }

        $GLOBALS['app_list_strings']['dynamic_message_template_list'] = $dynamic_email_template_list;
    }
}
