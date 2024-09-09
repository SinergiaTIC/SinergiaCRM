<?php

global $messageableModules;

// This array can be extended in custom folder to add new modules or change default fields
$messageableModules = array(
    'Contacts' => 'phone_mobile',
    'Accounts' => 'phone_office',
    'Leads' => 'phone_mobile',
    'Employees' => 'phone_mobile',
    'Users' => 'phone_mobile'
);



function getMessageableModules() {
    // global $beanFiles, $beanList, $app_list_strings, $dictionary;
    // $messageableModules = array();
    // // foreach ($app_list_strings['aow_moduleList'] as $bean_name => $bean_dis) {
    // foreach ($beanList as $bean_name => $bean_dis) {
    //     // if (isset($beanList[$bean_name]) && isset($beanFiles[$beanList[$bean_name]])) {
    //     //     require_once($beanFiles[$beanList[$bean_name]]);
    //     //     $obj = new $beanList[$bean_name];
    //     //     if ($obj instanceof Person || $obj instanceof Company) {
    //     //         $emailableModules[] = $bean_name;
    //     //     }
    //     // }
    //     if (isset($beanList[$bean_name]) && isset($beanFiles[$beanList[$bean_name]])) {
    //         $obj = new $beanList[$bean_name]; // We need to create a bean to force dictionary to load
    //         $beanNameOnDictionary = $beanList[$bean_name];
    //         if (isset($dictionary[$beanNameOnDictionary]['stic_properties']) && $dictionary[$beanNameOnDictionary]['stic_properties']['messagesAllowed']){
    //             $messageableModules[] = $bean_name;
    //         }
    //     }
    // }
    // asort($messageableModules);
    // return $messageableModules;

    global $messageableModules;
    $modules = array_keys($messageableModules);
    asort($modules);
    return $modules;
}


function getRelatedMessageableFields($module) {
    global $beanList, $app_list_strings;
    $relEmailFields = array();
    $checked_link = array();
    $emailableModules = getMessageableModules();
    if ($module != '') {
        if (isset($beanList[$module]) && $beanList[$module]) {
            $mod = new $beanList[$module]();

            foreach ($mod->get_related_fields() as $field) {
                if (isset($field['link'])) {
                    $checked_link[] = $field['link'];
                }
                if (!isset($field['module']) || !in_array($field['module'], $emailableModules) || (isset($field['dbType']) && $field['dbType'] == "id")) {
                    continue;
                }
                // STIC 20210720 AAM - Fix regarding the Emailable modules returned in Send Email Action
                // STIC#362
                // $relEmailFields[$field['name']] = translate($field['module']) . ": "
                $relEmailFields[$field['link'] ? $field['link'] : $field['name']] = translate($field['module']) . ": "
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

                    if (in_array($rel_module, $emailableModules)) {
                        if (isset($field['vname']) && $field['vname'] != '') {
                            $relEmailFields[$field['name']] = $app_list_strings['moduleList'][$rel_module] . ' : ' . translate($field['vname'], $mod->module_dir);
                        } else {
                            $relEmailFields[$field['name']] = $app_list_strings['moduleList'][$rel_module] . ' : ' . $field['name'];
                        }
                    }
                }
            }

            array_multisort($relEmailFields, SORT_ASC, $relEmailFields);
        }
    }
    return $relEmailFields;
}

function getPhoneForMessage($bean) {
    // switch ($bean->module_name) {
    //     case 'Contacts':
    //     case 'Leads':
    //     case 'Employees':
    //     case 'Users':
    //         return $bean->phone_mobile;
    //         break;
    //     case 'Accounts':
    //         return $bean->phone_office;
    //         break;
    //     default:
    //         return '';
    // }

    global $messageableModules;

    $fieldName = $messageableModules[$bean->module_name];
    if ($fieldName !== null){
        return $bean->$fieldName;
    }
    return '';
}

function get_stic_messages($type) {
    $beanId = $_REQUEST['record'];
    $return_array['select'] = 'SELECT stic_messages.id ';
    $return_array['from'] = ' FROM stic_messages ';
    $return_array['where'] = " WHERE stic_messages.parent_id = '{$beanId}'";

    if (isset($type) && ! empty($type['return_as_array'])) {
        return $return_array;
    }

    return $return_array['select'] . $return_array['from'] . $return_array['where'];
}