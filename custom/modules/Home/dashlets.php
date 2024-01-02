<?php

/**
 * STIC-Custom AAM 
 * The defaultDashlet array is transformed to contain the default column number. 
 * To accomplish this we overwrite the index.php file of this module, placing the new version in this custom folder.
 */

$defaultDashlets = array(
    'SticNewsDashlet'=> array(
        'module' => 'Home',
        'column' => 1,
    ),
    'MyCallsDashlet'=> array(
        'module' => 'Calls',
        'column' => 0,
    ),
    'MyMeetingsDashlet'=> array(
        'module' => 'Meetings',
        'column' => 0,
    ),
    'MyTasksDashlet'=> array(
        'module' => 'Tasks',
        'column' => 0,
    ),
);