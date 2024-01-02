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
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$pluginmetadata = array(
    'id' => 'kcsvexport', 
    'type' => 'integration', 
    'category' => 'export',
    'displayname' => 'LBL_CSV_EXPORT',
    'integration' => array(
        'include' => 'kcsvexport.php',
        'class' => 'kcsvexport'
    )
);
?>
