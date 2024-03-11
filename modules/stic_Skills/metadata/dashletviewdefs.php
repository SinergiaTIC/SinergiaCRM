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
$dashletData['stic_SkillsDashlet']['searchFields'] = array(
    'date_entered' => array(
        'default' => '',
    ),
    'stic_skills_contacts_name' => array(
        'default' => '',
    ),
    'skill' => array(
        'default' => '',
    ),
    'type' => array(
        'default' => '',
    ),
    'level' => array(
        'default' => '',
    ),
    'date_modified' => array(
        'default' => '',
    ),
    'assigned_user_id' => array(
        'default' => '',
    ),
);
$dashletData['stic_SkillsDashlet']['columns'] = array(
    'name' => array(
        'width' => '40%',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true,
        'name' => 'name',
    ),
    'stic_skills_contacts_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STIC_SKILLS_CONTACTS_FROM_CONTACTS_TITLE',
        'id' => 'STIC_SKILLS_CONTACTSCONTACTS_IDA',
        'width' => '10%',
        'default' => true,
    ),
    'skill' => array(
        'type' => 'varchar',
        'label' => 'LBL_SKILL',
        'width' => '10%',
        'default' => true,
    ),
    'type' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'level' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_LEVEL',
        'width' => '10%',
        'default' => true,
    ),
    'date_entered' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => true,
        'name' => 'date_entered',
    ),
    'language' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_LANGUAGE',
        'width' => '10%',
        'default' => false,
    ),
    'other' => array(
        'type' => 'varchar',
        'label' => 'LBL_OTHER',
        'width' => '10%',
        'default' => false,
    ),
    'written' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_WRITTEN ',
        'width' => '10%',
        'default' => false,
    ),
    'oral' => array(
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_ORAL ',
        'width' => '10%',
        'default' => false,
    ),
    'certificate_date' => array(
        'type' => 'date',
        'label' => 'LBL_CERTIFICATE_DATE',
        'width' => '10%',
        'default' => false,
    ),
    'certificate' => array(
        'type' => 'dynamicenum',
        'studio' => 'visible',
        'label' => 'LBL_CERTIFICATE',
        'width' => '10%',
        'default' => false,
    ),
    'description' => array(
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => false,
    ),
    'date_modified' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_MODIFIED',
        'name' => 'date_modified',
        'default' => false,
    ),
    'created_by' => array(
        'width' => '8%',
        'label' => 'LBL_CREATED',
        'name' => 'created_by',
        'default' => false,
    ),
    'assigned_user_name' => array(
        'width' => '8%',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'name' => 'assigned_user_name',
        'default' => false,
    ),
    'created_by_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CREATED',
        'id' => 'CREATED_BY',
        'width' => '10%',
        'default' => false,
    ),
    'modified_by_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_MODIFIED_NAME',
        'id' => 'MODIFIED_USER_ID',
        'width' => '10%',
        'default' => false,
    ),
);
