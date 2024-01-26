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
        return get_select_options_with_id($this->getAllFields(), "");
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
            $fieldOPeratorMapOptions[$fieldKey] = get_select_options_with_id($operator_list, "");
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
        return get_select_options_with_id($this->getOnlyViewFields(), "");
    }

    private $panelList;
    public function getPanels() {
        if($this->panelList == null) {
            $this->findPanelList();
        }
        return $this->panelList;
    }
    public function getPanels_as_select_options() {
        return get_select_options_with_id($this->getPanels(), "");
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


    private function findAllModuleFieldList() {
        global $beanList;
    
        $this->allModuleFieldList = array('' => $GLOBALS ['app_strings']['LBL_NONE']);
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

    private function findOnlyModuleViewFieldList() {
        $allFields = $this->getAllFields();

        $this->onlyModuleViewFieldList = array('' => $GLOBALS ['app_strings']['LBL_NONE']);
    }

    private function findPanelList() {
        require_once('modules/ModuleBuilder/parsers/ParserFactory.php') ;
    
        $this->panelList = array();

        $parser = ParserFactory::getParser($this->view, $this->module, null);
        $parsedPanels = $parser->getTabDefs();
        foreach ($parsedPanels as $panelKey => $panelDef) {
            $this->panelList[$panelKey] = translate($panelKey, $view_module);
        }
    }
}