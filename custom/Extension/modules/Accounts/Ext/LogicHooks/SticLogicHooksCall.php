<?php
//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
$hook_array['after_save'][] = array(100, 'after_save', 'custom/modules/Accounts/SticLogicHooksCode.php', 'AccountsLogicHooks', 'after_save');
$hook_array['before_save'][] = array(100, 'before_save', 'custom/modules/Accounts/SticLogicHooksCode.php', 'AccountsLogicHooks', 'before_save');


