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
class stic_Allocation_Proposals extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Allocation_Proposals';
    public $object_name = 'stic_Allocation_Proposals';
    public $table_name = 'stic_allocation_proposals';
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
    public $proposal_status;
    public $proposal_date;
    public $amount;
    public $proposal_type;
    public $priority;
    public $notes;
    public $approval_date;
    public $approved_by;

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }

    /**
     * Overriding SugarBean save function to insert additional logic:
     * 1) Set default values for certain fields if empty
     * 2) Auto-generate name if empty based on proposal type and date
     *
     * @param boolean $check_notify
     * @return void
     */
    public function save($check_notify = false)
    {
        global $timedate;

        // Auto-generate name if empty
        if (empty($this->name)) {
            $type_display = translate($this->proposal_type, 'stic_allocation_proposals_type_list');
            if (empty($type_display)) {
                $type_display = $this->proposal_type;
            }
            
            if (!empty($this->proposal_date)) {
                $userDate = $timedate->fromDBDate($this->proposal_date);
                if ($userDate) {
                    $this->name = $type_display . ' - ' . $userDate->asDBDate();
                } else {
                    $this->name = $type_display . ' - ' . $this->proposal_date;
                }
            } else {
                $this->name = $type_display . ' - ' . date('Y-m-d');
            }
        }

        // Set approval date if status is approved and no approval date is set
        if ($this->proposal_status == 'approved' && empty($this->approval_date)) {
            $this->approval_date = date('Y-m-d');
        }

        // Call the generic save() function from the SugarBean class
        parent::save($check_notify);
    }
}