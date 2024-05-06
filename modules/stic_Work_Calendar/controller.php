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

require_once 'modules/stic_Work_Calendar/stic_Work_Calendar.php';
class stic_Work_CalendarController extends SugarController 
{
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

            global $timedate;
            $assignedUser = BeanFactory::getBean('Users', $assignedUserId);            
            $startDate = $timedate->fromUser($startDate, $assignedUser);
            $startDate = $timedate->asDbDate($startDate);

            require_once 'modules/stic_Work_Calendar/Utils.php';
            echo(stic_Work_CalendarUtils::existsRecordsWithIncompatibleType($id, $startDate, $type, $assignedUserId));
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
        global $timedate, $current_user;
        // Check if any operator has been indicated
        if (!empty($_REQUEST['start_date_operator']) || !empty($_REQUEST['end_date_operator'])) 
        {            
            // Calculate the new date on the selected records
            $selectedIds = explode(',', $_REQUEST['selectedIDs']);
            foreach ($selectedIds as $id) 
            {            
                $bean = BeanFactory::getBean('stic_Work_Calendar', $id);
                $update = false;
                if (!empty($_REQUEST['start_date_operator'])) {                
                    $startDateArray = array (
                        'field' => 'start_date',
                        'operator' => $_REQUEST['start_date_operator'],
                        'hours' => $_REQUEST['start_date_hours'],
                        'minutes' => $_REQUEST['start_date_minutes']
                    );
                    $bean->start_date = $this->calculateNewDatesInRecord($bean, $startDateArray);
                    $update = true;
                } else {
                   $startDate = $timedate->fromUser($bean->start_date, $current_user);
                   $bean->start_date = $timedate->asDb($startDate);
                }
                if (!empty($_REQUEST['end_date_operator'])) {                
                    $endDateArray = array (
                        'field' => 'end_date',                        
                        'operator' => $_REQUEST['end_date_operator'],
                        'hours' => $_REQUEST['end_date_hours'],
                        'minutes' => $_REQUEST['end_date_minutes']
                    );
                    $bean->end_date = $this->calculateNewDatesInRecord($bean, $endDateArray);
                    $update = true;
                } else {
                    $endDate = $timedate->fromUser($bean->end_date, $current_user);
                    $bean->end_date = $timedate->asDb($endDate);
                }         
                
                if ($update) $bean->save(false);  
            }
            // Redirect to the list view
            SugarApplication::redirect('index.php?module=stic_Work_Calendar');
        }
    }
    

    /**
     * 
     * @return void
     */
    protected function calculateNewDatesInRecord($bean, $infoDateArray)
    {
        // Calculate new date
        $stringDate = '';
        if (!empty($infoDateArray['operator'])){
            $field = $infoDateArray['field'];
            if (!empty($bean->$field)){
                $infoDateArray['original'] = $bean->$field;
                $stringDate = $this->calculateNewDate($infoDateArray);
            } else {
                $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . 'The new date could not be calculated in the work calendar record with id = '  . $id);                        
            }
        }
        return $stringDate;
    }

    
    /**
     * 
     * @return String
     */
    protected function calculateNewDate($dateInfo) 
    {
        // User timezone and offset in hours
        global $current_user, $timedate;
        $userTz = $current_user->getUserDateTimePreferences();
        $userGMTOffsetInHours = $userTz["userGmtOffset"] / 60;      

        // Calculate the new date
        $format ='Y-m-d H:i:s';
        $date = $timedate->fromUser($dateInfo['original'], $current_user);
        $date = $timedate->asDb($date);
        $date = DateTime::createFromFormat($format, $date);
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