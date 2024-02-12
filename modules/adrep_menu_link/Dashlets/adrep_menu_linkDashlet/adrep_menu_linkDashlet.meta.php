<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
 
global $app_strings;

$dashletMeta['adrep_menu_linkDashlet'] = array('module'		=> 'adrep_menu_link',
										  'title'       => translate('LBL_HOMEPAGE_TITLE', 'adrep_menu_link'), 
                                          'description' => 'A customizable view into adrep_menu_link',
                                          'icon'        => 'icon_adrep_menu_link_32.gif',
                                          'category'    => 'Module Views');
