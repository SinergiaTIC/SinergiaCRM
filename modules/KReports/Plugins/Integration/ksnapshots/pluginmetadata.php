<?php
$pluginmetadata = array(
    'id' => 'ksnapshots', 
    'type' => 'integration', 
    'category' => 'tool',
    'displayname' => 'LBL_KSNAPSHOTS',
    'integration' => array(
        'include' => 'ksnapshots.php',
        'class' => 'ksnapshot',
    ), 
    'includes' => array(
        'edit' => 'ksnapshot.js',
        'editPanel' => 'K.kreports.ksnapshot.snapshotPanel'
    )
);