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

// Custom modules list
$moduleList[] = 'stic_Accounts_Relationships';
$moduleList[] = 'stic_Attendances';
$moduleList[] = 'stic_Contacts_Relationships';
$moduleList[] = 'stic_Events';
$moduleList[] = 'stic_Payment_Commitments';
$moduleList[] = 'stic_Payments';
$moduleList[] = 'stic_Registrations';
$moduleList[] = 'stic_Remittances';
$moduleList[] = 'stic_Sessions';
$moduleList[] = 'stic_Settings';
$moduleList[] = 'stic_Validation_Actions';
$moduleList[] = 'stic_Web_Forms';
$moduleList[] = 'stic_Assessments';
$moduleList[] = 'stic_Goals';
$moduleList[] = 'stic_Personal_Environment';
$moduleList[] = 'stic_FollowUps';
$moduleList[] = 'stic_Families';
$moduleList[] = 'stic_Job_Offers';
$moduleList[] = 'stic_Job_Applications';
$moduleList[] = 'stic_Sepe_Actions';
$moduleList[] = 'stic_Sepe_Incidents';
$moduleList[] = 'stic_Sepe_Files';
$moduleList[] = 'stic_Incorpora_Locations';
$moduleList[] = 'stic_Validation_Results';
$moduleList[] = 'stic_Bookings_Calendar';
$moduleList[] = 'stic_Bookings';
$moduleList[] = 'stic_Resources';
$moduleList[] = 'stic_Medication_Log';
$moduleList[] = 'stic_Medication';
$moduleList[] = 'stic_Prescription';
$moduleList[] = 'stic_Grants';
$moduleList[] = 'stic_Centers';
$moduleList[] = 'stic_Group_Opportunities';

// Bean names for custom modules
// Although they should be singular ModuleBuilder outputs them in plural and we keep them this way
$beanList['stic_Accounts_Relationships'] = 'stic_Accounts_Relationships';
$beanList['stic_Attendances'] = 'stic_Attendances';
$beanList['stic_Contacts_Relationships'] = 'stic_Contacts_Relationships';
$beanList['stic_Events'] = 'stic_Events';
$beanList['stic_Payment_Commitments'] = 'stic_Payment_Commitments';
$beanList['stic_Payments'] = 'stic_Payments';
$beanList['stic_Registrations'] = 'stic_Registrations';
$beanList['stic_Remittances'] = 'stic_Remittances';
$beanList['stic_Sessions'] = 'stic_Sessions';
$beanList['stic_Settings'] = 'stic_Settings';
$beanList['stic_Validation_Actions'] = 'stic_Validation_Actions';
$beanList['stic_Assessments'] = 'stic_Assessments';
$beanList['stic_Goals'] = 'stic_Goals';
$beanList['stic_Personal_Environment'] = 'stic_Personal_Environment';
$beanList['stic_FollowUps'] = 'stic_FollowUps';
$beanList['stic_Families'] = 'stic_Families';
$beanList['stic_Job_Offers'] = 'stic_Job_Offers';
$beanList['stic_Job_Applications'] = 'stic_Job_Applications';
$beanList['stic_Sepe_Actions'] = 'stic_Sepe_Actions';
$beanList['stic_Sepe_Incidents'] = 'stic_Sepe_Incidents';
$beanList['stic_Sepe_Files'] = 'stic_Sepe_Files';
$beanList['stic_Incorpora_Locations'] = 'stic_Incorpora_Locations';
$beanList['stic_Incorpora'] = 'stic_Incorpora';
$beanList['stic_Validation_Results'] = 'stic_Validation_Results';
$beanList['stic_Bookings_Calendar'] = 'stic_Bookings_Calendar';
$beanList['stic_Bookings'] = 'stic_Bookings';
$beanList['stic_Resources'] = 'stic_Resources';
$beanList['stic_Medication_Log'] = 'stic_Medication_Log';
$beanList['stic_Medication'] = 'stic_Medication';
$beanList['stic_Prescription'] = 'stic_Prescription';
$beanList['stic_Grants'] = 'stic_Grants';
$beanList['stic_Centers'] = 'stic_Centers';
$beanList['stic_Group_Opportunities'] = 'stic_Group_Opportunities';

// Location of custom modules main class files
$beanFiles['stic_Accounts_Relationships'] = 'modules/stic_Accounts_Relationships/stic_Accounts_Relationships.php';
$beanFiles['stic_Attendances'] = 'modules/stic_Attendances/stic_Attendances.php';
$beanFiles['stic_Contacts_Relationships'] = 'modules/stic_Contacts_Relationships/stic_Contacts_Relationships.php';
$beanFiles['stic_Events'] = 'modules/stic_Events/stic_Events.php';
$beanFiles['stic_Payment_Commitments'] = 'modules/stic_Payment_Commitments/stic_Payment_Commitments.php';
$beanFiles['stic_Payments'] = 'modules/stic_Payments/stic_Payments.php';
$beanFiles['stic_Registrations'] = 'modules/stic_Registrations/stic_Registrations.php';
$beanFiles['stic_Remittances'] = 'modules/stic_Remittances/stic_Remittances.php';
$beanFiles['stic_Sessions'] = 'modules/stic_Sessions/stic_Sessions.php';
$beanFiles['stic_Settings'] = 'modules/stic_Settings/stic_Settings.php';
$beanFiles['stic_Validation_Actions'] = 'modules/stic_Validation_Actions/stic_Validation_Actions.php';
$beanFiles['stic_Assessments'] = 'modules/stic_Assessments/stic_Assessments.php';
$beanFiles['stic_Goals'] = 'modules/stic_Goals/stic_Goals.php';
$beanFiles['stic_Personal_Environment'] = 'modules/stic_Personal_Environment/stic_Personal_Environment.php';
$beanFiles['stic_FollowUps'] = 'modules/stic_FollowUps/stic_FollowUps.php';
$beanFiles['stic_Families'] = 'modules/stic_Families/stic_Families.php';
$beanFiles['stic_Job_Offers'] = 'modules/stic_Job_Offers/stic_Job_Offers.php';
$beanFiles['stic_Job_Applications'] = 'modules/stic_Job_Applications/stic_Job_Applications.php';
$beanFiles['stic_Sepe_Actions'] = 'modules/stic_Sepe_Actions/stic_Sepe_Actions.php';
$beanFiles['stic_Sepe_Incidents'] = 'modules/stic_Sepe_Incidents/stic_Sepe_Incidents.php';
$beanFiles['stic_Sepe_Files'] = 'modules/stic_Sepe_Files/stic_Sepe_Files.php';
$beanFiles['stic_Incorpora_Locations'] = 'modules/stic_Incorpora_Locations/stic_Incorpora_Locations.php';
$beanFiles['stic_Incorpora'] = 'modules/stic_Incorpora/stic_Incorpora.php';
$beanFiles['stic_Validation_Results'] = 'modules/stic_Validation_Results/stic_Validation_Results.php';
$beanFiles['stic_Bookings_Calendar'] = 'modules/stic_Bookings_Calendar/stic_Bookings_Calendar.php';
$beanFiles['stic_Bookings'] = 'modules/stic_Bookings/stic_Bookings.php';
$beanFiles['stic_Resources'] = 'modules/stic_Resources/stic_Resources.php';
$beanFiles['stic_Medication_Log'] = 'modules/stic_Medication_Log/stic_Medication_Log.php';
$beanFiles['stic_Medication'] = 'modules/stic_Medication/stic_Medication.php';
$beanFiles['stic_Prescription'] = 'modules/stic_Prescription/stic_Prescription.php';
$beanFiles['stic_Grants'] = 'modules/stic_Grants/stic_Grants.php';
$beanFiles['stic_Centers'] = 'modules/stic_Centers/stic_Centers.php';
$beanFiles['stic_Group_Opportunities'] = 'modules/stic_Group_Opportunities/stic_Group_Opportunities.php';


// Modules in $modInvisList are hidden in the main menu, in reporting and as subpanels
$modInvisList[] = 'stic_Settings';
$modInvisList[] = 'stic_Validation_Actions';
$modInvisList[] = 'stic_Web_Forms';
$modInvisList[] = 'stic_Incorpora';
$modInvisList[] = 'stic_Import_Validation';
$modInvisList[] = 'stic_Validation_Results';

// Modules that have been hidden with $modInvisList, but have to be shown as subpanels
$modules_exempt_from_availability_check['stic_Validation_Actions'] = 'stic_Validation_Actions';
$modules_exempt_from_availability_check['stic_Incorpora'] = 'stic_Incorpora';

// Modules that have been hidden with $modInvisList, but must be available in reporting
// $report_include_modules['stic_XXXXXXXXXX'] = 'stic_XXXXXXXXXX'; // sample value

// Modules that should be accessed only by administrators through the Admin page
$adminOnlyList['stic_Settings'] = array('all' => 1);
$adminOnlyList['stic_Validation_Actions'] = array('all' => 1);

// Totally hide FP_Events because it can be confused with stic_Events
$modInvisList[] = 'FP_events';
if (($key = array_search('FP_events', $moduleList)) !== false) {
    unset($moduleList[$key]);
}
