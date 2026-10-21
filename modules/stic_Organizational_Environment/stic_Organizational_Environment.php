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

#[\AllowDynamicProperties]
class stic_Organizational_Environment extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Organizational_Environment';
    public $object_name = 'stic_Organizational_Environment';
    public $table_name = 'stic_organizational_environment';
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
    public $relationship_type;
    public $end_date;
    public $start_date;
    public $reference_account;
    public $active;
	
    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }
	
    public function save($check_notify = false)
    {
         $this->fillName();
         return parent::save($check_notify);
    }

    protected function fillName() {
        if (!empty($this->name)) {
            return;
        }   

        global $app_list_strings;

        // Environment contact or organization
        $environment = '';
        if (!empty($this->stic_organizational_environment_accountsaccounts_ida)) {
            $related = BeanFactory::getBean('Accounts', $this->stic_organizational_environment_accountsaccounts_ida);
            if ($related) {
                $environment = $related->name;
            }
        } elseif (!empty($this->stic_organizational_environment_contactscontacts_ida)) {
            $related = BeanFactory::getBean('Contacts', $this->stic_organizational_environment_contactscontacts_ida);
            if ($related) {
                $environment = trim($related->first_name . ' ' . $related->last_name);
            }
        }

        // Base organization
        $base = '';
        if (!empty($this->stic_organizational_environment_accounts_1accounts_ida)) {
            $related = BeanFactory::getBean('Accounts', $this->stic_organizational_environment_accounts_1accounts_ida);
            if ($related) {
                $base = $related->name;
            }
        }

        // Relationship type
        $list  = 'stic_organizational_environment_relationships_list';
        $type  = $app_list_strings[$list][$this->relationship_type] ?? '';

        $this->name = $environment . " - " . $type . " - " . $base;
    }
}
