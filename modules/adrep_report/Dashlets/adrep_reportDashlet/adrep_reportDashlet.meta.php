<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
 
global $app_strings;

$dashletMeta['adrep_reportDashlet'] = array('module'		=> 'adrep_report',
										  'title'       => translate('LBL_HOMEPAGE_TITLE', 'adrep_report'), 
                                          'description' => 'A customizable view into adrep_report',
                                          'icon'        => 'icon_adrep_report_32.gif',
                                          'category'    => 'Module Views');
