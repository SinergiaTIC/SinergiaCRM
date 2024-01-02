<?php
//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) die ('Not A Valid Entry Point');
if (! isset($hook_array) || ! is_array($hook_array)) {
	$hook_array = Array();
}

$hook_version = 1;

$hook_array['before_save'][] = Array(100, 'before_save', 'modules/stic_Sepe_Actions/LogicHooksCode.php', 'stic_Sepe_ActionsLogicHooks', 'before_save');
