<?php

//prevents directly accessing this file from a web browser
if(!defined('sugarEntry') ||!sugarEntry) die('Not A Valid Entry Point');

class Users_TrackLogin
{
	public function TrackLogin(&$bean, $event, $arguments)
	{
		$tracker = new Tracker();
		$tracker->item_summary = $bean->name.' - Login';
		$tracker->user_id = $bean->id;
		$tracker->item_id = $bean->name;
		$tracker->action = 'login_ok';
		$tracker->module_name = $bean->module_name;

		
		$tracker->save();
	}
}