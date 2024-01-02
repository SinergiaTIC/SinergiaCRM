<?php
// created: 2020-07-04 10:28:55
$searchdefs['Documents'] = array(
    'layout' => array(
        'basic_search' => array(
            0 => array(
                'type' => 'varchar',
                'label' => 'LBL_NAME',
                'width' => '10%',
                'default' => true,
                'name' => 'document_name',
            ),
            1 => array(
                'type' => 'file',
                'label' => 'LBL_FILENAME',
                'width' => '10%',
                'default' => true,
                'name' => 'filename',
            ),
            2 => array(
                'type' => 'enum',
                'label' => 'LBL_DOC_STATUS',
                'width' => '10%',
                'default' => true,
                'name' => 'status_id',
            ),
            3 => array(
                'type' => 'url',
                'default' => true,
                'label' => 'LBL_STIC_SHARED_DOCUMENT_LINK',
                'width' => '10%',
                'name' => 'stic_shared_document_link_c',
            ),
            4 => array(
                'name' => 'assigned_user_id',
                'label' => 'LBL_ASSIGNED_TO',
                'type' => 'enum',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(
                        0 => false,
                    ),
                ),
                'width' => '10%',
                'default' => true,
            ),
            5 => array(
                'name' => 'current_user_only',
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
            6 => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
        ),
        'advanced_search' => array(
            0 => array(
                'type' => 'varchar',
                'label' => 'LBL_NAME',
                'width' => '10%',
                'default' => true,
                'name' => 'document_name',
            ),
            1 => array(
                'type' => 'file',
                'label' => 'LBL_FILENAME',
                'width' => '10%',
                'default' => true,
                'name' => 'filename',
            ),
            2 => array(
                'type' => 'enum',
                'label' => 'LBL_DOC_STATUS',
                'width' => '10%',
                'default' => true,
                'name' => 'status_id',
            ),
            3 => array(
                'type' => 'url',
                'default' => true,
                'label' => 'LBL_STIC_SHARED_DOCUMENT_LINK',
                'width' => '10%',
                'name' => 'stic_shared_document_link_c',
            ),
            4 => array(
                'name' => 'assigned_user_id',
                'label' => 'LBL_ASSIGNED_TO',
                'type' => 'enum',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(
                        0 => false,
                    ),
                ),
                'width' => '10%',
                'default' => true,
            ),
            5 => array(
                'name' => 'active_date',
                'default' => true,
                'width' => '10%',
            ),
            6 => array(
                'name' => 'exp_date',
                'default' => true,
                'width' => '10%',
            ),
            7 => array(
                'type' => 'text',
                'label' => 'LBL_DESCRIPTION',
                'sortable' => false,
                'width' => '10%',
                'default' => true,
                'name' => 'description',
            ),
            8 => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_ENTERED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_entered',
            ),
            9 => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_modified',
            ),
            10 => array(
                'type' => 'assigned_user_name',
                'label' => 'LBL_CREATED',
                'width' => '10%',
                'default' => true,
                'name' => 'created_by',
            ),
            11 => array(
                'type' => 'assigned_user_name',
                'label' => 'LBL_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'modified_user_id',
            ),
            12 => array(
                'name' => 'current_user_only',
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
            13 => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
        ),
    ),
    'templateMeta' => array(
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => array(
            'label' => '10',
            'field' => '30',
        ),
    ),
);
