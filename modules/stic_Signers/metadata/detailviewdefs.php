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
                        'name' => 'parent_name',
                        'studio' => 'visible',
                        'label' => 'LBL_FLEX_RELATE',
                    ),
                    1 => array(
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'signature_image',
                        'studio' => 'visible',
                        'customCode' => '{if $fields.signature_image.value != \'\'}<img src="{$fields.signature_image.value}" alt="Signature Image" style="max-width:250px; max-height:100px;">{/if}',
                        'label' => 'LBL_SIGNATURE_IMAGE',
                    ),
                    1 => array(
                        'name' => 'signature_date',
                        'label' => 'LBL_SIGNATURE_DATE',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'phone',
                        'label' => 'LBL_PHONE',
                    ),
                    1 => array(
                        'name' => 'email_address',
                        'label' => 'LBL_EMAIL_ADDRESS',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'pdf_document',
                        'customCode' => '{if $fields.pdf_document.value != \'\'}<a href="index.php?entryPoint=sticDownloadSignedPdf&signerId={$fields.id.value}" >Descargar documento firmado</a>{else}<span>No hay documento firmado</span>{/if}',
                        'studio' => 'visible',
                        'label' => 'LBL_PDF_DOCUMENT',
                    ),
                    1 => array(
                        'name' => 'on_behalf_of_id',
                        'studio' => 'visible',
                        'label' => 'LBL_ON_BEHALF_OF_ID',
                    ),
                ),
                5 => array(
                    0 => 'description',
                    1 => array(
                        'name' => 'stic_signatures_stic_signers_name',
                    ),
                ),
            ),
        ),
    ),
);
