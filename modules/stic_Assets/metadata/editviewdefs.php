<?php
$module_name = 'stic_Assets';
$viewdefs[$module_name] =
array(
    'EditView' => array(
        'templateMeta' => array(
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
                'LBL_DEFAULT_PANEL' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_ADDRESS_PANEL' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_OWNERSHIP_PANEL' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_INSURANCES_PANEL' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_UTILITIES_PANEL' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => false,
        ),
        'panels' => array(
            'lbl_default_panel' => array(
                0 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => array(
                        'name' => 'stic_assets_contacts_name',
                    ),
                    1 => '',
                ),
                2 => array(
                    0 => array(
                        'name' => 'type',
                        'studio' => 'visible',
                        'label' => 'LBL_TYPE',
                    ),
                    1 => array(
                        'name' => 'other',
                        'label' => 'LBL_OTHER',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'start_date',
                        'label' => 'LBL_START_DATE',
                    ),
                    1 => array(
                        'name' => 'end_date',
                        'label' => 'LBL_END_DATE',
                    ),
                ),
                4 => array(
                    0 => 'description',
                ),
            ),
            'lbl_address_panel' => array(
                0 => array(
                    0 => array(
                        'name' => 'address_street',
                        'label' => 'LBL_ADDRESS_STREET',
                    ),
                    1 => array(
                        'name' => 'address_city',
                        'label' => 'LBL_ADDRESS_CITY',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'address_postalcode',
                        'label' => 'LBL_ADDRESS_POSTALCODE',
                    ),
                    1 => array(
                        'name' => 'address_state',
                        'studio' => 'visible',
                        'label' => 'LBL_ADDRESS_STATE',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'address_region',
                        'studio' => 'visible',
                        'label' => 'LBL_ADDRESS_REGION',
                    ),
                    1 => array(
                        'name' => 'address_country',
                        'label' => 'LBL_ADDRESS_COUNTRY',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'address_location_link',
                        'label' => 'LBL_ADDRESS_LOCATION_LINK',
                    ),
                    1 => '',
                ),
                4 => array(
                    0 => array(
                        'name' => 'latitude',
                        'label' => 'LBL_LATITUDE',
                    ),
                    1 => array(
                        'name' => 'longitude',
                        'label' => 'LBL_LONGITUDE',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'address_notes',
                        'studio' => 'visible',
                        'label' => 'LBL_ADDRESS_NOTES',
                    ),
                ),
            ),
            'lbl_ownership_panel' => array(
                0 => array(
                    0 => array(
                        'name' => 'ownership',
                        'studio' => 'visible',
                        'label' => 'LBL_OWNERSHIP',
                    ),
                    1 => array(
                        'name' => 'ownership_percentage',
                        'label' => 'LBL_OWNERSHIP_PERCENTAGE',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                    1 => array(
                        'name' => 'occupant_type',
                        'studio' => 'visible',
                        'label' => 'LBL_OCCUPANT_TYPE',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'contact_person',
                        'studio' => 'visible',
                        'label' => 'LBL_CONTACT_PERSON',
                    ),
                    1 => array(
                        'name' => 'owners_president',
                        'studio' => 'visible',
                        'label' => 'LBL_OWNERS_PRESIDENT',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'estate_company',
                        'studio' => 'visible',
                        'label' => 'LBL_ESTATE_COMPANY',
                    ),
                    1 => array(
                        'name' => 'estate_contact',
                        'studio' => 'visible',
                        'label' => 'LBL_ESTATE_CONTACT',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'key_set',
                        'studio' => 'visible',
                        'label' => 'LBL_KEY_SET',
                    ),
                    1 => '',
                ),
                5 => array(
                    0 => array(
                        'name' => 'ownership_notes',
                        'studio' => 'visible',
                        'label' => 'LBL_OWNERSHIP_NOTES',
                    ),
                ),
            ),
            'lbl_insurances_panel' => array(
                0 => array(
                    0 => array(
                        'name' => 'insurance',
                        'studio' => 'visible',
                        'label' => 'LBL_INSURANCE',
                    ),
                    1 => array(
                        'name' => 'policy_number',
                        'label' => 'LBL_POLICY_NUMBER',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'insured_building',
                        'studio' => 'visible',
                        'label' => 'LBL_INSURED_BUILDING',
                    ),
                    1 => array(
                        'name' => 'insured_contents',
                        'studio' => 'visible',
                        'label' => 'LBL_INSURED_CONTENTS',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'insurance_notes',
                        'studio' => 'visible',
                        'label' => 'LBL_INSURANCE_NOTES',
                    ),
                ),
            ),
            'lbl_utilities_panel' => array(
                0 => array(
                    0 => array(
                        'name' => 'electricity',
                        'studio' => 'visible',
                        'label' => 'LBL_ELECTRICITY',
                    ),
                    1 => array(
                        'name' => 'gas',
                        'studio' => 'visible',
                        'label' => 'LBL_GAS',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'water',
                        'studio' => 'visible',
                        'label' => 'LBL_WATER',
                    ),
                    1 => array(
                        'name' => 'phone',
                        'studio' => 'visible',
                        'label' => 'LBL_PHONE',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'security',
                        'studio' => 'visible',
                        'label' => 'LBL_SECURITY',
                    ),
                    1 => '',
                ),
                3 => array(
                    0 => array(
                        'name' => 'utilities_notes',
                        'studio' => 'visible',
                        'label' => 'LBL_UTILITIES_NOTES',
                    ),
                ),
            ),
        ),
    ),
);
