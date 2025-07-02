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
      'useTabs' => true,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'default' => 
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
            'name' => 'module',
            'studio' => 'visible',
            'label' => 'LBL_MODULE',
          ),
          1 => 
          array (
            'name' => 'signature_type',
            'studio' => 'visible',
            'label' => 'LBL_SIGNATURE_TYPE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
          1 => 
          array (
            'name' => 'is_representative_signature',
            'studio' => 'visible',
            'label' => 'LBL_IS_REPRESENTATIVE_SIGNATURE',
          ),
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
            'name' => 'start_datetime',
            'label' => 'LBL_START_DATETIME',
          ),
          1 => 
          array (
            'name' => 'end_datetime',
            'label' => 'LBL_END_DATETIME',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'completed_datetime',
            'label' => 'LBL_COMPLETED_DATETIME',
          ),
          1 => 
          array (
            'name' => 'email_template',
            'studio' => 'visible',
            'label' => 'LBL_EMAIL_TEMPLATE',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'recipient_path',
            'label' => 'LBL_RECIPIENT_PATH',
          ),
          1 => 
          array (
            'name' => 'signature_reminder_frequency',
            'label' => 'LBL_SIGNATURE_REMINDER_FREQUENCY',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'signature_hash',
            'label' => 'LBL_SIGNATURE_HASH',
          ),
          1 => 
          array (
            'name' => 'pdf_document',
            'studio' => 'visible',
            'label' => 'LBL_PDF_DOCUMENT',
          ),
        ),
        9 => 
        array (
          0 => 'description',
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'header_html',
            'label' => 'LBL_HEADER_HTML',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'main_html',
            'label' => 'LBL_MAIN_HTML',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'footer_html',
            'label' => 'LBL_FOOTER_HTML',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'page_size',
            'studio' => 'visible',
            'label' => 'LBL_PAGE_SIZE',
          ),
          1 => 
          array (
            'name' => 'orientation',
            'studio' => 'visible',
            'label' => 'LBL_ORIENTATION',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'margin_left',
            'label' => 'LBL_MARGIN_LEFT',
          ),
          1 => 
          array (
            'name' => 'margin_right',
            'label' => 'LBL_MARGIN_RIGHT',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'margin_top',
            'label' => 'LBL_MARGIN_TOP',
          ),
          1 => 
          array (
            'name' => 'margin_bottom',
            'label' => 'LBL_MARGIN_BOTTOM',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'margin_header',
            'label' => 'LBL_MARGIN_HEADER',
          ),
          1 => 
          array (
            'name' => 'margin_footer',
            'label' => 'LBL_MARGIN_FOOTER',
          ),
        ),
      ),
    ),
  ),
);
;
?>
