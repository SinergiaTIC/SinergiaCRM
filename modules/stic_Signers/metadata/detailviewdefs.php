<?php
$module_name = 'stic_Signers';
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
            'useTabs' => false,
            'tabDefs' => array(
                'DEFAULT' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => true,
        ),
        'panels' => array(
            'default' => array(
                0 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => array(
                        'name' => 'signature_date',
                        'label' => 'LBL_SIGNATURE_DATE',
                    ),
                    1 => array(
                        'name' => 'signature_image',
                        'studio' => 'visible',
                        'customCode' => '<img src="{$fields.signature_image.value}" alt="Signature Image" style="max-width:250px; max-height:100px;">',
                        'label' => 'LBL_SIGNATURE_IMAGE',
                    ),
                ),
                2 => array(
                    0 => '',
                    1 => array(
                        'name' => 'parent_name',
                        'studio' => 'visible',
                        'label' => 'LBL_FLEX_RELATE',
                    ),
                ),
                3 => array(
                    0 => 'description',
                    1 => array(
                        'name' => 'stic_signatures_stic_signers_name',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'preview',
                        'customCode' => '<div id=\'preview-container\'><button class=\'button\' type=\'button\' href=\'#\' onclick=\'previewSignature();\'><i class=\'glyphicon glyphicon-eye-open\'></i> Mostrar</button></div>',
                        'label' => 'LBL_SIGNER_PREVIEW',
                    ),
                ),
            ),
        ),
    ),
);
