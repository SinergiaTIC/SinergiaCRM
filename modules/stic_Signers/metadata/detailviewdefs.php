<?php
global $app_strings ;
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
                    0 => '',
                    1 => array(
                        'name' => 'parent_name',
                        'studio' => 'visible',
                        'label' => 'LBL_FLEX_RELATE',
                    ),
                ),
                1 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                2 => array(
                    0 => 'description',
                    1 => array(
                        'name' => 'stic_signatures_stic_signers_name',
                    ),

                ),
                3 => array(
                    
                    0 => array(
                        'name' => 'preview',
                        'customCode' => "<div id='preview-container'><button class='button' type='button' href='#' onclick='previewSignature();'><i class='glyphicon glyphicon-eye-open'></i> {$app_strings['LBL_SHOW']}</button></div>",
                        'label' => 'LBL_SIGNER_PREVIEW',
                    ),
                ),
            ),
        ),
    ),
);
