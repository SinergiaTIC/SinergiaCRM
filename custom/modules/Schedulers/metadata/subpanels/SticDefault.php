<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$subpanel_layout = array(
    'top_buttons' => array(
    ),
    'where' => "",

    'fill_in_additional_fields' => true,
    'list_fields' => array(
        'name' => array(
            'width' => '35%',
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'link' => true,
            'sortable' => true,
            'default' => true,
        ),
        'job_interval' => array(
            'width' => '20%',
            'sortable' => true,
            'vname' => 'LBL_LIST_JOB_INTERVAL',
            'default' => true,
            'sortable' => false,
        ),
        'date_time_start' => array(
            'width' => '25%',
            'vname' => 'LBL_LIST_RANGE',
            'customCode' => '{$DATE_TIME_START} - {$DATE_TIME_END}',
            'default' => true,
            'related_fields' => array(
                0 => 'date_time_end',
            ),
        ),
        'status' => array(
            'width' => '15%',
            'vname' => 'LBL_LIST_STATUS',
            'default' => true,
        ),
        'last_run' => array(
            'type' => 'datetime',
            'vname' => 'LBL_LAST_RUN',
            'width' => '10%',
            'default' => true,
        ),
    ),
);
