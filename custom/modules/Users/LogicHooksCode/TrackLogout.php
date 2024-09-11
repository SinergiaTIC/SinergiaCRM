<?php

//prevents directly accessing this file from a web browser
if(!defined('sugarEntry') ||!sugarEntry) die('Not A Valid Entry Point');

class Users_TrackLogout
{
	public function TrackLogout(&$bean, $event, $arguments)
	{
		$tracker = new Tracker();
		$tracker->item_summary = $bean->name.' - Logout';
		$tracker->user_id = $bean->id;
		$tracker->item_id = $bean->id;
		$tracker->module_name = 'Users';
		$tracker->action = 'logout';
		$tracker->tracker_user = $bean->user_name;

		$tracker->save();
	}
}