<?php
$viewdefs['Notes'] =
array(
    'DetailView' => array(
        'templateMeta' => array(
            // STIC-Custom 20220325 MHP - Add default actions (EDIT, DUPLICATE and DELETE)
            // STIC#640            
            'form' => array(
                'buttons' => array(
                    0 => 'EDIT',
                    1 => 'DUPLICATE',
                    2 => 'DELETE',
                ),
            ),          
            // END-CUSTOM     
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
                'LBL_NOTE_INFORMATION' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_STIC_PANEL_RECORD_DETAILS' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' => array(
            'lbl_note_information' => array(
                0 => array(
                    0 => array(
                        'name' => 'name',
                        'label' => 'LBL_SUBJECT',
                    ),
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => array(
                        'name' => 'parent_name',
                        'customLabel' => '{sugar_translate label=\'LBL_MODULE_NAME\' module=$fields.parent_type.value}',
                    ),
                    1 => 'contact_name',
                ),
                2 => array(
                    0 => array(
                        'name' => 'filename',
                    ),
                    1 => '',
                ),
                3 => array(
                    0 => array(
                        'name' => 'description',
                        'label' => 'LBL_NOTE_STATUS',
                    ),
                ),
            ),
            'LBL_STIC_PANEL_RECORD_DETAILS' => array(
                0 => array(
                    0 => array(
                        'name' => 'created_by_name',
                        'label' => 'LBL_CREATED_BY',
                    ),
                    1 => array(
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value}',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'modified_by_name',
                        'label' => 'LBL_MODIFIED_BY',
                    ),
                    1 => array(
                        'name' => 'date_modified',
                        'label' => 'LBL_DATE_MODIFIED',
                        'customCode' => '{$fields.date_modified.value}',
                    ),
                ),
            ),
        ),
    ),
);
