<?php
$module_name = 'stic_Bookings';
$viewdefs[$module_name] =
array(
    'DetailView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    0 => 'EDIT',
                    1 => 'DUPLICATE',
                    2 => 'DELETE',
                    3 => 'FIND_DUPLICATES',
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
                'LBL_PANEL_STIC_BOOKINGS_INFORMATION' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_DETAILVIEW_PANEL1' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => array(
            'LBL_PANEL_STIC_BOOKINGS_INFORMATION' => array(
                0 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => array(
                        'name' => 'code',
                        'label' => 'LBL_CODE',
                    ),
                    1 => '',
                ),
                2 => array(
                    0 => array(
                        'name' => 'stic_bookings_contacts_name',
                    ),
                    1 => array(
                        'name' => 'stic_bookings_accounts_name',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'all_day',
                        'label' => 'LBL_ALL_DAY',
                    ),
                    1 => '',
                ),
                4 => array(
                    0 => array(
                        'name' => 'start_date',
                        'label' => 'LBL_START_DATE',
                    ),
                    1 => array(
                        'name' => 'end_date',
                        'label' => 'LBL_END_DATE',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'status',
                        'label' => 'LBL_STATUS',
                    ),
                    1 => array(
                        'name' => 'parent_name',
                        'studio' => 'visible',
                        'label' => 'LBL_FLEX_RELATE',
                    ),
                ),
                6 => array(
                    0 => 'description',
                ),
            ),
            'lbl_detailview_panel1' => array(
                0 => array(
                    0 => array(
                        'name' => 'created_by_name',
                        'label' => 'LBL_CREATED',
                    ),
                    1 => array(
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value}',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'modified_by_name',
                        'label' => 'LBL_MODIFIED_NAME',
                    ),
                    1 => array(
                        'name' => 'date_modified',
                        'customCode' => '{$fields.date_modified.value}',
                    ),
                ),
            ),
        ),
    ),
);
