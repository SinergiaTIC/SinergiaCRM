<?php

if (file_exists('modules/AOS_PDF_Templates/controller.php')) {
    require 'modules/AOS_PDF_Templates/controller.php';
    if(class_exists('AOS_PDF_TemplatesController')) {
        class DynamicParent extends AOS_PDF_TemplatesController {}
    } else {
        class DynamicParent extends SugarController{}
    }
} else {
    class DynamicParent extends SugarController{}
}

class CustomAOS_PDF_TemplatesController extends DynamicParent
{
    public function action_AddPDFLinkToEmail()
    {
        
        global $current_user, $mod_strings, $sugar_config, $timedate;

        // Retrieve module name
        $moduleName = $_REQUEST['targetModule'];
        if (!$moduleName) {
            sugar_die("Module name not provided");
        }

        // Retrieve the record and template beans
        $bean = BeanFactory::getBean($moduleName, $_REQUEST['uid']);
        if (!$bean) {
            sugar_die("Invalid Record");
        }

        $templateBean = BeanFactory::getBean('AOS_PDF_Templates', $_REQUEST['templateID']);
        if (!$templateBean) {
            sugar_die("Invalid Template");
        }

        // Create an email draft
        $email = BeanFactory::newBean('Emails');
        // Set the bean id for use in relationships
        $email->id = create_guid();
        $email->new_with_id = true;
        // Set type/status as draft
        $email->type = "draft";
        $email->status = "draft";
        // Set subject
        $email->name = $templateBean->name . ' - '. $bean->name;
        // Set body
        $email->description = $mod_strings['LBL_BODY_DESCRIPTION_PDF_URL'];
        
        $linkDownloadPdf = $sugar_config['site_url'].'/index.php?entryPoint=sticGeneratePdf&task=pdf&module='.$moduleName.'&uid='.$bean->id.'&templateID='.$templateBean->id;
        $linkDownloadPdf = '<a href="'.$linkDownloadPdf.'">'.$linkDownloadPdf.'</a>';
        $email->description .= $linkDownloadPdf;

        $inboundEmailID = $current_user->getPreference('defaultIEAccount', 'Emails');
        $email->mailbox_id = $inboundEmailID;

        // Relate the email to the record
        $email->parent_type = $bean->module_name;
        $email->parent_id = $bean->id;

        // Get record's primary email address
        $emailAddress = $bean->emailAddress->getPrimaryAddress($bean);
        if (!empty($emailAddress)) {
            $email->to_addrs_emails = $emailAddress . ";";
            $email->to_addrs = $bean->name . " <" . $emailAddress . ">";
            $email->to_addrs_names = $bean->name . " <" . $emailAddress . ">";
            $email->parent_name = $bean->name;
        }

        // Assign other data
        $email->team_id = $current_user->default_team;
        $email->assigned_user_id = $current_user->id;
        $email->date_start = $timedate->to_display_date_time(gmdate($GLOBALS['timedate']->get_db_date_time_format()));
        $email->save(false);

        // Redirect to Compose Email View screen
        header('Location: index.php?action=ComposeView&module=Emails&return_module=' . $bean->module_dir . '&return_action=DetailView&return_id=' . $bean->id . '&record=' . $email->id);
    }
}
