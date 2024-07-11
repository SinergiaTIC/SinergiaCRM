<?php

/**
 * This script converts the existing SuiteCRM menu structure to a new advanced format.
 * It reads the current tab configuration, transforms it using convertFromSuiteCRMMenu(),
 * and saves the new structure to custom/include/AdvancedTabConfig.php.
 * The conversion reorganizes the menu groups into a numerically indexed array
 * with 'id' and 'children' properties for each group.
 */

if (file_exists('custom/include/tabConfig.php')) {
    // Convert the existing SuiteCRM menu structure to the new format
    require_once 'custom/include/tabConfig.php';

    $newTabStructure = convertFromSuiteCRMMenu($GLOBALS['tabStructure']);

    // Create the content for the new configuration file
    $fileContent = "<?php\n\n";
    $fileContent .= "\$GLOBALS[\"SticTabStructure\"] = " . var_export($newTabStructure, true) . ";\n";

    // Set the path for the new configuration file
    $filePath = 'custom/include/AdvancedTabConfig.php';

    // Save the new configuration file
    if (file_put_contents($filePath, $fileContent)) {
        echo "File successfully saved to $filePath";
    } else {
        echo "An error occurred while saving the file";
    }
    
}

/**
 * Converts the SuiteCRM menu structure to the new advanced tab structure.
 *
 * @param array $tabStructure The original SuiteCRM tab structure
 * @return array The new advanced tab structure
 */
function convertFromSuiteCRMMenu($tabStructure)
{
    $newStructure = array();
    $index = 0;

    foreach ($tabStructure as $groupId => $group) {
        $newGroup = array(
            'id' => $groupId,
            'children' => array(),
        );

        // Convert each module to a child in the new structure
        foreach ($group['modules'] as $moduleId) {
            $newGroup['children'][] = array('id' => $moduleId);
        }

        $newStructure[$index] = $newGroup;
        $index++;
    }

    return $newStructure;
}
