<?php
$module_name = 'stic_Custom_View_Conditions';
$viewdefs [$module_name] = 
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
          0 => 'description',
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'stic_custom_view_conditions_stic_custom_view_customizations_name',
            'label' => 'LBL_STIC_CUSTOM_VIEW_CONDITIONS_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_FROM_STIC_CUSTOM_VIEW_CUSTOMIZATIONS_TITLE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
