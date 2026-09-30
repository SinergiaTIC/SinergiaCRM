<?php
$module_name = 'stic_Portal_Login_Attempts';
$viewdefs[$module_name] =
array(
    'DetailView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    // 0 => 'EDIT',
                    // 1 => 'DUPLICATE',
                    // 2 => 'DELETE',
                ),
            ),
            'maxColumns' => '2',
            'widths' => array(
                array('label' => '10', 'field' => '30'),
                array('label' => '10', 'field' => '30'),
            ),
            'useTabs' => true,
            'tabDefs' => array(
                'LBL_DEFAULT_PANEL' => array('newTab' => true, 'panelDefault' => 'expanded'),
                'LBL_PANEL_RECORD_DETAILS' => array('newTab' => true, 'panelDefault' => 'expanded'),
            ),
        ),
        'panels' => array(
            'lbl_default_panel' => array(
                0 => array(
                    0 => array(
                        'name' => 'ip_address',
                        'label' => 'LBL_IP_ADDRESS',
                    ),
                    1 => array(
                        'name' => 'name',
                        'label' => 'LBL_NAME',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'failed_attempts',
                        'label' => 'LBL_FAILED_ATTEMPTS',
                    ),
                    1 => array(
                        'name' => 'locked_until',
                        'label' => 'LBL_LOCKED_UNTIL',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'last_attempt',
                        'label' => 'LBL_LAST_ATTEMPT',
                    ),
                    1 => array(
                        'name' => 'description',
                        'label' => 'LBL_DESCRIPTION',
                    ),
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
            ),        ),
    ),
);
