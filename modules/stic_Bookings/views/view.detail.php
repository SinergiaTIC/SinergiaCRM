<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/MVC/View/views/view.detail.php';
require_once 'SticInclude/Views.php';

class stic_BookingsViewDetail extends ViewDetail
{

    public function __construct()
    {

        parent::__construct();

    }

    public function preDisplay()
    {
        global $timedate, $current_user;

        // If all_day is checked then remove the hours and minutes
        // and apply timezone to the dates

        if ($this->bean->all_day == '1') {
            $startDate = explode(' ', $this->bean->fetched_row['start_date']);
            if ($startDate[1] > "12:00") {
                $startDate = $timedate->fromDbDate($startDate[0]);
                $startDate = $startDate->modify("next day");
                $startDate = $timedate->asUserDate($startDate, false, $current_user);
                $this->bean->start_date = $startDate;
            } else {
                $startDate = $timedate->fromDbDate($startDate[0]);
                $startDate = $timedate->asUserDate($startDate, false, $current_user);
                $this->bean->start_date = $startDate;
            }

            $endDate = explode(' ', $this->bean->fetched_row['end_date']);
            if ($endDate[1] > "12:00") {
                $endDate = $timedate->fromDbDate($endDate[0]);
                $endDate = $timedate->asUserDate($endDate, false, $current_user);
                $this->bean->end_date = $endDate;
            } else {
                $endDate = $timedate->fromDbDate($endDate[0]);
                $endDate = $endDate->modify("previous day");
                $endDate = $timedate->asUserDate($endDate, false, $current_user);
                $this->bean->end_date = $endDate;
            }
        }

        parent::preDisplay();

        SticViews::preDisplay($this);

        // Write here you custom code

    }

    public function display()
    {

        parent::display();

        SticViews::display($this);

        echo getVersionedScript("modules/stic_Bookings/Utils.js");

    }

}
