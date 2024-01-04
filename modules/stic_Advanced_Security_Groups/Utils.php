 <?php
global $current_user, $app_strings;

if (!is_admin($current_user)) {
    sugar_die($app_strings['ERR_NOT_ADMIN']);
}

require_once 'modules/SecurityGroups/SecurityGroup.php';
require_once 'modules/MySettings/TabController.php';

class stic_Advanced_Security_GroupsUtils
{

    /**
     * Genera y devuelve un array con los módulos filtrados.
     *
     * @return array Array de módulos filtrados.
     */
    public static function generateFilteredModuleArray()
    {
        global $app_list_strings;
        $systemTabs = TabController::get_system_tabs();
        $resArray = array();

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

    public static function setCustomFilteredModuleList()
    {
        $resArray = self::generateFilteredModuleArray();

        // Nombre de la lista que se generará
        $overridedListName = 'dynamic_filtered_module_list';

        // Población de la lista global con los módulos filtrados y ordenados
        global $app_list_strings;
        $app_list_strings[$overridedListName] = $resArray;
    }

    /**
     * Genera y devuelve un array con opciones de módulos relacionados.
     *
     * @param string $mainModule El módulo principal.
     * @return array devuelve un array por cada módulo relacionado, que inclutye el nombre del módulo, el nombre de la relación, el campo relacionado y la etiqueta traducida de la relación.
     */
    public static function getRelatedModulesList($mainModule)
    {
        $mainModuleBean = BeanFactory::newBean($mainModule);
        $options = array();

        if (!empty($mainModuleBean)) {
            foreach ($mainModuleBean->getFieldDefinitions() as $val) {
                if (isset($val['type']) && $val['type'] === 'relate' && ($val['ext2'] && $val['module'] !== 'SecurityGroups') && $val['id_name']) {
                    $options[] = ['relationship' => $mainModule . $val['id_name'], 'field' => $val['id_name'], 'module' => $val['module'], 'label' => translate($val['vname'], $mainModule)];

                } else if (isset($val['type']) && in_array($val['type'], ['link', 'relate']) && !in_array($val['module'], ['SecurityGroups', 'Users'])) {
                    if (!empty($val['link'])) {
                        $options[] = ['relationship' => $val['link'], 'field' => $val['id_name'], 'module' => $val['module'], 'label' => translate($val['vname'], $mainModule)];
                    }
                }
            }
        }

        return $options;
    }

    /**
     * Establece la lista personalizada de módulos relacionados.
     *
     * @param string $mainModule El módulo principal.
     */
    public static function setCustomRelatedModuleList($mainModule)
    {
        $options = array_column(self::getRelatedModulesList($mainModule), 'label', 'relationship');

        // Nombre de la lista que se generará
        $overridedListName = 'dynamic_related_module_list';

        // Población de la lista global con las opciones
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
        foreach ($systemTabs as $key => $value) {

        }
        $options = array();
        foreach ($systemTabs as $key => $mainModule) {

            $mainModuleBean = BeanFactory::newBean($mainModule);
            if (!empty($mainModuleBean)) {
                foreach ($mainModuleBean->getFieldDefinitions() as $val) {
                    // Add to options if the field is a 'relate' type and relates to SecurityGroups
                    if (isset($val['type']) && $val['type'] === 'relate' && ($val['ext2'] && $val['module'] !== 'SecurityGroups') && $val['id_name']) {
                        $options[$mainModule . $val['id_name']] = translate($val['vname'], $mainModule);
                        if (empty($val['id_name'])) {
                            echo '';
                        }
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