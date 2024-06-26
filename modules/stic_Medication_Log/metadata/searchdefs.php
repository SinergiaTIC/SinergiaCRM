<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */
$module_name = 'stic_Medication_Log';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'intake_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_INTAKE_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'intake_date',
      ),
      'administered' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_ADMINISTERED',
        'width' => '10%',
        'name' => 'administered',
      ),
      'schedule' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_SCHEDULE',
        'width' => '10%',
        'default' => true,
        'name' => 'schedule',
      ),
      'stic_medication_log_contacts_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_MEDICATION_LOG_CONTACTS_FROM_CONTACTS_TITLE',
        'id' => 'STIC_MEDICATION_LOG_CONTACTSCONTACTS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'stic_medication_log_contacts_name',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'width' => '10%',
        'default' => true,
      ),
      'current_user_only' => array(
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'favorites_only' => array(
          'name' => 'favorites_only',
          'label' => 'LBL_FAVORITES_FILTER',
          'type' => 'bool',
          'default' => true,
          'width' => '10%',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'intake_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_INTAKE_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'intake_date',
      ),
      'administered' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_ADMINISTERED',
        'width' => '10%',
        'name' => 'administered',
      ),
      'schedule' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_SCHEDULE',
        'width' => '10%',
        'default' => true,
        'name' => 'schedule',
      ),
      'stic_medication_log_contacts_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_MEDICATION_LOG_CONTACTS_FROM_CONTACTS_TITLE',
        'width' => '10%',
        'default' => true,
        'id' => 'STIC_MEDICATION_LOG_CONTACTSCONTACTS_IDA',
        'name' => 'stic_medication_log_contacts_name',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'default' => true,
        'width' => '10%',
      ),
      'stic_medication_log_stic_prescription_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_MEDICATION_LOG_STIC_PRESCRIPTION_FROM_STIC_PRESCRIPTION_TITLE',
        'width' => '10%',
        'default' => true,
        'id' => 'STIC_MEDICATION_LOG_STIC_PRESCRIPTIONSTIC_PRESCRIPTION_IDA',
        'name' => 'stic_medication_log_stic_prescription_name',
      ),
      'dosage' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_DOSAGE',
        'width' => '10%',
        'default' => true,
        'name' => 'dosage',
      ),
      'schedule' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_SCHEDULE',
        'width' => '10%',
        'default' => true,
        'name' => 'schedule',
      ),
      'medication' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_MEDICATION',
        'width' => '10%',
        'default' => true,
        'name' => 'medication',
      ),
      'stock_depletion' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_STOCK_DEPLETION',
        'width' => '10%',
        'name' => 'stock_depletion',
      ),
      'description' => array(
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'description',
      ),
      'created_by' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_CREATED',
        'width' => '10%',
        'default' => true,
        'name' => 'created_by',
      ),
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'modified_user_id' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'modified_user_id',
      ),
      'date_modified' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_modified',
      ),
      'current_user_only' => 
      array (
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
        'name' => 'current_user_only',
      ),
      'favorites_only' => array(
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
;
?>
