<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
 
global $app_strings;

$dashletMeta['adrep_cacheDashlet'] = array('module'		=> 'adrep_cache',
										  'title'       => translate('LBL_HOMEPAGE_TITLE', 'adrep_cache'), 
                                          'description' => 'A customizable view into adrep_cache',
                                          'icon'        => 'icon_adrep_cache_32.gif',
                                          'category'    => 'Module Views');
