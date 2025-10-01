<?php
$module_name = 'stic_Signatures';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_STEP1_PANEL' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_STEP2_PANEL' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_STEP3_PANEL' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'lbl_step1_panel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'pdf_template',
            'studio' => 'visible',
            'label' => 'LBL_PDF_TEMPLATE',
          ),
        ),
      ),
      'lbl_step2_panel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'main_module',
            'studio' => 'visible',
            'label' => 'LBL_MAIN_MODULE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'signer_path',
            'label' => 'LBL_SIGNER_PATH',
          ),
        ),
      ),
      'lbl_step3_panel' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
          1 => 
          array (
            'name' => 'type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'signature_mode',
            'studio' => 'visible',
            'label' => 'LBL_SIGNATURE_MODE',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'auth_method',
            'studio' => 'visible',
            'label' => 'LBL_AUTH_METHOD',
          ),
          1 => 
          array (
            'name' => 'minimum_signatures',
            'label' => 'LBL_MINIMUM_SIGNATURES',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'generate_pdf',
            'studio' => 'visible',
            'label' => 'LBL_GENERATE_PDF',
          ),
          1 => 
          array (
            'name' => 'pdf_audit_page',
            'studio' => 'visible',
            'label' => 'LBL_PDF_AUDIT_PAGE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'activation_date',
            'label' => 'LBL_ACTIVATION_DATE',
          ),
          1 => 
          array (
            'name' => 'expiration_date',
            'label' => 'LBL_EXPIRATION_DATE',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'end_date',
            'label' => 'LBL_END_DATE',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'email_template',
            'studio' => 'visible',
            'label' => 'LBL_EMAIL_TEMPLATE',
          ),
          1 => 
          array (
            'name' => 'on_behalf_of',
            'studio' => 'visible',
            'label' => 'LBL_ON_BEHALF_OF',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'reminder_frequency',
            'label' => 'LBL_REMINDER_FREQUENCY',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'verification_code',
            'label' => 'LBL_VERIFICATION_CODE',
          ),
          1 => 
          array (
            'name' => 'pdf_document',
            'studio' => 'visible',
            'label' => 'LBL_PDF_DOCUMENT',
          ),
        ),
        10 => 
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
;
?>
