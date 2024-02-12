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
$module_name='adrep_column';
$subpanel_layout = array (
  'top_buttons' => 
  array (
    /*0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'popup_module' => 'adrep_column',
    ),*/
  ),
  'where' => '',
  'list_fields' => 
  array (
    'name' => 
    array (
      'vname' => 'LBL_NAME',
      //'widget_class' => 'SubPanelDetailViewLink',
      'width' => '10%',
      'default' => true,
    ),
    'column_label' => 
    array (
      'type' => 'varchar',
      'vname' => 'LBL_PARAMETER_LABEL',
      'width' => '10%',
      'default' => true,
    ),
    'type' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'vname' => 'LBL_TYPE',
      'width' => '10%',
      'default' => true,
    ),
    'decimals' => 
    array (
      'type' => 'int',
      'default' => true,
      'vname' => 'LBL_DECIMALS',
      'width' => '10%',
    ),
    'priority' => 
    array (
      'type' => 'int',
      'default' => true,
      'vname' => 'LBL_PRIORITY',
      'width' => '10%',
    ),
    'dropdown_name' => 
    array (
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'vname' => 'LBL_DROPDOWN_NAME',
      'width' => '10%',
    ),
    'linked_module_name' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'vname' => 'LBL_LINKED_MODULE_NAME',
      'width' => '10%',
      'default' => true,
    ),
    'total_type' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'default' => true,
      'vname' => 'LBL_TOTAL_TYPE',
      'width' => '10%',
    ),
    'width' => 
    array (
      'type' => 'enum',
      'studio' => 'visible',
      'default' => true,
      'vname' => 'LBL_WIDTH',
      'width' => '10%',
    ),
    /*'active_flag' => 
    array (
      'type' => 'bool',
      'default' => true,
      'vname' => 'LBL_ACTIVE_FLAG',
      'width' => '10%',
    ),*/
  ),
);
