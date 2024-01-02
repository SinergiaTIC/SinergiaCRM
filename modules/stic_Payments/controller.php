<?php

class stic_PaymentsController extends SugarController
{

    /**
     * Show the M182 wizard
     *
     * @return void
     */
    public function action_model182Wizard()
    {
        global $app_list_strings;

        // Check settings needing for m182
        require_once 'modules/stic_Settings/Utils.php';
        $missingSettings = array();
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
        foreach ($neededSettings as $key) {
            if (stic_SettingsUtils::getSetting($key) == '') {
                $missingSettings[] = $key;
            }
        }

        // We read the drop-down list of payment types
        $movementClassList = $app_list_strings['stic_payments_types_list'];

        // We divide the associative array into two arrays, one that contains the labels and another that contains the internal value
        $i = '0';
        foreach ($movementClassList as $x => $xValue) {
            if ($x != '') {
                $listLabel[$i] = $x;
                $listIntern[$i] = $xValue;
                $i++;
            }
        }

        // Call to the smarty template
        $this->view = "m182wizard";
        $this->view_object_map['MISSING_SETTINGS'] = $missingSettings;
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
     * Directly call of the function stic_PaymentsUtils::createCurrentMonthRecurringPayments using a URL like
     * <server_url>/index.php?module=stic_Payments&action=createCurrentMonthRecurringPayments
     *
     * @return void
     */
    public function action_createCurrentMonthRecurringPayments()
    {
        require_once 'modules/stic_Payments/Utils.php';
        stic_PaymentsUtils::createCurrentMonthRecurringPayments();
        SugarApplication::redirect('index.php?module=stic_Payments&action=index');

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
