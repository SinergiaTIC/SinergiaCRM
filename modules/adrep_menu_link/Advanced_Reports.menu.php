<?php 
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
