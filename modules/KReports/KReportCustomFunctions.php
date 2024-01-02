<?php
/* * *******************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed
 * by Christian Knoll. All rights are (c) 2012 by Christian Knoll
 *
 * This Version of the KReporter is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of Christian Knoll
 *
 * You can contact us at info@kreporter.org
 * ****************************************************************************** */
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$kreportCustomFunctions = array(
	'getcurrentuserid' => 'current User ID',
        'interval1630' => 'Interval 16:30'
);

if(!function_exists('getcurrentuserid'))
{
	function getcurrentuserid($whereConditionRecord)
	{
		global $current_user;
		
		return array(
		    'operator' => 'oneof',
		    'value' => $current_user->id . ',seed_chris_id'
		);
	}
}

if(!function_exists('interval1630'))
{
	function interval1630($whereConditionRecord)
	{
		global $current_user;
		
		return array(
                    'operator' => 'between',
                    'value' => '',
		    'valuekey' => date('Y-m-d', time()-86400) . ' 16:30:01', 
                    'valueto' => '',
                    'valuetokey' => date('Y-m-d') . ' 16:30:00', 
		);
	}
}
?>