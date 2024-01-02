<?php
// created: 2020-07-04 10:28:55
$viewdefs['Documents']['QuickCreate'] = array (
  'templateMeta' => 
  array (
    'form' => 
    array (
      'enctype' => 'multipart/form-data',
      'hidden' => 
      array (
        0 => '<input type="hidden" name="old_id" value="{$fields.document_revision_id.value}">',
        1 => '<input type="hidden" name="parent_id" value="{$smarty.request.parent_id}">',
        2 => '<input type="hidden" name="parent_type" value="{$smarty.request.parent_type}">',
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
    'includes' => 
    array (
      0 => 
      array (
        'file' => 'include/javascript/popup_parent_helper.js',
      ),
      1 => 
      array (
        'file' => 'cache/include/javascript/sugar_grp_jsolait.js',
      ),
      2 => 
      array (
        'file' => 'modules/Documents/documents.js',
      ),
    ),
    'useTabs' => true,
    'tabDefs' => 
    array (
      'LBL_OVERVIEW_PANEL' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
    ),
  ),
  'panels' => 
  array (
    'lbl_overview_panel' => 
    array (
      0 => 
      array (
        0 => 'document_name',
        1 => 
        array (
          'name' => 'assigned_user_name',
          'label' => 'LBL_ASSIGNED_TO_NAME',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'filename',
          'displayParams' => 
          array (
            'onchangeSetFileNameTo' => 'document_name',
          ),
        ),
        1 => 'status_id',
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'stic_shared_document_link_c',
          'label' => 'LBL_STIC_SHARED_DOCUMENT_LINK',
        ),
        1 => '',
      ),
      3 => 
      array (
        0 => 
        array (
          'name' => 'active_date',
          'displayParams' => 
          array (
            'required' => true,
          ),
        ),
        1 => 
        array (
          'name' => 'exp_date',
          'label' => 'LBL_DOC_EXP_DATE',
        ),
      ),
      4 => 
      array (
        0 => 
        array (
          'name' => 'description',
          'displayParams' => 
          array (
            'rows' => 2,
            'cols' => 120,
          ),
        ),
      ),
    ),
  ),
);
