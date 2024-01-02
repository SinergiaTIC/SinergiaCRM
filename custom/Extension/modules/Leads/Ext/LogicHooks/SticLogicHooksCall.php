<?php

// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
$hook_array['after_retrieve'][] = array(100, 'after_retrieve', 'custom/modules/Leads/SticLogicHooksCode.php', 'LeadsLogicHooks', 'after_retrieve');
