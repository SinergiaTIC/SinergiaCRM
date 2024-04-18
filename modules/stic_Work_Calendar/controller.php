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
     * Check:
     * - If there is already a non-work record that takes up the entire day, in that case, it is not posible to create the record
     * - If exist a record that does not occupy the entire day, in that case, since the record to be created is an all-day record
     * @return void
     */
    public function action_existsOtherTypesIncompatibleRecords()
    {
        // getParams
        $data = json_decode(file_get_contents('php://input'), true);
        if (!empty($data)) 
        {
            $id = $data['id'];
            $startDate = $data["startDate"];
            $type = $data['type'];
            $assignedUserId = $data['assignedUserId'];
            $allDayTypes = ["working", "punctual_absence"];

            global $db, $timedate;
            $assignedUser = BeanFactory::getBean('Users', $assignedUserId);
            $startDate = $timedate->fromUser($startDate, $assignedUser);
            $startDate = $timedate->asDb($startDate);
            $startDate = substr($startDate, 0, 10);

            // Check if there is already a non-work record that takes up the entire day, in that case, it is not posible to create the record
            $query = "SELECT * FROM stic_work_calendar
                    WHERE deleted = 0 
                        AND id != '". $id . "' 
                        AND start_date LIKE '%".$startDate."%' 
                        AND assigned_user_id = '" . $assignedUserId . "' 
                        AND type NOT IN ('" .  implode("', '", $allDayTypes) . "');";

            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": " . $query);
            $result = $db->query($query);

            if (!is_null($result) && $result->num_rows > 0) {
                echo "0";
            } else {
                if (!in_array($type, $allDayTypes)) {
                    // Checks if exist a record that does not occupy the entire day, in that case, since the record to be created is an all-day record, it is not possible to create the record.
                    $query = "SELECT * FROM stic_work_calendar
                    WHERE deleted = 0 
                        AND id != '". $id . "' 
                        AND start_date LIKE '%".$startDate."%' 
                        AND assigned_user_id = '" . $assignedUserId . "' 
                        AND type IN ('" .  implode("', '", $allDayTypes) . "');";
                    $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": " . $query);
                    $result = $db->query($query);

                    if (!is_null($result) && $result->num_rows > 0) {
                        echo "0";
                    } else {
                        echo "1";
                    }
                } else {
                    echo "1";
                }
            }
        }
        die();        
    }
        
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
        // Check if any operator has been indicated
        if (!empty($_REQUEST['start_date_operator']) || !empty($_REQUEST['end_date_operator'])) 
        {            
            // Calculate the new date on the selected records
            $selectedIds = explode(',', $_REQUEST['selectedIDs']);
            foreach ($selectedIds as $id) 
            {            
                if (!empty($_REQUEST['start_date_operator'])) {                
                    $startDateArray = array (
                        'field' => 'start_date',
                        'operator' => $_REQUEST['start_date_operator'],
                        'hours' => $_REQUEST['start_date_hours'],
                        'minutes' => $_REQUEST['start_date_minutes']
                    );
                    $this->calculateNewDateInRecord($id, $startDateArray);
                }
                if (!empty($_REQUEST['end_date_operator'])) {                
                    $endDateArray = array (
                        'field' => 'end_date',                        
                        'operator' => $_REQUEST['end_date_operator'],
                        'hours' => $_REQUEST['end_date_hours'],
                        'minutes' => $_REQUEST['end_date_minutes']
                    );
                    $this->calculateNewDateInRecord($id, $endDateArray);
                }                
            }
            // Redirect to the list view
            SugarApplication::redirect('index.php?module=stic_Work_Calendar');
        }
    }
    
    /**
     * 
     * @return void
     */
    protected function calculateNewDateInRecord($id, $infoDateArray)
    {
        // Get time information from the work calendar record
        global $db, $current_user;
        $query = "SELECT start_date, end_date FROM stic_work_calendar WHERE id ='" . $id . "' AND deleted = 0;";
        $result = $db->query($query);

        // Calculate new date
        $stringDate = '';
        if (!empty($infoDateArray['operator'])){
            $originalDate = $db->fetchByAssoc($result)[$infoDateArray['field']];
            if (!empty($originalDate)){
                $infoDateArray['original'] = $originalDate;
                $stringDate = $this->calculateNewDate($infoDateArray);
            } else {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'The new date could not be calculated in the work calendar record with id = '  . $id);                        
            }
        }

        // Update time in work calendar record
        if ($stringDate) {
            $query = "UPDATE stic_work_calendar SET " . 
                        $infoDateArray['field'] . " = '" . $stringDate . "',
                        date_modified = NOW(),
                        modified_user_id = '" . $current_user->id . "' 
                        WHERE id ='" . $id . "'";
            $result = $db->query($query);
        } else {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'Error work calendar record with id = '  . $id . ' has no ' . $infoDateArray['field'] . ' value.');                                            
        }
    } 

    /**
     * 
     * @return void
     */
    protected function calculateNewDate($dateInfo) 
    {
        // User timezone and offset in hours
        global $current_user;
        $userTz = $current_user->getUserDateTimePreferences();
        $userGMTOffsetInHours = $userTz["userGmtOffset"] / 60;      

        // Calculate the new date
        $format ='Y-m-d H:i:s';
        $date = DateTime::createFromFormat($format, $dateInfo['original']);
        switch ($dateInfo['operator']) {
            case '=':
                $hours = $dateInfo['hours'] - $userGMTOffsetInHours;         
                $date->setTime($hours, $dateInfo['minutes']);                            
                break;
            case '+':
                $date->modify("+{$dateInfo['hours']} hours");                        
                $date->modify("+{$dateInfo['minutes']} minutes");                        
                break;
            case '-':
                $date->modify("-{$dateInfo['hours']} hours");                        
                $date->modify("-{$dateInfo['minutes']} minutes");    
                break;
            default:
                break; 
        }
        return $date->format($format);
    }
}