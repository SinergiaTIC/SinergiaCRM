<?php
require_once('modules/adrep_role/adrep_role_sugar.php');

// Build dropdown list of roles
global $app_list_strings, $db;
$role_query = "SELECT id,name FROM acl_roles WHERE deleted=0 ORDER BY name";
$role_res = $db->query($role_query);
while ($role_row = $db->fetchByAssoc($role_res))
	$app_list_strings['adrep_role_list'][$role_row['id']] = $role_row['name'];

class adrep_role extends adrep_role_sugar
{
	function __construct()
	{
		parent::__construct();
	}

	function save($check_notify = FALSE)
	{
		if (empty($this->fetched_row['id']))	// New record
		{
			$this->id = md5($this->report_id . $this->role_id);
			$this->new_with_id = true;
		}

		$retval = parent::save($check_notify);

		return $retval;
	}
}
