<?php
$module_name = 'stic_Payment_Commitments';
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
        ),
        'panels' => array(
            'lbl_default_panel' => array(
                0 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => array(
                        'name' => 'stic_payment_commitments_contacts_name',
                        'label' => 'LBL_STIC_PAYMENT_COMMITMENTS_CONTACTS_FROM_CONTACTS_TITLE',
                    ),
                    1 => array(
                        'name' => 'stic_payment_commitments_accounts_name',
                        'label' => 'LBL_STIC_PAYMENT_COMMITMENTS_ACCOUNTS_FROM_ACCOUNTS_TITLE',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'amount',
                        'label' => 'LBL_AMOUNT',
                    ),
                    1 => array(
                        'name' => 'periodicity',
                        'studio' => 'visible',
                        'label' => 'LBL_PERIODICITY',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'first_payment_date',
                        'label' => 'LBL_FIRST_PAYMENT_DATE',
                    ),
                    1 => array(
                        'name' => 'end_date',
                        'label' => 'LBL_END_DATE',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'active',
                        'label' => 'LBL_ACTIVE',
                    ),
                    1 => '',
                ),
                5 => array(
                    0 => array(
                        'name' => 'payment_method',
                        'studio' => 'visible',
                        'label' => 'LBL_PAYMENT_METHOD',
                    ),
                    1 => array(
                        'name' => 'payment_type',
                        'studio' => 'visible',
                        'label' => 'LBL_PAYMENT_TYPE',
                    ),
                ),
                6 => array(
                    0 => array(
                        'name' => 'bank_account',
                        'label' => 'LBL_BANK_ACCOUNT',
                    ),
                    1 => array(
                        'name' => 'banking_concept',
                        'label' => 'LBL_BANKING_CONCEPT',
                    ),
                ),
                7 => array(
                    0 => array(
                        'name' => 'mandate',
                        'label' => 'LBL_MANDATE',
                    ),
                    1 => array(
                        'name' => 'signature_date',
                        'label' => 'LBL_SIGNATURE_DATE',
                    ),
                ),
                8 => array(
                    0 => array(
                        'name' => 'stic_payment_commitments_campaigns_name',
                        'label' => 'LBL_STIC_PAYMENT_COMMITMENTS_CAMPAIGNS_FROM_CAMPAIGNS_TITLE',
                    ),
                    1 => array(
                        'name' => 'channel',
                        'studio' => 'visible',
                        'label' => 'LBL_CHANNEL',
                    ),
                ),
                9 => array(
                    0 => array(
                        'name' => 'segmentation',
                        'studio' => 'visible',
                        'label' => 'LBL_SEGMENTATION',
                    ),
                    1 => array(
                        'name' => 'annualized_fee',
                        'label' => 'LBL_ANNUALIZED_FEE',
                    ),
                ),
                10 => array(
                    0 => array(
                        'name' => 'destination',
                        'studio' => 'visible',
                        'label' => 'LBL_DESTINATION',
                    ),
                    1 => array(
                        'name' => 'stic_payment_commitments_project_name',
                        'label' => 'LBL_STIC_PAYMENT_COMMITMENTS_PROJECT_FROM_PROJECT_TITLE',
                    ),
                ),
                11 => array(
                    0 => array(
                        'name' => 'stic_payment_commitments_contacts_1_name',
                        'label' => 'LBL_STIC_PAYMENT_COMMITMENTS_CONTACTS_1_FROM_CONTACTS_TITLE',
                    ),
                    1 => array(
                        'name' => 'stic_payment_commitments_accounts_1_name',
                        'label' => 'LBL_STIC_PAYMENT_COMMITMENTS_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
                    ),
                ),
                12 => array(
                    0 => array(
                        'name' => 'in_kind_donation',
                        'label' => 'LBL_IN_KIND_DONATION',
                    ),
                    1 => array(
                        'name' => 'transaction_type',
                        'studio' => 'visible',
                        'label' => 'LBL_TRANSACTION_TYPE',
                    ),
                ),
                13 => array(
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
                        'customCode' => '{$fields.date_entered.value}',
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
                        'customCode' => '{$fields.date_modified.value}',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),
            ),
        ),
    ),
);

?>
