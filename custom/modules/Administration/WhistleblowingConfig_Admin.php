<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Canal de Denúncies - Administració
 * Gestiona l'activació i els 3 apartats de text del canal.
 */

global $current_user, $sugar_config, $mod_strings, $app_strings;

if (!is_admin($current_user)) {
    sugar_die('Unauthorized access to administration.');
}

require_once 'modules/Configurator/Configurator.php';

/* ============================================================
 * ACCIÓ: GUARDAR CONFIGURACIÓ
 * ============================================================ */
if (!empty($_REQUEST['do']) && $_REQUEST['do'] === 'save') {
    
    $cfg = new Configurator();
    
    // 1. Llegim els valors del formulari (Noms actualitzats segons el teu TPL)
    $whistleblowingEnabled = (isset($_POST['whistleblowing_enabled']) && $_POST['whistleblowing_enabled'] === '1') ? '1' : '0';
    
    // Els 3 nous camps de text
    $whistleAbout   = $_POST['whistleblowing_text_about'] ?? '';
    $whistleConfid  = $_POST['whistleblowing_text_confidentiality'] ?? '';
    $whistleSecurity = $_POST['whistleblowing_text_security'] ?? '';

    // 2. Assignem a la configuració del CRM
    $cfg->config['whistleblowing_enabled']              = $whistleblowingEnabled;
    $cfg->config['whistleblowing_text_about']           = $whistleAbout;
    $cfg->config['whistleblowing_text_confidentiality'] = $whistleConfid;
    $cfg->config['whistleblowing_text_security']        = $whistleSecurity;

    // 3. Guardem físicament al fitxer config_override.php
    $cfg->handleOverride();

    // 4. Netegem memòria cau per assegurar que es vegi el canvi
    if (class_exists('MetaDataManager')) {
        MetaDataManager::refreshCache();
    }

    // Missatge de l'aplicació i redirecció
    SugarApplication::appendSuccessMessage($app_strings['LBL_SAVE_BUTTON_LABEL']);
    SugarApplication::redirect('index.php?module=Administration&action=WhistleblowingConfig_Admin');
}

/* ============================================================
 * RENDERITZACIÓ DE LA VISTA
 * ============================================================ */
require_once 'include/Sugar_Smarty.php';
$sugar_smarty = new Sugar_Smarty();

// Assignem els strings i la configuració actual a Smarty
$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);

// Passarem els valors actuals llegits de $sugar_config
$whistleblowingConfig = [
    'whistleblowing_enabled'             => $sugar_config['whistleblowing_enabled'] ?? '0',
    'whistleblowing_text_about'           => $sugar_config['whistleblowing_text_about'] ?? '',
    'whistleblowing_text_confidentiality' => $sugar_config['whistleblowing_text_confidentiality'] ?? '',
    'whistleblowing_text_security'        => $sugar_config['whistleblowing_text_security'] ?? '',
];
$sugar_smarty->assign('config', $whistleblowingConfig);

// Títol de la pàgina d'administració
echo getClassicModuleTitle(
    'Administration',
    [
        $mod_strings['LBL_WHISTLEBLOWING_TITLE'] ?? 'Canal de Denúncies'
    ],
    false
);

// Mostrem el template TPL
$sugar_smarty->display('custom/modules/Administration/WhistleblowingConfig_Admin.tpl');