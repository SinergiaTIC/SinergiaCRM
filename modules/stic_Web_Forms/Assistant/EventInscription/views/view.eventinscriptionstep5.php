<?php

require_once 'modules/stic_Web_Forms/Assistant/AssistantView.php';

class ViewEventinscriptionstep5 extends stic_Web_FormsAssistantView
{
    /**
     * Do what is needed before showing the view
     */
    public function preDisplay()
    {
        parent::preDisplay();

        $this->templateDir = "modules/stic_Web_Forms/Assistant/EventInscription/tpls";
        $this->tpl = "Step5.tpl";

        // Email Templates popup
        $popup_request_data = array
            (
            'call_back_function' => 'set_return',
            'form_name' => 'webforms',
            'field_to_name_array' => array(
                'id' => 'email_template_id',
                'name' => 'email_template_name',
            ),
        );

        $this->view_object_map['EMAIL_TEMPLATES_POPUP_REQ_DATA'] = json_encode($popup_request_data);

        // Users Popup
        $popup_request_data = array
            (
            'call_back_function' => 'set_return',
            'form_name' => 'webforms',
            'field_to_name_array' => array(
                'id' => 'assigned_user_id',
                'user_name' => 'assigned_user_name',
            ),
        );

        $this->view_object_map['USERS_POPUP_REQ_DATA'] = json_encode($popup_request_data);

        // Events popup
        $popup_request_data = array
            (
            'call_back_function' => 'set_return',
            'form_name' => 'webforms',
            'field_to_name_array' => array(
                'id' => 'event_id',
                'name' => 'event_name',
            ),
        );
        $this->view_object_map['EVENTS_POPUP_REQ_DATA'] = json_encode($popup_request_data);
    }

    /**
     * Display the view
     */
    public function display()
    {
        global $app_strings, $mod_strings;
        parent::display();

        $javascript = new javascript();
        $javascript->setFormName('webforms');
        $javascript->addFieldGeneric('event_name', '', javascript_escape($mod_strings['LBL_WEBFORMS_NOTIFY_EVENT']), 'true');
        $javascript->addFieldGeneric('email_template_name', '', javascript_escape($mod_strings['LBL_NOTIFICATION_EMAIL_TEMPLATE']), 'false');
        $javascript->addFieldGeneric('assigned_user_name', '', javascript_escape($app_strings['LBL_ASSIGNED_TO']), 'true');
        $javascript->addFieldGeneric('validate_identification_number', '', javascript_escape($app_strings['LBL_CHECK_IDENTIFICATION_NUMBER']), 'true');
        $javascript->addFieldGeneric('redirect_ok_url', '', javascript_escape($mod_strings['LBL_WEBFORMS_REDIRECT_OK_URL']), 'true');
        $javascript->addFieldGeneric('redirect_ko_url', '', javascript_escape($mod_strings['LBL_WEBFORMS_REDIRECT_KO_URL']), 'true');
        if ($this->view_object_map['PERSISTENT_DATA']['include_payment_commitment']) {
            $javascript->addFieldGeneric('payment_type', '', javascript_escape($mod_strings['LBL_WEBFORMS_PAYMENT_TYPE']), 'true');
        }

        $javascript->addToValidateBinaryDependency('event_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . javascript_escape($mod_strings['LBL_WEBFORMS_NOTIFY_EVENT'] . ':'), 'false', '', 'event_id');
        $javascript->addToValidateBinaryDependency('email_template_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . javascript_escape($mod_strings['LBL_WEBFORMS_NOTIFY_EVENT'] . ':'), 'false', '', 'email_template_id');
        $javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . javascript_escape($app_strings['LBL_ASSIGNED_TO']), 'false', '', 'assigned_user_id');

        echo $javascript->getScript();
    }
}
