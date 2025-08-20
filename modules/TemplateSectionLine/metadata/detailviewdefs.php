<?php
$module_name = 'TemplateSectionLine';
$viewdefs [$module_name] =
array(
  'DetailView' =>
  array(
    'templateMeta' =>
    array(
      'form' =>
      array(
        'buttons' =>
        array(
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
      'maxColumns' => '2',
      'widths' =>
      array(
        0 =>
        array(
          'label' => '10',
          'field' => '30',
        ),
        1 =>
        array(
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => true,
      'tabDefs' =>
      array(
        'DEFAULT' =>
        array(
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_STIC_PANEL_RECORD_DETAILS' => array(
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),        
      ),
    ),
    'panels' =>
    array(
      'default' => array(
        0 => array(
          0 => 'name',
          1 => 'assigned_user_name',          
        ),
        1 => array(
          0 => array(
            'name' => 'grp',
            'label' => 'LBL_GRP',
          ),
          1 => array(
            'name' => 'ord',
            'label' => 'LBL_ORD',
          ),
        ),
        3 => array(
          0 => 'htmlcode_c',
        ),
        4 => array(
          0 => array (
            'name' => 'thumbnail_image_c',
            'studio' => 'visible',
            'label' => 'LBL_THUMBNAIL_IMAGE',
          ),
          1 => array (
            'name' => 'thumbnail_name_c',
            'label' => 'LBL_THUMBNAIL_NAME',
          ),
        ),      
      ),
      'LBL_STIC_PANEL_RECORD_DETAILS' => array(
        0 => array(
          0 => array(
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED',
          ),
          1 => array(
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value}',
            'label' => 'LBL_DATE_ENTERED',
          ),
        ),
        1 => array(
          0 => array(
            'name' => 'modified_by_name',
            'label' => 'LBL_MODIFIED_NAME',
          ),
          1 => array(
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value}',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
      ),    
    ),
  ),
);
