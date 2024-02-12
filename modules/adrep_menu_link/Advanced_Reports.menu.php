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

// Report menus
global $db, $module_menu, $current_user;
{
	if (isset($_REQUEST['record']) && !empty($_REQUEST['record']))
		$record = $_REQUEST['record'];
	else
		$record = '';
	$module = $_REQUEST['module'];
	$action = $_REQUEST['action'];
	$view = $_REQUEST['action'];
	if ($view == 'index')
		$view = 'ListView';

	// Get user's roles
	$roles = array();
	$query = "SELECT role_id FROM acl_roles_users WHERE user_id='$current_user->id' AND deleted=0";
	$res = $db->query($query);
	while ($row = $db->fetchByAssoc($res))
		$roles[] = $row['role_id'];
	$roles_joined = join("','",$roles);

	$query = "SELECT l.*
FROM adrep_menu_link l
JOIN adrep_report rep ON l.report_id=rep.id AND rep.deleted=0
LEFT JOIN adrep_role role ON rep.id=role.report_id AND role.deleted=0
WHERE l.menu_module='$module' AND l.menu_view='$view' AND l.deleted=0 AND l.active_flag=1
	AND ( rep.permission='Public' OR
		( rep.permission='Private' AND rep.assigned_user_id='$current_user->id') OR
		( rep.permission='Roles' AND role.role_id IN ('$roles_joined') AND role.deleted=0 AND role.allowed_flag=1) )
GROUP BY l.id
ORDER BY l.name";

	$res = $db->query($query);
	while ($row = $db->fetchByAssoc($res))
	{
		$id = $row['id'];
		$name = $row['name'];
		$format = $row['format'];
		$menu_view =  $row['menu_view'];
		$date_range = $row['date_range'];
		$id_parameter = $row['id_parameter'];
		$additional_parameters = $row['additional_parameters'];

		if ($menu_view == 'DetailView')
			$module_menu[] = array("index.php?module=adrep_menu_link&action=ReportFromMenu&record=$id&$id_parameter=$record&format=$format&return_module=$module&return_action=$action&return_record=$record&$additional_parameters", $name, "print", $module);
		else
			$module_menu[] = array("index.php?module=adrep_menu_link&action=ReportFromMenu&record=$id&format=$format&return_module=$module&return_action=$action&return_record=$record&$additional_parameters", $name, "print", $module);
	}
}
