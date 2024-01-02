<?php

// Ver el codigo de \modules\Emails\Compose.php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

if(isset($_REQUEST['recordId'])) {
   global $current_language;
   
   $emailData = getEmailData($_REQUEST['recordId']);
   
   $namePlusEmail = $emailData['toAddress_names'];
   $Email = $emailData['toAddress'];
  
   $ret = array(
      'to_email_addrs' => $namePlusEmail,
      'parent_type'    => $emailData['parent_type'],
      'parent_id'      => $emailData['parent_id'],
      'parent_name'    => $emailData['parent_name'],
      'subject'        => $emailData['subject'],
      'body'           => $emailData['body'],
      'attachments'    => $emailData['attachments'],
      'email_id'       => $emailData['email_id'],
   );
   
   initFullCompose($ret);
}

// ESTA FUNCION ES IDENTICA A LA QUE ENCONTRAMOS EN \modules\Emails\Compose.php
function initFullCompose($ret) {
   global $current_user;
   $json = getJSONobj();
   $composeOut = $json->encode($ret);

   //For listview 'Email' call initiated by subpanels, just returned the composePackage data, do not
   //include the entire Emails page
   if ( isset($_REQUEST['ajaxCall']) && $_REQUEST['ajaxCall']) {
      echo $composeOut;
   }
   else {
      //For normal full compose screen
      include('modules/Emails/index.php');
      echo "<script type='text/javascript' language='javascript'>\ncomposePackage = {$composeOut};\n</script>";
   }
}


// ESTA FUNCION ES CASI IDENTICA A LA FUNCION getQuotesRelatedData QUE ENCONTRAMOS EN \modules\Emails\Compose.php
function getEmailData($emailId) {
   $return = array();
   
   require_once("modules/Emails/EmailUI.php");
   $email = new Email();
   $email->retrieve($emailId);
   $return['subject'] = $email->name;
   $return['body'] = from_html($email->description_html);
   $return['toAddress'] = $email->to_addrs;   
   $ret = array();
   $ret['uid'] = $emailId;
   $ret = EmailUI::getDraftAttachments($ret);
   $return['attachments'] = $ret['attachments'];
   $return['email_id'] = $emailId;
   
   $return['toAddress_names'] = $email->to_addrs_names;
   $return['parent_id'] = $email->parent_id;
   $return['parent_type'] = $email->parent_type;
   $return['parent_name'] = $email->parent_name;
   
   return $return;
}
