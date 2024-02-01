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
    
                if (isset($arr['vname']) && $arr['vname'] !== '') {
                    $this->allModuleFieldList[$name] = rtrim(translate($arr['vname'], $mod->module_dir), ':');
                } else {
                    $this->allModuleFieldList[$name] = $name;
                }
                $this->allModuleFieldOperatorMap[$name] = $this->getValidOperators($arr['type']);
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
            }
            asort($this->allModuleFieldList);
            asort($this->allModuleFieldOperatorMap);
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
                $validOps = array('Equal_To','Not_Equal_To','Greater_Than','Less_Than','Greater_Than_or_Equal_To','Less_Than_or_Equal_To','is_null');
                break;
            case 'enum':
            case 'multienum':
                $validOps = array('Equal_To','Not_Equal_To','is_null');
                break;
            default:
                $validOps = array('Equal_To','Not_Equal_To','Contains', 'Starts_With', 'Ends_With','is_null');
                break;
        }
        $operatorList = array();
        foreach ($validOps as $op) {
            $operatorList[$op] = $app_list_strings['aow_operator_list'][$op];
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
                $validActions = array('visible', 'readonly', 'mandatory', 'inline', 'fixedvalue', 'color', 'background', 'bold', 'italic', 'underline');
                break;
            case 'panel_modification':
                $validActions = array('visible', 'color', 'background', 'bold', 'italic', 'underline');
                break;
            case 'tab_modification':
                $validActions = array('visible', 'color', 'background', 'bold', 'italic', 'underline');
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
                        $validSections = array('field', 'field_label', 'field_input');
                        break;
                    case 'readonly':
                    case 'mandatory':
                    case 'inline':
                    case 'fixedvalue':
                        $validSections = array('field');
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
                    default:
                        $validSections = array('panel_header');
                        break;
                }
                break;
            case 'tab_modification':
                switch($action) {
                    case 'visible':
                        $validSections = array('tab', 'tab_header');
                        break;
                    default:
                        $validSections = array('tab_header');
                        break;
                }
                break;
        }
        $sectionsList = array();
        foreach ($validSections as $section) {
            $sectionsList[$section] = $app_list_strings['stic_custom_views_element_section_list'][$section];
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
    }

    private function findPanelTabList() {
        require_once('modules/ModuleBuilder/parsers/ParserFactory.php');
    
        $this->panelList = array();
        $this->tabList = array();
        
        $parser = ParserFactory::getParser($this->view, $this->module, null);
        $useTabs = $parser->getUseTabs();
        $parsedPanels = $parser->getTabDefs();
        $i = 0;
        foreach ($parsedPanels as $panelKey => $panelDef) {
            if($useTabs && $panelDef["newTab"]) {
                // Tabs are indexed as numbers in browser
                $this->tabList[$i] = translate($panelKey, $this->module);
                $i++;
            } else {
                $this->panelList[$panelKey] = translate($panelKey, $this->module);    
            }
        }
    }
}