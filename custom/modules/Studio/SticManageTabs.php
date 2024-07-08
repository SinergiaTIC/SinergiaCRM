<?php

switch ($_POST['manageMode']) {
    case 'save':
        // Decodificas los caracteres HTML
        $decodedJson = html_entity_decode($_POST['menuJson']);

        // Convirtiendo el JSON decodificado en un array de PHP
        $GLOBALS["SticTabStructure"] = json_decode($decodedJson, true);

        //Write the tabstructure to custom so that the grouping are not shown for the un-selected scenarios
        $fileContents = "<?php \n" . '$GLOBALS["SticTabStructure"] =' . var_export($GLOBALS['SticTabStructure'], true) . ';';
        sugar_file_put_contents('custom/include/AdvancedTabConfig.php', $fileContents);
        ob_clean();
        SugarApplication::appendSuccessMessage("<div id='saved-notice' class='alert alert-success' role='alert'>{$app_strings['LBL_SAVED']}</div>");
        die('ok');

        break;

    case 'restore':
        unlink('custom/include/AdvancedTabConfig.php');
        unset($GLOBALS["SticTabStructure"]);
        die('ok');
        break;

    default:
        die('no action!');
        break;
}
