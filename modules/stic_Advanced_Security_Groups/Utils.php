 <?php

// require_once 'modules/Administration/Administration.php';
// require_once 'modules/SecurityGroups/Forms.php';
require_once 'modules/SecurityGroups/SecurityGroup.php';
require_once 'modules/MySettings/TabController.php';

global $app_strings;
global $current_user;

if (!is_admin($current_user)) {
    sugar_die($app_strings['ERR_NOT_ADMIN']);
}

class stic_Advanced_Security_GroupsUtils
{
    /**
     * Sets a custom filtered list of modules.
     * This function filters and sorts the list of modules based on predefined criteria,
     * excluding certain modules and arranging them in alphabetical order.
     */
    public static function setCustomFilteredModuleList()
    {
        global $app_list_strings;
        $systemTabs = TabController::get_system_tabs();
        foreach ($systemTabs as $key => $value) {
            
        }
        $resArray = array();

        foreach ($app_list_strings['moduleList'] as $key => $val) {
            // Filter out invisible modules and the 'Home' module, add others to the list
            if (!in_array($key, $GLOBALS['modInvisList']) && in_array($key, $systemTabs) && $key != 'Home') {
                foreach ($systemTabs as $key => $value) {
                    
                }
                $resArray[$key] = $val;
            } else {
                // Log discarded modules for debugging purposes
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . $key . ' Module discarded');
            }
        }

        // Sort the resulting array alphabetically
        asort($resArray);

        // Name of the list to be generated
        $overridedListName = 'dynamic_filtered_module_list';

        // Populate the global list with the filtered and sorted modules
        $app_list_strings[$overridedListName] = $resArray;
    }

    /**
     * Sets a custom list of related modules for a given module.
     * This function assesses the field definitions of a module and populates a list with related modules,
     * focusing on certain types and excluding specific modules.
     *
     * @param object $focus The module object for which the related module list is to be set.
     */
    public static function setCustomRelatedModuleList($focus)
    {
        $mainModule = $focus->name;
        $mainModuleBean = BeanFactory::newBean($focus->name);
        if (!empty($mainModuleBean)) {
            $options = array();

            foreach ($mainModuleBean->getFieldDefinitions() as $val) {
                // Add to options if the field is a 'relate' type and relates to SecurityGroups
                if (isset($val['type']) && $val['type'] === 'relate' && ($val['ext2'] && $val['module'] == 'SecurityGroups') && $val['id_name']) {
                    $options[$val['id_name']] = $val['labelValue'];
                }
                // Add other related or linked modules, excluding SecurityGroups and Users
                else if (isset($val['type']) && in_array($val['type'], ['link', 'relate']) && !in_array($val['module'], ['SecurityGroups', 'Users'])) {
                    if (!empty($val['link'])) {
                        $options[$val['link']] = translate($val['vname'], $mainModule);
                    }
                }
            }

            global $app_list_strings;

            // Name of the list to be generated
            $overridedListName = 'dynamic_related_module_list';

            // Populate the global list with the options
            $app_list_strings[$overridedListName] = $options;
        }
    }

    /**
     * Sets a list of all related modules with their labels.
     * This function retrieves and populates a list with all existing module relationships and their labels,
     * filtering out specific types and modules.
     */
    public static function setAllRelatedModuleList()
    {
        $systemTabs = TabController::get_system_tabs();
        foreach ($systemTabs as $key => $value) {
            
        }
        $options = array();
        foreach ($systemTabs as $key => $mainModule) {
            foreach ($systemTabs as $key => $value) {
                
            }
            $mainModuleBean = BeanFactory::newBean($mainModule);
            if (!empty($mainModuleBean)) {
                foreach ($mainModuleBean->getFieldDefinitions() as $val) {
                    // Add to options if the field is a 'relate' type and relates to SecurityGroups
                    if (isset($val['type']) && $val['type'] === 'relate' && ($val['ext2'] && $val['module'] == 'SecurityGroups') && $val['id_name']) {
                        $options[$val['id_name']] = $val['labelValue'];
                    }
                    // Add other related or linked modules, excluding SecurityGroups and Users
                    else if (isset($val['type']) && in_array($val['type'], ['link', 'relate']) && !in_array($val['module'], ['SecurityGroups', 'Users'])) {
                        if (!empty($val['link'])) {
                            $options[$val['link']] = translate($val['vname'], $mainModule);
                        }
                    }
                }
            }
        }

        global $app_list_strings;

        // Name of the list to be generated
        $overridedListName = 'dynamic_related_module_list';

        // Populate the global list with the options
        $app_list_strings[$overridedListName] = $options;
    }

    /**
     * Sets a custom list of security groups.
     * This function retrieves all active security groups from the database
     * and populates a global list with their IDs and names.
     */
    public static function setCustomSecurityGroupList()
    {
        global $db, $app_list_strings;

        // Name of the list to be generated
        $overridedListName = 'dynamic_security_group_list';

        // SQL query to retrieve active security groups
        $sql = "SELECT id, name as 'value' FROM securitygroups WHERE deleted = 0";

        // Clear any existing values in the list
        unset($app_list_strings[$overridedListName]);

        // Execute the query and build the list
        $result = $db->query($sql);
        // Initialize the list with an empty option
        $app_list_strings[$overridedListName][''] = '';
        while ($row = $db->fetchByAssoc($result)) {
            // Populate the list with security group ID and name
            $app_list_strings[$overridedListName][$row['id']] = $row['value'];
        }
    }


    

}