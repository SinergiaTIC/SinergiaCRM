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
require_once 'include/MVC/View/views/view.edit.php';
require_once 'SticInclude/Views.php';

#[\AllowDynamicProperties]
class stic_BookingsViewEdit extends ViewEdit
{

    public function __construct()
    {
        parent::__construct();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
    }

    public function preDisplay()
    {
        global $timedate, $current_user;

        // If the Bookings' EditView is launched from the Bookings' Calendar, retrieve start and end dates from there
        if (isset($_REQUEST['return_module'], $_REQUEST['start'], $_REQUEST['end']) && $_REQUEST['return_module'] == 'stic_Bookings_Calendar' && $_REQUEST['start'] && $_REQUEST['end']) {
            // Parse the dates received from the calendar
            $startDate = new DateTime($_REQUEST['start']);
            $this->bean->start_date = $timedate->to_display_date_time(date_format($startDate, 'Y-m-d H:i:s'), false, false, $current_user);
            $endDate = new DateTime($_REQUEST['end']);
            $this->bean->end_date = $timedate->to_display_date_time(date_format($endDate, 'Y-m-d H:i:s'), false, false, $current_user);
            if ($_REQUEST['allDay'] == "true") {
                $this->bean->all_day = true;
            }
        } else {
            // If all_day is checked then remove the hours and minutes
            // and apply timezone to the dates
            if (isset($this->bean->all_day) && $this->bean->all_day == '1') {
                $startDate = explode(' ', $this->bean->fetched_row['start_date']);
                if ($startDate[1] > "12:00") {
                    $startDate = new DateTime($startDate[0]);
                    $startDate = $startDate->modify("next day");
                    $startDateDate = $timedate->asUserDate($startDate, false, $current_user);
                    $this->bean->start_date = $startDateDate . ' 00:00';
                } else {
                    $startDate = new DateTime($startDate[0]);
                    $startDate = $timedate->asUserDate($startDate, false, $current_user);
                    $this->bean->start_date = $startDate . ' 00:00';
                }

                $endDate = explode(' ', $this->bean->fetched_row['end_date']);
                if ($endDate[1] > "12:00") {
                    $endDate = new DateTime($endDate[0]);
                    $endDate = $endDate->modify("next day");
                    $endDate = $timedate->asUserDate($endDate, false, $current_user);
                    $this->bean->end_date = $endDate . ' 00:00';
                } else {
                    $endDate = new DateTime($endDate[0]);
                    $endDate = $timedate->asUserDate($endDate, false, $current_user);
                    $this->bean->end_date = $endDate . ' 00:00';
                }
            }
        }

        parent::preDisplay();

        SticViews::preDisplay($this);

        // Write here you custom code

    }

    public function display()
    {
        require_once 'SticInclude/Utils.php';

        global $mod_strings, $app_strings;
        SticViews::display($this);
        
        $config_resource_fields = require 'modules/stic_Bookings/configResourceFields.php';
        $config_place_fields = require 'modules/stic_Bookings/configPlaceFields.php';
    

        global $sugar_config, $current_language, $app_list_strings, $current_user;

        $repeat_intervals = array();
        for ($i = 1; $i <= 30; $i++) {
            $repeat_intervals[$i] = $i;
        }

        $repeat_hours = array("00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23");

        // Set minute interval as defined in $sugar_config
        $m = 0;
        $minutesInterval = 1;
        $repeat_minutes = array('00');
        do {
            $m = $m + $minutesInterval;
            $repeat_minutes[] = str_pad($m, 2, '0', STR_PAD_LEFT);
        } while ($m < (60 - $minutesInterval));

        $fdow = $current_user->get_first_day_of_week();
        $dow = array();
        for ($i = $fdow; $i < $fdow + 7; $i++) {
            $day_index = $i % 7;
            $dow[] = array("index" => $day_index, "label" => $app_list_strings['dom_cal_day_short'][$day_index + 1]);
        }


        // Add the resources template
        $this->ev->defs['templateMeta']['form']['footerTpl'] = 'modules/stic_Bookings/tpls/EditViewFooter.tpl';
        $this->ss->assign('REQUEST', $_REQUEST);
        $this->ss->assign('APPLIST', $app_list_strings);
        $this->ss->assign('repeat_intervals', $repeat_intervals);
        $this->ss->assign('repeat_hours', $repeat_hours);
        $this->ss->assign('repeat_minutes', $repeat_minutes);
        $this->ss->assign('dow', $dow);

        $relationshipName = 'stic_resources_stic_bookings';

        $config_resource_fields_json = json_encode(array_keys($config_resource_fields));
        $config_place_fields_json = json_encode(array_keys($config_place_fields)); 
        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('APP', $app_strings);

        echo "<script>
            var config_resource_fields = $config_resource_fields_json;
            var config_place_fields = $config_place_fields_json;
        </script>";
        
        echo '<link rel="stylesheet" href="include/javascript/selectize/selectize.bootstrap3.css">';
        echo getVersionedScript("include/javascript/selectize/selectize.min.js");

        // If the Bookings editview is launched from the "new" button in the Resources detailview Bookings subpanel,
        // then add the resource into the new booking. Notice that stic_resources_id is only available in that case,
        // not when Bookings editview is launched from the "edit" button in an already existing booking in the subpanel.
        if (($_REQUEST['return_module'] ?? null) == 'stic_Resources' && !empty($_REQUEST['stic_resources_id'] ?? '')){
            // When creating a record from a subpanel, the record in the detailview will be set as the parent record of the new one, 
            // ie, it will be assigned to the flex related field if there is any. In this case, the new booking would have a resource
            // as a parent record, what is nonsense. So let's remove the assignment from the $_REQUEST array.
            unset($_REQUEST['parent_type']);
            unset($_REQUEST['parent_name']);
            unset($_REQUEST['parent_id']);

            $resources[] = BeanFactory::getBean('stic_Resources', $_REQUEST['stic_resources_id']);
            $parsedResources = $this->parseResourceItems($resources);
            $parsedResourcesJson = json_encode($parsedResources);

            echo <<<SCRIPT
			<script>resources = $parsedResourcesJson;</script>
			SCRIPT;
        } else {
            // In any other case, check if there are currently related resources and load them into the Bookings editview
            if (!$this->bean->load_relationship($relationshipName)) {
                $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ': : Failed retrieving related resources data');
            } else {
                if ($resources = $this->bean->$relationshipName->getBeans()) {
                    $parsedResources = $this->parseResourceItems($resources);
                    $parsedResourcesJson = json_encode($parsedResources);

                    echo <<<SCRIPT
					<script>resources = $parsedResourcesJson;</script>
				SCRIPT;
                } else {
                    echo <<<SCRIPT
					<script>resources = [];</script>
				SCRIPT;
                }
            }
        }
        // Check if it's a place booking
        if (!empty($resources)) { 
            $isPlaceBooking = true;
            foreach ($resources as $resourceBean) {
                if ($resourceBean->type !== 'place') {
                    $isPlaceBooking = false;
                    break;
                }
            }
            if ($isPlaceBooking) {
                $this->bean->place_booking = true; 
            }
        }
        parent::display();

        SticViews::display($this);
        echo getVersionedScript("SticInclude/vendor/jqColorPicker/jqColorPicker.min.js");
        echo getVersionedScript("modules/stic_Bookings/Utils.js");
    }

    // Prepare resources data to be displayed in the editview
    public function parseResourceItems($resourcesBeanArray)
    {
        global $app_list_strings;

        $config_resource_fields = require 'modules/stic_Bookings/configResourceFields.php';

        $parsedResources = array();
        foreach ($resourcesBeanArray as $resourceBean) {
            $resource = array();
            $resource['resource_id'] = $resourceBean->id;
            foreach ($config_resource_fields as $field => $label) {
                $value = $resourceBean->$field;
                
                if ($field === 'gender' || $field === 'user_type' || $field === 'place_type') {
                    $listKey = 'stic_resources_' . $field . '_list';
            
                    if (!empty($app_list_strings[$listKey]) && !empty($app_list_strings[$listKey][$value])) {
                        $value = $app_list_strings[$listKey][$value];
                    }
                } elseif ($field === 'daily_rate' || $field === 'hourly_rate') {
                    $value = self::formatNumberDec($value);
                }
                $resource['resource_' . $field] = $value;
            }
            
            $parsedResources[] = $resource;
        }
        return $parsedResources;
    }

    public static function formatNumberDec($str)
    {
        $separator = get_number_separators();
        $thousandsSeparator = $separator[0];
        $decimalSeparator = $separator[1];
        // STIC Custom 20250206 JBL - Avoid Uncaught TypeError in number_format
        // https://github.com/SinergiaTIC/SinergiaCRM/pull/477
        // return number_format($str, 2, $decimalSeparator, $thousandsSeparator);
        return number_format((float) $str, 2, $decimalSeparator, $thousandsSeparator);
        // End STIC Custom
    }
}
