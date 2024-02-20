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
class stic_Custom_Views_ModuleView
{
/*
*   Properties and Getters
*/

    private $module;
    public function getModuleName() {
        return $this->module;
    }
    private $view;
    public function getViewName() {
        return $this->view;
    }
    
    private $allModuleFieldList;
    public function getAllFields() {
        if($this->allModuleFieldList == null) {
            $this->findAllModuleFieldList();
        }
        return $this->allModuleFieldList;
    }
    public function getAllFields_as_select_options() {
        return $this->convertToSelectOptions($this->getAllFields());
    }

    private $allModuleFieldTypeList;
    public function getAllFieldTypes() {
        if($this->allModuleFieldTypeList == null) {
            $this->findAllModuleFieldList();
        }
        return $this->allModuleFieldTypeList;
    }
    public function getFieldType($field) {
        return $this->getAllFieldTypes()[$field];
    }

    private $allModuleFieldOperatorMap;
    public function getAllFieldOperatorMap() {
        if($this->allModuleFieldOperatorMap == null) {
            $this->findAllModuleFieldList();
        }
        return $this->allModuleFieldOperatorMap;
    }
    public function getAllFieldOperatorMap_as_select_options() {
        $fieldOPeratorMapOptions = array();
        foreach ($this->getAllFieldOperatorMap() as $fieldKey => $operator_list) {
            $fieldOPeratorMapOptions[$fieldKey] = $this->convertToSelectOptions($operator_list);
        }
        return $fieldOPeratorMapOptions;
    }

    private $onlyModuleViewFieldList;
    public function getOnlyViewFields() {
        if($this->onlyModuleViewFieldList == null) {
            $this->findOnlyModuleViewFieldList();
        }
        return $this->onlyModuleViewFieldList;
    }
    public function getOnlyViewFields_as_select_options() {
        return $this->convertToSelectOptions($this->getOnlyViewFields());
    }
    private $onlyModuleViewFieldOperatorMap;
    public function getOnlyViewFieldOperatorMap() {
        if($this->onlyModuleViewFieldOperatorMap == null) {
            $this->findOnlyModuleViewFieldList();
        }
        return $this->onlyModuleViewFieldOperatorMap;
    }
    public function getOnlyViewFieldOperatorMap_as_select_options() {
        $fieldOPeratorMapOptions = array();
        foreach ($this->getOnlyViewFieldOperatorMap() as $fieldKey => $operator_list) {
            $fieldOPeratorMapOptions[$fieldKey] = $this->convertToSelectOptions($operator_list);
        }
        return $fieldOPeratorMapOptions;
    }
    public function getViewFieldOperators($fieldKey) {
        return $this->getOnlyViewFieldOperatorMap()[$fieldKey];
    }
    public function getViewFieldOperators_as_select_options($fieldKey) {
        return $this->convertToSelectOptions($this->getViewFieldOperators($fieldKey));
    }

    private $panelList;
    public function getPanels() {
        if($this->panelList == null) {
            $this->findPanelTabList();
        }
        return $this->panelList;
    }
    public function getPanels_as_select_options() {
        return $this->convertToSelectOptions($this->getPanels());
    }

    private $tabList;
    public function getTabs() {
        if($this->tabList == null) {
            $this->findPanelTabList();
        }
        return $this->tabList;
    }
    public function getTabs_as_select_options() {
        return $this->convertToSelectOptions($this->getTabs());
    }


    /**
     * Constructor
     */
    public function __construct($moduleName, $viewName) {
        $this->module = $moduleName;
        $this->view = $viewName;

        $this->allModuleFieldList = null;
        $this->allModuleFieldOperatorMap = null;

        $this->onlyModuleViewFieldList = null;

        $this->panelList = null;
    }

    private function convertToSelectOptions($list) {
        return trim(preg_replace('/\s+/', ' ', get_select_options_with_id($list, "")));
    }

    private function findAllModuleFieldList() {
        global $beanList;
    
        $this->allModuleFieldList = array(); //array('' => $GLOBALS ['app_strings']['LBL_NONE']);
        $this->allModuleFieldOperatorMap = array();
        $unset = array();
        if ($this->module != '' && isset($beanList[$this->module]) && $beanList[$this->module]) {
            $mod = new $beanList[$this->module]();
            foreach ($mod->field_defs as $name => $arr) {
                if (($arr['type'] === 'link') ||
                    ($name === 'currency_name' || $name === 'currency_symbol') ||
                    (isset($arr['source']) && $arr['source'] === 'non-db' && 
                        ($arr['type'] !== 'relate' || !isset($arr['id_name'])))) {
                    continue;
                }
                //IEPA!!
                // // De moment s'ignoren els relacionats.
                // if(isset($arr['id_name'])) {
                //     continue;
                // }
    
                if (isset($arr['vname']) && $arr['vname'] !== '') {
                    $this->allModuleFieldList[$name] = rtrim(translate($arr['vname'], $mod->module_dir), ':');
                } else {
                    $this->allModuleFieldList[$name] = $name;
                }
                $this->allModuleFieldOperatorMap[$name] = $this->getValidOperators($arr['type']);
                $this->allModuleFieldTypeList[$name] = $arr['type'];
                if ($arr['type'] === 'relate' && isset($arr['id_name']) && $arr['id_name'] !== '') {
                    $unset[] = $arr['id_name'];
                }
            }
    
            foreach ($unset as $name) {
                if (isset($this->allModuleFieldList[$name])) {
                    unset($this->allModuleFieldList[$name]);
                }
                if (isset($this->allModuleFieldOperatorMap[$name])) {
                    unset($this->allModuleFieldOperatorMap[$name]);
                }
                if (isset($this->allModuleFieldTypeList[$name])) {
                    unset($this->allModuleFieldTypeList[$name]);
                }
            }
            asort($this->allModuleFieldList);
            asort($this->allModuleFieldOperatorMap);
            asort($this->allModuleFieldTypeList);
        }
    }
    
    /**
     * Get Valid operators for a given field Type
     */
    private function getValidOperators($fieldType) {
        global $app_list_strings;
    
        $validOps = array();
        switch ($fieldType) {
            case 'double':
            case 'decimal':
            case 'float':
            case 'currency':
            case 'uint':
            case 'ulong':
            case 'long':
            case 'short':
            case 'tinyint':
            case 'int':
            case 'date':
            case 'datetime':
            case 'datetimecombo':
                $validOps = array('Equal_To','Not_Equal_To','Greater_Than','Less_Than','Greater_Than_or_Equal_To','Less_Than_or_Equal_To','is_null', 'is_not_null');
                break;
            case 'enum':
            case 'multienum':
                $validOps = array('Equal_To','Not_Equal_To','is_null','is_not_null');
                break;
            default:
                $validOps = array('Equal_To','Not_Equal_To','Contains', 'Not_Contains', 'Starts_With', 'Ends_With','is_null','is_not_null');
                break;
        }
        $operatorList = array();
        foreach ($validOps as $op) {
            $operatorList[$op] = $app_list_strings['stic_custom_views_operator_list'][$op];
        }
        return $operatorList;
    }

    public function getValidActions_as_select_options($actionType) {
        return $this->convertToSelectOptions($this->getValidActions($actionType));
    }
    public function getValidActions($actionType) {
        global $app_list_strings;
        $validActions = array();
        switch($actionType) {
            case 'field_modification':
                switch($this->view) {
                    case 'editview':
                    case 'quickcreate':
                        $validActions = array('visible', 'readonly', 'required', 'fixed_value', 'fixed_text', 'color', 'background', 'bold', 'italic', 'underline', 'css_style');
                        break;
                    case 'detailview':
                        $validActions = array('visible', /*'inline',*/ 'fixed_text', 'color', 'background', 'bold', 'italic', 'underline', 'css_style');
                        break;
                }
                break;
            case 'panel_modification':
                $validActions = array('visible', 'fixed_text', 'color', 'background', 'bold', 'italic', 'underline', 'css_style');
                break;
            case 'tab_modification':
                $validActions = array('visible', 'fixed_text', 'color', 'background', 'bold', 'italic', 'underline', 'css_style');
                break;
            }
        $actionsList = array();
        foreach ($validActions as $action) {
            $actionsList[$action] = $app_list_strings['stic_custom_views_action_list'][$action];
        }
        return $actionsList;
    }

    public function getActionTypes_as_select_options() {
        return $this->convertToSelectOptions($this->getActionTypes());
    }
    public function getActionTypes() {
        global $app_list_strings;
        return $app_list_strings['stic_custom_views_action_type_list'];
    }

    public function getValidElements_as_select_options($actionType) {
        return $this->convertToSelectOptions($this->getValidElements($actionType));
    }
    public function getValidElements($actionType) {
        $validElements = array();
        switch($actionType) {
            case 'field_modification':
                $validElements = $this->getOnlyViewFields();
                break;
            case 'panel_modification':
                $validElements = $this->getPanels();
                break;
            case 'tab_modification':
                $validElements = $this->getTabs();
                break;
        }
        return $validElements;
    }

    public function getValidSections_as_select_options($actionType, $action) {
        return $this->convertToSelectOptions($this->getValidSections($actionType, $action));
    }
    public function getValidSections($actionType, $action) {
        global $app_list_strings;
        $validSections = array();
        switch($actionType) {
            case 'field_modification':
                switch($action) {
                    case 'visible':
                    case 'css_style':
                        $validSections = array('field', 'field_label', 'field_input');
                        break;
                    case 'readonly':
                    case 'required':
                    case 'inline':
                    case 'fixed_value':
                        $validSections = array('field');
                        break;
                    case 'fixed_text':
                        $validSections = array('field_label');
                        break;
                    default:
                        $validSections = array('field_label', 'field_input');
                        break;
                }
                break;
            case 'panel_modification':
                switch($action) {
                    case 'visible':
                        $validSections = array('panel', 'panel_header');
                        break;
                    case 'background':
                        $validSections = array('panel_header', 'panel_content');
                        break;
                    case 'css_style':
                        $validSections = array('panel','panel_header', 'panel_content');
                        break;
                    default:
                        $validSections = array('panel_header');
                        break;
                }
                break;
            case 'tab_modification':
                switch($action) {
                    case 'visible':
                        $validSections = array('tab');
                        break;
                    case 'background':
                        $validSections = array('tab', 'tab_header', 'tab_content');
                        break;
                    case 'css_style':
                        $validSections = array('tab_header', 'tab_content');
                        break;
                    default:
                        $validSections = array('tab_header');
                        break;
                }
                break;
        }
        $sectionsList = array();
        foreach ($validSections as $section) {
            $sectionsList[$section] = $app_list_strings['stic_custom_views_element_list'][$section];
        }
        return $sectionsList;
    }

    private function findOnlyModuleViewFieldList() {
        require_once('modules/ModuleBuilder/parsers/ParserFactory.php');
        $allFields = $this->getAllFields();
        $allFieldsOperatorsMap = $this->getAllFieldOperatorMap();

        $parser = ParserFactory::getParser($this->view, $this->module, null);
        $this->onlyModuleViewFieldList = array();
        $this->onlyModuleViewFieldOperatorMap = array();
        foreach ($parser->_viewdefs ['panels'] as $panel) {
            foreach ($panel as $row) {
                foreach ($row as $field) {
                    if(isset($allFields[$field])) {
                        $this->onlyModuleViewFieldList[$field] = $allFields[$field];
                        $this->onlyModuleViewFieldOperatorMap[$field] = $allFieldsOperatorsMap[$field];
                    }
                }
            }
        }
        asort($this->onlyModuleViewFieldList);
    }

    private function findPanelTabList() {
        require_once('modules/ModuleBuilder/parsers/ParserFactory.php');
    
        $this->panelList = array();
        $this->tabList = array();
        
        $parser = ParserFactory::getParser($this->view, $this->module, null);
        $useTabs = $parser->getUseTabs();
        $parsedPanels = $parser->getTabDefs();
        foreach ($parsedPanels as $panelKey => $panelDef) {
            if($useTabs && $panelDef["newTab"]) {
                $this->tabList[$panelKey] = translate($panelKey, $this->module);
            } else {
                $this->panelList[$panelKey] = translate($panelKey, $this->module);    
            }
        }
    }

    private function getEditorForCommonAction($action, $newEditorId) {
        switch($action) {
            // case 'fixed_value':
            //     return $this->getEditorForFieldValue($element, $newEditorId);
            //     break;

            case 'color':
            case 'background':
                return $this->getEditorForColor($newEditorId);
                break;

            case 'visible':
            case 'readonly':
            case 'required':
            case 'inline':
            case 'bold':
            case 'italic':
            case 'underline':
                return $this->getEditorForYesNo($newEditorId);
                break;

            case 'fixed_text':
                return $this->getEditorForText($newEditorId);
                break;
            case 'css_style':
                return $this->getEditorForTextArea($newEditorId);
                break;
        }
        return "";
    }
    public function getEditorForCommonAction_Base64($action, $newEditorId) {
        $html = base64_encode($this->getEditorForCommonAction($action, $newEditorId));
        return $html;
    }

    private function getEditorForColor($newEditorId) {
        return "<input type='color' id='{$newEditorId}'/>";
    }
    private function getEditorForText($newEditorId) {
        return "<input type='text' id='{$newEditorId}'/>";
    }
    private function getEditorForYesNo($newEditorId) {
        global $app_list_strings;
        $list = $app_list_strings['stic_boolean_list'];
        unset($list['']);
        $options=$this->convertToSelectOptions($list);
        return "<select id='{$newEditorId}'>{$options}</select>";
    }
    private function getEditorForTextArea($newEditorId) {
        return "<textarea id='{$newEditorId}' rows='2'></textarea>";
    }

    private function getEditorForFieldValue($fieldName, $newEditorId) { 
        require_once("modules/AOW_WorkFlow/aow_utils.php");
        $editor = getModuleField($this->module, $fieldName, $newEditorId);
        return str_replace('"form_name":"EditView"', '"form_name":"form_SubpanelQuickCreate_stic_Custom_View_Customizations"', $editor);
    }
    public function getEditorForFieldValue_Base64($fieldName, $newEditorId) {
        $html = base64_encode($this->getEditorForFieldValue($fieldName, $newEditorId));
        return $html;
    }
}