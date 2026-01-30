<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
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
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
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
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */


class stic_Assets extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Assets';
    public $object_name = 'stic_Assets';
    public $table_name = 'stic_assets';
    public $importable = true;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
    public $securitygroups_name;
    public $code;
    public $type;
    public $other;
    public $start_date;
    public $end_date;
    public $address_street;
    public $address_city;
    public $address_postalcode;
    public $address_state;
    public $address_region;
    public $address_country;
    public $address_location_link;
    public $latitude;
    public $longitude;
    public $address_notes;
    public $ownership;
    public $status;
    public $occupant_type;
    public $contact_id_c;
    public $contact_person;
    public $contact_id1_c;
    public $estate_contact;
    public $account_id_c;
    public $estate_company;
    public $ownership_percentage;
    public $contact_id2_c;
    public $owners_president;
    public $key_set;
    public $ownership_notes;
    public $account_id1_c;
    public $insurance;
    public $policy_number;
    public $insured_building;
    public $insured_contents;
    public $insurance_notes;
    public $account_id2_c;
    public $electricity;
    public $account_id3_c;
    public $gas;
    public $account_id4_c;
    public $water;
    public $account_id5_c;
    public $phone;
    public $account_id6_c;
    public $security;
    public $utilities_notes;
	
    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }
	
}
