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
 * STIC-Custom 20230707 AAM - This "SugarWidget" is based on "SugarWidgetSubPanelTopButton". 
 * This widget will display a button that allows the user to navigate to the ListView form of a listed record in a subpanel.
 * This file is a clone of include/generic/SugarWidgets/SugarWidgetSubPanelTopButton.php that has some modifications added with Stic-Custom
 * STIC#1157
 */

class SugarWidgetSubPanelTopButtonListView extends SugarWidgetSubPanelTopButton
{
    public $module;
    public $title;
    public $access_key;
    public $form_value;
    public $additional_form_fields;
    public $acl;

    //TODO rename defines to layout defs and make it a member variable instead of passing it multiple layers with extra copying.

    /** Take the keys for the strings and look them up.  Module is literal, the rest are label keys
    */
    public function __construct($module='', $title='', $access_key='', $form_value='')
    {
        global $app_strings;

        parent::__construct($module, $title, $access_key, $form_value);
        // STIC-Custom 20230706 AAM - Adding parameters used in the button in the constructor, so we don't modify the SuiteCRM core.
        // This params usually come from the array $class_map in include/generic/LayoutManager.php but there isn't a way to add them in custom
        // They can also come where the widget is instantiated from the subpanel definition in the top_buttons property.
        $this->module = '';
        $this->title = $app_strings['LBL_LIST_VIEW_SUBPANEL_BUTTON_TITLE'];
        $this->form_value = $app_strings['LBL_LIST_VIEW_SUBPANEL_BUTTON_TITLE'];
        // END STIC-Custom

    }

    // STIC-Custom 20260113 JCH - Override get_subpanel_relationship_name to sanitize the relationship name
    // This prevents jQuery selector errors when the relationship name contains special characters like "::"
    // which can occur when using custom functions like "function:stic_SignersUtils::getSticSignersForSignature"
    // https://github.com/SinergiaTIC/SinergiaCRM/issues/726
    /**
     * get_subpanel_relationship_name
     * Get the relationship name based on the subpanel definition
     * Sanitizes the name to remove characters invalid for jQuery/CSS selectors
     * @param mixed $defines The subpanel definition
     * @return string The sanitized relationship name
     */
    public function get_subpanel_relationship_name($defines)
    {
        $relationship_name = parent::get_subpanel_relationship_name($defines);
        // Sanitize the relationship name by replacing :: with underscores
        // This is necessary because jQuery selectors cannot handle :: in IDs
        $relationship_name = str_replace('::', '___', $relationship_name);
        return $relationship_name;
    }
    // END STIC-Custom

    // STIC-Custom 20260113 JCH - Helper method to get the search field name for filtering
    // When using custom functions in get_subpanel_data, we need to find the actual relationship field
    // https://github.com/SinergiaTIC/SinergiaCRM/issues/726
    /**
     * get_search_field_name
     * Get the correct field name to use in the advanced search form
     * When a custom function is used, tries to find the actual relationship field
     * @param mixed $defines The subpanel definition
     * @param string $relationship_name The sanitized relationship name
     * @return array Array with 'name' and 'id' field names
     */
    protected function get_search_field_name($defines, $relationship_name)
    {
        // Check if the relationship_name suggests this is from a custom function
        // If it contains '___' it means it was sanitized from '::' (custom function pattern)
        $is_custom_function = (strpos($relationship_name, '___') !== false && 
                               !isset($defines[$relationship_name]));
        
        if ($is_custom_function) {
            // This is likely a custom function, construct the field name based on table names
            $parent_table = !empty($defines['focus']->table_name) ? $defines['focus']->table_name : '';
            $child_module_name = $defines['child_module_name'];
            
            if (!empty($child_module_name) && !empty($parent_table)) {
                $childBean = BeanFactory::newBean($child_module_name);
                $child_table = !empty($childBean->table_name) ? $childBean->table_name : '';
                
                if (!empty($child_table) && !empty($childBean->field_defs)) {
                    // SuiteCRM pattern for many-to-many relationships: {parent_table}_{child_table}
                    $relationship_base = $parent_table . '_' . $child_table;
                    $field_name = $relationship_base . '_name';
                    $field_id = $relationship_base . $parent_table . '_ida';
                    
                    // First try: exact match with constructed field names  
                    if (!empty($childBean->field_defs[$field_name]) && !empty($childBean->field_defs[$field_id])) {
                        return array(
                            'name' => $field_name,
                            'id' => $field_id
                        );
                    }
                    
                    // Second try: use id_name from the relate field definition
                    if (!empty($childBean->field_defs[$field_name]) && !empty($childBean->field_defs[$field_name]['id_name'])) {
                        return array(
                            'name' => $field_name,
                            'id' => $childBean->field_defs[$field_name]['id_name']
                        );
                    }
                    
                    // Third try: search for any relate field that points to the parent table
                    foreach ($childBean->field_defs as $fname => $fdef) {
                        if (!empty($fdef['type']) && $fdef['type'] == 'relate' 
                            && !empty($fdef['table']) && $fdef['table'] == $parent_table
                            && !empty($fdef['id_name'])) {
                            return array(
                                'name' => $fname,
                                'id' => $fdef['id_name']
                            );
                        }
                    }
                }
            }
        }
        
        // Default: use the relationship_name with _name suffix
        return array(
            'name' => $relationship_name . '_name',
            'id' => $relationship_name . '_id'
        );
    }
    // END STIC-Custom

    public function &_get_form($defines, $additionalFormFields = null, $asUrl = false)
    {
        global $app_strings;
        global $currentModule;

        // Create the additional form fields with real values if they were not passed in
        if (empty($additionalFormFields) && $this->additional_form_fields) {
            foreach ($this->additional_form_fields as $key=>$value) {
                // STIC Custom 20250213 JBL - Protect access for PHP 8+ restrictions 
                // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
                // if (!empty($defines['focus']->$value)) {
                if (isset($defines['focus']) && is_object($defines['focus']) && property_exists($defines['focus'], $value) && !empty($defines['focus']->$value)) {
                // END STIC Custom
                    $additionalFormFields[$key] = $defines['focus']->$value;
                } else {
                    $additionalFormFields[$key] = '';
                }
            }
        }


        if (!empty($this->module)) {
            $defines['child_module_name'] = $this->module;
        } else {
            $defines['child_module_name'] = $defines['module'];
        }

        $defines['parent_bean_name'] = get_class($defines['focus']);
        $relationship_name = $this->get_subpanel_relationship_name($defines);


        $formValues = array();

        //module_button is used to override the value of module name
        $formValues['module'] = $defines['child_module_name'];
        $formValues[strtolower($defines['parent_bean_name'])."_id"] = $defines['focus']->id;

        if (isset($defines['focus']->name)) {
            $formValues[strtolower($defines['parent_bean_name'])."_name"] = $defines['focus']->name;
            // #26451,add these fields for custom one-to-many relate field.
            if (!empty($defines['child_module_name'])) {
                $formValues[$relationship_name."_name"] = $defines['focus']->name;
                $childFocusName = !empty($GLOBALS['beanList'][$defines['child_module_name']]) ? $GLOBALS['beanList'][$defines['child_module_name']] : "";
                if (!empty($GLOBALS['dictionary'][ $childFocusName ]["fields"][$relationship_name .'_name']['id_name'])) {
                    $formValues[$GLOBALS['dictionary'][ $childFocusName ]["fields"][$relationship_name .'_name']['id_name']] = $defines['focus']->id;
                }
            }
        }

        $formValues['return_module'] = $currentModule;

        if ($currentModule == 'Campaigns') {
            $formValues['return_action'] = "DetailView";
        } else {
            $formValues['return_action'] = $defines['action'];
            if ($formValues['return_action'] == 'SubPanelViewer') {
                $formValues['return_action'] = 'DetailView';
            }
        }

        $formValues['return_id'] = $defines['focus']->id;
        $formValues['return_relationship'] = $relationship_name;
        switch (strtolower($currentModule)) {
            case 'prospects':
                $name = $defines['focus']->account_name ;
                break ;
            case 'documents':
                $name = $defines['focus']->document_name ;
                break ;
            case 'kbdocuments':
                $name = $defines['focus']->kbdocument_name ;
                break ;
            case 'leads':
            case 'contacts':
                $name = $defines['focus']->first_name . " " .$defines['focus']->last_name ;
                break ;
            default:
               $name = (isset($defines['focus']->name)) ? $defines['focus']->name : "";
        }
        $formValues['return_name'] = $name;

        // TODO: move this out and get $additionalFormFields working properly
        if (empty($additionalFormFields['parent_type'])) {
            if ($defines['focus']->object_name=='Contact') {
                $additionalFormFields['parent_type'] = 'Accounts';
            } else {
                $additionalFormFields['parent_type'] = $defines['focus']->module_dir;
            }
        }
        if (empty($additionalFormFields['parent_name'])) {
            if ($defines['focus']->object_name=='Contact') {
                $additionalFormFields['parent_name'] = $defines['focus']->account_name;
                $additionalFormFields['account_name'] = $defines['focus']->account_name;
            } else {
                $additionalFormFields['parent_name'] = $defines['focus']->name;
            }
        }
        if (empty($additionalFormFields['parent_id'])) {
            if ($defines['focus']->object_name=='Contact') {
                $additionalFormFields['parent_id'] = $defines['focus']->account_id;
                $additionalFormFields['account_id'] = $defines['focus']->account_id;
            } else {
                if ($defines['focus']->object_name=='Contract') {
                    $additionalFormFields['contract_id'] = $defines['focus']->id;
                } else {
                    $additionalFormFields['parent_id'] = $defines['focus']->id;
                }
            }
        }

        if ($defines['focus']->object_name=='Opportunity') {
            $additionalFormFields['account_id'] = $defines['focus']->account_id;
            $additionalFormFields['account_name'] = $defines['focus']->account_name;
        }

        if (!empty($defines['child_module_name']) and $defines['child_module_name']=='Contacts' and !empty($defines['parent_bean_name']) and $defines['parent_bean_name']=='contact') {
            if (!empty($defines['focus']->id) and !empty($defines['focus']->name)) {
                $formValues['reports_to_id'] = $defines['focus']->id;
                $formValues['reports_to_name'] = $defines['focus']->name;
            }
        }
        $formValues['action'] = "EditView";

        // STIC-Custom 20230706 AAM - Adding parameters that will bring the user to the Filtered List View
        // STIC-Custom 20260113 JCH - Use the correct search field name for custom functions
        // https://github.com/SinergiaTIC/SinergiaCRM/issues/726
        $formValues['action'] = "ListView";
        $formValues['query'] = "true";
        $formValues['searchFormTab'] = "advanced_search";
        $search_fields = $this->get_search_field_name($defines, $relationship_name);
        
        if (!empty($search_fields['name'])) {
            $formValues[$search_fields['name'].'_advanced'] = $name;
        }
        if (!empty($search_fields['id']) && !empty($defines['focus']->id)) {
            $formValues[$search_fields['id'].'_advanced'] = $defines['focus']->id;
        }
        // END STIC-Custom

        if ($asUrl) {
            $returnLink = '';
            foreach ($formValues as $key => $value) {
                $returnLink .= $key.'='.$value.'&';
            }
            foreach ($additionalFormFields as $key => $value) {
                $returnLink .= $key.'='.$value.'&';
            }
            $returnLink = rtrim($returnLink, '&');

            return $returnLink;
        } else {
            $form = 'form' . $relationship_name;
            $button = '<form action="index.php" method="post" name="form" id="' . $form . "\">\n";
            foreach ($formValues as $key => $value) {
                $button .= "<input type='hidden' name='" . $key . "' value='" . $value . "' />\n";
            }

            // fill in additional form fields for all but action
            foreach ($additionalFormFields as $key => $value) {
                if ($key != 'action') {
                    $button .= "<input type='hidden' name='" . $key . "' value='" . $value . "' />\n";
                }
            }


            return $button;
        }
    }

}
