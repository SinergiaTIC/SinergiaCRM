<?php
// created: 2020-06-25 19:27:47
$subpanel_layout['list_fields'] = array(
    'object_image' => array(
        'width' => '2%',
        'vname' => 'LBL_OBJECT_IMAGE',
        'default' => true,
        'widget_class' => 'SubPanelIcon',
    ),
    'document_name' => array(
        'name' => 'document_name',
        'vname' => 'LBL_DOC_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'width' => '20%',
        'default' => true,
    ),
    'filename' => array(
        'name' => 'filename',
        'vname' => 'LBL_FILENAME',
        'width' => '20%',
        'module' => 'Documents',
        'sortable' => false,
        'displayParams' => array(
            'module' => 'Documents',
        ),
        'default' => true,
    ),
    'status_id' => array(
        'name' => 'status_id',
        'vname' => 'LBL_LIST_STATUS',
        'width' => '10%',
        'default' => true,
    ),
    'stic_shared_document_link_c' => array(
        'type' => 'url',
        'default' => true,
        'vname' => 'LBL_STIC_SHARED_DOCUMENT_LINK',
        'width' => '10%',
    ),
    'assigned_user_name' => array(
        'link' => true,
        'type' => 'relate',
        'vname' => 'LBL_ASSIGNED_TO_NAME',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '10%',
        'default' => true,
        'widget_class' => 'SubPanelDetailViewLink',
        'target_module' => 'Users',
        'target_record_key' => 'assigned_user_id',
    ),
    'edit_button' => array(
        'vname' => 'LBL_EDIT_BUTTON',
        'widget_class' => 'SubPanelEditButton',
        'module' => 'Documents',
        'width' => '5%',
        'default' => true,
    ),
    'remove_button' => array(
        'vname' => 'LBL_REMOVE',
        'widget_class' => 'SubPanelRemoveButton',
        'module' => 'Documents',
        'width' => '5%',
        'default' => true,
    ),
    'document_revision_id' => array(
        'name' => 'document_revision_id',
        'usage' => 'query_only',
    ),
);
