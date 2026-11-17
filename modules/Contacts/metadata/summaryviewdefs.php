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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$viewdefs['Contacts']['summaryview'] = array(
    'templateMeta' => array(
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30'),
        ),
    ),
    'panels' => array(
        'LBL_CONTACT_INFORMATION' => array(
            array(
                array('name' => 'photo', 'label' => 'LBL_PHOTO'),
            ),
            array(
                array('name' => 'first_name', 'label' => 'LBL_FIRST_NAME'),
                array('name' => 'last_name', 'label' => 'LBL_LAST_NAME'),
            ),
            array(
                array('name' => 'email_addresses_primary', 'label' => 'LBL_EMAIL_ADDRESS'),
                array('name' => 'phone_mobile', 'label' => 'LBL_MOBILE_PHONE'),
            ),
            array(
                array('name' => 'stic_gender_c', 'label' => 'LBL_STIC_GENDER'),
                array('name' => 'stic_age_c', 'label' => 'LBL_STIC_AGE'),
            ),
        ),
    ),
);