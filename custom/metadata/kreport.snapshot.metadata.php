<?php

/* * *******************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed
 * by Christian Knoll. All rights are (c) 2012 by Christian Knoll
 *
 * This Version of the KReporter is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of Christian Knoll
 *
 * You can contact us at info@kreporter.org
 * ****************************************************************************** */
if (!defined('sugarEntry') || !sugarEntry)
   die('Not A Valid Entry Point');

$dictionary['KReportSnapshots'] = array(
    'table' => 'kreportsnapshots',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'report_id' => array(
            'name' => 'report_id',
            'type' => 'id',
        ),
        'snapshotdate' => array(
            'name' => 'snapshotdate',
            'type' => 'datetime',
        ),
        'snapshotquery' => array(
            'name' => 'snapshotquery',
            'type' => 'text',
        ),
        'data' => array(
            'name' => 'data',
            'type' => 'longblob'
        ),
    ),
    'indices' => array(
    ),
);

$dictionary['KReportSnapshotsData'] = array(
    'table' => 'kreportsnapshotsdata',
    'fields' => array(
        /* 	   'id' => array(
          'name' => 'id',
          'type' => 'id',
          ), */
        'snapshot_id' => array(
            'name' => 'snapshot_id',
            'type' => 'id',
        ),
        'record_id' => array(
            'name' => 'record_id',
            'type' => 'double',
        ),
        'data' => array(
            'name' => 'data',
            'type' => 'blob',
        ),
    ),
    'indices' => array(
        //array('name' => 'snapshot_data', 'type' => 'primary', 'fields' => array('snapshot_id', 'record_id'))
    ),
);
