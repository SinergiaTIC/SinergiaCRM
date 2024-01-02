<?php


require_once 'include/utils/activity_utils.php';
require_once('modules/Calendar/CalendarActivity.php');


class CustomCalendarActivity extends CalendarActivity
{
    // Overriding this function to include STIC modules into iCal service
    // STIC#625
    public function __construct($args)
    {
        // if we've passed in an array, then this is a free/busy slot
        // and does not have a sugarbean associated to it
        global $timedate;

        if (is_array($args)) {
            $this->start_time = clone $args[0];
            $this->end_time = clone $args[1];
            $this->sugar_bean = null;
            $timedate->tzGMT($this->start_time);
            $timedate->tzGMT($this->end_time);

            return;
        }

        // else do regular constructor..

        $sugar_bean = $args;
        $this->sugar_bean = $sugar_bean;


        if ($sugar_bean->object_name === 'Task') {
            if (!empty($this->sugar_bean->date_start)) {
                $this->start_time = $timedate->fromUser($this->sugar_bean->date_start);
            } else {
                $this->start_time = $timedate->fromUser($this->sugar_bean->date_due);
            }
            if (empty($this->start_time)) {
                return;
            }
            $this->end_time = $timedate->fromUser($this->sugar_bean->date_due);
        // STIC-Custom 20220314 AAM - Adding STIC modules to iCal
        // STIC#625
        } else if ($sugar_bean->object_name === 'stic_Sessions') {
            $this->start_time = $timedate->fromUser($this->sugar_bean->start_date);
            if (empty($this->start_time)) {
                return;
            }
            $this->end_time = $timedate->fromUser($this->sugar_bean->end_date);
            if (empty($this->end_time)) {
                return;
            }
        } else if ($sugar_bean->object_name === 'stic_FollowUps') {
            $this->start_time = $timedate->fromUser($this->sugar_bean->start_date);
            if (empty($this->start_time)) {
                return;
            }
            $mins = $this->sugar_bean->duration;
            if (empty($mins)) {
                $mins = 0;
            }
            $this->end_time = $this->start_time->get("+$mins minutes");
            if (empty($this->end_time)) {
                return;
            }
        // END STIC
        } else {
            $this->start_time = $timedate->fromUser($this->sugar_bean->date_start);
            if (empty($this->start_time)) {
                return;
            }
            $hours = $this->sugar_bean->duration_hours;
            if (empty($hours)) {
                $hours = 0;
            }
            $mins = $this->sugar_bean->duration_minutes;
            if (empty($mins)) {
                $mins = 0;
            }
            $this->end_time = $this->start_time->get("+$hours hours $mins minutes");
        }
        // Convert it back to database time so we can properly manage it for getting the proper start and end dates
        $timedate->tzGMT($this->start_time);
        $timedate->tzGMT($this->end_time);
    }

    // Overriding this function to use CustomCalendarActivity
    // STIC#625
    public static function get_activities(
        $activities,
        $user_id,
        $show_tasks,
        $view_start_time,
        $view_end_time,
        $view,
        $show_calls = true,
        $show_completed = true
    ) {
        global $current_user;
        global $beanList;
        $act_list = array();
        $seen_ids = array();

        $completedCalls = '';
        $completedMeetings = '';
        $completedTasks = '';
        if (!$show_completed) {
            $completedCalls = " AND calls.status = 'Planned' ";
            $completedMeetings = " AND meetings.status = 'Planned' ";
            $completedTasks = " AND tasks.status != 'Completed' ";
        }

        foreach ($activities as $key => $activity) {
            if (ACLController::checkAccess($key, 'list', true)) {
                /* END - SECURITY GROUPS */
                $class = $beanList[$key];
                $bean = new $class();

                if ($current_user->id === $user_id) {
                    $bean->disable_row_level_security = true;
                }

                $where = self::get_occurs_within_where_clause(
                    $bean->table_name,
                    isset($bean->rel_users_table) ? $bean->rel_users_table : null,
                    $view_start_time,
                    $view_end_time,
                    $activity['start'],
                    $activity['end']
                );

                if ($key === 'Meetings') {
                    $where .= $completedMeetings;
                } elseif ($key === 'Calls') {
                    $where .= $completedCalls;
                    if (!$show_calls) {
                        continue;
                    }
                } elseif ($key === 'Tasks') {
                    $where .= $completedTasks;
                    if (!$show_tasks) {
                        continue;
                    }
                }

                $focus_list = build_related_list_by_user_id($bean, $user_id, $where);
                // require_once 'modules/SecurityGroups/SecurityGroup.php';
                foreach ($focus_list as $focusBean) {
                    if (isset($seen_ids[$focusBean->id])) {
                        continue;
                    }
                    /* TODO update currently unused functionality, disabled as expensive
                    $in_group = SecurityGroup::groupHasAccess($key, $focusBean->id, 'list');
                    $show_as_busy = !ACLController::checkAccess(
                        $key,
                        'list',
                        $current_user->id === $user_id,
                        'module',
                        $in_group
                    );
                    $focusBean->show_as_busy = $show_as_busy;*/

                    $seen_ids[$focusBean->id] = 1;
                    // STIC-Custom 20220314 AAM - Adding STIC modules to iCal
                    // STIC#625
                    // $act = new CalendarActivity($focusBean);
                    $act = new CustomCalendarActivity($focusBean);

                    if (!empty($act)) {
                        $act_list[] = $act;
                    }
                }
            }
        }

        return $act_list;
    }
}