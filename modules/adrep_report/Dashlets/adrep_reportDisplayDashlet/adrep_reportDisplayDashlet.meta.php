<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
global $app_strings;

$dashletMeta['adrep_reportDisplayDashlet'] = array('module'		=> 'adrep_report',
                                          'title'       => translate('LBL_DISPLAY_REPORT_DASHLET', 'adrep_report'),
                                          'description' => 'Displays adrep Report',
                                          'category'    => 'Module Views');
