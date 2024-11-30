<?php
/*
 * The MIT License (MIT)
 * 
 * Copyright (c) 2018 Marnus van Niekerk, crm@mjvn.net
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings, $app_strings, $sugar_config, $bean, $current_user;
$record = $_REQUEST['record'];
$module = $_REQUEST['module'];

if(ACLController::checkAccess("$module", 'edit', true))$module_menu[]=Array("index.php?module=$module&action=EditView&return_module=$module&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'],"Create$module", "$module");
if(ACLController::checkAccess("$module", 'list', true))$module_menu[]=Array("index.php?module=$module&action=index&return_module=$module&return_action=DetailView", $mod_strings['LNK_LIST'],"$module", "$module");

if ($_REQUEST['action'] == 'DetailView')
{
	$module_menu[]=Array("index.php?module=$module&action=RunReport&record=$record&return_module=$module&return_action=DetailView&return_record=$record", $mod_strings['LNK_RUN_REPORT'],$module, $module);
	if(ACLController::checkAccess("$module", 'edit', true))$module_menu[]=Array("index.php?module=$module&action=RunSQL&record=$record&return_module=$module&return_action=DetailView&return_record=$record", $mod_strings['LBL_FORMAT_SQL'],$module, $module);
	/*if(ACLController::checkAccess($module, 'edit', true))$module_menu[]=Array("index.php?module=$module&action=EditParams&record=$record&return_module=$module&return_action=DetailView", $mod_strings['LNK_EDIT_PARAMETERS'],$module, $module);
	if(ACLController::checkAccess($module, 'edit', true))$module_menu[]=Array("index.php?module=$module&action=EditColumns&record=$record&return_module=$module&return_action=DetailView", $mod_strings['LNK_EDIT_COLUMNS'],$module, $module);
	if(ACLController::checkAccess($module, 'edit', true))$module_menu[]=Array("index.php?module=$module&action=EditRoles&record=$record&return_module=$module&return_action=DetailView", $mod_strings['LNK_EDIT_ROLES'],$module, $module);
	if(ACLController::checkAccess($module, 'edit', true))$module_menu[]=Array("index.php?module=$module&action=EditMenus&record=$record&return_module=$module&return_action=DetailView", $mod_strings['LNK_EDIT_MENUS'],$module, $module);*/
}
elseif (!empty($record))
{
	if(ACLController::checkAccess($module, 'view', true))$module_menu[]=Array("index.php?module=$module&action=DetailView&record=$record", $mod_strings['LNK_DETAIL_VIEW'],$module, $module);
}
