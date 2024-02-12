<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
 
global $app_strings;

$dashletMeta['adrep_chartDashlet'] = array('module'		=> 'adrep_chart',
										  'title'       => translate('LBL_HOMEPAGE_TITLE', 'adrep_chart'), 
                                          'description' => 'A customizable view into adrep_chart',
                                          'icon'        => 'icon_adrep_chart_32.gif',
                                          'category'    => 'Module Views');
