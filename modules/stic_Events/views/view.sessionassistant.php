<?php
class stic_EventsViewsessionassistant extends SugarView
{

    public function display()
    {
        global $sugar_config;

        parent::display();
        $repeat_intervals = array();
        for ($i = 1; $i <= 30; $i++) {
            $repeat_intervals[$i] = $i;
        }

        $repeat_hours = array("00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24");

        // Set minute interval as defined in $sugar_config
        $m = 0;
        $minutesInterval = $sugar_config['stic_datetime_combo_minute_interval'] ?: 15;
        $repeat_minutes = array('00');
        do {
            $m = $m + $minutesInterval;
            $repeat_minutes[] = str_pad($m, 2, '0', STR_PAD_LEFT);
        } while ($m < (60 - $minutesInterval));

        $fdow = $GLOBALS['current_user']->get_first_day_of_week();
        $dow = array();
        for ($i = $fdow; $i < $fdow + 7; $i++) {
            $day_index = $i % 7;
            $dow[] = array("index" => $day_index, "label" => $GLOBALS['app_list_strings']['dom_cal_day_short'][$day_index + 1]);
        }

        $this->ss->assign('REQUEST', $_REQUEST);
        $this->ss->assign('APPLIST', $GLOBALS['app_list_strings']);
        $this->ss->assign("repeat_intervals", $repeat_intervals);
        $this->ss->assign("repeat_hours", $repeat_hours);
        $this->ss->assign("repeat_minutes", $repeat_minutes);
        $this->ss->assign("minutes_interval", $sugar_config['stic_datetime_combo_minute_interval'] ?: 15);
        $this->ss->assign("dow", $dow);
        $this->ss->display('modules/stic_Events/tpls/SessionWizard.tpl'); //call tpl file

    }
}
