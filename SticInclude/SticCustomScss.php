<?php

global $log, $current_user;

$current_user = new User();
$current_user->getSystemUser();

// Replacing base color in CustomPalette.scss
// We always extract the pain of Stic_Setting, to avoid any discordance between what is shown and defined in setting
$db = DBManagerFactory::getInstance();
$color = $db->getOne("select value from stic_settings where name='GENERAL_CUSTOM_THEME_COLOR' and deleted=0");
$setting_sidebar_color = $db->getOne("select value from stic_settings where name='GENERAL_CUSTOM_SIDEBAR_COLOR' and deleted=0");

if (!preg_match('/#([a-fA-F0-9]{3}){1,2}\b/m', $color)) {
    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Color [$color] is empty. Aborting SticCustom subtheme base color change.");
    echo "<li> Color [$color] is empty or invalid. Aborting SticCustom subtheme base color change";
    return;
}

if ($color == '#b5bc31') {
    // If $color is color default, simply clone compiled Stic css
    copy('themes/SuiteP/css/Stic/style.css', 'themes/SuiteP/css/SticCustom/style.css');
    echo '<li> $color is defaulf. Cloned from Stic subtheme. Compiled unnecessary.';
    // Remove cache/themes/SuiteP/css/SticCustom/style.css to force reload css theme
    unlink('cache/themes/SuiteP/css/SticCustom/style.css');
    return;
}

$GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Starting SticCustom subtheme base color change to {$color}");

$file = 'themes/SuiteP/css/SticCustom/CustomPalette.scss';
$data = file_get_contents($file); // reads an array of lines

if ($setting_sidebar_color==0){
    $sidebar_color= "#D9DEE3";
    $sidebar_text_color= "#001E40";
    $replacements = array(
        '\\$stic-base: ' . $color . ';',
        '\\$stic-sidebar: ' . $sidebar_color . ';',
        '\\$stic-sidebar-text: ' . $sidebar_text_color . ';'
    );
    $patterns = array(
        '/\$stic-base:.*;/m',
        '/\$stic-sidebar:.*;/m',
        '/\$stic-sidebar-text:.*;/m'
    );
    $data = preg_replace($patterns, $replacements, $data);
    file_put_contents($file, $data);
}else{
    $sidebar_color= " #353535;";
    $sidebar_text_color= "#F5F5F5";
    $replacements = array(
        '\\$stic-base: ' . $color . ';',
        '\\$stic-sidebar: ' . $sidebar_color . ';',
        '\\$stic-sidebar-text: ' . $sidebar_text_color . ';'
    );
    $patterns = array(
        '/\$stic-base:.*;/m',
        '/\$stic-sidebar:.*;/m',
        '/\$stic-sidebar-text:.*;/m'
    );
    $data = preg_replace($patterns, $replacements, $data);
    file_put_contents($file, $data);
}

$GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "SticCustom color palette is :" . print_r(file_get_contents($file), true));

// Compile the SCSS of the folder themes/SuiteP/css/SticCustom/ using library scssphp
// more info https://scssphp.github.io/scssphp/
require "SticInclude/vendor/scssphp/scss.inc.php";

use ScssPhp\ScssPhp\Compiler;

$compiler = new Compiler();
$compiler->setImportPaths('themes/SuiteP/css/SticCustom/');
$compiler->setOutputStyle('compressed');

$sourceString = file_get_contents('themes/SuiteP/css/SticCustom/style.scss');

file_put_contents('themes/SuiteP/css/SticCustom/style.css', $compiler->compileString($sourceString)->getCss());

// Remove cache/themes/SuiteP/css/SticCustom/style.css to force reload css theme
unlink('cache/themes/SuiteP/css/SticCustom/style.css');

$GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "End SticCustom subtheme base color change to {$color}");
echo "<li>End SticCustom subtheme base color change to {$color}";

if (isset($_REQUEST['keepUserTheme']) && $_REQUEST['keepUserTheme'] == true ) {
    $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Do not set SticCustom subtheme to all users");
    echo "<li>Do not set SticCustom subtheme to all users";
} else {
    $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Setting SticCustom subtheme to all users");

    // Get all users
    $userEmptyBean = BeanFactory::getBean('Users');
    $userBeanArray = $userEmptyBean->get_full_list();

    // Set the config params for each user
    foreach ($userBeanArray as $userBean) {
        
        // Reload user preferences before saving subtheme
        $userBean->reloadPreferences();

        // Enable "SticCustom" as subtheme
        $userBean->setPreference('subtheme', 'SticCustom', 0, 'global');

        // Save preferences
        $userBean->savePreferencesToDB();
    }
    $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Setting SticCustom subtheme to all users. Done.");
    echo "<li>Setting SticCustom subtheme to all users. Done.";
}

// Write default_subtheme info in config_override to use as default SuiteP subtheme
require_once 'modules/Configurator/Configurator.php';
$cfg = new Configurator();
$cfg->config['default_subtheme'] = 'SticCustom';
$cfg->handleOverride();
$GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "Writing SticCustom as default subtheme in config_override.php.");
echo "<li>Writing SticCustom as default subtheme in config_override.php. Done.";
