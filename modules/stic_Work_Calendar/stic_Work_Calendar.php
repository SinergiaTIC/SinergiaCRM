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

class stic_Work_Calendar extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Work_Calendar';
    public $object_name = 'stic_Work_Calendar';
    public $table_name = 'stic_work_calendar';
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
    public $type;
    public $start_date;
    public $end_date;
    public $duration;
    public $weekday;

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
     * Override bean's save function to calculate the valor of the some fields
     *
     * @param boolean $check_notify
     * @return void
     */
    public function save($check_notify = true)
    {
        global $app_list_strings, $timedate;

        // Set name
        if (empty($this->name)) 
        {
            $employee = $this->stic_work_calendar_users_name;
            $startDate = $timedate->to_display_date_time($this->start_date);
            $type = $app_list_strings['stic_work_calendar_types_list'][$this->type];
            
            $this->name = $employee . " - " . $type . " - " . $startDate;

            if (!empty($this->end_date)) {
                $this->name .= " - " . $timedate->to_display_date_time($this->end_date);
            }
        }

        // Set weekday field
        if ($this->start_date != $this->fetched_row['start_date']) {
            $this->weekday = date('w', strtotime($this->start_date));
        }

        // Set duration field
        if (!empty($this->end_date)) {
            $startTime = strtotime($this->start_date);
            $endTime = strtotime($this->end_date);
            $duration = $endTime - $startTime;            
            // Casting the result into float, otherwise a string is returned and may give wrong values in workflows
            $this->duration = (float) number_format($duration / 3600, 2);
        }

        // Save the bean
        parent::save($check_notify);
    }
}