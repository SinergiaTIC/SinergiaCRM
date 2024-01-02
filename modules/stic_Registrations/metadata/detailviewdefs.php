<?php
$module_name = 'stic_Registrations';
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
                        'name' => 'stic_registrations_stic_events_name',
                        'label' => 'LBL_STIC_REGISTRATIONS_STIC_EVENTS_FROM_STIC_EVENTS_TITLE',
                    ),
                    1 => array(
                        'name' => 'registration_date',
                        'label' => 'LBL_REGISTRATION_DATE',
                    ),
                ),
                2 => array(
                    0 => array(
                        'name' => 'stic_registrations_contacts_name',
                        'label' => 'LBL_STIC_REGISTRATIONS_CONTACTS_FROM_CONTACTS_TITLE',
                    ),
                    1 => array(
                        'name' => 'stic_registrations_accounts_name',
                        'label' => 'LBL_STIC_REGISTRATIONS_ACCOUNTS_FROM_ACCOUNTS_TITLE',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'stic_registrations_leads_name',
                        'label' => 'LBL_STIC_REGISTRATIONS_LEADS_FROM_LEADS_TITLE',
                    ),
                    1 => array(
                        'name' => 'stic_payments_stic_registrations_name',
                        'label' => 'LBL_STIC_PAYMENTS_STIC_REGISTRATIONS_FROM_STIC_PAYMENTS_TITLE',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'participation_type',
                        'studio' => 'visible',
                        'label' => 'LBL_PARTICIPATION_TYPE',
                    ),
                    1 => array(
                        'name' => 'attendees',
                        'label' => 'LBL_ATTENDEES',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                    1 => array(
                        'name' => 'disabled_weekdays',
                        'studio' => 'visible',
                        'label' => 'LBL_DISABLED_WEEKDAYS',
                    ),
                ),
                6 => array(
                    0 => array(
                        'name' => 'not_participating_reason',
                        'studio' => 'visible',
                        'label' => 'LBL_NOT_PARTICIPATING_REASON',
                    ),
                    1 => array(
                        'name' => 'rejection_reason',
                        'studio' => 'visible',
                        'label' => 'LBL_REJECTION_REASON',
                    ),
                ),
                7 => array(
                    0 => array(
                        'name' => 'special_needs',
                        'studio' => 'visible',
                        'label' => 'LBL_SPECIAL_NEEDS',
                    ),
                    1 => array(
                        'name' => 'special_needs_description',
                        'label' => 'LBL_SPECIAL_NEEDS_DESCRIPTION',
                    ),
                ),
                8 => array(
                    0 => array(
                        'name' => 'attended_hours',
                        'label' => 'LBL_ATTENDED_HOURS',
                    ),
                    1 => array(
                        'name' => 'attendance_percentage',
                        'label' => 'LBL_ATTENDANCE_PERCENTAGE',
                    ),
                ),
                9 => array(
                    0 => array(
                        'name' => 'session_amount',
                        'label' => 'LBL_SESSION_AMOUNT',
                    ),
                    1 => array(
                        'name' => 'stic_payment_commitments_stic_registrations_name',
                        'label' => 'LBL_STIC_PAYMENT_COMMITMENTS_STIC_REGISTRATIONS_FROM_STIC_PAYMENT_COMMITMENTS_TITLE',
                    ),
                ),
                10 => array(
                    0 => 'description',
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
                        'customCode' => '{$fields.date_entered.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
                1 => array(
                    0 => array(
                        'name' => 'modified_by_name',
                        'label' => 'LBL_MODIFIED_NAME',
                    ),
                    1 => array(
                        'name' => 'date_modified',
                        'customCode' => '{$fields.date_modified.value}',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),
            ),
        ),
    ),
);
