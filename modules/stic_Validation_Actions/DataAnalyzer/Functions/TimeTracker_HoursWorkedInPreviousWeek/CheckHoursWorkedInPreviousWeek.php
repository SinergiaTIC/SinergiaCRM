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

require_once 'SticInclude/Utils.php';

/**
 * Check for each user who has activated the Time Tracker and Work Calendar modules if the user has completed, or not, 
 * a percentage of the duration of his working day, whether he has worked more or less hours
 */
class CheckHoursWorkedInPreviousWeek extends DataCheckFunction 
{
    /**
     * It receives a proposal from SQL and modifies it with the necessary features for the function.
     * Most functions should override this method.
     * @param $actionBean Bean of the action the function is running on.
     * @param $proposedSQL Automatically generated array (if possible) with the keys select, from, where and order_by.
     * @return string
     */
    public function prepareSQL(stic_Validation_Actions $actionBean, $proposedSQL)
    {
        // Obtain the total hours scheduled and worked by each user during the previous week.
        // Only those users who have activated the use of the Time Registration and Work Calendar modules will be selected.
        $sql = "
        SELECT tt.assigned_user_id as ttAssignedUser, tt.total_duration as ttDuration, wc.assigned_user_id  as wcAssignedUser, wc.total_duration  as wcDuration
        FROM (
            SELECT stt.assigned_user_id, SUM(stt.duration) AS total_duration, sttu.stic_clock_c
            FROM stic_time_tracker as stt
            JOIN users_cstm as sttu ON stt.assigned_user_id = sttu.id_c
            WHERE stt.application_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                AND stt.application_date < CURDATE()
                AND stt.deleted = 0
            GROUP BY stt. assigned_user_id) AS tt
        LEFT JOIN (
            SELECT swc.assigned_user_id, SUM(swc.duration) AS total_duration, swcu.stic_work_calendar_c
            FROM stic_work_calendar swc
            JOIN users_cstm as swcu ON swc.assigned_user_id = swcu.id_c            
            WHERE swc.application_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                AND swc.application_date < CURDATE()
                AND swc.type = 'working'
                AND swc.deleted = 0
            GROUP BY swc.assigned_user_id) AS wc 
        ON tt.assigned_user_id = wc.assigned_user_id

        UNION ALL
        
        SELECT tt.assigned_user_id as ttAssignedUser, tt.total_duration as ttDuration, wc.assigned_user_id  as wcAssignedUser, wc.total_duration  as wcDuration
        FROM (
            SELECT stt.assigned_user_id, SUM(stt.duration) AS total_duration, sttu.stic_clock_c
            FROM stic_time_tracker as stt
            JOIN users_cstm as sttu ON stt.assigned_user_id = sttu.id_c
            WHERE stt.application_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                AND stt.application_date < CURDATE()
                AND stt.deleted = 0
            GROUP BY stt. assigned_user_id) AS tt
        RIGHT JOIN (
            SELECT swc.assigned_user_id, SUM(swc.duration) AS total_duration, swcu.stic_work_calendar_c
            FROM stic_work_calendar swc
            JOIN users_cstm as swcu ON swc.assigned_user_id = swcu.id_c            
            WHERE swc.application_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                AND swc.application_date < CURDATE()
                AND swc.type = 'working'  
                AND swc.deleted = 0
            GROUP BY swc.assigned_user_id) AS wc 
        ON tt.assigned_user_id = wc.assigned_user_id;";

        return $sql;
    }


    /**
     * DoAction function
     * Perform the action defined in the function
     * @param $records Set of records on which the validation action is to be applied 
     * @param $actionBean stic_Validation_Actions Bean of the action in which the function is being executed.
     * @return boolean It will return true in case of success and false in case of error.
     */
    public function doAction($records, stic_Validation_Actions $actionBean) 
    {
        include_once 'modules/stic_Validation_Actions/DataAnalyzer/Functions/include/Mailing/Utils.php';
        global $sugar_config;

        // It will indicate if records have been found to validate.
        $count = 0;
        // It will indicate if records with errors have been found.
        $errors = 0;
        // Array donde ir añadiendo a los usuarios validados ya que la query, al consultar dos módulos, pudiera devolver usuarios repetidos. 
        $validatedUsers = array();

        while ($row = array_pop($records)) 
        {
            $stAssignedUser = $row['ttAssignedUser'];
            $wcAssignedUser = $row['wcAssignedUser'];

            // Get the assigned user
            if (!empty($stAssignedUser)) {
                $assignedUser = BeanFactory::getBean('Users', $stAssignedUser);
            } else {
                $assignedUser = BeanFactory::getBean('Users', $wcAssignedUser);
            }

            if (!empty($assignedUser) && !in_array($assignedUser->id, $validatedUsers)) 
            {
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ": Validating the next users: {$assignedUser->name}.");

                // Manage the counter
                $count++; 

                // get the durations of both modules
                $ttDuration = $row['ttDuration'];
                $wcDuration = $row['wcDuration'];

                if (!empty($ttDuration) && !empty($wcDuration)) 
                {
                    // Get upper and lower margin settings
                    include_once 'modules/stic_Settings/Utils.php';
                    $lowerMarginPercent = stic_SettingsUtils::getSetting('LOWER_MARGIN_PERCENT') / 100;
                    $upperMarginPercent = stic_SettingsUtils::getSetting('UPPER_MARGIN_PERCENT') / 100;
                    
                    // Calculate the upper and lower allowed difference
                    $lowerDifference = $wcDuration * $lowerMarginPercent;
                    $upperDifference = $wcDuration * $upperMarginPercent;
                    
                    // Get the difference between the two durations
                    $difference = abs($ttDuration - $wcDuration);
                    
                    if ($difference > $lowerDifference || $difference > $upperDifference) {
                        $errorMsg = $this->getLabel('HOURS_NOT_MATCH') . $assignedUser->name;
                        $error = 1;
                    }
                } else {
                    $errorMsg = $this->getLabel('HOURS_NOT_MATCH') . $assignedUser->name;
                    $error = 1;
                }

                if ($error == 1) 
                {
                    // Manage the counter
                    $errors++;
                    
                    // Create the validation result
                    if (!empty($errorMsg)) 
                    {    
                        $errorMsg .= '<br /><br />';
                        $errorMsg .= '<a style="text-decoration:none" href="' . $sugar_config["site_url"] . '/index.php?module=stic_Time_Tracker&action=index&query=true&searchFormTab=advanced_search&assigned_user_id_advanced=' . $assignedUser->id . '"><span class="suitepicon suitepicon-action-list-maps" style="font-size:12px">&nbsp;&nbsp;</span><span> - ' . $this->getLabel("STIC_TIME_TRACKER_LIST_VIEW") . '</span></a><br />';
                        $errorMsg .= '<a style="text-decoration:none" href="' . $sugar_config["site_url"] . '/index.php?module=stic_Work_Calendar&action=index&query=true&searchFormTab=advanced_search&assigned_user_id_advanced=' . $assignedUser->id . '"><span class="suitepicon suitepicon-action-list-maps" style="font-size:12px">&nbsp;&nbsp;</span><span> - ' . $this->getLabel("STIC_WORK_CALENDAR_LIST_VIEW") . '</span></a><br />';
                        $errorMsg .= '<br />';
                        $validationResultMsg = '<span style="color:red;">' . $errorMsg . '</span>';                        
                        $data = array(
                            'name' => $this->getLabel('NAME'),
                            'stic_validation_actions_id' => $actionBean->id,
                            'log' => '<div>' . $validationResultMsg . '</div>',
                            'parent_type' => $this->functionDef['module'],
                            'parent_id' => $assignedUser->id,
                            'reviewed' => 'no',   
                            'assigned_user_id' => $assignedUser->id,
                        );
                        $this->logValidationResult($data);

                        $info['subject'] = $this->getLabel('EMAIL_SUBJECT');
                        $info['errorMsg'] = $errorMsg;
                        sendEmailToEmployeeAndResponsible($assignedUser, $row, $info);
                    }

                    // Report_always
                    global $current_user;
                    if (!$count && $actionBean->report_always) {
                        $errorMsg = $this->getLabel('NO_ROWS');
                        $data = array(
                            'name' => $errorMsg,
                            'stic_validation_actions_id' => $actionBean->id,
                            'log' => '<div>' . $errorMsg . '</div>',
                            'reviewed' => 'not_necessary',              
                            'assigned_user_id' => $current_user->id, // In this message we indicate the administrator user
                        );
                        $this->logValidationResult($data);
                    } else if (!$errors && $actionBean->report_always) {
                        $errorMsg = $this->getLabel('NO_ERRORS');
                        $data = array(
                            'name' => $errorMsg,
                            'stic_validation_actions_id' => $actionBean->id,
                            'log' => '<div>' . $errorMsg . '</div>',
                            'reviewed' => 'not_necessary',       
                            'assigned_user_id' => $current_user->id, // In this message we indicate the administrator user            
                        );
                        $this->logValidationResult($data);
                    }
            
                    // Add the user id to the validated users array
                    array_push($validatedUsers, $assignedUser->id);
                    $error = 0;
                }
            }
        }
        
        $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ": Reviewed Records [{$count}] wrong [{$errors}]");        
        return true;
    }
}