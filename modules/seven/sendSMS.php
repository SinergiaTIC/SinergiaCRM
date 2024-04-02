<?php
require_once 'seven.php';

$sms = new seven;

$number = $_POST['number'] ?? '';
$id = $_POST['id'] ?? '';
$module = $_POST['module'] ?? '';

if (!empty($number)) {
    if (!empty($module) && !empty($id)) {
        $bean = BeanFactory::getBean($module);
        $sms->setRelation($bean->retrieve($id));
    }

    $sms->setNumber($number);
    $res = $res = $sms->sendSMS();

    if (!$sms->isUserFriendlyResponses()) {
        echo json_encode($res);
        return;
    }

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
