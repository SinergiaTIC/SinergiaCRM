<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/


$module_name='stic_Job_Applications';
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopCreateButton'),
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => $module_name),
	),

	'where' => '',

	'list_fields' => array(
		'name'=>array(
			'vname' => 'LBL_NAME',
		   'widget_class' => 'SubPanelDetailViewLink',
			'width' => '20%',
	    ),
		'status' => 
		array (
			'type' => 'enum',
			'studio' => 'visible',
			'vname' => 'LBL_STATUS',
			'width' => '10%',
			'default' => true,
		),
		'stic_job_applications_stic_job_offers_name' => 
		array (
			'type' => 'relate',
			'link' => true,
			'vname' => 'LBL_STIC_JOB_APPLICATIONS_STIC_JOB_OFFERS_FROM_STIC_JOB_OFFERS_TITLE',
			'id' => 'STIC_JOB_APPLICATIONS_STIC_JOB_OFFERSSTIC_JOB_OFFERS_IDA',
			'width' => '10%',
			'default' => true,
			'widget_class' => 'SubPanelDetailViewLink',
			'target_module' => 'stic_Job_Offers',
			'target_record_key' => 'stic_job_applications_stic_job_offersstic_job_offers_ida',
		),
		'stic_job_applications_contacts_name' => 
		array (
			'type' => 'relate',
			'link' => true,
			'vname' => 'LBL_STIC_JOB_APPLICATIONS_CONTACTS_FROM_CONTACTS_TITLE',
			'id' => 'STIC_JOB_APPLICATIONS_CONTACTSCONTACTS_IDA',
			'width' => '10%',
			'default' => true,
			'widget_class' => 'SubPanelDetailViewLink',
			'target_module' => 'Contacts',
			'target_record_key' => 'stic_job_applications_contactscontacts_ida',
		),
		'start_date' => 
		array (
			'type' => 'date',
			'vname' => 'LBL_START_DATE',
			'width' => '10%',
			'default' => true,
		),
		'end_date' => 
		array (
			'type' => 'date',
			'vname' => 'LBL_END_DATE',
			'width' => '10%',
			'default' => true,
		),
		'assigned_user_name' => 
		array (
			'width' => '9%',
			'vname' => 'LBL_ASSIGNED_TO_NAME',
			'module' => 'Employees',
			'id' => 'ASSIGNED_USER_ID',
			'default' => true,
		),
		'edit_button'=>array(
            'vname' => 'LBL_EDIT_BUTTON',
			'widget_class' => 'SubPanelEditButton',
		 	'module' => $module_name,
	 		'width' => '4%',
		),
		'remove_button'=>array(
            'vname' => 'LBL_REMOVE',
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => $module_name,
			'width' => '5%',
		),
	),
);

?>