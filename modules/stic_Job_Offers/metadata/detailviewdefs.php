<?php
$module_name = 'stic_Job_Offers';
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
        'LBL_DEFAULT_PANEL' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL5' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL3' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL7' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_DETAILVIEW_PANEL6' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_RECORD_DETAILS' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'LBL_DEFAULT_PANEL' => 
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
            'name' => 'stic_job_offers_accounts_name',
          ),
          1 => 
          array (
            'name' => 'interlocutor',
            'studio' => 'visible',
            'label' => 'LBL_INTERLOCUTOR',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
          1 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'offer_origin',
            'studio' => 'visible',
            'label' => 'LBL_OFFER_ORIGIN',
          ),
          1 => 
          array (
            'name' => 'offered_positions',
            'label' => 'LBL_OFFERED_POSITIONS',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'process_start_date',
            'label' => 'LBL_PROCESS_START_DATE',
          ),
          1 => 
          array (
            'name' => 'process_end_date',
            'label' => 'LBL_PROCESS_END_DATE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'applications_start_date',
            'label' => 'LBL_APPLICATIONS_START_DATE',
          ),
          1 => 
          array (
            'name' => 'applications_end_date',
            'label' => 'LBL_APPLICATIONS_END_DATE',
          ),
        ),
        6 => 
        array (
          0 => 'description',
          1 => 
          array (
            'name' => 'offer_code',
            'label' => 'LBL_OFFER_CODE',
          ),
        ),
      ),
      'lbl_editview_panel5' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'professional_profile',
            'studio' => 'visible',
            'label' => 'LBL_PROFESSIONAL_PROFILE',
          ),
          1 => 
          array (
            'name' => 'job_requirements',
            'studio' => 'visible',
            'label' => 'LBL_JOB_REQUIREMENTS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'hours_per_week',
            'label' => 'LBL_HOURS_PER_WEEK',
          ),
          1 => 
          array (
            'name' => 'contract_description',
            'studio' => 'visible',
            'label' => 'LBL_CONTRACT_DESCRIPTION',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'retribution',
            'label' => 'LBL_RETRIBUTION',
          ),
          1 => 
          array (
            'name' => 'retribution_conditions',
            'studio' => 'visible',
            'label' => 'LBL_RETRIBUTION_CONDITIONS',
          ),
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'sepe_contract_type',
            'studio' => 'visible',
            'label' => 'LBL_SEPE_CONTRACT_TYPE',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'sepe_activation_date',
            'label' => 'LBL_SEPE_ACTIVATION_DATE',
          ),
          1 => 
          array (
            'name' => 'sepe_covered_date',
            'label' => 'LBL_SEPE_COVERED_DATE',
          ),
        ),
      ),
      'lbl_editview_panel3' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'inc_id',
            'label' => 'LBL_INC_ID',
          ),
          1 => 
          array (
            'name' => 'inc_incorpora_record',
            'studio' => 
            array (
              'no_duplicate' => true,
              'editview' => false,
              'quickcreate' => false,
            ),
            'label' => 'LBL_INC_INCORPORA_RECORD',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'inc_status',
            'studio' => 'visible',
            'label' => 'LBL_INC_STATUS',
          ),
          1 => 
          array (
            'name' => 'inc_status_details',
            'studio' => 
            array (
              'editview' => false,
              'quickcreate' => false,
            ),
            'label' => 'LBL_INC_STATUS_DETAILS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'inc_officer_email',
            'label' => 'LBL_INC_OFFICER_EMAIL',
          ),
          1 => 
          array (
            'name' => 'inc_officer_telephone',
            'label' => 'LBL_INC_OFFICER_TELEPHONE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'inc_offer_origin',
            'studio' => 'visible',
            'label' => 'LBL_INC_OFFER_ORIGIN',
          ),
          1 => 
          array (
            'name' => 'inc_checkin_date',
            'label' => 'LBL_INC_CHECKIN_DATE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'inc_register_start_date',
            'label' => 'LBL_INC_REGISTER_START_DATE',
          ),
          1 => 
          array (
            'name' => 'inc_register_end_date',
            'label' => 'LBL_INC_REGISTER_END_DATE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'inc_contract_start_date',
            'label' => 'LBL_INC_CONTRACT_START_DATE',
          ),
          1 => 
          array (
            'name' => 'inc_contract_type',
            'studio' => 'visible',
            'label' => 'LBL_INC_CONTRACT_TYPE',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'inc_contract_duration',
            'studio' => 'visible',
            'label' => 'LBL_INC_CONTRACT_DURATION',
          ),
          1 => 
          array (
            'name' => 'contract_duration_details',
            'comment' => 'Full text of the note',
            'studio' => 'visible',
            'label' => 'LBL_CONTRACT_DURATION_DETAILS',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'inc_remuneration',
            'label' => 'LBL_INC_REMUNERATION',
          ),
          1 => 
          array (
            'name' => 'inc_tasks_responsabilities',
            'comment' => 'Full text of the note',
            'studio' => 'visible',
            'label' => 'LBL_INC_TASKS_RESPONSABILITIES',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'inc_cno_n1',
            'studio' => 'visible',
            'label' => 'LBL_INC_CNO_N1',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'inc_cno_n2',
            'studio' => 'visible',
            'label' => 'LBL_INC_CNO_N2',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'inc_cno_n3',
            'studio' => 'visible',
            'label' => 'LBL_INC_CNO_N3',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'inc_working_day',
            'studio' => 'visible',
            'label' => 'LBL_INC_WORKING_DAY',
          ),
          1 => 
          array (
            'name' => 'inc_observations',
            'studio' => 'visible',
            'label' => 'LBL_INC_OBSERVATIONS',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'inc_municipality',
            'label' => 'LBL_INC_MUNICIPALITY',
          ),
          1 => 
          array (
            'name' => 'inc_town',
            'label' => 'LBL_INC_TOWN',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'inc_state',
            'label' => 'LBL_INC_STATE',
          ),
          1 => '',
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'inc_location',
            'studio' => 'visible',
            'label' => 'LBL_INC_LOCATION',
          ),
          1 => 
          array (
            'name' => 'inc_country',
            'studio' => 'visible',
            'label' => 'LBL_INC_COUNTRY',
          ),
        ),
      ),
      'lbl_editview_panel7' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'inc_collective_requirements',
            'studio' => 'visible',
            'label' => 'LBL_INC_COLLECTIVE_REQUIREMENTS',
          ),
          1 => 
          array (
            'name' => 'inc_education_languages',
            'studio' => 'visible',
            'label' => 'LBL_INC_EDUCATION_LANGUAGES',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'inc_education',
            'studio' => 'visible',
            'label' => 'LBL_INC_EDUCATION',
          ),
          1 => 
          array (
            'name' => 'inc_working_experience',
            'comment' => '',
            'studio' => 'visible',
            'label' => 'LBL_INC_WORKING_EXPERIENCE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'inc_minimum_age',
            'label' => 'LBL_INC_MINIMUM_AGE',
          ),
          1 => 
          array (
            'name' => 'inc_maximum_age',
            'label' => 'LBL_INC_MAXIMUM_AGE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'inc_driving_licenses',
            'label' => 'LBL_INC_DRIVING_LICENSES',
          ),
          1 => 
          array (
            'name' => 'inc_professional_licenses',
            'label' => 'LBL_INC_PROFESSIONAL_LICENSES',
          ),
        ),
      ),
      'LBL_DETAILVIEW_PANEL6' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'inc_reference_officer',
            'label' => 'LBL_INC_REFERENCE_OFFICER',
          ),
          1 => 
          array (
            'name' => 'inc_reference_entity',
            'studio' => 'visible',
            'label' => 'LBL_INC_REFERENCE_ENTITY',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'inc_reference_group',
            'studio' => 'visible',
            'label' => 'LBL_INC_REFERENCE_GROUP',
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'inc_synchronization_date',
            'studio' => 
            array (
              'no_duplicate' => true,
              'editview' => false,
              'quickcreate' => false,
            ),
            'label' => 'LBL_INC_SYNCHRONIZATION_DATE',
          ),
          1 => 
          array (
            'name' => 'inc_synchronization_errors',
            'studio' => 
            array (
              'no_duplicate' => true,
              'editview' => false,
              'quickcreate' => false,
            ),
            'label' => 'LBL_INC_SYNCHRONIZATION_ERRORS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'inc_synchronization_log',
            'comment' => 'Full text of log',
            'studio' => 
            array (
              'no_duplicate' => true,
              'editview' => false,
              'quickcreate' => false,
            ),
            'label' => 'LBL_INC_SYNCHRONIZATION_LOG',
          ),
        ),
      ),
      'LBL_PANEL_RECORD_DETAILS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED',
          ),
          1 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value}',
            'label' => 'LBL_DATE_ENTERED',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'modified_by_name',
            'label' => 'LBL_MODIFIED_NAME',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value}',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
      ),
    ),
  ),
);
;
?>
