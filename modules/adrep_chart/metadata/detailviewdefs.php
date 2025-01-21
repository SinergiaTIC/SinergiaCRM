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
$module_name = 'adrep_chart';
$viewdefs [$module_name] =
array (
  'DetailView' =>
  array (
    'templateMeta' =>
    array (
      'form' =>
      array (
        'buttons' =>
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
      'maxColumns' => '2',
      'widths' =>
      array (
        0 =>
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 =>
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' =>
      array (
        'DEFAULT' =>
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' =>
    array (
      'default' =>
      array (
        0 =>
        array (
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 =>
        array (
          0 =>
          array (
            'name' => 'report_name',
            'studio' => 'visible',
            'label' => 'LBL_REPORT_NAME',
          ),
          1 =>
          array (
            'name' => 'chart_type',
            'studio' => 'visible',
            'label' => 'LBL_CHART_TYPE',
          ),
        ),
        2 =>
        array (
          0 =>
          array (
            'name' => 'group2_field',
            'studio' => 'visible',
            'label' => 'LBL_GROUP2_FIELD',
          ),
          1 =>
          array (
            'name' => 'group1_field',
            'studio' => 'visible',
            'label' => 'LBL_GROUP1_FIELD',
          ),
        ),
        3 =>
        array (
          0 =>
          array (
            'name' => 'x_label',
            'label' => 'LBL_X_LABEL',
          ),
          1 =>
          array (
            'name' => 'y_label',
            'label' => 'LBL_Y_LABEL',
          ),
        ),
				4 =>
				array (
					0 =>
					array (
						'name' => 'width',
						'label' => 'LBL_WIDTH',
					),
					1 =>
					array (
						'name' => 'height',
						'label' => 'LBL_HEIGHT',
					),
				),
        5 => 
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
?>
