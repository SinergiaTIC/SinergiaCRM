<?php
$viewdefs ['Campaigns'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
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
      'javascript' => '{sugar_getscript file="include/javascript/popup_parent_helper.js"}
                     {sugar_getscript file="modules/Campaigns/SticEditView.js"}',
      'useTabs' => true,
      'tabDefs' => 
      array (
        'LBL_CAMPAIGN_INFORMATION' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_NAVIGATION_MENU_GEN2' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'lbl_campaign_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'name',
          ),
          1 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'campaign_type',
            'displayParams' => 
            array (
              'javascript' => 'onchange="type_change();"',
            ),
          ),
          1 => 
          array (
            'name' => 'status',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'start_date',
            'displayParams' => 
            array (
              'required' => false,
              'showFormats' => true,
            ),
          ),
          1 => 
          array (
            'name' => 'end_date',
            'displayParams' => 
            array (
              'showFormats' => true,
            ),
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'frequency',
            'customCode' => '<div style=\'none\' id=\'freq_field\'>{html_options name="frequency" options=$fields.frequency.options selected=$fields.frequency.value}</div></TD>',
            'customLabel' => '<div style=\'none\' id=\'freq_label\'>{$MOD.LBL_CAMPAIGN_FREQUENCY}</div>',
          ),
          1 => 
          array (
            'name' => 'parent_name',
            'studio' => 'visible',
            'label' => 'LBL_FLEX_RELATE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'content',
            'displayParams' => 
            array (
              'rows' => 8,
              'cols' => 80,
            ),
          ),
        ),
      ),
      'LBL_NAVIGATION_MENU_GEN2' => 
      array (
        0 => 
        array (
          0 => 'currency_id',
          1 => 'impressions',
        ),
        1 => 
        array (
          0 => 'budget',
          1 => 'expected_cost',
        ),
        2 => 
        array (
          0 => 'actual_cost',
          1 => 'expected_revenue',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'objective',
            'displayParams' => 
            array (
              'rows' => 8,
              'cols' => 80,
            ),
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
;
?>
