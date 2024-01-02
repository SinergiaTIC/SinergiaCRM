<?php
$module_name = 'stic_Bookings';
$viewdefs[$module_name] =
array(
    'QuickCreate' => array(
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
                'LBL_PANEL_STIC_BOOKINGS_INFORMATION' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'form' => array(
                'buttons' => array(
                    0 => array(
                        'customCode' => '<input id="stic_Bookings_subpanel_save_button" class="button primary" type="submit" value="Guardar" name="stic_Bookings_subpanel_save_button"
               accesskey="a" title="Save123123">',
                    ),
                    1 => 'CANCEL',
                ),
            ),
        ),
        'panels' => array(
            'LBL_PANEL_STIC_BOOKINGS_INFORMATION' => array(
                0 => array(
                    0 => 'name',
                    1 => 'assigned_user_name',
                ),
                1 => array(
                    0 => 'code',
                ),
                2 => array(
                    0 => array(
                        'name' => 'start_date',
                        'label' => 'LBL_START_DATE',
                    ),
                    1 => array(
                        'name' => 'end_date',
                        'label' => 'LBL_END_DATE',
                    ),
                ),
                3 => array(
                    0 => array(
                        'name' => 'status',
                        'label' => 'LBL_STATUS',
                    ),
                    1 => array(
                        'name' => 'parent_name',
                        'studio' => 'visible',
                        'label' => 'LBL_FLEX_RELATE',
                    ),
                ),
                4 => array(
                    0 => array(
                        'name' => 'stic_bookings_contacts_name',
                        'label' => 'LBL_STIC_BOOKINGS_CONTACTS_FROM_CONTACTS_TITLE',
                    ),
                    1 => array(
                        'name' => 'stic_bookings_accounts_name',
                        'label' => 'LBL_STIC_BOOKINGS_ACCOUNTS_FROM_ACCOUNTS_TITLE',
                    ),
                ),
                5 => array(
                    0 => array(
                        'name' => 'description',
                        'comment' => 'Full text of the note',
                        'label' => 'LBL_DESCRIPTION',
                    ),
                ),
            ),
        ),
    ),
);
