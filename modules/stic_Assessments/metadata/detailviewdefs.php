<?php
$module_name = 'stic_Assessments';
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
            'syncDetailEditViews' => true,
        ),
        'panels' => array(
            'lbl_default_panel' => array(
                0 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => array(
                        'name' => 'stic_assessments_contacts_name',
                        'label' => 'LBL_STIC_ASSESSMENTS_CONTACTS_FROM_CONTACTS_TITLE',
                    ),
                    1 => array(
                        'name' => 'working_with',
                        'studio' => 'visible',
                        'label' => 'LBL_WORKING_WITH',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                    1 => array(
                        'name' => 'derivation',
                        'studio' => 'visible',
                        'label' => 'LBL_DERIVATION',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'assessment_date',
                        'label' => 'LBL_ASSESSMENT_DATE',
                    ),
                    1 => array(
                        'name' => 'next_date',
                        'label' => 'LBL_NEXT_DATE',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'scope',
                        'studio' => 'visible',
                        'label' => 'LBL_SCOPE',
                    ),
                    1 => array(
                        'name' => 'moment',
                        'studio' => 'visible',
                        'label' => 'LBL_MOMENT',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'areas',
                        'studio' => 'visible',
                        'label' => 'LBL_AREAS',
                    ),
                    1 => array(
                        'name' => 'recommendations',
                        'studio' => 'visible',
                        'label' => 'LBL_RECOMMENDATIONS',
                    ),
                ),
                6 => array(
                    0 => array(
                        'name' => 'conclusions',
                        'studio' => 'visible',
                        'label' => 'LBL_CONCLUSIONS',
                    ),
                    1 => 'description',
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
                      'comment' => 'Date record created',
                      'label' => 'LBL_DATE_ENTERED',
                      'customCode' => '{$fields.date_entered.value}',
                  ),
              ),
              1 => array(
                  0 => array(
                      'name' => 'modified_by_name',
                      'label' => 'LBL_MODIFIED_NAME',
                  ),
                  1 => array(
                      'name' => 'date_modified',
                      'comment' => 'Date record last modified',
                      'label' => 'LBL_DATE_MODIFIED',
                      'customCode' => '{$fields.date_modified.value}',
                  ),
              ),
          ),
        ),
    ),
);

?>
