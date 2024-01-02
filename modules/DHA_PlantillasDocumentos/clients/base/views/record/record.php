<?php

$fields = array(
   array (
      'name' => 'uploadfile',
      'displayParams' => array (
         'onchangeSetFileNameTo' => 'document_name',
      ),
   ),
   //'document_name',
   array(  // vacío
      'name' => '',
      'view' => 'detail',
      'readonly' => true,
   ),   
   'modulo',
   'idioma'
);

$fieldsHidden = array(
   'status_id',
   'category_id',
   'description',
   'assigned_user_name',
   array (
      'name' => 'aclroles',
      'customLabel' => '<span>{sugar_translate label=\'LBL_ROLES_WITH_ACCESS\' module=$fields.parent_type.value}<br>(<i>{sugar_translate label=\'LBL_ROLES_WITH_ACCESS_HELP\' module=$fields.parent_type.value}</i>) </span>',
      // 'displayParams' => array (
        // 'size' => ($count_roles > 20)? 20 : ($count_roles < 5)? 5 : $count_roles,
      // ),
   ),   
   array(  // vacío
      'name' => '',
      'view' => 'detail',
      'readonly' => true,
   ),   
          
   array(
      'name' => 'date_entered_by',
      'readonly' => true,
      'type' => 'fieldset',
      'label' => 'LBL_DATE_ENTERED',
      'fields' => array(
         array(
            'name' => 'date_entered',
         ),
         array(
            'type' => 'label',
            'default_value' => 'LBL_BY'
         ),
         array(
            'name' => 'created_by_name',
         ),
      ),
   ),
   array(
      'name' => 'date_modified_by',
      'readonly' => true,
      'type' => 'fieldset',
      'label' => 'LBL_DATE_MODIFIED',
      'fields' => array(
         array(
            'name' => 'date_modified',
         ),
         array(
            'type' => 'label',
            'default_value' => 'LBL_BY',
         ),
         array(
            'name' => 'modified_by_name',
         ),
      ),
   ),
);


$moduleName = 'DHA_PlantillasDocumentos';
$viewdefs[$moduleName]['base']['view']['record'] = array(
   'panels' => array(
      array(
         'name' => 'panel_header',
         'header' => true,
         'fields' => array(
            array(
               'name'          => 'picture',
               'type'          => 'avatar',
               'size'          => 'large',
               'dismiss_label' => true,
               'readonly'      => true,
            ),
            array(
               'name' => 'document_name',
            ),
            array(
               'name' => 'favorite',
               'label' => 'LBL_FAVORITE',
               'type' => 'favorite',
               'dismiss_label' => true,
            ),
            array(
               'name' => 'follow',
               'label'=> 'LBL_FOLLOW',
               'type' => 'follow',
               'readonly' => true,
               'dismiss_label' => true,
            ),
         ),
      ),
      array(
         'name' => 'panel_body',
         'label' => 'LBL_RECORD_BODY',
         'columns' => 2,
         'labels' => true,
         'labelsOnTop' => true,
         'placeholders' => false,
         'fields' => $fields,
      ),
      array(
         'name' => 'panel_hidden',
         'label' => 'LBL_RECORD_SHOWMORE',
         'hide' => true,
         'labelsOnTop' => true,
         'placeholders' => false,
         'columns' => 2,
         'fields' => $fieldsHidden,
      ),
   ),
);
