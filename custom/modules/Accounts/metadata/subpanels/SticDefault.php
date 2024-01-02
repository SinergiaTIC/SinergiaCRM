<?php
// created: 2020-01-14 17:06:30
$subpanel_layout['list_fields'] = array(
    'name' => array(
        'vname' => 'LBL_LIST_ACCOUNT_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'width' => '25%',
        'default' => true,
    ),
    'stic_acronym_c' => array(
        'type' => 'varchar',
        'default' => true,
        'vname' => 'LBL_STIC_ACRONYM',
        'width' => '10%',
    ),
    'stic_relationship_type_c' => array(
        'type' => 'multienum',
        'default' => true,
        'studio' => 'visible',
        'vname' => 'LBL_STIC_RELATIONSHIP_TYPE',
        'width' => '10%',
    ),
    'stic_category_c' => array(
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'vname' => 'LBL_STIC_CATEGORY',
        'width' => '10%',
    ),
    'stic_subcategory_c' => array(
        'type' => 'dynamicenum',
        'default' => true,
        'studio' => 'visible',
        'vname' => 'LBL_STIC_SUBCATEGORY',
        'width' => '10%',
    ),
    'phone_office' => array(
        'vname' => 'LBL_LIST_PHONE',
        'width' => '10%',
        'default' => true,
    ),
    'email1' => array(
        'width' => '15%',
        'vname' => 'LBL_LIST_EMAIL',
        'widget_class' => 'SubPanelEmailLink',
        'sortable' => true,
        'default' => true,
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '10%',
        'vname' => 'LBL_LIST_ASSIGNED_USER',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true,
    ),
    'edit_button' => array(
        'vname' => 'LBL_EDIT_BUTTON',
        'widget_class' => 'SubPanelEditButton',
        'width' => '4%',
        'default' => true,
    ),
    'remove_button' => array(
        'vname' => 'LBL_REMOVE',
        'widget_class' => 'SubPanelRemoveButtonAccount',
        'width' => '4%',
        'default' => true,
    ),
);
