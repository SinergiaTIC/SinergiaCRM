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
class stic_Work_CalendarController extends SugarController {

    /**
     * 
     * @return void
     */
    public function action_showMassUpdateDatesForm()
    {
        $this->view = "massupdatedatesform"; //call for the view file in views dir
    }

    /**
     * 
     * @return void
     */
    public function action_runMassUpdateDates()
    {
        global $db;
        $format = 'Y-m-d H:i:s';
        
        // IDs of the selected records
        $ids = explode(',', $_REQUEST['selectedIDs']);
        
        // OPERATE ON RECORDS
        foreach ($ids as $id) 
        {
            $query = "SELECT start_date, end_date FROM stic_work_calendar WHERE id ='" . $id . "' AND deleted = 0;";
            $result = $db->query($query);

            // START DATE
            $startDate = $db->fetchByAssoc($result)['start_date'];
            $startDateOperator = $_REQUEST['startDateOperator'];
            
            if (!empty($startDate) && !empty($startDateOperator)) 
            {
                $startDate = DateTime::createFromFormat($format, $startDate);
                $hours = $_REQUEST['startDateHours'];
                $minutes = $_REQUEST['startDateMinutes'];

                switch ($startDateOperator) {
                    case '=':
                        $startDate->setTime($hours, $minutes);                            
                        break;
                    case '+':
                        $startDate->modify("+{$hours} hours");                        
                        $startDate->modify("+{$minutes} minutes");                        
                        break;
                    case '-':
                        $startDate->modify("-{$hours} hours");                        
                        $startDate->modify("-{$minutes} minutes");   
                        break;
                    default:
                        break;
                } 
                $query = "UPDATE stic_work_calendar SET start_date = '" . $startDate->format($format) . "' WHERE id ='" . $id . "'";
                $result = $db->query($query);
            } else {

            }

            // END DATE
            $endDate = $db->fetchByAssoc($result)['end_date'];
            $endDateOperator = $_REQUEST['endDateOperator'];

            if (!empty($endDate) && !empty($endDateOperator)) 
            {
                $endDate = DateTime::createFromFormat($format, $endDate);
                $hours = $_REQUEST['endDateHours'];
                $minutes = $_REQUEST['endDateMinutes'];

                switch ($endDateOperator) {
                    case '=':
                        $endDate->setTime($hours, $minutes);                            
                        break;
                    case '+':
                        $endDate->modify("+{$hours} hours");                        
                        $endDate->modify("+{$minutes} minutes");                        
                        break;
                    case '-':
                        $endDate->modify("-{$hours} hours");                        
                        $endDate->modify("-{$minutes} minutes");   
                        break;
                    default:
                        break;
                } 
                $query = "UPDATE stic_work_calendar SET start_date = '" . $endDate->format($format) . "' WHERE id ='" . $id . "'";
                $result = $db->query($query);
            } else {

            }

        }
    }    
}