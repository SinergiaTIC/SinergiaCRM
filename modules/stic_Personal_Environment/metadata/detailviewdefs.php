<?php
$module_name = 'stic_Personal_Environment';
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
                'LBL_DEFAULT_PANEL' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PANEL_RECORD_DETAILS' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => true,
        ),
        'panels' => array(
            'lbl_default_panel' => array(
                0 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => array(
                        'name' => 'stic_personal_environment_contacts_name',
                        'label' => 'LBL_STIC_PERSONAL_ENVIRONMENT_CONTACTS_FROM_CONTACTS_TITLE',
                    ),
                    1 => array(
                        'name' => 'relationship_type',
                        'studio' => 'visible',
                        'label' => 'LBL_RELATIONSHIP_TYPE',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'stic_personal_environment_contacts_1_name',
                        'label' => 'LBL_STIC_PERSONAL_ENVIRONMENT_CONTACTS_1_FROM_CONTACTS_TITLE',
                    ),
                    1 => array(
                        'name' => 'stic_personal_environment_accounts_name',
                        'label' => 'LBL_STIC_PERSONAL_ENVIRONMENT_ACCOUNTS_FROM_ACCOUNTS_TITLE',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'start_date',
                        'label' => 'LBL_START_DATE',
                    ),
                    1 => array(
                        'name' => 'end_date',
                        'label' => 'LBL_END_DATE',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'reference_contact',
                        'studio' => 'visible',
                        'label' => 'LBL_REFERENCE_CONTACT',
                    ),
                    1 => array(
                        'name' => 'coexistence_status',
                        'studio' => 'visible',
                        'label' => 'LBL_COEXISTENCE_STATUS',
                    ),
                ),
                5 => array(
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
                        'comment' => 'Date record last modified',
                        'label' => 'LBL_DATE_MODIFIED',
                        'customCode' => '{$fields.date_modified.value}',
                    ),
                ),
            ),
        ),
    ),
);

?>
