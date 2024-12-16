<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (file_exists('custom/include/AdvancedTabConfig.php')) {
    include_once 'custom/include/AdvancedTabConfig.php';
}

// If the menu is not defined in the instance, the default menu is loaded
if (empty($GLOBALS["SticTabStructure"])) {
    $GLOBALS["SticTabStructure"] = array(
        0 => array(
            'id' => 'LBL_GROUPTAB_MAIN',
            'children' => array(
                0 => array(
                    'id' => 'Contacts',
                ),
                1 => array(
                    'id' => 'Accounts',
                ),
                2 => array(
                    'id' => 'Leads',
                ),
                3 => array(
                    'id' => 'stic_Contacts_Relationships',
                ),
                4 => array(
                    'id' => 'stic_Accounts_Relationships',
                ),
                5 => array(
                    'id' => 'stic_Centers',
                ),
            ),
        ),
        1 => array(
            'id' => 'LBL_GROUPTAB_ACTIVITIES',
            'children' => array(
                0 => array(
                    'id' => 'Home',
                ),
                1 => array(
                    'id' => 'Calendar',
                ),
                2 => array(
                    'id' => 'Calls',
                ),
                3 => array(
                    'id' => 'Meetings',
                ),
                4 => array(
                    'id' => 'Emails',
                ),
                5 => array(
                    'id' => 'Tasks',
                ),
                6 => array(
                    'id' => 'Notes',
                ),
            ),
        ),
        2 => array(
            'id' => 'LBL_GROUPTAB_ECONOMY',
            'children' => array(
                0 => array(
                    'id' => 'stic_Payment_Commitments',
                ),
                1 => array(
                    'id' => 'stic_Payments',
                ),
                2 => array(
                    'id' => 'stic_Remittances',
                ),
                3 => array(
                    'id' => 'Opportunities',
                ),
                4 => array(
                    'id' => 'stic_Group_Opportunities',
                ),
            ),
        ),
        3 => array(
            'id' => 'LBL_GROUPTAB_CAMPAIGNS',
            'children' => array(
                0 => array(
                    'id' => 'Campaigns',
                ),
                1 => array(
                    'id' => 'ProspectLists',
                ),
                2 => array(
                    'id' => 'Surveys',
                ),
            ),
        ),
        4 => array(
            'id' => 'LBL_GROUPTAB_EVENTS',
            'children' => array(
                0 => array(
                    'id' => 'stic_Events',
                ),
                1 => array(
                    'id' => 'stic_Registrations',
                ),
                2 => array(
                    'id' => 'stic_Sessions',
                ),
                3 => array(
                    'id' => 'stic_Attendances',
                ),
                4 => array(
                    'id' => 'FP_Event_Locations',
                ),
            ),
        ),
        5 => array(
            'id' => 'LBL_GROUPTAB_DIRECTCARE',
            'children' => array(
                0 => array(
                    'id' => 'stic_Assessments',
                ),
                1 => array(
                    'id' => 'stic_Goals',
                ),
                2 => array(
                    'id' => 'stic_Personal_Environment',
                ),
                3 => array(
                    'id' => 'stic_FollowUps',
                ),
                4 => array(
                    'id' => 'stic_Grants',
                ),
                5 => array(
                    'id' => 'stic_Families',
                ),
                6 => array(
                    'id' => 'stic_Medication',
                ),
                7 => array(
                    'id' => 'stic_Prescription',
                ),
                8 => array(
                    'id' => 'stic_Medication_Log',
                ),
                9 => array(
                    'id' => 'stic_Journal',
                ),
            ),
        ),
        6 => array(
            'id' => 'LBL_GROUPTAB_LABOURINSERTION',
            'children' => array(
                0 => array(
                    'id' => 'stic_Job_Offers',
                ),
                1 => array(
                    'id' => 'stic_Job_Applications',
                ),
                2 => array(
                    'id' => 'stic_Sepe_Actions',
                ),
                3 => array(
                    'id' => 'stic_Sepe_Incidents',
                ),
                4 => array(
                    'id' => 'stic_Sepe_Files',
                ),
                5 => array(
                    'id' => 'stic_Training',
                ),
                6 => array(
                    'id' => 'stic_Skills',
                ),
                7 => array(
                    'id' => 'stic_Work_Experience',
                ),
            ),
        ),
        7 => array(
            'id' => 'LBL_GROUPTAB_BOOKINGS',
            'children' => array(
                0 => array(
                    'id' => 'stic_Bookings',
                ),
                1 => array(
                    'id' => 'stic_Resources',
                ),
                2 => array(
                    'id' => 'stic_Bookings_Calendar',
                ),
            ),
        ),
        8 => array(
            'id' => 'LBL_GROUPTAB_SALES',
            'children' => array(
                0 => array(
                    'id' => 'AOS_Product_Categories',
                ),
                1 => array(
                    'id' => 'AOS_Products',
                ),
                2 => array(
                    'id' => 'AOS_Quotes',
                ),
                3 => array(
                    'id' => 'AOS_Contracts',
                ),
                4 => array(
                    'id' => 'AOS_Invoices',
                ),
            ),
        ),
        9 => array(
            'id' => 'LBL_GROUPTAB_OTHER',
            'children' => array(
                0 => array(
                    'id' => 'Project',
                ),
                1 => array(
                    'id' => 'KReports',
                ),
                2 => array(
                    'id' => 'Documents',
                ),
                3 => array(
                    'id' => 'DHA_PlantillasDocumentos',
                ),
                4 => array(
                    'id' => 'AOW_WorkFlow',
                ),
                5 => array(
                    'id' => 'AOR_Reports',
                ),
                6 => array(
                    'id' => 'AOS_PDF_Templates',
                ),
            ),
        ),
    );
}
