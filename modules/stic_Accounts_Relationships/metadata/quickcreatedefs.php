<?php
$module_name = 'stic_Accounts_Relationships';
$viewdefs[$module_name] =
array(
    'QuickCreate' => array(
        'templateMeta' => array(
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
                        'name' => 'stic_accounts_relationships_accounts_name',
                        'label' => 'LBL_STIC_ACCOUNTS_RELATIONSHIPS_ACCOUNTS_FROM_ACCOUNTS_TITLE',
                    ),
                    1 => array(
                        'name' => 'relationship_type',
                        'studio' => 'visible',
                        'label' => 'LBL_RELATIONSHIP_TYPE',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'start_date',
                        'label' => 'LBL_START_DATE',
                    ),
                    1 => array(
                        'name' => 'end_date',
                        'label' => 'LBL_END_DATE',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'stic_accounts_relationships_project_name',
                        'label' => 'LBL_STIC_ACCOUNTS_RELATIONSHIPS_PROJECT_FROM_PROJECT_TITLE',
                    ),
                    1 => array(
                        'name' => 'role',
                        'studio' => 'visible',
                        'label' => 'LBL_ROLE',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'end_reason',
                        'studio' => 'visible',
                        'label' => 'LBL_END_REASON',
                    ),
                    1 => array(
                        'name' => 'other_end_reasons',
                        'label' => 'LBL_OTHER_END_REASONS',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'description',
                        'comment' => 'Full text of the note',
                        'label' => 'LBL_DESCRIPTION',
                    ),
                ),
            ),
        ),
    ),
);
