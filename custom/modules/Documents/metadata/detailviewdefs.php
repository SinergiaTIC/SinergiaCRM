<?php
// created: 2020-07-04 10:28:55
$viewdefs['Documents']['DetailView'] = array (
  'templateMeta' => 
  array (
    'maxColumns' => '2',
    'form' => 
    array (
      'buttons' => 
      array (
        0 => 'EDIT',
        1 => 'DUPLICATE',
        2 => 'DELETE',
      ),
      'headerTpl' => 'modules/Documents/tpls/detailHeader.tpl',
    ),
    0 => 
    array (
      'hidden' => 
      array (
        0 => '<input type="hidden" name="old_id" value="{$fields.document_revision_id.value}">',
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
    'useTabs' => true,
    'tabDefs' => 
    array (
      'LBL_OVERVIEW_PANEL' => 
      array (
        'newTab' => true,
        'panelDefault' => 'expanded',
      ),
      'LBL_STIC_PANEL_RECORD_DETAILS' => 
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
        0 => 
        array (
          'name' => 'document_name',
          'label' => 'LBL_DOC_NAME',
        ),
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
            'link' => 'filename',
            'id' => 'document_revision_id',
          ),
        ),
        1 => 
        array (
          'name' => 'status_id',
          'label' => 'LBL_DOC_STATUS',
        ),
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
        0 => 'active_date',
        1 => 'exp_date',
      ),
      4 => 
      array (
        0 => 
        array (
          'name' => 'description',
          'comment' => 'Full text of the note',
          'label' => 'LBL_DESCRIPTION',
        ),
      ),
    ),
    'lbl_stic_panel_record_details' => 
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
          'customCode' => '{$fields.date_entered.value}',
          'label' => 'LBL_DATE_ENTERED',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'modified_by_name',
          'label' => 'LBL_MODIFIED_NAME',
        ),
        1 => 
        array (
          'name' => 'date_modified',
          'customCode' => '{$fields.date_modified.value}',
          'label' => 'LBL_DATE_MODIFIED',
        ),
      ),
    ),
  ),
);
