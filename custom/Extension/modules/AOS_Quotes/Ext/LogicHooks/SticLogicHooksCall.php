<?php
//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
$hook_array['after_save'][] = array(100, 'after_save', 'custom/modules/AOS_Quotes/SticLogicHooksCode.php', 'AOS_QuotesLogicHooks', 'after_save');


