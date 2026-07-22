<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'modules/stic_Settings/Utils.php';
require_once 'custom/include/TextToSpeech/ttsCsrfUtils.php';
require_once 'custom/include/TextToSpeech/TTSProviderManager.php';
require_once 'custom/include/TextToSpeech/Entrypoints/ttsTextAssembler.php';

global $current_user, $db;

$validation = ttsValidateRequest(array('json_input' => true));
if (!$validation['valid']) {
    ttsOutputJson(array('success' => false, 'error' => $validation['error']), $validation['code']);
    return;
}

$data = $validation['data'];
if ($data === null) {
    ttsOutputJson(array('success' => false, 'error' => 'Invalid or missing JSON data'), 400);
    return;
}

$module = $data['module'] ?? '';
$record = $data['record'] ?? '';
$scenario = $data['scenario'] ?? 'a';
$fragmentIndex = isset($data['fragmentIndex']) ? (int)$data['fragmentIndex'] : 0;
$fields = $data['fields'] ?? array();
$text = $data['text'] ?? '';
$language = $data['language'] ?? 'es';
$listContext = $data['listContext'] ?? null;

if (empty($module)) {
    ttsOutputJson(array('success' => false, 'error' => 'Module is required'), 400);
    return;
}

$enabled = stic_SettingsUtils::getSetting('TTS_ENABLED');
if ($enabled === false || $enabled === '' || $enabled === '0') {
    ttsOutputJson(array('success' => false, 'error' => 'TTS is disabled'), 403);
    return;
}

$manager = TTSProviderManager::getInstance();
$provider = $manager->getActiveProvider();
if (!$provider || !$provider->isConfigured()) {
    ttsOutputJson(array('success' => false, 'error' => 'TTS provider not configured'), 503);
    return;
}

session_write_close();

$dailyLimit = stic_SettingsUtils::getSetting('TTS_DAILY_CHAR_LIMIT');
if ($dailyLimit !== false && $dailyLimit !== '-1' && is_numeric($dailyLimit) && (int)$dailyLimit > 0) {
    $limit = (int)$dailyLimit;
    $used = ttsGetDailyUsage($current_user->id);
    if ($used >= $limit) {
        ttsOutputJson(array('success' => false, 'error' => 'Daily character limit reached'), 429);
        return;
    }
}

$assembler = new TtsTextAssembler();
$synthesizeText = '';

switch ($scenario) {
    case 'a':
        if (empty($text)) {
            ttsOutputJson(array('success' => false, 'error' => 'No text provided'), 400);
            return;
        }
        $synthesizeText = $assembler->buildFromText($text);
        break;

    case 'b':
        if (empty($record) || empty($fields)) {
            ttsOutputJson(array('success' => false, 'error' => 'Record ID and fields required'), 400);
            return;
        }
        $bean = BeanFactory::getBean($module, $record);
        if (!$bean || !$bean->id) {
            ttsOutputJson(array('success' => false, 'error' => 'Record not found'), 404);
            return;
        }
        if (!$bean->ACLAccess('view')) {
            ttsOutputJson(array('success' => false, 'error' => 'Access denied'), 403);
            return;
        }
        $synthesizeText = $assembler->buildFromBean($bean, $fields, $language);
        break;

    case 'c':
        if ($listContext === null) {
            ttsOutputJson(array('success' => false, 'error' => 'List context required'), 400);
            return;
        }
        $listContext['module'] = $module;
        require_once 'custom/include/TextToSpeech/Entrypoints/ttsListviewOrder.php';
        $orderHelper = new TtsListviewOrder();
        $orderedIds = $orderHelper->getOrderedIds($listContext);

        if (empty($orderedIds) || $fragmentIndex >= count($orderedIds)) {
            ttsOutputJson(array('success' => false, 'error' => 'No more records'), 404);
            return;
        }

        $targetId = $orderedIds[$fragmentIndex];
        $bean = BeanFactory::getBean($module, $targetId);
        if (!$bean || !$bean->id) {
            ttsOutputJson(array('success' => false, 'error' => 'Record not found'), 404);
            return;
        }
        if (!$bean->ACLAccess('view')) {
            ttsOutputJson(array('success' => false, 'error' => 'Access denied'), 403);
            return;
        }

        $separator = stic_SettingsUtils::getSetting('TTS_LIST_SEPARATOR');
        if ($separator === false || $separator === '') {
            $separator = 'Registro siguiente.';
        }

        $recordText = $assembler->buildFromBean($bean, $fields, $language);
        if ($fragmentIndex > 0) {
            $recordText = $separator . ' ' . $recordText;
        }
        $synthesizeText = $recordText;
        break;

    default:
        ttsOutputJson(array('success' => false, 'error' => 'Invalid scenario'), 400);
        return;
}

if (empty(trim($synthesizeText))) {
    ttsOutputJson(array('success' => false, 'error' => 'No content to synthesize'), 204);
    return;
}

$config = array('language' => $language, 'encoding' => 'mp3');
$result = $provider->synthesize($synthesizeText, $config);

if ($result === null) {
    ttsOutputJson(array('success' => false, 'error' => 'Synthesis failed'), 500);
    return;
}

$charCount = $result['charCount'];
$recordName = '';
if (isset($bean) && !empty($bean->id)) {
    $recordName = !empty($bean->name) ? $bean->name : $bean->get_summary_text();
}

@ob_end_clean();
ob_start();
ob_clean();

http_response_code(200);
header('Content-Type: audio/mpeg');
header('X-TTS-Char-Count: ' . $charCount);
if (!empty($recordName)) {
    header('X-TTS-Record-Name: ' . base64_encode($recordName));
}
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

echo $result['audio'];
ob_flush();

function ttsGetDailyUsage($userId)
{
    global $db;
    $safeUserId = $db->quote($userId);
    $today = gmdate('Y-m-d');
    $sql = "SELECT COALESCE(SUM(char_count), 0) FROM tts_usage
            WHERE user_id = '{$safeUserId}' AND DATE(created_at) = '{$today}'";
    $result = $db->getOne($sql);
    return (int)$result;
}
