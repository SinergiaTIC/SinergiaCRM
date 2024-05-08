<?php

function seven_assign_templates(Basic $bean, Sugar_Smarty $sugarSmarty) {
    $templates = (new seven_templates)
        ->get_full_list('ASC', 'type = \'' . strtolower($bean->object_name) . '\'');

    $sugarSmarty->assign('templates', $templates);
}

function seven_smarty_render(
    Basic $bean,
    Configurator $configurator,
    string $template,
    array $placeholders,
    array $settingsForm
): Sugar_Smarty {
    global $mod_strings, $app_strings, $app_list_strings;

    $sugar_smarty = new Sugar_Smarty;
    seven_assign_templates($bean, $sugar_smarty);
    $sugar_smarty->assign('MOD', $mod_strings);
    $sugar_smarty->assign('APP', $app_strings);
    $sugar_smarty->assign('APP_LIST', $app_list_strings);
    $sugar_smarty->assign('config', $configurator->config);
    $sugar_smarty->assign('seven_config', $settingsForm);
    $sugar_smarty->assign('error', $configurator->errors);
    $sugar_smarty->assign('placeholders', implode('<br/>', $placeholders));
    $sugar_smarty->display($template);

    return $sugar_smarty;
}
