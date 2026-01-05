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
        // Checking if user has m182 issuing organization assigned then using the key to get the parameters.
        require_once 'modules/stic_Payments/Utils.php';
        $orgKeyArray = stic_PaymentsUtils::getM182IssuingOrganizationKeyForCurrentUser();
        if (count($orgKeyArray) > 1 && empty($_REQUEST['issuing_organization_selected'])) {
            global $app_list_strings;
            // If user has organizations assigned and no issuing organization selectred, we redirect to select one
            include_once "modules/stic_Remittances/Utils.php";
            stic_RemittancesUtils::fillDynamicListForIssuingOrganizations(true);
            $orgLabelArray = array();
            foreach ($orgKeyArray as $value) {
                $orgKeyClean = str_replace('_', '', $value);
                if (isset($app_list_strings['dynamic_issuing_organization_list'][$orgKeyClean]) == false) {
                    continue;
                }
                $orgLabelArray[] = $app_list_strings['dynamic_issuing_organization_list'][$orgKeyClean];
            }
            $this->view = "m182selectissuingorganization";
            $this->view_object_map['ISSUING_ORGANIZATIONS_IDS'] = $orgKeyArray ?? array();
            $this->view_object_map['ISSUING_ORGANIZATIONS_LABELS'] = $orgLabelArray ?? array();
        } else {
            // if $orgKeyArray has exactly one element, we use it
            if (count($orgKeyArray) == 1 && empty($_REQUEST['issuing_organization_selected'])) {
                $_REQUEST['issuing_organization_selected'] = $orgKeyArray[0];
            }
            // If user has no organization assigned or has selected one, we go to the wizard
            $this->action_model182Wizard();
        }
    }

    /**
     * Show the M182 wizard
     *
     * @return void
     */
    public function action_model182Wizard() {
        global $app_list_strings, $mod_strings;

        // Check settings needing for m182
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

        // Use only the issuing organization explicitly selected in the request for filtering
        $selectedOrgKey = '';
        if (!empty($_REQUEST['issuing_organization_selected'])) {
            $selectedOrgKey = (string) $_REQUEST['issuing_organization_selected'];
        }
        // We read the drop-down list of payment types
        $movementClassList = $app_list_strings['stic_payments_types_list'];

        // If an issuing organization is explicitly selected, check its settings and filter payment types by that suffix
        if ($selectedOrgKey !== '') {
            foreach ($neededSettings as $key) {
                if (stic_SettingsUtils::getSetting($key . $selectedOrgKey) == '') {
                    $missingSettings[] = $key . $selectedOrgKey;
                }
            }

            $suf = strtolower($selectedOrgKey);
            $filteredMovementClassList = array();
            foreach ($movementClassList as $x => $xValue) {
                if ($x === '') {
                    continue;
                }
                $xLower = strtolower($x);
                $sufLen = strlen($suf);
                if ($sufLen > 0 && $sufLen <= strlen($xLower) && substr($xLower, -$sufLen) === $suf) {
                    $filteredMovementClassList[$x] = $xValue;
                }
            }
            $movementClassList = $filteredMovementClassList;

            // Check dynamic field exists in Contacts and Accounts: stic_m182_amount_{lower(orgKey)}_c and is decimal
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
                    $missingFields[] = $contactsLabel . ': ' . $dynamicField . ' ' .$mod_strings['LBL_M182_MISSING_FIELDS_WRONG_TYPE'];
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
                    $missingFields[] = $accountsLabel . ': ' . $dynamicField . ' ' .$mod_strings['LBL_M182_MISSING_FIELDS_WRONG_TYPE'];
                }
            }
        } else {
            // No org selected: check base settings
            foreach ($neededSettings as $key) {
                if (stic_SettingsUtils::getSetting($key) == '') {
                    $missingSettings[] = $key;
                }
            }
        }

        // Build label and internal arrays for the template
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
        // We get the Organization label of the issuing organization selected
        include_once "modules/stic_Remittances/Utils.php";
        stic_RemittancesUtils::fillDynamicListForIssuingOrganizations(true);
        $orgLabel = '';
        $orgKeyClean = '';
        if ($selectedOrgKey !== '') {
            $orgKeyClean = str_replace('_', '', $selectedOrgKey);
            global $app_list_strings;
            $orgLabel = $app_list_strings['dynamic_issuing_organization_list'][$orgKeyClean] ?? '';
        }
        $this->view_object_map['ISSUING_ORGANIZATION_LABEL'] = $orgLabel;
        $this->view_object_map['ISSUING_ORGANIZATION_KEY'] = $orgKeyClean;

        // Call to the smarty template
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
        // $formatedBody = $this->applyInlineStyles($this->body);
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

}
