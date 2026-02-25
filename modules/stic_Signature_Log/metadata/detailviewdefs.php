<?php
$module_name = 'stic_Signature_Log';
$viewdefs[$module_name] =
array(
    'DetailView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    // 0 => 'EDIT',
                    // 1 => 'DUPLICATE',
                    // 2 => 'DELETE',
                    // 3 => 'FIND_DUPLICATES',
                ),
            ),
            'maxColumns' => '2',
            'widths' => array(
                0 => array(
                    'label' => '10',
                    'field' => '30',
                ),
                1 => array(
                    'label' => '10',
                    'field' => '30',
                ),
            ),
            'useTabs' => true,
            'tabDefs' => array(
                'LBL_DEFAULT_PANEL' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PANEL_RECORD_DETAILS' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => array(
            'lbl_default_panel' => array(
                0 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => array(
                        'name' => 'action',
                        'label' => 'LBL_ACTION',
                    ),
                    1 => array(
                        'name' => 'date',
                        'label' => 'LBL_DATE',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'stic_signatures_stic_signature_log_name',
                    ),
                    1 => array(
                        'name' => 'stic_signers_stic_signature_log_name',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'user_agent',
                        'label' => 'LBL_USER_AGENT',
                    ),
                    1 => array(
                        'name' => 'ip_address',
                        'label' => 'LBL_IP_ADDRESS',
                    ),
                ),
                4 => array(
                    0 => 'description',
                ),
            ),
            'lbl_panel_record_details' => array(
                0 => array(
                    0 => array(
                        'name' => 'created_by_name',
                        'label' => 'LBL_CREATED',
                    ),
                    1 => array(
                        'name' => 'date_entered',
                        'comment' => 'Date record created',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'modified_by_name',
                        'label' => 'LBL_MODIFIED_NAME',
                    ),
                    1 => array(
                        'name' => 'date_modified',
                        'comment' => 'Date record last modified',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),
            ),
        ),
    ),
);
