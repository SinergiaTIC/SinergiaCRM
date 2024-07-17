<?php
require_once 'seven.php';
// STIC-Custom EPS 20240404
require_once 'sendSMSfunctions.php';
// END STIC-Custom

$sms = new seven;

$number = $_POST['number'] ?? '';
$id = $_POST['id'] ?? '';
$module = $_POST['module'] ?? '';

if (!empty($number)) {
    if (!empty($module) && !empty($id)) {
        $bean = BeanFactory::getBean($module);
        $sms->setRelation($bean->retrieve($id));
    }

    // STIC-Custom EPS 20240404
    $templateId = $_POST['template'];
    // if (!empty($templateId)) {
    //     $emailTemp = BeanFactory::newBean('EmailTemplates');
    //     $emailTemp->retrieve($templateId);
    //     seven_parse_template($bean, $emailTemp);
    //     $text = $emailTemp->body;
    //     $_POST['message'] = $text;
    // }
    // else {
    //     $text = $_POST['message'];
    // }
    $emailTemp = sevenReplaceEmailVariables($_POST['message'], $templateId);
    $text = $emailTemp;
    $_POST['message'] = $text;
    // END STIC-Custom

    $sms->setNumber($number);
    // STIC-Custom EPS 20240404
    // $res = $res = $sms->sendSMS();
    $res = $res = $sms->sendSMSwithText($text);
    
    // if (!$sms->isUserFriendlyResponses()) {
    //     echo json_encode($res);
    //     return;
    // }
    $GLOBALS['log']->fatal('SEVEN response ' . __METHOD__ . __LINE__ , $res);
    // END STIC-Custom
    
    $json = $res[0];
    $count = 0;
    $price = $json['total_price'];
    foreach ($json['messages'] as $message) {
        if (!$message['success']) continue;
        $count++;
    }

    $text = (new \SuiteCRM\LangText)
        ->getText('LBL_SEVEN_USER_FRIENDLY_RESPONSES_SMS', compact('count', 'price'), null, 'seven');

    echo json_encode([$text, $res[1]]);
}


