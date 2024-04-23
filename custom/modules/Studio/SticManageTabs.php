<?php

switch ($_POST['manageMode']) {
    case 'save':
        // Decodificas los caracteres HTML
        $decodedJson = html_entity_decode($_POST['menuJson']);

        // Convirtiendo el JSON decodificado en un array de PHP
        $GLOBALS["SticTabStructure"] = json_decode($decodedJson, true);

        //Write the tabstructure to custom so that the grouping are not shown for the un-selected scenarios
        $fileContents = "<?php \n" . '$GLOBALS["SticTabStructure"] =' . var_export($GLOBALS['SticTabStructure'], true) . ';';
        sugar_file_put_contents('custom/include/SticTabConfig.php', $fileContents);

        ob_clean();

        die('ok');

        break;

    case 'restore':
        unlink('custom/include/SticTabConfig.php');
        die('ok');
        break;

    default:
        die('the is no action');
        break;
}
