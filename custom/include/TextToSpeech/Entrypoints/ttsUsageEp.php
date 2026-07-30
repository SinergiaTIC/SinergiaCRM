<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'modules/stic_Settings/Utils.php';
require_once 'custom/include/TextToSpeech/ttsCsrfUtils.php';

global $current_user, $db;

$validation = ttsValidateRequest(array('json_input' => true));
if (!$validation['valid']) {
    ttsOutputJson(array('success' => false, 'error' => $validation['error']), $validation['code']);
    return;
}

$data = $validation['data'];
if ($data === null) {
    ttsOutputJson(array('success' => false, 'error' => 'Datos JSON inválidos o faltantes.'), 400);
    return;
}

$charCount = isset($data['charCount']) ? (int)$data['charCount'] : 0;
$language = $data['language'] ?? '';
$module = $data['module'] ?? '';
$provider = $data['provider'] ?? '';
$scenario = $data['scenario'] ?? '';
$recordId = $data['recordId'] ?? '';

if ($charCount < 1 || $charCount > 100000) {
    ttsOutputJson(array('success' => false, 'error' => 'Recuento de caracteres inválido.'), 400);
    return;
}

$dailyLimit = stic_SettingsUtils::getSetting('TTS_DAILY_CHAR_LIMIT');
$wasLimited = false;
$remaining = -1;

if ($dailyLimit !== false && $dailyLimit !== '-1' && is_numeric($dailyLimit) && (int)$dailyLimit > 0) {
    $limit = (int)$dailyLimit;
    $today = gmdate('Y-m-d');
    $safeUserId = $db->quote($current_user->id);
    $sql = "SELECT COALESCE(SUM(char_count), 0) FROM tts_usage
            WHERE user_id = '{$safeUserId}' AND DATE(created_at) = '{$today}'";
    $usedToday = (int)$db->getOne($sql);
    $remaining = $limit - $usedToday;

    if ($remaining <= 0) {
        ttsOutputJson(array(
            'success' => false, 'error' => 'Límite diario de caracteres alcanzado.',
            'remaining' => 0, 'limit' => $limit,
        ), 429);
        return;
    }

    if ($charCount > $remaining) {
        $charCount = $remaining;
        $wasLimited = true;
    }
}

$id = create_guid();
$safeUserId = $db->quote($current_user->id);
$safeCharCount = (int)$charCount;
$safeLanguage = $db->quote($language);
$safeModule = $db->quote($module);
$safeProvider = $db->quote($provider);
$safeScenario = $db->quote($scenario);
$safeRecordId = $db->quote($recordId);
$createdAt = gmdate('Y-m-d H:i:s');

$sql = "INSERT INTO tts_usage (id, user_id, created_at, char_count, language, module, provider, scenario, record_id)
        VALUES ('{$id}', '{$safeUserId}', '{$createdAt}', {$safeCharCount}, '{$safeLanguage}', '{$safeModule}', '{$safeProvider}', '{$safeScenario}', '{$safeRecordId}')";

$result = $db->query($sql);
if (!$result) {
    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Failed to insert usage record');
    ttsOutputJson(array('success' => false, 'error' => 'Error al registrar el uso.'), 500);
    return;
}

$response = array(
    'success' => true,
    'used' => $safeCharCount,
    'remaining' => $remaining,
);

if ($wasLimited) {
    $response['was_limited'] = true;
}
if ($dailyLimit !== false && $dailyLimit !== '-1' && is_numeric($dailyLimit)) {
    $response['limit'] = (int)$dailyLimit;
}

ttsOutputJson($response, 200);
