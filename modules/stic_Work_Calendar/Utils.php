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
class stic_Work_CalendarUtils
{
    /**
     * This function creates periodic work calendar records for a certain employee, based on the parameters received via $_REQUEST
     * and defined in the periodic work calendar records creation wizard (custom/modules/Employees/tpls/workCalendarWizard.tpl)
     *
     * @return void
     */
    public static function createPeriodicWorkCalendarRecords()
    {
        // Disable Advanced Open Discovery to avoid slowing down the writing of the records affected by this function.
        global $sugar_config;
        $aodConfig = $sugar_config['aod']['enable_aod'];
        $sugar_config['aod']['enable_aod'] = false;

        $startTime = microtime(true);

        // TIP: the action can be run from the web browser using this url:
        // http://<CRM domain>/index.php?module=stic_Work_Calendar&action=createPeriodicWorkCalendarRecords&return_module=stic_Work_Calendar&return_action=index&repeat_type=Daily&repeat_interval=1&repeat_count=3&repeat_until=&repeat_start_day=02/04/2019&repeat_final_day=02/04/2019&repeat_start_hour=09&repeat_start_minute=0&repeat_final_hour=10&repeat_final_minute=1&employeeId=<id_employee>"

        global $current_user, $timedate;

        // Get the data from the smarty template
        $repeat_type = $_REQUEST['repeat_type'];
        $interval = $_REQUEST['repeat_interval'];
        $count = $_REQUEST['repeat_count'];
        $until = $_REQUEST['repeat_until'];
        $startDay = $_REQUEST['repeat_start_day'];
        $finalDay = $_REQUEST['repeat_final_day'];
        $startHour = $_REQUEST['repeat_start_hour'];
        $startMinute = $_REQUEST['repeat_start_minute'];
        $finalHour = $_REQUEST['repeat_final_hour'];
        $finalMinute = $_REQUEST['repeat_final_minute'];

        // Get absolute values of 'minutes' and set values
        // for $finalDay and $finalHour if necessary

        // Set minute interval as defined in $sugar_config
        $m = 0;
        $minutesInterval = 1;
        $repeatMinuts1 = array('00');
        do {
            $m = $m + $minutesInterval;
            $repeatMinuts1[] = str_pad($m, 2, '0', STR_PAD_LEFT);
        } while ($m < (60 - $minutesInterval));

        $startMinute = $repeatMinuts1[$startMinute];
        $finalMinute = $repeatMinuts1[$finalMinute];
        if ($finalDay == '') {$finalDay = $startDay;}
        if ($finalHour == '00') {$finalHour = $startHour + 1;}
        if ($finalHour < $startHour and $finalDay == $startDay) {$finalHour = $startHour + 1;}

        // Take the dates collected in the smarty template and set their values
        // in order to calculate the duration of the work calendar record
        $until = str_replace('/', '-', $until);
        $until = date("Y-m-d", strtotime($until));
        $startDay = str_replace('/', '-', $startDay);
        $startDay = date('Y-m-d H:i:s', strtotime($startDay . " + $startHour hours + $startMinute minutes"));
        $finalDay = str_replace('/', '-', $finalDay);
        $finalDay = date('Y-m-d H:i:s', strtotime($finalDay . " + $finalHour hours + $finalMinute minutes"));
        $duration = strtotime($finalDay) - strtotime($startDay);

        // Depending on the chosen type, perform the right operation
        // (none, daily, weekly, monthly or annual)
        if ($repeat_type == '') {
            header("Location: index.php?action=index&module=stic_Work_Calendar");
        } else {
            // Daily
            if ($repeat_type == 'Daily') {
                $firstDay = $startDay;
                if ($count != '' and $count != '0') {
                    for ($i = 0; $i < $count; $i++) {
                        $date[$i] = $firstDay;
                        $firstDay = date('Y-m-d H:i:s', strtotime($firstDay . " + $interval days"));
                    }
                } else if ($until != '') {
                    $first_d = date("Y-m-d", strtotime($firstDay));
                    for ($i = 0; strtotime($first_d) <= strtotime($until); $i++) {
                        $date[$i] = $firstDay;
                        $firstDay = date('Y-m-d H:i:s', strtotime($firstDay . " + $interval days"));
                        $first_d = date("Y-m-d", strtotime($firstDay));
                    }
                }
            }
            // Monthly
            if ($repeat_type == 'Monthly') {
                $firstMonth = $startDay;
                if ($count != '' and $count != '0') {
                    for ($i = 0; $i < $count; $i++) {
                        $date[$i] = $firstMonth;
                        $firstMonth = date('Y-m-d H:i:s', strtotime($firstMonth . " + $interval months"));
                    }
                } else if ($until != '') {
                    $firstM = date("Y-m-d", strtotime($firstMonth));
                    for ($i = 0; strtotime($firstM) <= strtotime($until); $i++) {
                        $date[$i] = $firstMonth;
                        $firstMonth = date('Y-m-d H:i:s', strtotime($firstMonth . " + $interval  months"));
                        $firstM = date("Y-m-d", strtotime($firstMonth));
                    }
                }
            }
            // Yearly
            if ($repeat_type == 'Yearly') {
                $firstYear = $startDay;
                if ($count != '' and $count != '0') {
                    for ($i = 0; $i < $count; $i++) {
                        $date[$i] = $firstYear;
                        $firstYear = date('Y-m-d H:i:s', strtotime($firstYear . " + $interval years"));
                    }
                } else if ($until != '') {
                    $firstY = date("Y-m-d", strtotime($firstYear));
                    for ($i = 0; strtotime($firstY) <= strtotime($until); $i++) {
                        $date[$i] = $firstYear;
                        $firstYear = date('Y-m-d H:i:s', strtotime($firstYear . " + $interval years"));
                        $firstY = date("Y-m-d", strtotime($firstYear));
                    }
                }
            }
            // Weekly
            if ($repeat_type == 'Weekly') {
                // We create the table $dow of the days of the week, fixing the problem that
                // in the smarty template Sunday is in position '0' and not in position '7'
                $times = 0;
                for ($i = 1; $i < 7; $i++) {
                    $dow[$i] = $_REQUEST['repeat_dow_' . $i];
                    if ($dow[$i] == 'on') {
                        $times = $times + 1;
                        $dow[$i] = 1;} else { $dow[$i] = 0;}
                }
                $zero = 0;
                $dow[7] = $_REQUEST['repeat_dow_' . $zero];
                if ($dow[7] == 'on') {
                    $times = $times + 1;
                    $dow[7] = 1;} else { $dow[7] = 0;}

                if ($times > '0') {
                    $days = array('1', '2', '3', '4', '5', '6', '7');
                    $firstWeek = $startDay;
                    $week = 1;
                    $i = 0;
                    if ($count != '' and $count != '0') {
                        while ($i < $count) {
                            if ($week == '1') {
                                $firstDate = $firstWeek;
                                $weekDay = $days[date('w', strtotime($firstDate))];
                                $weekDay2 = $weekDay;
                                if ($weekDay == '1') {$weekDay = '7';} else { $weekDay = $weekDay - 1;}
                                for ($x = $weekDay; $x < 8; $x++) {
                                    if ($dow[$x] == 1) {
                                        $adder = $x - $weekDay;
                                        $firstWeek = date('Y-m-d H:i:s', strtotime($firstDate . " + $adder days"));
                                        $date[$i] = $firstWeek;
                                        $i = $i + 1;
                                        if ($i == $count) {$x = 8;}
                                    }
                                }
                                $week = '2';
                                if ($interval > 1) {$x = $interval - 1;} else { $x = '0';}
                                if ($weekDay2 == '1') {
                                    $weekDay2 = '8';
                                    $x = 0;}
                                $differenceDay = 8 - $weekDay2;
                                $firstWeek = date('Y-m-d H:i:s', strtotime($startDay . " + $x weeks + $differenceDay days"));
                            }

                            if ($week == '2' and $i < $count) {
                                $newWeek = $firstWeek;
                                for ($x = 1; $x < 8; $x++) {
                                    if ($dow[$x] == 1) {
                                        $addDays = $x;
                                        $firstWeek = date('Y-m-d H:i:s', strtotime($newWeek . " + $addDays days"));
                                        $date[$i] = $firstWeek;
                                        $i = $i + 1;if ($i == $count) {$x = 8;}
                                    }
                                }
                                $firstWeek = date('Y-m-d H:i:s', strtotime($newWeek . " + $interval weeks"));
                            }
                        }
                    } else if ($until != '') {
                        $firstWeek = $startDay;
                        $week = 1;
                        $i = 0;

                        while (strtotime($firstWeek) <= strtotime($until)) {
                            if ($week == '1') {
                                $startDay = $timedate->asDb($timedate->fromString($startDay), $current_user);
                                $finalDay = $timedate->asDb($timedate->fromString($finalDay), $current_user);
                                $firstDate = $firstWeek;
                                $weekDay = $days[date('w', strtotime($firstDate))];
                                $weekDay2 = $weekDay;
                                if ($weekDay == '1') {$weekDay = '7';} else { $weekDay = $weekDay - 1;}
                                for ($x = $weekDay; $x < 8; $x++) {
                                    if ($dow[$x] == 1) {
                                        $adder = $x - $weekDay;
                                        $firstWeek = date('Y-m-d H:i:s', strtotime($firstDate . " + $adder days"));
                                        $date[$i] = $firstWeek;
                                        $i = $i + 1;
                                        if (strtotime($firstWeek) == strtotime($until)) {$x = 8;}
                                    }
                                }
                                $week = '2';
                                if ($interval > 1) {$x = $interval - 1;} else { $x = '0';}
                                if ($weekDay2 == '1') {
                                    $weekDay2 = '8';
                                    $x = 0;}
                                $differenceDay = 8 - $weekDay2;
                                $firstWeek = date('Y-m-d H:i:s', strtotime($firstDate . " + $x weeks + $differenceDay days"));
                            }
                            if ($week == '2' and strtotime($firstWeek) <= strtotime($until)) {
                                $newWeek = $firstWeek;
                                for ($x = 1; $x < 8; $x++) {
                                    if ($dow[$x] == 1) {
                                        $addDays = $x;
                                        $firstWeek = date('Y-m-d H:i:s', strtotime($newWeek . " + $addDays days"));

                                        if ($until >= substr($firstWeek, 0, 10)) {
                                            $date[$i] = $firstWeek;
                                        }

                                        $i = $i + 1;
                                        if (strtotime($firstWeek) == strtotime($until)) {$x = 8;}
                                    }
                                }

                                $firstWeek = date('Y-m-d H:i:s', strtotime($newWeek . " + $interval weeks"));
                            }
                        }
                    }
                } else {
                    $firstWeek = $startDay;
                    if ($count != '' and $count != '0') {
                        for ($i = 0; $i < $count; $i++) {
                            $date[$i] = $firstWeek;
                            $firstWeek = date('Y-m-d H:i:s', strtotime($firstWeek . " + $interval weeks"));
                        }
                    } else if ($until != '') {
                        $firstW = date("Y-m-d", strtotime($firstWeek));
                        for ($i = 0; strtotime($firstW) <= strtotime($until); $i++) {
                            $date[$i] = $firstWeek;
                            $firstWeek = date('Y-m-d H:i:s', strtotime($firstWeek . " + $interval weeks"));
                            $firstW = date("Y-m-d", strtotime($firstWeek));
                        }
                    }
                }
            }
        }

        // Loop for work calendar records creation
        $counter = count($date);
        $assignedUserId = $_REQUEST['assigned_user_id'];        
        for ($i = 0; $i < $counter; $i++) 
        {
            $date[$i] = $timedate->to_db($timedate->to_display_date_time($date[$i], true, false, $current_user));

            if ($finalDay != '') {
                $finalDay = strtotime($date[$i]) + $duration;
                $finalDay = date('Y-m-d H:i:s', $finalDay);
            }
            $workCalendarBean = BeanFactory::newBean('stic_Work_Calendar');
            if (isset($_REQUEST['work_calendar_name']) && $_REQUEST['work_calendar_name'] != '') {
                $workCalendarBean->name = $_REQUEST['work_calendar_name'];
            }
            $workCalendarBean->start_date = $date[$i];
            $workCalendarBean->end_date = $finalDay;
            if (isset($_REQUEST['type']) && $_REQUEST['type'] != '') {
                $workCalendarBean->type = $_REQUEST['type'];
            }
            $workCalendarBean->assigned_user_id = $assignedUserId;
            if (isset($_REQUEST['description']) && $_REQUEST['description'] != '') {
                $workCalendarBean->description = $_REQUEST['description'];
            }
            $workCalendarBean->save(false);
        }
        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;
        $GLOBALS['log']->debug(__METHOD__ . '(' . __LINE__ . ") >> Has been created $i work calendar records in $totalTime seconds");

        // Reactivamos la configuración previa de Advanced Open Discovery
        $sugar_config['aod']['enable_aod'] = $aodConfig;

        header("Location: index.php?module=stic_Work_Calendar&action=index");
    }
}
