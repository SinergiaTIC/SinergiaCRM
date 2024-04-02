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

class stic_Time_Tracker extends Basic
{
    public $new_schema = true;
    public $module_dir = 'stic_Time_Tracker';
    public $object_name = 'stic_Time_Tracker';
    public $table_name = 'stic_time_tracker';
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
    public $end_date;
    public $duration;
    
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
        global $timedate;

        $startDate = $_REQUEST["start_date"] ?? $this->start_date;
        $endDate = $_REQUEST["end_date"] ?? $this->end_date;

        // Set name
        if (empty($this->name)) {
            $employee = $this->users_stic_time_tracker_name;
            $this->name = $employee . " - " . $startDate;
            if (!empty($endDate)) {
                $this->name .= " - " . $endDate;
            }
        }

        // Set duration field
        if (!empty($endDate)) {
            $startTime = strtotime($timedate->to_db($startDate));
            $endTime = strtotime($timedate->to_db($endDate));
            $duration = $endTime - $startTime;            
            // Casting the result into float, otherwise a string is returned and may give wrong values in workflows
            $this->duration = (float) number_format($duration / 3600, 2);
        }

        // Save the bean
        parent::save($check_notify);
    }
  
    /**
     * 
     *
     * @return void
     */
    public static function getLastTodayTimeTrackerRecordForEmployeeData($idEmployee)
    {
        global $db;
        $query = "SELECT st.* FROM stic_time_tracker  as st
                JOIN users_stic_time_tracker_c as ust
                ON st.id = ust.users_stic_time_trackerstic_time_tracker_idb
                WHERE st.deleted = 0 
                and ust.deleted = 0
                AND st.start_date IS NOT NULL AND st.start_date <> ''
                AND DATE(st.start_date) = DATE(NOW())
                AND ust.users_stic_time_trackerusers_ida = '" . $idEmployee . "' 
                ORDER BY st.start_date desc
                LIMIT 1;";

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": " . $query);
        $result = $db->query($query);
        $data = $db->fetchByAssoc($result);
        return $data;
    }
}