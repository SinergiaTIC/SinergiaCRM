<?php
// created: 2020-07-04 10:28:55
$viewdefs['Project']['DetailView'] = array (
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
    'includes' => 
    array (
      0 => 
      array (
        'file' => 'modules/Project/Project.js',
      ),
      1 => 
      array (
        'file' => 'modules/Project/js/custom_project.js',
      ),
    ),
    'form' => 
    array (
      'buttons' => 
      array (
        0 => 
        array (
          'customCode' => '<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="button" type="submit" name="Edit" id="edit_button" value="{$APP.LBL_EDIT_BUTTON_LABEL}"onclick="{if $IS_TEMPLATE}this.form.return_module.value=\'Project\'; this.form.return_action.value=\'ProjectTemplatesDetailView\'; this.form.return_id.value=\'{$id}\'; this.form.action.value=\'ProjectTemplatesEditView\';{else}this.form.return_module.value=\'Project\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$id}\'; this.form.action.value=\'EditView\'; {/if}"/>',
          'sugar_html' => 
          array (
            'type' => 'submit',
            'value' => ' {$APP.LBL_EDIT_BUTTON_LABEL} ',
            'htmlOptions' => 
            array (
              'id' => 'edit_button',
              'class' => 'button',
              'accessKey' => '{$APP.LBL_EDIT_BUTTON_KEY}',
              'name' => 'Edit',
              'onclick' => '{if $IS_TEMPLATE}this.form.return_module.value=\'Project\'; this.form.return_action.value=\'ProjectTemplatesDetailView\'; this.form.return_id.value=\'{$id}\'; this.form.action.value=\'ProjectTemplatesEditView\';{else}this.form.return_module.value=\'Project\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$id}\'; this.form.action.value=\'EditView\'; {/if}',
            ),
          ),
        ),
        1 => 
        array (
          'customCode' => '<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="button" type="submit" name="Duplicate" id="duplicate_button" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}"onclick="{if $IS_TEMPLATE}this.form.return_module.value=\'Project\'; this.form.return_action.value=\'ProjectTemplatesDetailView\'; this.form.isDuplicate.value=true; this.form.action.value=\'projecttemplateseditview\'; this.form.return_id.value=\'{$id}\';{else}this.form.return_module.value=\'Project\'; this.form.return_action.value=\'DetailView\'; this.form.isDuplicate.value=true; this.form.action.value=\'EditView\'; this.form.return_id.value=\'{$id}\';{/if}""/>',
          'sugar_html' => 
          array (
            'type' => 'submit',
            'value' => '{$APP.LBL_DUPLICATE_BUTTON_LABEL}',
            'htmlOptions' => 
            array (
              'title' => '{$APP.LBL_DUPLICATE_BUTTON_TITLE}',
              'accessKey' => '{$APP.LBL_DUPLICATE_BUTTON_KEY}',
              'class' => 'button',
              'name' => 'Duplicate',
              'id' => 'duplicate_button',
              'onclick' => '{if $IS_TEMPLATE}this.form.return_module.value=\'Project\'; this.form.return_action.value=\'ProjectTemplatesDetailView\'; this.form.isDuplicate.value=true; this.form.action.value=\'projecttemplateseditview\'; this.form.return_id.value=\'{$id}\';{else}this.form.return_module.value=\'Project\'; this.form.return_action.value=\'DetailView\'; this.form.isDuplicate.value=true; this.form.action.value=\'EditView\'; this.form.return_id.value=\'{$id}\';{/if}',
            ),
          ),
        ),
        2 => 
        array (
          'customCode' => '<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="button" type="button" name="Delete" id="delete_button" value="{$APP.LBL_DELETE_BUTTON_LABEL}" onclick="project_delete(this);"/>',
          'sugar_html' => 
          array (
            'type' => 'button',
            'id' => 'delete_button',
            'value' => '{$APP.LBL_DELETE_BUTTON_LABEL}',
            'htmlOptions' => 
            array (
              'title' => '{$APP.LBL_DELETE_BUTTON_TITLE}',
              'accessKey' => '{$APP.LBL_DELETE_BUTTON_KEY}',
              'id' => 'delete_button',
              'class' => 'button',
              'onclick' => 'project_delete(this);',
            ),
          ),
        ),
        3 => 
        array (
          'customCode' => '<input title="{$APP.LBL_VIEW_GANTT_TITLE}" class="button" type="button" name="view_gantt" id="view_gantt" value="{$APP.LBL_GANTT_BUTTON_LABEL}" onclick="javascript:window.location.href=\'index.php?module=Project&action=view_GanttChart&record={$id}\'"/>',
        ),
        4 => 
        array (
          'customCode' => '<input title="{$APP.LBL_VIEW_DETAIL}" class="button" type="button" name="view_detail" id="view_detail" value="{$APP.LBL_DETAIL_BUTTON_LABEL}" onclick="javascript:window.location.href=\'index.php?module=Project&action=DetailView&record={$id}\'"/>',
        ),
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
    'syncDetailEditViews' => true,
  ),
  'panels' => 
  array (
    'lbl_overview_panel' => 
    array (
      0 => 
      array (
        0 => 'name',
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
          'name' => 'estimated_start_date',
          'label' => 'LBL_DATE_START',
        ),
        1 => 
        array (
          'name' => 'estimated_end_date',
          'label' => 'LBL_DATE_END',
        ),
      ),
      2 => 
      array (
        0 => 'status',
        1 => 
        array (
          'name' => 'am_projecttemplates_project_1_name',
        ),
      ),
      3 => 
      array (
        0 => 'priority',
        1 => 'override_business_hours',
      ),
      4 => 
      array (
        0 => 
        array (
          'name' => 'stic_location_c',
          'studio' => 'visible',
          'label' => 'LBL_STIC_LOCATION',
        ),
        1 => '',
      ),
      5 => 
      array (
        0 => 
        array (
          'name' => 'description',
          'comment' => 'Project description',
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