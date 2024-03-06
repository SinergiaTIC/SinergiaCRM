<?php
$module_name = 'jblt_TestingCustomViews';
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
      'useTabs' => true,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL3' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL6' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL4' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL5' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
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
        ),
        2 => 
        array (
          0 => '',
          1 => '',
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'adreca',
            'label' => 'LBL_ADRECA',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'adreca_city',
            'label' => 'LBL_ADRECA_CITY',
          ),
          1 => 
          array (
            'name' => 'adreca_country',
            'label' => 'LBL_ADRECA_COUNTRY',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'adreca_postalcode',
            'label' => 'LBL_ADRECA_POSTALCODE',
          ),
          1 => 
          array (
            'name' => 'adreca_state',
            'label' => 'LBL_ADRECA_STATE',
          ),
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'casellaverificacio',
            'label' => 'LBL_CASELLAVERIFICACIO',
          ),
          1 => 
          array (
            'name' => 'colorselector',
            'label' => 'LBL_COLORSELECTOR',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'data',
            'label' => 'LBL_DATA',
          ),
          1 => 
          array (
            'name' => 'datahora',
            'label' => 'LBL_DATAHORA',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'decimalfield',
            'label' => 'LBL_DECIMALFIELD',
          ),
          1 => 
          array (
            'name' => 'enter',
            'label' => 'LBL_ENTER',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'flotant',
            'label' => 'LBL_FLOTANT',
          ),
          1 => 
          array (
            'name' => 'moneda',
            'label' => 'LBL_MONEDA',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'opcio',
            'studio' => 'visible',
            'label' => 'LBL_OPCIO',
          ),
          1 => 
          array (
            'name' => 'selecciomultiple',
            'studio' => 'visible',
            'label' => 'LBL_SELECCIOMULTIPLE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'telefon',
            'label' => 'LBL_TELEFON',
          ),
          1 => 
          array (
            'name' => 'currency_id',
            'studio' => 'visible',
            'label' => 'LBL_CURRENCY',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'llistadesplegable',
            'studio' => 'visible',
            'label' => 'LBL_LLISTADESPLEGABLE',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'llistadesplegabledinamica',
            'studio' => 'visible',
            'label' => 'LBL_LLISTADESPLEGABLEDINAMICA',
          ),
        ),
      ),
      'lbl_editview_panel3' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'areatext',
            'studio' => 'visible',
            'label' => 'LBL_AREATEXT',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'textfield',
            'label' => 'LBL_TEXTFIELD',
          ),
        ),
      ),
      'lbl_editview_panel6' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED',
          ),
          1 => 
          array (
            'name' => 'date_entered',
            'comment' => 'Date record created',
            'label' => 'LBL_DATE_ENTERED',
          ),
        ),
      ),
      'lbl_editview_panel4' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'enllac',
            'label' => 'LBL_ENLLAC',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'parent_name',
            'studio' => 'visible',
            'label' => 'LBL_FLEX_RELATE',
          ),
          1 => 
          array (
            'name' => 'relacionat',
            'studio' => 'visible',
            'label' => 'LBL_RELACIONAT',
          ),
        ),
      ),
      'lbl_editview_panel5' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'imatge',
            'studio' => 'visible',
            'label' => 'LBL_IMATGE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'html',
            'studio' => 'visible',
            'label' => 'LBL_HTML',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'wysiwygfield',
            'label' => 'LBL_WYSIWYGFIELD',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'iframe',
            'label' => 'LBL_IFRAME',
          ),
        ),
      ),
    ),
  ),
);
;
?>
