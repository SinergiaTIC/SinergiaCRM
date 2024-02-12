<?php
require_once "modules/adrep_report/adrep_report.php";
require_once "modules/adrep_column/adrep_column.php";

$focus = new adrep_report();
$focus->retrieve($_REQUEST['record']);
$focus->retrieve_columns();

$colnames = explode(',',$_REQUEST['colnames']);
$fields = array('name','decimals','dropdown_name','type',
				'linked_module_name','column_label','priority','total_type','width');

foreach ($colnames as $name)
{
	if (!empty($_REQUEST["column_label_".$name]))
	{
		$column = new adrep_column();
	
		if (!empty($_REQUEST["id_".$name]))	// Existing record
			$column->retrieve($_REQUEST["id_".$name]);
	
		foreach($fields as $field)
			if (isset($_REQUEST["{$field}_$name"]))
				$column->$field = $_REQUEST["{$field}_$name"];
	
		$column->report_id = $focus->id;
		$column->active_flag = 1;
	
		$column->save();
	}
}

if (isset($_REQUEST['new']) && $_REQUEST['new'] == 'yes' && $focus->permission == 'Roles')
	header("Location: index.php?module=adrep_report&action=EditRoles&record=$focus->id&new=yes");
else
	header("Location: index.php?module=adrep_report&action=DetailView&record=$focus->id");
die('redirect');
