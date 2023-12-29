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

class stic_Grants extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Grants';
    public $object_name = 'stic_Grants';
    public $table_name = 'stic_grants';
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
    public $start_date;
    public $expected_end_date;
    public $renovation_date;
    public $end_date;
    public $amount;
    public $percentage;
    public $returned_amount;
    public $active;
    public $periodicity;
    public $type;
    public $subtype;

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
     *
     * @param boolean $check_notify
     * @return void
     */
    public function save($check_notify = true)
    {
        global $db, $timedate, $current_user;

        // Set active/inactive status
        $start = $this->start_date;
        $end = $this->end_date;

        if ($userDate = $timedate->fromUserDate($start, false, $current_user)) {
            $start = $userDate->asDBDate();
        }

        if ($userDate = $timedate->fromUserDate($end, false, $current_user)) {
            $end = $userDate->asDBDate();
        }

        if ((empty($start) || $start <= date("Y-m-d"))
            && (empty($end) || $end > date("Y-m-d"))) {
            $this->active = true;
        } else {
            $this->active = false;
        }

        // Save the bean
        parent::save($check_notify);
    }

}
