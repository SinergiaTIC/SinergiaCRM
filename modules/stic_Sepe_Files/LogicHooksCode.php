<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class stic_Sepe_FilesLogicHooks {

    public function before_save(&$bean, $event, $arguments) {
        global $app_list_strings;
        // Create name if empty
        if (empty($bean->name)) {
            $xmlType = $app_list_strings['stic_sepe_file_types_list'][$bean->type];
            $date = $bean->reported_month;
            $month = date("n",strtotime($date));
            $month = str_pad($month, 2, 0, STR_PAD_LEFT);
            $year = date("Y",strtotime($date));
            $agreement = $bean->agreement ? (' - '.$bean->agreement) : '';
            $bean->name = $xmlType .$agreement. ' - ' .$year . ($bean->type === 'annual_ac' || $bean->type === 'annual_accd' ? '' : '-'.$month);
        }
    }
}