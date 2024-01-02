<?php

$viewdefs['base']['view']['massgeneratedocument'] = array(
    'buttons' => array(
          array (
            'type' => 'button',
            'value' => 'cancel',
            'css_class' => 'btn-link btn-invisible cancel_button',
            'label' => 'LBL_CANCEL_BUTTON_LABEL',
            'primary' => false,
          ),    
          array (
            'name' => 'generatedocument_button',
            'type' => 'button',
            'label' => 'LBL_GENERAR_DOCUMENTO',
            'acl_action' => 'view',
            'css_class' => 'btn-primary',
            'primary' => true,
          ),
          array (
            'name' => 'generatedocumentpdf_button',
            'type' => 'button',
            'label' => 'LBL_GENERAR_DOCUMENTO_PDF',
            'acl_action' => 'view',
            'css_class' => 'btn-primary',
            'primary' => true,
          ),
    ),
);
