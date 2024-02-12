<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
 
global $app_strings;

$dashletMeta['adrep_columnDashlet'] = array('module'		=> 'adrep_column',
										  'title'       => translate('LBL_HOMEPAGE_TITLE', 'adrep_column'), 
                                          'description' => 'A customizable view into adrep_column',
                                          'icon'        => 'icon_adrep_column_32.gif',
                                          'category'    => 'Module Views');
