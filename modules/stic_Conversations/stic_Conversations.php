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


class stic_Conversations extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Conversations';
    public $object_name = 'stic_Conversations';
    public $table_name = 'stic_Conversations';
    public $importable = false;

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
    public $subject;
    public $type;
	
    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }
	
    /**
     * Overriding SugarBean save function to insert additional logic:
     * Build the name of the conversation using the code, the type and the subject
     *
     * @param boolean $check_notify
     * @return void
     */
    public function save($check_notify = false) {
        
        global $app_list_strings, $sugar_config;

        // Generate progressive code only once
        if (empty($this->code)) {
            if (!empty($sugar_config['dbconfig']['db_type']) && $sugar_config['dbconfig']['db_type'] === 'mssql') {
                $this->code = $this->db->getOne("SELECT MAX(CAST(code as INT))+1 FROM {$this->table_name} WHERE deleted = 0");
            } else {
                $this->code = $this->db->getOne("SELECT MAX(CAST(code as UNSIGNED))+1 FROM {$this->table_name} WHERE deleted = 0");
            }

            if (empty($this->code)) {
                $this->code = 1;
            }
        }

        // Create name if empty
        if(empty($this->name)) {
            $typeLabel = $this->type;
            if (!empty($this->type) && !empty($app_list_strings['stic_conversations_types_list'][$this->type])) {
                $typeLabel = $app_list_strings['stic_conversations_types_list'][$this->type];
            }

            $this->name = $this->code . ' - ' . $typeLabel . ' - ' . $this->subject;
        }
        
        // Call the generic save() function from the SugarBean class
        parent::save();
    }


}
