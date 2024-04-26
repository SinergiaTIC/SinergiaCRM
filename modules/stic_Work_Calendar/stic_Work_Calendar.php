<?php

use function GuzzleHttp\default_user_agent;

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
        global $app_list_strings, $current_user, $timedate;

        switch ($_REQUEST["action"]) {
            case 'Save':
                $startDate = $_REQUEST['start_date'];
                $endDate = $_REQUEST['end_date'];
                $applicationDate = $timedate->fromUserDate($_REQUEST['application_date'], $current_user);
                $this->application_date = $timedate->asDbDate($applicationDate, false);
                break;
            // case 'saveHTMLField': (inline edit)
            //     $startDate = $this->start_date;
            //     $endDate = $this->end_date;
            //     break;
            default: // API | Importation | Mass Periodic Creation | Time Change
                $bbddFormat = 'Y-m-d H:i:s';
                $startDate = $timedate->fromDbFormat($this->start_date, $bbddFormat);
                $startDate = $timedate->asUser($startDate, $current_user);
                $endDate = $timedate->fromDbFormat($this->end_date, $bbddFormat);
                $endDate = $timedate->asUser($endDate, $current_user);                
                break;
        }

        $assignedUser = BeanFactory::getBean('Users', $this->assigned_user_id);
        $typeLabel = $app_list_strings['stic_work_calendar_types_list'][$this->type];
        $allDayTypes = ["working", "punctual_absence"];

        if (in_array($this->type, $allDayTypes)) {
            // Set name
            if ($_REQUEST["action"] != "MassUpdate"){
                $this->name = $assignedUser->name . " - " . $typeLabel . " - " . $startDate . " - " . substr($endDate, -5);
            } else {
                // En actualización masiva no tenemos acceso a las fechas por lo que se reutiliza la parte del nombre con las fechas
                $this->name = $assignedUser->name . " - " . $typeLabel . substr($this->name, -25);
            }
        } else { // All day register
            // Set name
            if ($_REQUEST["action"] != "MassUpdate"){
                $this->name = $assignedUser->name . " - " . $typeLabel . " - " . substr($startDate, 0, 10);
            } else {
                // En actualización masiva no tenemos acceso a las fechas por lo que se reutiliza la parte del nombre con las fechas
                $this->name = $assignedUser->name . " - " . $typeLabel . substr($this->name, -10);
            }
            
            // Set the dates - Do not apply the timezone when converting to database format 
            // $startDate = $timedate->fromUser($startDate, $assignedUser);
            // $this->start_date = $startDate->format($timedate->get_db_date_time_format());
            // $endDate = $timedate->fromUser($endDate, $assignedUser);
            // $this->end_date = $endDate->format($timedate->get_db_date_time_format());
        }

        // Set duration field
        if (!empty($this->end_date)) {
            $this->duration = self::calculateDuration($startDate, $endDate);
        } else {
            $this->duration = 0;
        }      

        // Set weekday field
        if ($this->start_date != $this->fetched_row['start_date']) {
            $this->weekday = date('w', strtotime($this->start_date));
        }

        // Save the bean
        parent::save($check_notify);
    }

    /**
     * 
     *
     * @return void
     */
    public static function existAtLeastOneRecordForEmployeeAndDate($date, $userId)
    {
        global $db;
        $query = "SELECT count(id) as count
                    FROM stic_work_calendar
                  WHERE deleted = 0 
                    AND start_date LIKE '%" . $date . "%'
                    AND assigned_user_id = '" . $userId . "';";

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": " . $query);
        $result = $db->query($query);
        $data = $db->fetchByAssoc($result);
        return $data['count'] > 0;
    }

    /**
     * 
     *
     * @return void
     */
    public static function calculateDuration($startDate, $endDate)
    {
        global $timedate;
        $startTime = strtotime($timedate->to_db($startDate));
        $endTime = strtotime($timedate->to_db($endDate));
        $duration = $endTime - $startTime;            
        // Casting the result into float, otherwise a string is returned and may give wrong values in workflows
        return (float) number_format($duration / 3600, 2);
    }
}