<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
 
global $app_strings;

$dashletMeta['adrep_parameterDashlet'] = array('module'		=> 'adrep_parameter',
										  'title'       => translate('LBL_HOMEPAGE_TITLE', 'adrep_parameter'), 
                                          'description' => 'A customizable view into adrep_parameter',
                                          'icon'        => 'icon_adrep_parameter_32.gif',
                                          'category'    => 'Module Views');
