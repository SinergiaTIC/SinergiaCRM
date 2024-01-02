<?php
$viewdefs ['Contacts'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'hidden' => 
        array (
          0 => '<input type="hidden" name="opportunity_id" value="{$smarty.request.opportunity_id}">',
          1 => '<input type="hidden" name="case_id" value="{$smarty.request.case_id}">',
          2 => '<input type="hidden" name="bug_id" value="{$smarty.request.bug_id}">',
          3 => '<input type="hidden" name="email_id" value="{$smarty.request.email_id}">',
          4 => '<input type="hidden" name="inbound_email_id" value="{$smarty.request.inbound_email_id}">',
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
        'LBL_CONTACT_INFORMATION' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_STIC_PANEL_CONTACT_DATA' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_STIC_PANEL_ADDRESSES' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_STIC_PANEL_SOCIOECONOMIC_DATA' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_STIC_PANEL_GDPR' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_STIC_PANEL_SEPE' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_STIC_PANEL_INCORPORA' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_STIC_PANEL_INCORPORA_ADDRESS' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => false,
    ),
    'panels' => 
    array (
      'lbl_contact_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'photo',
            'studio' => 
            array (
              'listview' => true,
            ),
            'label' => 'LBL_PHOTO',
          ),
          1 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
        ),
        1 => 
        array (
          0 => 'first_name',
          1 => 'last_name',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'stic_identification_type_c',
            'label' => 'LBL_STIC_IDENTIFICATION_TYPE',
          ),
          1 => 
          array (
            'name' => 'stic_identification_number_c',
            'label' => 'LBL_STIC_IDENTIFICATION_NUMBER',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'stic_language_c',
            'label' => 'LBL_STIC_LANGUAGE',
          ),
          1 => 
          array (
            'name' => 'stic_gender_c',
            'label' => 'LBL_STIC_GENDER',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'birthdate',
            'label' => 'LBL_BIRTHDATE',
          ),
          1 => '',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'campaign_name',
            'label' => 'LBL_CAMPAIGN',
          ),
          1 => 
          array (
            'name' => 'stic_acquisition_channel_c',
            'label' => 'LBL_STIC_ACQUISITION_CHANNEL',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
      'LBL_STIC_PANEL_CONTACT_DATA' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'email1',
            'studio' => 'false',
            'label' => 'LBL_EMAIL_ADDRESS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'phone_mobile',
            'label' => 'LBL_MOBILE_PHONE',
          ),
          1 => 
          array (
            'name' => 'phone_home',
            'label' => 'LBL_HOME_PHONE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'phone_work',
            'label' => 'LBL_OFFICE_PHONE',
          ),
          1 => 
          array (
            'name' => 'phone_other',
            'label' => 'LBL_OTHER_PHONE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'stic_preferred_contact_channel_c',
            'label' => 'LBL_STIC_PREFERRED_CONTACT_CHANNEL',
          ),
          1 => 
          array (
            'name' => 'stic_postal_mail_return_reason_c',
            'label' => 'LBL_STIC_POSTAL_MAIL_RETURN_REASON',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'do_not_call',
          ),
          1 => 
          array (
            'name' => 'stic_do_not_send_postal_mail_c',
            'label' => 'LBL_STIC_DO_NOT_SEND_POSTAL_MAIL',
          ),
        ),
      ),
      'LBL_STIC_PANEL_ADDRESSES' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'stic_primary_address_type_c',
            'label' => 'LBL_STIC_PRIMARY_ADDRESS_TYPE',
          ),
          1 => 
          array (
            'name' => 'stic_alt_address_type_c',
            'label' => 'LBL_STIC_ALT_ADDRESS_TYPE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_street',
            'label' => 'LBL_PRIMARY_ADDRESS_STREET',
          ),
          1 => 
          array (
            'name' => 'alt_address_street',
            'label' => 'LBL_ALT_ADDRESS_STREET',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_postalcode',
            'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
          ),
          1 => 
          array (
            'name' => 'alt_address_postalcode',
            'label' => 'LBL_ALT_ADDRESS_POSTALCODE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_city',
            'label' => 'LBL_PRIMARY_ADDRESS_CITY',
          ),
          1 => 
          array (
            'name' => 'alt_address_city',
            'label' => 'LBL_ALT_ADDRESS_CITY',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'stic_primary_address_county_c',
            'label' => 'LBL_STIC_PRIMARY_ADDRESS_COUNTY',
          ),
          1 => 
          array (
            'name' => 'stic_alt_address_county_c',
            'label' => 'LBL_STIC_ALT_ADDRESS_COUNTY',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_state',
            'label' => 'LBL_PRIMARY_ADDRESS_STATE',
          ),
          1 => 
          array (
            'name' => 'alt_address_state',
            'label' => 'LBL_ALT_ADDRESS_STATE',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'stic_primary_address_region_c',
            'label' => 'LBL_STIC_PRIMARY_ADDRESS_REGION',
          ),
          1 => 
          array (
            'name' => 'stic_alt_address_region_c',
            'label' => 'LBL_STIC_ALT_ADDRESS_REGION',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_country',
            'label' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
          ),
          1 => 
          array (
            'name' => 'alt_address_country',
            'label' => 'LBL_ALT_ADDRESS_COUNTRY',
          ),
        ),
      ),
      'LBL_STIC_PANEL_SOCIOECONOMIC_DATA' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'stic_professional_profile_c',
            'studio' => 'visible',
            'label' => 'LBL_STIC_PROFESSIONAL_PROFILE',
          ),
          1 => 
          array (
            'name' => 'stic_referral_agent_c',
            'label' => 'LBL_STIC_REFERRAL_AGENT',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'stic_professional_sector_c',
            'label' => 'LBL_STIC_PROFESSIONAL_SECTOR',
          ),
          1 => 
          array (
            'name' => 'stic_professional_sector_other_c',
            'label' => 'LBL_STIC_PROFESSIONAL_SECTOR_OTHER',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'stic_employment_status_c',
            'label' => 'LBL_STIC_EMPLOYMENT_STATUS',
          ),
          1 => 
          array (
            'name' => 'account_name',
            'displayParams' => 
            array (
              'key' => 'billing',
              'copy' => 'primary',
              'billingKey' => 'primary',
              'additionalFields' => 
              array (
                'phone_office' => 'phone_work',
              ),
            ),
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'title',
            'label' => 'LBL_TITLE',
          ),
          1 => 'department',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'stic_tax_name_c',
            'label' => 'LBL_STIC_TAX_NAME',
          ),
          1 => 
          array (
            'name' => 'stic_182_excluded_c',
            'label' => 'LBL_STIC_182_EXCLUDED',
          ),
        ),
      ),
      'LBL_STIC_PANEL_GDPR' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'lawful_basis',
            'label' => 'LBL_LAWFUL_BASIS',
          ),
          1 => 
          array (
            'name' => 'lawful_basis_source',
            'label' => 'LBL_LAWFUL_BASIS_SOURCE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'date_reviewed',
            'label' => 'LBL_DATE_REVIEWED',
          ),
          1 => '',
        ),
      ),
      'LBL_STIC_PANEL_SEPE' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'sepe_education_level_c',
            'studio' => 'visible',
            'label' => 'LBL_SEPE_EDUCATION_LEVEL',
          ),
          1 => 
          array (
            'name' => 'sepe_immigrant_c',
            'studio' => 'visible',
            'label' => 'LBL_SEPE_IMMIGRANT',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'sepe_insertion_difficulties_c',
            'studio' => 'visible',
            'label' => 'LBL_SEPE_INSERTION_DIFFICULTIES',
          ),
          1 => 
          array (
            'name' => 'sepe_disability_c',
            'studio' => 'visible',
            'label' => 'LBL_SEPE_DISABILITY',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'sepe_benefit_perceiver_c',
            'studio' => 'visible',
            'label' => 'LBL_SEPE_BENEFIT_PERCEIVER',
          ),
          1 => '',
        ),
      ),
      'LBL_STIC_PANEL_INCORPORA' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'inc_id_c',
            'label' => 'LBL_INC_ID',
          ),
          1 => 
          array (
            'name' => 'inc_incorpora_record_c',
            'label' => 'LBL_INC_INCORPORA_RECORD',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'inc_lopd_consent_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_LOPD_CONSENT',
          ),
          1 => 
          array (
            'name' => 'inc_incorporation_date_c',
            'label' => 'LBL_INC_INCORPORATION_DATE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'inc_collectives_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_COLLECTIVES',
          ),
          1 => 
          array (
            'name' => 'inc_derivation_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_DERIVATION',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'inc_disability_degree_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_DISABILITY_DEGREE',
          ),
          1 => 
          array (
            'name' => 'inc_disability_cert_id_c',
            'label' => 'LBL_INC_DISABILITY_CERT_ID',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'inc_economic_benefits_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_ECONOMIC_BENEFITS',
          ),
          1 => 
          array (
            'name' => 'inc_nationality_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_NATIONALITY',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'inc_disabled_children_c',
            'label' => 'LBL_INC_DISABLED_CHILDREN',
          ),
          1 => 
          array (
            'name' => 'inc_children_c',
            'label' => 'LBL_INC_CHILDREN',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'inc_people_in_charge_c',
            'label' => 'LBL_INC_PEOPLE_IN_CHARGE',
          ),
          1 => 
          array (
            'name' => 'inc_requested_workday_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_REQUESTED_WORKDAY',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'inc_job_characteristics_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_JOB_CHARACTERISTICS',
          ),
          1 => 
          array (
            'name' => 'inc_communications_language_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_COMMUNICATIONS_LANGUAGE',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'inc_geographical_proximity_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_GEOGRAPHICAL_PROXIMITY',
          ),
          1 => 
          array (
            'name' => 'inc_max_commuting_time_c',
            'label' => 'LBL_INC_MAX_COMMUTING_TIME',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'inc_driving_licenses_c',
            'label' => 'LBL_INC_DRIVING_LICENSES',
          ),
          1 => 
          array (
            'name' => 'inc_own_vehicle_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_OWN_VEHICLE',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'inc_car_use_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_CAR_USE',
          ),
          1 => 
          array (
            'name' => 'inc_travel_availability_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_TRAVEL_AVAILABILITY',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'inc_employment_status_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_EMPLOYMENT_STATUS',
          ),
          1 => 
          array (
            'name' => 'inc_employ_office_reg_time_c',
            'label' => 'LBL_INC_EMPLOY_OFFICE_REG_TIME',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'inc_requested_employment_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_REQUESTED_EMPLOYMENT',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'inc_requested_employment_det_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_REQUESTED_EMPLOYMENT_DET',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'inc_unwanted_employments_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_UNWANTED_EMPLOYMENTS',
          ),
          1 => 
          array (
            'name' => 'inc_observations_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_OBSERVATIONS',
          ),
        ),
      ),
      'LBL_STIC_PANEL_INCORPORA_ADDRESS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'inc_address_street_type_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_ADDRESS_STREET_TYPE',
          ),
          1 => 
          array (
            'name' => 'inc_address_street_c',
            'label' => 'LBL_INC_ADDRESS_STREET',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'inc_address_num_a_c',
            'label' => 'LBL_INC_ADDRESS_NUM_A',
          ),
          1 => 
          array (
            'name' => 'inc_address_num_b_c',
            'label' => 'LBL_INC_ADDRESS_NUM_B',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'inc_address_block_c',
            'label' => 'LBL_INC_ADDRESS_BLOCK',
          ),
          1 => 
          array (
            'name' => 'inc_address_floor_c',
            'label' => 'LBL_INC_ADDRESS_FLOOR',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'inc_address_door_c',
            'label' => 'LBL_INC_ADDRESS_DOOR',
          ),
          1 => 
          array (
            'name' => 'inc_address_postal_code_c',
            'label' => 'LBL_INC_ADDRESS_POSTAL_CODE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'inc_address_district_c',
            'label' => 'LBL_INC_ADDRESS_DISTRICT',
          ),
          1 => 
          array (
            'name' => 'inc_location_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_LOCATION',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'inc_country_c',
            'studio' => 'visible',
            'label' => 'LBL_INC_COUNTRY',
          ),
        ),
      ),
    ),
  ),
);
;
?>
