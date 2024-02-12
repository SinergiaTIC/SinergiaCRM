<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings, $app_strings, $sugar_config, $bean, $current_user;
$record = $_REQUEST['record'];
$module = $_REQUEST['module'];

if(ACLController::checkAccess("$module", 'edit', true))$module_menu[]=Array("index.php?module=$module&action=EditView&return_module=$module&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'],"Create$module", "$module");
if(ACLController::checkAccess("$module", 'list', true))$module_menu[]=Array("index.php?module=$module&action=index&return_module=$module&return_action=DetailView", $mod_strings['LNK_LIST'],"$module", "$module");

if ($_REQUEST['action'] == 'DetailView')
{
	$module_menu[]=Array("index.php?module=$module&action=RunReport&record=$record&return_module=$module&return_action=DetailView&return_record=$record", $mod_strings['LNK_RUN_REPORT'],$module, $module);
	if(ACLController::checkAccess("$module", 'edit', true))$module_menu[]=Array("index.php?module=$module&action=RunSQL&record=$record&return_module=$module&return_action=DetailView&return_record=$record", 'Format SQL',$module, $module);
	/*if(ACLController::checkAccess($module, 'edit', true))$module_menu[]=Array("index.php?module=$module&action=EditParams&record=$record&return_module=$module&return_action=DetailView", $mod_strings['LNK_EDIT_PARAMETERS'],$module, $module);
	if(ACLController::checkAccess($module, 'edit', true))$module_menu[]=Array("index.php?module=$module&action=EditColumns&record=$record&return_module=$module&return_action=DetailView", $mod_strings['LNK_EDIT_COLUMNS'],$module, $module);
	if(ACLController::checkAccess($module, 'edit', true))$module_menu[]=Array("index.php?module=$module&action=EditRoles&record=$record&return_module=$module&return_action=DetailView", $mod_strings['LNK_EDIT_ROLES'],$module, $module);
	if(ACLController::checkAccess($module, 'edit', true))$module_menu[]=Array("index.php?module=$module&action=EditMenus&record=$record&return_module=$module&return_action=DetailView", $mod_strings['LNK_EDIT_MENUS'],$module, $module);*/
}
elseif (!empty($record))
{
	if(ACLController::checkAccess($module, 'view', true))$module_menu[]=Array("index.php?module=$module&action=DetailView&record=$record", $mod_strings['LNK_DETAIL_VIEW'],$module, $module);
}
