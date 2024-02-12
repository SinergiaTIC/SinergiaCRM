<?php
class adrep_menu_link_sugar extends Basic {
	var $new_schema = true;
	var $module_dir = 'adrep_menu_link';
	var $object_name = 'adrep_menu_link';
	var $table_name = 'adrep_menu_link';
	var $importable = false;
	var $disable_row_level_security = true ; // to ensure that modules created and deployed under CE will continue to function under team security if the instance is upgraded to PRO

	var $id;
	var $name;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $modified_by_name;
	var $created_by;
	var $created_by_name;
	var $description;
	var $deleted;
	var $created_by_link;
	var $modified_user_link;
	var $assigned_user_id;
	var $assigned_user_name;
	var $assigned_user_link;
	var $SecurityGroups;
	var $report_id;
	var $report_name;
	var $menu_module;
	var $menu_view;
	var $date_range;
	var $id_parameter;
	var $from_date_paramter;
	var $to_date_parameter;
	var $additional_parameters;
	var $active_flag;
	var $run_flag;
	var $format;
	
	function __construct(){
		parent::__construct();
	}
	
	function bean_implements($interface){
		switch($interface){
			case 'ACL': return true;
		}
		return false;
	}
		
}
?>
