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
    ttsOutputJson(array('success' => false, 'error' => 'Datos JSON inválidos o faltantes.'), 400);
    return;
}

$module = $data['module'] ?? '';
$record = $data['record'] ?? '';
$scenario = $data['scenario'] ?? 'a';
$fragmentIndex = isset($data['fragmentIndex']) ? (int)$data['fragmentIndex'] : 0;
$fields = $data['fields'] ?? array();
$text = $data['text'] ?? '';
$language = $data['language'] ?? (stic_SettingsUtils::getSetting('TTS_DEFAULT_LANGUAGE') ?: 'es');
$listContext = $data['listContext'] ?? null;

if (empty($module)) {
    ttsOutputJson(array('success' => false, 'error' => 'Módulo requerido.'), 400);
    return;
}

$enabled = stic_SettingsUtils::getSetting('TTS_ENABLED');
if ($enabled === false || $enabled === '' || $enabled === '0') {
    ttsOutputJson(array('success' => false, 'error' => 'TTS desactivado.'), 403);
    return;
}

$manager = TTSProviderManager::getInstance();
$provider = $manager->getActiveProvider();
if (!$provider || !$provider->isConfigured()) {
    ttsOutputJson(array('success' => false, 'error' => 'Proveedor TTS no configurado.'), 503);
    return;
}

session_write_close();

$dailyLimit = stic_SettingsUtils::getSetting('TTS_DAILY_CHAR_LIMIT');
$timeLimit = stic_SettingsUtils::getSetting('TTS_DAILY_TIME_LIMIT');
if (($dailyLimit !== false && $dailyLimit !== '-1' && is_numeric($dailyLimit) && (int)$dailyLimit > 0)
    || ($timeLimit !== false && $timeLimit !== '-1' && is_numeric($timeLimit) && (int)$timeLimit > 0)) {
    $used = ttsGetDailyUsage($current_user->id);
    if ($dailyLimit !== false && $dailyLimit !== '-1' && is_numeric($dailyLimit) && (int)$dailyLimit > 0) {
        $limit = (int)$dailyLimit;
        if ($used >= $limit) {
            ttsOutputJson(array('success' => false, 'error' => 'Límite diario de caracteres alcanzado.'), 429);
            return;
        }
    }
    if ($timeLimit !== false && $timeLimit !== '-1' && is_numeric($timeLimit) && (int)$timeLimit > 0) {
        $limitMin = (int)$timeLimit;
        $usedMin = (int)($used / 10 / 60);
        if ($usedMin >= $limitMin) {
            ttsOutputJson(array('success' => false, 'error' => 'Límite diario de tiempo alcanzado.'), 429);
            return;
        }
    }
}

$assembler = new TtsTextAssembler();
$synthesizeText = '';

switch ($scenario) {
    case 'a':
        if (empty($text)) {
            ttsOutputJson(array('success' => false, 'error' => 'No se proporcionó texto.'), 400);
            return;
        }
        $synthesizeText = $assembler->buildFromText($text);
        if (!empty($record)) {
            $bean = BeanFactory::getBean($module, $record);
        }
        break;

    case 'b':
        if (empty($record) || empty($fields)) {
            ttsOutputJson(array('success' => false, 'error' => 'ID de registro y campos requeridos.'), 400);
            return;
        }
        $bean = BeanFactory::getBean($module, $record);
        if (!$bean || !$bean->id) {
            ttsOutputJson(array('success' => false, 'error' => 'Registro no encontrado.'), 404);
            return;
        }
        if (!$bean->ACLAccess('view')) {
            ttsOutputJson(array('success' => false, 'error' => 'Acceso denegado.'), 403);
            return;
        }
        $synthesizeText = $assembler->buildFromBean($bean, $fields, $language);
        break;

    case 'c':
        if ($listContext === null) {
            ttsOutputJson(array('success' => false, 'error' => 'Contexto de lista requerido.'), 400);
            return;
        }
        $listContext['module'] = $module;
        require_once 'custom/include/TextToSpeech/Entrypoints/ttsListviewOrder.php';
        $orderHelper = new TtsListviewOrder();
        $orderedIds = $orderHelper->getOrderedIds($listContext);

        if (empty($orderedIds) || $fragmentIndex >= count($orderedIds)) {
            ttsOutputJson(array('success' => false, 'error' => 'No hay más registros.'), 404);
            return;
        }

        $targetId = $orderedIds[$fragmentIndex];
        $bean = BeanFactory::getBean($module, $targetId);
        if (!$bean || !$bean->id) {
            ttsOutputJson(array('success' => false, 'error' => 'Registro no encontrado.'), 404);
            return;
        }
        if (!$bean->ACLAccess('view')) {
            ttsOutputJson(array('success' => false, 'error' => 'Acceso denegado.'), 403);
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
        ttsOutputJson(array('success' => false, 'error' => 'Escenario inválido.'), 400);
        return;
}

if (empty(trim($synthesizeText))) {
    ttsOutputJson(array('success' => false, 'error' => 'Sin contenido para sintetizar.'), 204);
    return;
}

$recordName = '';
if (isset($bean) && !empty($bean->id)) {
    $recordName = !empty($bean->name) ? $bean->name : $bean->get_summary_text();
}

@ob_end_clean();
while (ob_get_level() > 0) ob_end_clean();

$config = array('language' => $language, 'encoding' => 'mp3');
$result = $provider->synthesizeStreamed($synthesizeText, $config, $recordName);

if ($result === null) {
    ttsOutputJson(array('success' => false, 'error' => 'Error al sintetizar.'), 500);
    return;
}

if (!empty($result['charCount'])) {
    $id = create_guid();
    $safeUserId = $db->quote($current_user->id);
    $safeCharCount = (int)$result['charCount'];
    $safeLanguage = $db->quote($language);
    $safeModule = $db->quote($module);
    $safeProvider = $db->quote($provider->getId());
    $safeScenario = $db->quote($scenario);
    $safeRecordId = $db->quote($record ?? '');
    $createdAt = gmdate('Y-m-d H:i:s');
    $sql = "INSERT INTO tts_usage (id, user_id, created_at, char_count, language, module, provider, scenario, record_id)
            VALUES ('{$id}', '{$safeUserId}', '{$createdAt}', {$safeCharCount}, '{$safeLanguage}', '{$safeModule}', '{$safeProvider}', '{$safeScenario}', '{$safeRecordId}')";
    $db->query($sql);
}

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
