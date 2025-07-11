<?php
$module_name = 'stic_Signatures';
$viewdefs[$module_name] =
array(
    'EditView' => array(
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
                'DEFAULT' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_EDITVIEW_PANEL2' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_EDITVIEW_PANEL1' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),

        ),
        'panels' => array(
            'default' => array(

                1 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                2 => array(
                    0 => array(
                        'name' => 'type',
                        'studio' => 'visible',
                        'label' => 'LBL_TYPE',
                    ),
                    1 => array(
                        'name' => 'main_module',
                        'studio' => 'visible',
                        'label' => 'LBL_MAIN_MODULE',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                    1 => array(
                        'name' => 'on_behalf_of',
                        'studio' => 'visible',
                        'label' => 'LBL_ON_BEHALF_OF',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'auth_method',
                        'studio' => 'visible',
                        'label' => 'LBL_AUTH_METHOD',
                    ),
                    1 => array(
                        'name' => 'minimum_signatures',
                        'label' => 'LBL_MINIMUM_SIGNATURES',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'generate_pdf',
                        'studio' => 'visible',
                        'label' => 'LBL_GENERATE_PDF',
                    ),
                    1 => array(
                        'name' => 'pdf_audit_page',
                        'studio' => 'visible',
                        'label' => 'LBL_PDF_AUDIT_PAGE',
                    ),
                ),
                6 => array(
                    0 => array(
                        'name' => 'activation_date',
                        'label' => 'LBL_ACTIVATION_DATE',
                    ),
                    1 => array(
                        'name' => 'expiration_date',
                        'label' => 'LBL_EXPIRATION_DATE',
                    ),
                ),
                7 => array(
                    0 => array(
                        'name' => 'end_date',
                        'label' => 'LBL_END_DATE',
                    ),
                    1 => array(

                    ),

                ),
                8 => array(
                    0 => array(
                        'name' => 'email_template',
                        'studio' => 'visible',
                        'label' => 'LBL_EMAIL_TEMPLATE',
                    ),
                    1 => array(
                        'name' => 'pdf_template',
                        'studio' => 'visible',
                        'label' => 'LBL_PDF_TEMPLATE',
                    ),
                ),
                9 => array(
                    0 => array(
                        'name' => 'signer_path',
                        'label' => 'LBL_SIGNER_PATH',
                    ),
                    1 => array(
                        'name' => 'reminder_frequency',
                        'label' => 'LBL_REMINDER_FREQUENCY',
                    ),
                ),
                10 => array(
                    0 => array(
                        'name' => 'verification_code',
                        'label' => 'LBL_VERIFICATION_CODE',
                    ),
                    1 => array(
                        'name' => 'pdf_document',
                        'studio' => 'visible',
                        'label' => 'LBL_PDF_DOCUMENT',
                    ),
                ),
                11 => array(
                    0 => 'description',
                ),
            ),

        ),
    ),
);
