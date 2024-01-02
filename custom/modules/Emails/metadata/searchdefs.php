<?php
$module_name = 'Emails';
$searchdefs[$module_name] =
array(
    'layout' => array(
        'basic_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'from_addr_name' => array(
                'name' => 'from_addr_name',
                'label' => 'LBL_FROM',
                'width' => '10%',
                'default' => true,
            ),
            'date_sent_received' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_SENT_RECEIVED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_sent_received',
            ),
            'category_id' => array(
                'name' => 'category_id',
                'default' => true,
                'width' => '10%',
            ),
            'assigned_user_id' => array(
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
            'current_user_only' => array(
                'name' => 'current_user_only',
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
            'favorites_only' => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
            ),
        ),
        'advanced_search' => array(
            'name' => array(
                'name' => 'name',
                'label' => 'LBL_SUBJECT',
                'default' => true,
                'width' => '10%',
            ),
            'from_addr_name' => array(
                'name' => 'from_addr_name',
                'label' => 'LBL_FROM',
                'default' => true,
                'width' => '10%',
            ),
            'date_sent_received' => array(
                'type' => 'datetime',
                'label' => 'LBL_DATE_SENT_RECEIVED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_sent_received',
            ),
            'category_id' => array(
                'name' => 'category_id',
                'default' => true,
                'width' => '10%',
            ),
            'assigned_user_id' => array(
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
            'imap_keywords' => array(
                'name' => 'imap_keywords',
                'label' => 'LBL_IMAP_KEYWORDS',
                'default' => true,
                'width' => '10%',
            ),
            'to_addrs_names' => array(
                'name' => 'to_addrs_names',
                'label' => 'LBL_TO',
                'default' => true,
                'width' => '10%',
            ),
            'description' => array(
                'name' => 'description',
                'label' => 'LBL_BODY',
                'default' => true,
                'width' => '10%',
            ),
            'parent_name' => array(
                'name' => 'parent_name',
                'default' => true,
                'width' => '10%',
            ),
            'status' => array(
                'type' => 'enum',
                'label' => 'LBL_STATUS',
                'width' => '10%',
                'default' => true,
                'name' => 'status',
            ),
            'has_attachment' => array(
                'type' => 'function',
                'studio' => 'visible',
                'label' => 'LBL_HAS_ATTACHMENT_INDICATOR',
                'width' => '10%',
                'default' => true,
                'name' => 'has_attachment',
            ),
            'emails_email_templates_name' => array(
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_EMAIL_TEMPLATE',
                'id' => 'EMAILS_EMAIL_TEMPLATES_IDB',
                'width' => '10%',
                'default' => true,
                'name' => 'emails_email_templates_name',
            ),
            'current_user_only' => array(
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
                'name' => 'current_user_only',
            ),
            'favorites_only' => array(
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

?>
