<?php
//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) die ('Not A Valid Entry Point');
if (! isset($hook_array) || ! is_array($hook_array)) {
	$hook_array = Array();
}
$hook_version = 1;

$hook_array['before_save'][] = Array(100, 'before_save', 'modules/stic_Job_Offers/LogicHooksCode.php', 'stic_Job_OffersLogicHooks', 'before_save');
