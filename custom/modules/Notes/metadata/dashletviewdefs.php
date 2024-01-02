<?php
$dashletData['NotesDashlet']['searchFields'] = array(
    'name' => array(
        'default' => '',
    ),
    'contact_name' => array(
        'default' => '',
    ),
    'file_mime_type' => array(
        'default' => '',
    ),
    'assigned_user_name' => array(
        'default' => '',
    ),
);
$dashletData['NotesDashlet']['columns'] = array(
    'name' => array(
        'width' => '40%',
        'label' => 'LBL_LIST_SUBJECT',
        'link' => true,
        'default' => true,
        'name' => 'name',
    ),
    'contact_name' => array(
        'width' => '20%',
        'label' => 'LBL_LIST_CONTACT',
        'link' => true,
        'id' => 'CONTACT_ID',
        'module' => 'Contacts',
        'default' => true,
        'ACLTag' => 'CONTACT',
        'related_fields' => array(
            0 => 'contact_id',
        ),
        'name' => 'contact_name',
    ),
    'parent_name' => array(
        'width' => '20%',
        'label' => 'LBL_LIST_RELATED_TO',
        'dynamic_module' => 'PARENT_TYPE',
        'id' => 'PARENT_ID',
        'link' => true,
        'default' => true,
        'sortable' => false,
        'ACLTag' => 'PARENT',
        'related_fields' => array(
            0 => 'parent_id',
            1 => 'parent_type',
        ),
        'name' => 'parent_name',
    ),
    'filename' => array(
        'width' => '20%',
        'label' => 'LBL_LIST_FILENAME',
        'default' => true,
        'type' => 'file',
        'related_fields' => array(
            0 => 'file_url',
            1 => 'id',
            2 => 'doc_id',
            3 => 'doc_type',
        ),
        'displayParams' => array(
            'module' => 'Notes',
        ),
        'name' => 'filename',
    ),
    'assigned_user_name' => array(
        'link' => true,
        'type' => 'relate',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '10%',
        'default' => true,
    ),
    'contact_phone' => array(
        'type' => 'phone',
        'label' => 'LBL_PHONE',
        'width' => '10%',
        'default' => false,
    ),
    'embed_flag' => array(
        'type' => 'bool',
        'default' => false,
        'label' => 'LBL_EMBED_FLAG',
        'width' => '10%',
    ),
    'portal_flag' => array(
        'type' => 'bool',
        'label' => 'LBL_PORTAL_FLAG',
        'width' => '10%',
        'default' => false,
    ),
    'file_url' => array(
        'type' => 'function',
        'label' => 'LBL_FILE_URL',
        'width' => '10%',
        'default' => false,
    ),
    'file_mime_type' => array(
        'type' => 'varchar',
        'label' => 'LBL_FILE_MIME_TYPE',
        'width' => '10%',
        'default' => false,
    ),
    'description' => array(
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => false,
    ),
    'created_by_name' => array(
        'type' => 'relate',
        'label' => 'LBL_CREATED_BY',
        'width' => '10%',
        'default' => false,
        'name' => 'created_by_name',
    ),
    'date_entered' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
        'name' => 'date_entered',
    ),
    'modified_by_name' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_MODIFIED_BY',
        'id' => 'MODIFIED_USER_ID',
        'width' => '10%',
        'default' => false,
    ),
    'date_modified' => array(
        'width' => '20%',
        'label' => 'LBL_DATE_MODIFIED',
        'link' => false,
        'default' => false,
        'name' => 'date_modified',
    ),
);
