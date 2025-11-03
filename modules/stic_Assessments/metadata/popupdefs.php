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
$popupMeta = array (
    'moduleMain' => 'stic_Assessments',
    'varName' => 'stic_Assessments',
    'orderBy' => 'stic_assessments.name',
    'whereClauses' => array (
  'name' => 'stic_assessments.name',
  'stic_assessments_contacts_name' => 'stic_assessments.stic_assessments_contacts_name',
  'status' => 'stic_assessments.status',
  'assessment_date' => 'stic_assessments.assessment_date',
  'moment' => 'stic_assessments.moment',
  'scope' => 'stic_assessments.scope',
  'areas' => 'stic_assessments.areas',
  'assigned_user_id' => 'stic_assessments.assigned_user_id',
  'type' => 'stic_assessments.type',
  'project' => 'stic_assessments.project',
  'training_completed' => 'stic_assessments.training_completed',
  'has_criminal_certificate' => 'stic_assessments.has_criminal_certificate',
  'needs_financial_aid' => 'stic_assessments.needs_financial_aid',
  'needs_parental_authorization' => 'stic_assessments.needs_parental_authorization',
  'available_days' => 'stic_assessments.available_days',
  'available_time' => 'stic_assessments.available_time',
  'resignation_signed' => 'stic_assessments.resignation_signed',
  'resignation_date' => 'stic_assessments.resignation_date',
  'material_returned' => 'stic_assessments.material_returned',
  'insurance_canceled' => 'stic_assessments.insurance_canceled',
),
    'searchInputs' => array (
  1 => 'name',
  3 => 'status',
  4 => 'stic_assessments_contacts_name',
  5 => 'assessment_date',
  6 => 'moment',
  7 => 'scope',
  8 => 'areas',
  9 => 'assigned_user_id',
  10 => 'type',
  11 => 'project',
  12 => 'training_completed',
  13 => 'has_criminal_certificate',
  14 => 'needs_financial_aid',
  15 => 'needs_parental_authorization',
  16 => 'available_days',
  17 => 'available_time',
  18 => 'resignation_signed',
  19 => 'resignation_date',
  20 => 'material_returned',
  21 => 'insurance_canceled',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'stic_assessments_contacts_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STIC_ASSESSMENTS_CONTACTS_FROM_CONTACTS_TITLE',
    'id' => 'STIC_ASSESSMENTS_CONTACTSCONTACTS_IDA',
    'width' => '10%',
    'name' => 'stic_assessments_contacts_name',
  ),
  'status' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'name' => 'status',
  ),
  'assessment_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_ASSESSMENT_DATE',
    'width' => '10%',
    'name' => 'assessment_date',
  ),
  'moment' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_MOMENT',
    'width' => '10%',
    'name' => 'moment',
  ),
  'scope' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_SCOPE',
    'width' => '10%',
    'name' => 'scope',
  ),
  'areas' => 
  array (
    'type' => 'multienum',
    'studio' => 'visible',
    'label' => 'LBL_AREAS',
    'width' => '10%',
    'name' => 'ambits',
  ),
  'type' => array(
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'name' => 'type',
  ),
  'project' => array(
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_PROJECT',
    'id' => 'PROJECT_ID',
    'width' => '10%',
    'default' => false,
    'name' => 'project',
  ),
  'training_completed' => array(
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_TRAINING_COMPLETED',
    'width' => '10%',
    'name' => 'training_completed',
  ),
  'has_criminal_certificate' => array(
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_HAS_CRIMINAL_CERTIFICATE',
    'width' => '10%',
    'name' => 'has_criminal_certificate',
  ),
  'needs_financial_aid' => array(
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_NEEDS_FINANCIAL_AID',
    'width' => '10%',
    'name' => 'needs_financial_aid',
  ),
  'needs_parental_authorization' => array(
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_NEEDS_PARENTAL_AUTHORIZATION',
    'width' => '10%',
    'name' => 'needs_parental_authorization',
  ),
  'available_days' => array(
    'type' => 'multienum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_AVAILABLE_DAYS',
    'width' => '10%',
    'name' => 'available_days',
  ),
  'available_time' => array (
    'type' => 'varchar',
    'label' => 'LBL_AVAILABLE_TIME',
    'width' => '10%',
    'default' => false,
    'name' => 'available_time',
  ),
  'resignation_signed' => array(
    'type' => 'bool',
    'label' => 'LBL_RESIGNATION_SIGNED',
    'width' => '10%',
    'default' => false,
    'name' => 'resignation_signed',
  ),
  'resignation_date' => array(
    'type' => 'datetime',
    'label' => 'LBL_RESIGNATION_DATE',
    'width' => '10%',
    'default' => false,
    'name' => 'resignation_date',
  ),
  'material_returned' => array(
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_MATERIAL_RETURNED',
    'width' => '10%',
    'name' => 'material_returned',
  ),
  'insurance_canceled' => array(
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_INSURANCE_CANCELED',
    'width' => '10%',
    'name' => 'insurance_canceled',
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
  ),
),
);
