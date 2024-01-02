<?php
//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$hook_array['before_save'][] = array(100, 'before_save', 'custom/modules/Users/SticLogicHooksCode.php', 'UsersLogicHooks', 'before_save');

$hook_array['after_login'][] = array(100, 'after_login', 'custom/modules/Users/SticLogicHooksCode.php', 'UsersLogicHooks', 'after_login');
