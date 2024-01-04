<?php
// Including necessary dependencies
global $current_user, $app_strings;
require_once 'modules/SecurityGroups/SecurityGroup.php';
require_once 'modules/MySettings/TabController.php';

// Checking if the current user is an administrator
if (!is_admin($current_user)) {
    sugar_die($app_strings['ERR_NOT_ADMIN']);
}

// Class stic_Advanced_Security_GroupsUtils
class stic_Advanced_Security_GroupsUtils
{
    /**
     * Generates and returns an array with filtered modules.
     *
     * @return array Array of filtered modules.
     */
    public static function generateFilteredModuleArray()
    {
        global $app_list_strings;
        $systemTabs = TabController::get_system_tabs();
        $resArray = array();

        // Filtering modules based on specific criteria
        foreach ($app_list_strings['moduleList'] as $key => $val) {
            if (!in_array($key, $GLOBALS['modInvisList']) && in_array($key, $systemTabs) && $key != 'Home') {
                $resArray[$key] = $val;
            } else {
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . $key . ' Module discarded');
            }
        }

        asort($resArray);
        return $resArray;
    }

    /**
     * Sets the custom filtered module list.
     */
    public static function setCustomFilteredModuleList()
    {
        $resArray = self::generateFilteredModuleArray();
        $overridedListName = 'dynamic_filtered_module_list';
        global $app_list_strings;
        $app_list_strings[$overridedListName] = $resArray;
    }

    /**
     * Generates and returns an array with related module options.
     *
     * @param string $mainModule The main module.
     * @return array Returns an array for each related module, including the module name, relationship name, related field, and the translated label of the relationship.
     */
    public static function getRelatedModulesList($mainModule)
    {
        $mainModuleBean = BeanFactory::newBean($mainModule);
        $options = array();

        if (!empty($mainModuleBean)) {
            foreach ($mainModuleBean->getFieldDefinitions() as $val) {
                if (isset($val['type']) && $val['type'] === 'relate' && ($val['ext2'] && $val['module'] !== 'SecurityGroups') && $val['id_name']) {
                    $options[] = [
                        'relationship' => $mainModule . $val['id_name'],
                        'field' => $val['id_name'],
                        'module' => $val['module'],
                        'label' => translate($val['vname'], $mainModule)
                    ];
                } else if (isset($val['type']) && in_array($val['type'], ['link', 'relate']) && !in_array($val['module'], ['SecurityGroups', 'Users'])) {
                    if (!empty($val['link'])) {
                        $options[] = [
                            'relationship' => $val['link'],
                            'field' => $val['id_name'],
                            'module' => $val['module'],
                            'label' => translate($val['vname'], $mainModule)
                        ];
                    }
                }
            }
        }

        return $options;
    }

    /**
     * Sets the custom related module list.
     *
     * @param string $mainModule The main module.
     */
    public static function setCustomRelatedModuleList($mainModule)
    {
        $options = array_column(self::getRelatedModulesList($mainModule), 'label', 'relationship');
        $overridedListName = 'dynamic_related_module_list';
        global $app_list_strings;
        $app_list_strings[$overridedListName] = $options;
    }

    /**
     * Sets a list of all related modules with their labels.
     * This function retrieves and populates a list with all existing module relationships and their labels,
     * filtering out specific types and modules.
     */
    public static function setAllRelatedModuleList()
    {
        $systemTabs = TabController::get_system_tabs();
        $options = array();

        foreach ($systemTabs as $key => $mainModule) {
            $mainModuleBean = BeanFactory::newBean($mainModule);
            if (!empty($mainModuleBean)) {
                foreach ($mainModuleBean->getFieldDefinitions() as $val) {
                    if (isset($val['type']) && $val['type'] === 'relate' && ($val['ext2'] && $val['module'] !== 'SecurityGroups') && $val['id_name']) {
                        $options[$mainModule . $val['id_name']] = translate($val['vname'], $mainModule);
                    } else if (isset($val['type']) && in_array($val['type'], ['link', 'relate']) && !in_array($val['module'], ['SecurityGroups', 'Users'])) {
                        if (!empty($val['link'])) {
                            $options[$val['link']] = translate($val['vname'], $mainModule);
                        }
                    }
                }
            }
        }

        global $app_list_strings;
        $overridedListName = 'dynamic_related_module_list';
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
        $overridedListName = 'dynamic_security_group_list';
        $sql = "SELECT id, name as 'value' FROM securitygroups WHERE deleted = 0";

        unset($app_list_strings[$overridedListName]);
        $result = $db->query($sql);
        
        $app_list_strings[$overridedListName][''] = '';
        while ($row = $db->fetchByAssoc($result)) {
            $app_list_strings[$overridedListName][$row['id']] = $row['value'];
        }
    }
}
