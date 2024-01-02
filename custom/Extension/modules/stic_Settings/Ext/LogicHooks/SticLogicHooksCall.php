<?php
//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
$hook_array['before_save'][] = array(100, 'before_save', 'modules/stic_Settings/LogicHooksCode.php', 'stic_SettingsLogicHooks', 'before_save');
$hook_array['after_save'][] = array(100, 'after_save', 'modules/stic_Settings/LogicHooksCode.php', 'stic_SettingsLogicHooks', 'after_save');
