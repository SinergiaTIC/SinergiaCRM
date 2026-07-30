<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'custom/include/TextToSpeech/ttsCsrfUtils.php';

global $current_user, $db;

$validation = ttsValidateRequest(array('json_input' => true));
if (!$validation['valid']) {
    ttsOutputJson(array('success' => false, 'error' => $validation['error']), $validation['code']);
    return;
}

$input = $validation['data'];
$module = isset($input['module']) ? $input['module'] : '';
$uids = isset($input['uids']) ? $input['uids'] : array();

if (empty($module) || empty($uids) || !is_array($uids)) {
    ttsOutputJson(array('success' => false, 'error' => 'Parámetros inválidos.'), 400);
    return;
}

$seed = BeanFactory::getBean($module);
if (!$seed) {
    ttsOutputJson(array('success' => false, 'error' => 'Módulo inválido.'), 400);
    return;
}

$names = array();
foreach ($uids as $uid) {
    $bean = BeanFactory::getBean($module, $uid);
    if ($bean && $bean->id) {
        $names[$uid] = $bean->get_summary_text();
    } else {
        $names[$uid] = '';
    }
}

ttsOutputJson($names);
