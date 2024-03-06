<?php
/*
 * The MIT License (MIT)
 * 
 * Copyright (c) 2018 Marnus van Niekerk, crm@mjvn.net
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
$module_name='adrep_menu_link';
$subpanel_layout = array (
  'top_buttons' => 
  array (
  ),
  'where' => '',
  'list_fields' => 
  array (
    'menu_module' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'vname' => 'LBL_MENU_MODULE',
      'width' => '10%',
      'default' => true,
    ),
    'menu_view' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'vname' => 'LBL_MENU_VIEW',
      'width' => '10%',
      'default' => true,
    ),
    'name' => 
    array (
      'vname' => 'LBL_NAME',
      'width' => '10%',
      'default' => true,
      'widget_class' => 'SubPanelDetailViewLink',
    ),
    'active_flag' => 
    array (
      'type' => 'bool',
      'default' => true,
      'vname' => 'LBL_ACTIVE_FLAG',
      'width' => '10%',
    ),
    'run_flag' => 
    array (
      'type' => 'bool',
      'default' => true,
      'vname' => 'LBL_RUN_FLAG',
      'width' => '10%',
    ),
    'edit_button' =>
    array(
      'vname' => 'LBL_EDIT_BUTTON',      
      'widget_class' => 'SubPanelEditButton',
      'module' => 'adrep_menu_link',
      'width' => '4%',
      'default' => true,
    ),    
    'remove_button' => array(
      'vname' => 'LBL_REMOVE',
      'widget_class' => 'SubPanelRemoveButton',
      'module' => 'adrep_menu_link',
      'width' => '5%',
      'default' => true,
    ),        
  ),
);