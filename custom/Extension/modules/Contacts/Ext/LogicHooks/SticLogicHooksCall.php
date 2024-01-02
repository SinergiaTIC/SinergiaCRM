<?php

// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
$hook_array['before_save'][] = array(100, 'before_save', 'custom/modules/Contacts/SticLogicHooksCode.php', 'ContactsLogicHooks', 'before_save');
$hook_array['after_save'][] = array(100, 'after_save', 'custom/modules/Contacts/SticLogicHooksCode.php', 'ContactsLogicHooks', 'after_save');
$hook_array['after_retrieve'][] = array(100, 'after_retrieve', 'custom/modules/Contacts/SticLogicHooksCode.php', 'ContactsLogicHooks', 'after_retrieve');
