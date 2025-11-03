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
            'useTabs' => false,
            'tabDefs' => array(
                'LBL_EDITVIEW_PANEL1' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_EDITVIEW_PANEL2' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'DEFAULT' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => array(
            'lbl_step1_panel' => array(
                0 => array(
                    0 => array(
                        'name' => 'pdf_template',
                        'studio' => 'visible',
                        'label' => 'LBL_PDF_TEMPLATE',
                    ),
                ),
            ),
            'lbl_step2_panel' => array(
                0 => array(
                    0 => array(
                        'name' => 'main_module',
                        'studio' => 'visible',
                        'label' => 'LBL_MAIN_MODULE',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'signer_path',
                        'label' => 'LBL_SIGNER_PATH',
                    ),
                ),
            ),
            'lbl_step3_panel' => array(
                0 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => array(
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                    1 => array(
                        'name' => 'type',
                        'studio' => 'visible',
                        'label' => 'LBL_TYPE',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'signature_mode',
                        'studio' => 'visible',
                        'label' => 'LBL_SIGNATURE_MODE',
                    ),
                    1 => array(
                        'name' => 'auth_method',
                        'studio' => 'visible',
                        'label' => 'LBL_AUTH_METHOD',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'activation_date',
                        'label' => 'LBL_ACTIVATION_DATE',
                    ),
                    1 => array(
                        'name' => 'expiration_date',
                        'label' => 'LBL_EXPIRATION_DATE',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'pdf_audit_page',
                        'studio' => 'visible',
                        'label' => 'LBL_PDF_AUDIT_PAGE',
                    ),
                    1 => array(
                        'name' => 'on_behalf_of',
                        'studio' => 'visible',
                        'label' => 'LBL_ON_BEHALF_OF',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'email_template',
                        'studio' => 'visible',
                        'label' => 'LBL_EMAIL_TEMPLATE',
                    ),
                    1 => array(
                        'name' => 'email_template_send_document',
                        'studio' => 'visible',
                        'label' => 'LBL_EMAIL_TEMPLATE_SEND_DOCUMENT',
                    ),
                ),
                6 => array(
                    0 => array(
                        'name' => 'email_template_otp',
                        'studio' => 'visible',
                        'label' => 'LBL_EMAIL_TEMPLATE_OTP',
                    ),
                    1 => array(
                        'name' => 'email_template_otp_sms',
                        'studio' => 'visible',
                        'label' => 'LBL_EMAIL_TEMPLATE_OTP_SMS',
                    ),
                ),
                7 => array(
                    0 => array(
                        'name' => 'description',

                    ),

                ),
            ),
        ),
    ),
);
