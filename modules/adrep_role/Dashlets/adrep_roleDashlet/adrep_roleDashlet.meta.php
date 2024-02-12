<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
 
global $app_strings;

$dashletMeta['adrep_roleDashlet'] = array('module'		=> 'adrep_role',
										  'title'       => translate('LBL_HOMEPAGE_TITLE', 'adrep_role'), 
                                          'description' => 'A customizable view into adrep_role',
                                          'icon'        => 'icon_adrep_role_32.gif',
                                          'category'    => 'Module Views');
