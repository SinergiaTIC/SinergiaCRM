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
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }

    /**
     * Override the bean's save function to assign an auto-incrementing value to the code field when a new record is created
     *
     * @param boolean $check_notify
     * @return void
     */
    public function save($check_notify = false)
    {

        // Set the code if it's a new record
        if (!$this->fetched_row) {
            $query = "SELECT MAX(CAST(code AS UNSIGNED)) FROM {$this->table_name} WHERE deleted = 0";
            $result = intval($this->db->getOne($query));
            $this->code = str_pad($result + 1, 4, '0', STR_PAD_LEFT);
        }

        // Set the name field if it's empty always after generating the code
        $this->fillName();

        // Save the bean
        parent::save($check_notify);
        return $this->id;
    }

    protected function fillName()
    {
        if (empty($this->name)) {
            global $app_list_strings;
            $contactName = '';
            $contactBean = BeanFactory::getBean('Contacts', $this->stic_assets_contactscontacts_ida);
            if ($contactBean) {
                $contactName = $contactBean->first_name . ' ' . $contactBean->last_name;
                $this->name = "{$contactName} - {$app_list_strings['stic_asset_managment_types_list'][$this->type]} - {$this->code}";
            } else {
                $GLOBALS['log']->error("Could not find contact with id {$this->stic_assets_contactscontacts_ida} for asset with id {$this->id}");
            }

        }
    }
}
