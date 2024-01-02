<?php
$module_name = 'DHA_PlantillasDocumentos';
$_object_name = 'dha_plantillasdocumentos';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'form' => 
      array (
        'buttons' => array (
          0 => 'EDIT',
          //1 => 'DUPLICATE',  // no se permite el duplicado !!!
          1 => 'DELETE',
        ),              
      ),
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
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'document_name',
            'label' => 'LBL_DOC_NAME',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'uploadfile',
            //'type' => 'varchar',  // sobreescribimos aqui el tipo para poder rellenar el contenido de la variable en el bean de forma customizada (ANULADO DE MOMENTO, AUNQUE FUNCIONA)

            'displayParams' => 
            array (
              'link' => 'uploadfile',
              'id' => 'id',
            ),
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'modulo',
            'studio' => 'visible',
            'label' => 'LBL_MODULO',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'idioma',
            'studio' => 'visible',
            'label' => 'LBL_IDIOMA_PLANTILLA',
          ),
          1 => '',
        ),
        4 => 
        array (
          0 => 'status',
          1 => '',
        ),
        5 => 
        array (
          0 => 'category_id',
          1 => '',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DOC_DESCRIPTION',
          ),
          1 => '',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'aclroles',
          ),
          1 => '',
        ),        
        9 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'label' => 'LBL_DATE_ENTERED',            
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
          ),
          1 => '',
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'date_modified',
            'label' => 'LBL_DATE_MODIFIED',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
?>
