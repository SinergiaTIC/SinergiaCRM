<?php
$module_name = 'stic_Validation_Results';
$listViewDefs[$module_name] =
array(
    'NAME' => array(
        'width' => '5%',
        'default' => true,
        'link' => true,
        'sortable' => false,
        'customCode' => '<a style="text-decoration:none" href="index.php?action=DetailView&module=stic_Validation_Results&record={$ID}" ><span class="suitepicon suitepicon-action-view-record"></span></a>',
    ),
    'EXECUTION_DATE' => array(
        'type' => 'datetimecombo',
        'label' => 'LBL_EXECUTION_DATE',
        'width' => '10%',
        'default' => true,
    ),
    'VALIDATION_ACTION' => array(
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_VALIDATION_ACTION',
        'id' => 'STIC_VALIDATION_ACTIONS_ID_C',
        'link' => true,
        'width' => '10%',
        'default' => true,
    ),
    'PARENT_NAME' => array(
        'type' => 'parent',
        'studio' => 'visible',
        'label' => 'LBL_FLEX_RELATE',
        'link' => true,
        'sortable' => false,
        'ACLTag' => 'PARENT',
        'dynamic_module' => 'PARENT_TYPE',
        'id' => 'PARENT_ID',
        'related_fields' => array(
            0 => 'parent_id',
            1 => 'parent_type',
        ),
        'width' => '10%',
        'default' => true,
    ),
    'LOG' => array(
        'type' => 'html',
        'studio' => 'visible',
        'label' => 'LBL_LOG',
        'sortable' => true,
        'width' => '60%',
        'default' => true,
    ),
    'REVIEWED' => array(
        'type' => 'enum',
        'default' => true,
        'label' => 'LBL_REVIEWED',
        'width' => '5%',
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '10%',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true,
    ),
    'DESCRIPTION' => array(
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => false,
    ),
    'SCHEDULER' => 
    array (
      'type' => 'relate',
      'studio' => 
      array (
        'editview' => false,
        'quickcreate' => false,
      ),
      'label' => 'LBL_SCHEDULER',
      'id' => 'SCHEDULERS_ID_C',
      'link' => true,
      'width' => '10%',
      'default' => false,
    ),
    'MODIFIED_BY_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_MODIFIED_NAME',
        'id' => 'MODIFIED_USER_ID',
        'width' => '10%',
        'default' => false,
    ),
    
    'DATE_MODIFIED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => false,
    ),
    'CREATED_BY_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CREATED',
        'id' => 'CREATED_BY',
        'width' => '10%',
        'default' => false,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
    ),
);
