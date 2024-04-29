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
        global $timedate, $current_user;

        switch ($_REQUEST["action"]) {
            case 'Save':
                $startDate = $_REQUEST['start_date'];
                $endDate = $_REQUEST['end_date'];
                $applicationDate = $timedate->fromUserDate($_REQUEST['application_date'], $current_user);                
                $this->application_date = $timedate->asDbDate($applicationDate, false);                
                break;
            case 'createOrUpdateTodayRegister':                
                $startDate = $this->start_date;
                $endDate = $this->end_date;
                if (empty($this->application_date)) {
                    $applicationDate = substr($this->start_date, 0, 10);
                    $applicationDate = $timedate->fromUserDate($applicationDate, $current_user);                
                    $this->application_date = $timedate->asDbDate($applicationDate, false); 
                }
                break;
            default: // API | Importation | Mass Periodic Creation 
                $bbddFormat = 'Y-m-d H:i:s';
                $startDate = $timedate->fromDbFormat($this->start_date, $bbddFormat);
                $startDate = $timedate->asUser($startDate, $current_user);
                if (!empty($this->end_date)) {
                    $endDate = $timedate->fromDbFormat($this->end_date, $bbddFormat);
                    $endDate = $timedate->asUser($endDate, $current_user);                
                } else {
                    $endDate = '';
                }
                break;
        }

        // Set name
        $assignedUser = BeanFactory::getBean('Users', $this->assigned_user_id);
        if ($_REQUEST["action"] != "MassUpdate"){
            $this->name = $assignedUser->name . " - " . $startDate;
            if (!empty($endDate)) {
                $this->name .= " - " .  substr($endDate, -5);
            }
        } else {
            // En actualización masiva no tenemos acceso a las fechas por lo que se reutiliza la parte del nombre con las fechas
            $this->name = $assignedUser->name . " - " . substr($this->name, -25);
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
    public static function getLastTodayTimeTrackerRecordForEmployeeData($userId)
    {
        global $db;
        $query = "SELECT * FROM stic_time_tracker
                WHERE deleted = 0 
                AND start_date IS NOT NULL AND start_date <> ''
                AND DATE(application_date) = DATE(NOW())
                AND assigned_user_id = '" . $userId . "'  
                ORDER BY start_date desc
                LIMIT 1;";

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": " . $query);
        $result = $db->query($query);
        $data = $db->fetchByAssoc($result);
        return $data;
    }

    /**
     * 
     *
     * @return 
     */
    public static function existAtLeastOneRecordForEmployeeAndDate($applicatioDate, $userId)
    {
        global $db;
        $query = "SELECT count(id) as count
                    FROM stic_time_tracker
                  WHERE deleted = 0 
                    AND application_date = '" . $applicatioDate . "'
                    AND assigned_user_id = '" . $userId . "';";

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": " . $query);
        $result = $db->query($query);
        $data = $db->fetchByAssoc($result);
        return $data['count'] > 0;
    }
}