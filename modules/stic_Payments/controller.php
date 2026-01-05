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

class stic_PaymentsController extends SugarController
{
    /**
     * Select the issuing organization for M182 report
     *
     * @return void
     */
    public function action_m182SelectIssuingOrganization()
    {
        global $app_list_strings;
        // Determine issuing organizations available to the current user and populate the dynamic list
        require_once 'modules/stic_Payments/Utils.php';
        $orgKeyArray = stic_PaymentsUtils::getM182IssuingOrganizationKeyForCurrentUser();
        include_once "modules/stic_Remittances/Utils.php";
        stic_RemittancesUtils::fillDynamicListForIssuingOrganizations(true);
        if (!empty($_REQUEST['issuing_organization_selected']) || count($orgKeyArray) == 1) {
            // If an issuing organization is already selected or the user has only one, proceed to the wizard
            $_REQUEST['issuing_organization_selected'] = !empty($_REQUEST['issuing_organization_selected']) ? $_REQUEST['issuing_organization_selected'] : $orgKeyArray[0];
            $this->action_model182Wizard();
        } else {
            // The user has multiple organizations and no selection; show the organization selection view
            $orgLabelArray = array();
            // If the user has no assigned organizations but the system provides multiple dynamic organizations,
            // present all available issuing organizations to choose from. 
            if (count($orgKeyArray) == 0 && count($app_list_strings['dynamic_issuing_organization_list']) > 2) {
                foreach ($app_list_strings['dynamic_issuing_organization_list'] as $key => $value) {
                    if ($key === '') {
                        continue;
                    }
                    $orgLabelArray[] = $value;
                    $orgKeyArray[] = $key;
                }
            } else {
                foreach ($orgKeyArray as $value) {
                    // $orgKeyClean = str_replace('_', '', $value);
                    if (isset($app_list_strings['dynamic_issuing_organization_list'][$value]) == false) {
                        continue;
                    }
                    $orgLabelArray[] = $app_list_strings['dynamic_issuing_organization_list'][$value];
                }
            }
            $this->view = "m182selectissuingorganization";
            $this->view_object_map['ISSUING_ORGANIZATIONS_IDS'] = $orgKeyArray ?? array();
            $this->view_object_map['ISSUING_ORGANIZATIONS_LABELS'] = $orgLabelArray ?? array();
        } 
    }

    /**
     * Show the M182 wizard
     *
     * @return void
     */
    public function action_model182Wizard() {
        global $app_list_strings, $mod_strings;

        // Ensure dynamic list of issuing organizations is filled (for labels)
        include_once "modules/stic_Remittances/Utils.php";
        stic_RemittancesUtils::fillDynamicListForIssuingOrganizations(true);   

        // Prepare lists of settings and fields required for the M182 report
        require_once 'modules/stic_Settings/Utils.php';
        $missingSettings = array();
        $missingFields = array();
        $neededSettings = array(
            'GENERAL_ORGANIZATION_NAME',
            'GENERAL_ORGANIZATION_ID',
            'M182_CLAVE_DONATIVO',
            'M182_NATURALEZA_DECLARANTE',
            // 'M182_PORCENTAJE_DEDUCCION_AUTONOMICA_XX',
            'M182_PERSONA_CONTACTO_APELLIDO_1',
            'M182_PERSONA_CONTACTO_APELLIDO_2',
            'M182_PERSONA_CONTACTO_NOMBRE',
            'M182_PERSONA_CONTACTO_TELEFONO',
            'M182_NUMERO_JUSTIFICANTE',
        );


        // Read selected issuing organization from request (if any)
        $selectedOrgKey = '';
        if (!empty($_REQUEST['issuing_organization_selected'])) {
            $selectedOrgKey = (string) $_REQUEST['issuing_organization_selected'];
        }
        // Read payment types drop-down list
        $movementClassList = $app_list_strings['stic_payments_types_list'];

        // Treat '__default__' as no specific organization (use global settings and remove org-specific payment types)
        if ($selectedOrgKey === '__default__') {
            $selectedOrgKey = '';
            require_once 'modules/stic_Payments/Utils.php';
            $movementClassList = stic_PaymentsUtils::filterMovementClassListForDefaultOrg($movementClassList, $app_list_strings['dynamic_issuing_organization_list']);
        }
        // If a specific issuing organization is selected, validate its settings and filter payment types by suffix
        if ($selectedOrgKey !== '') {
            // Apply selected organization filters: modifies movement list and records missing settings/fields, returns key with leading underscore
            $selectedOrgKey = $this->applySelectedOrganizationFilters($selectedOrgKey, $movementClassList, $neededSettings, $missingSettings, $missingFields, $mod_strings);
        } else {
            // No organization selected: validate global/base settings
            foreach ($neededSettings as $key) {
                if (stic_SettingsUtils::getSetting($key) == '') {
                    $missingSettings[] = $key;
                }
            }
        }

        // Build label and internal arrays for the template
        // Convert $movementClassList (assoc map) into parallel arrays for select option labels and values.
        $i = 0;
        $listLabel = array();
        $listIntern = array();
        foreach ($movementClassList as $x => $xValue) {
            if ($x != '') {
                $listLabel[$i] = $x;
                $listIntern[$i] = $xValue;
                $i++;
            }
        }

        $orgLabel = '';
        $orgKeyClean = '';
        // Prepare issuing organization label and key for the template (strip leading underscore from key)
        if ($selectedOrgKey !== '') {
            $orgKeyClean = str_replace('_', '', $selectedOrgKey);
            global $app_list_strings;
            $orgLabel = $app_list_strings['dynamic_issuing_organization_list'][$orgKeyClean] ?? '';
        }
        $this->view_object_map['ISSUING_ORGANIZATION_LABEL'] = $orgLabel;
        $this->view_object_map['ISSUING_ORGANIZATION_KEY'] = $orgKeyClean;

        // Call to the smarty template — render collected data in the M182 wizard
        $this->view = "m182wizard";
        $this->view_object_map['MISSING_SETTINGS'] = $missingSettings;
        $this->view_object_map['MISSING_FIELDS'] = $missingFields;
        $this->view_object_map['PAYMENT_TYPE_VALUES'] = $listLabel;
        $this->view_object_map['PAYMENT_TYPE_OUTPUT'] = $listIntern;
    }


    /**
     * Create the model 182 spanish AEAT report
     *
     * It allows downloading a file in plain text format prepared to be sent to the Agency of Spanish Tax Administration (AEAT)
     * @return void
     */
    public function action_createModel182()
    {
        // All generation code is in include file
        require_once 'modules/stic_Payments/GenerateModel182.php';
    }

    /**
     * Validate if IBAN is correct, calling to main checkIBAN function. This action is for use in javascript ajax calls
     *
     * @return Boolean json_encoded, for use in ajax response
     */
    public function action_checkIBAN()
    {
        require_once 'SticInclude/Utils.php';
        $iban = $_REQUEST['iban'];
        $resp = SticUtils::checkIBAN($iban);
        echo json_encode($resp);
        exit;
    }

    /**
     * Process an individual Redsys recurring payment with debug mode flag
     * location.href=STIC.siteUrl+'/index.php?module=stic_Payments&action=proccessIndividualRedsysRecurringPayment&paymentId='+STIC.record.id
     *
     * @return void
     */
    public function action_proccessIndividualRedsysRecurringPayment()
    {
        require_once 'modules/stic_Payments/Utils.php';
        require_once 'modules/stic_Payments/RedsysUtils.php';

        RedsysUtils::runRecurringCardPayment($_REQUEST['paymentId'], true);
        SugarApplication::redirect('index.php?module=stic_Payments&action=DetailView&record=' . $_REQUEST['paymentId']);

    }

    /**
     * It runs the action of the functionality Aggregate Payments. The logic of the code is in 'modules/stic_Payments/AggregatePayments.php
     * STIC#498
     *
     * @return void
     */
    public function action_aggregatePayments()
    {
        require_once 'modules/stic_Payments/AggregatePayments.php';
        $this->view = 'aggregated';
    }

    /**
     * On aggregated services payment calculating, sends an email to users with review pending attendances.
     * The function is called from UI through JS.
     *
     * STIC#498
     *
     * @return void
     */
    public function action_notifyUser()
    {
        global $current_user, $mod_strings;

        $userId = $_REQUEST['assigned_user_id'];
        $userBean = BeanFactory::getBean('Users', $userId);
        $destAddress = $userBean->email1;

        // Prepare mail
        require_once 'include/SugarPHPMailer.php';
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();

        // Set From and FromName
        $fromEmail = $current_user->email1;
        if (!$fromEmail) {
            $fromEmail = $defaults['email'];
        }
        $mail->From = $fromEmail;

        $fromName = $current_user->name;
        if (!$fromName) {
            $fromName = $defaults['name'];
        }
        $mail->FromName = $fromName;

        // Add recipient
        if (!$destAddress) {
            echo json_encode(false);
            die();
        }
        $mail->AddAddress($destAddress);

        // Set the subject
        $subject = $mod_strings['LBL_AGGREGATED_NOTIFICATION_EMAIL_SUBJECT'];
        $mail->Subject = $subject;
        $htmlBody = $mod_strings['LBL_AGGREGATED_NOTIFICATION_EMAIL_BODY'];
        // $formattedBody = $this->applyInlineStyles($this->body);
        $completeHTML = "<html>
                            <head>
                                <title>{$subject}</title>
                            </head>
                            <body style=\"font-family: Arial\">
                            {$htmlBody}
                            </body>
                        </html>";
        $mail->Body = from_html($completeHTML);
        $mail->isHtml(true);
        $mail->prepForOutbound();

        if (!$mail->Send()) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ":  There was an error sending the email.");
            echo json_encode(false);
            die();
        }
        echo json_encode(true);
        die();
    }


    /**
     * Apply selected organization filters and checks.
     *
     * Modifies $movementClassList, $missingSettings and $missingFields by reference.
     * Returns the selected organization key prefixed with an underscore.
     *
     * @param string $selectedOrgKey
     * @param array  &$movementClassList
     * @param array  $neededSettings
     * @param array  &$missingSettings
     * @param array  &$missingFields
     * @param array  $mod_strings
     * @return string
     */
    private function applySelectedOrganizationFilters($selectedOrgKey, &$movementClassList, $neededSettings, &$missingSettings, &$missingFields, $mod_strings)
    {
        

        require_once 'modules/stic_Payments/Utils.php';
        $movementClassList = stic_PaymentsUtils::filterMovementClassListForSelectedOrg($movementClassList, $selectedOrgKey);

        $selectedOrgKey = '_' . $selectedOrgKey;
        // Validate organization-specific settings and record any that are missing
        foreach ($neededSettings as $key) {
            if (stic_SettingsUtils::getSetting($key . $selectedOrgKey) == '') {
                $missingSettings[] = $key . $selectedOrgKey;
            }
        }
        // Check that dynamic field 'stic_m182_amount{lower(orgKey)}_c' exists and is decimal in Contacts and Accounts
        $dynamicField = 'stic_m182_amount' . strtolower($selectedOrgKey) . '_c';

        // Contacts
        $contactBean = BeanFactory::newBean('Contacts');
        $contactsLabel = translate('LBL_MODULE_NAME', 'Contacts');
        if (!isset($contactBean->field_defs[$dynamicField])) {
            $missingFields[] = $contactsLabel . ': ' . $dynamicField;
        } else {
            $def = $contactBean->field_defs[$dynamicField];
            $type = isset($def['type']) ? $def['type'] : '';
            if ($type !== 'decimal') {
                $missingFields[] = $contactsLabel . ': ' . $dynamicField . ' ' . $mod_strings['LBL_M182_MISSING_FIELDS_WRONG_TYPE'];
            }
        }

        // Accounts
        $accountBean = BeanFactory::newBean('Accounts');
        $accountsLabel = translate('LBL_MODULE_NAME', 'Accounts');
        if (!isset($accountBean->field_defs[$dynamicField])) {
            $missingFields[] = $accountsLabel . ': ' . $dynamicField;
        } else {
            $def = $accountBean->field_defs[$dynamicField];
            $type = isset($def['type']) ? $def['type'] : '';
            if ($type !== 'decimal') {
                $missingFields[] = $accountsLabel . ': ' . $dynamicField . ' ' . $mod_strings['LBL_M182_MISSING_FIELDS_WRONG_TYPE'];
            }
        }

        return $selectedOrgKey;
    }


}
