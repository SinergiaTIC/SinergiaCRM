<?php
require_once('modules/adrep_column/adrep_column_sugar.php');

class adrep_column extends adrep_column_sugar
{
	function __construct()
	{
		parent::__construct();
	}
	
	function save($check_notify = FALSE)
	{
		if (empty($this->fetched_row['id']))	// New record
		{
			$this->id = md5($this->report_id . $this->name);
			$this->new_with_id = true;
		}

		$retval = parent::save($check_notify);

		return $retval;
	}
}
