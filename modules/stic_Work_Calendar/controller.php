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
        global $db;
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
            $query = "UPDATE stic_work_calendar SET " . $infoDateArray['field'] . " = '" . $stringDate . "' WHERE id ='" . $id . "'";
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