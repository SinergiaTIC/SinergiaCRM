<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

function ttsGetModulesConfig($settingName)
{
    $value = stic_SettingsUtils::getSetting($settingName);
    if (!empty($value)) {
        return $value;
    }
    global $db;
    $safeName = $db->quote($settingName);
    $sql = "SELECT description FROM stic_settings WHERE name = '{$safeName}' AND type = 'TTS' AND deleted = 0 LIMIT 1";
    $result = $db->getOne($sql);
    if ($result !== false && strlen($result) > 0) {
        return trim($result);
    }
    return false;
}

class ttsInjectorHook
{
    public function injectTtsAssets()
    {
        if (!class_exists('stic_SettingsUtils') || !method_exists('stic_SettingsUtils', 'getSetting')) {
            return;
        }

        $enabled = stic_SettingsUtils::getSetting('TTS_ENABLED');
        if ($enabled === false || $enabled === '' || $enabled === '0') {
            return;
        }

        $apiKey = stic_SettingsUtils::getSetting('TTS_DEEPGRAM_API_KEY');
        if (empty($apiKey)) {
            return;
        }

        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        $module = isset($_REQUEST['module']) ? $_REQUEST['module'] : '';
        if (empty($module)) {
            return;
        }

        $textareaConfig = ttsGetModulesConfig('TTS_TEXTAREAS');
        $highlightConfig = ttsGetModulesConfig('TTS_HIGHLIGHT_FIELDS');
        if (empty($textareaConfig) && empty($highlightConfig)) {
            return;
        }

        $hasTextarea = false;
        $textareaFields = array();
        if (!empty($textareaConfig)) {
            foreach (explode(';', $textareaConfig) as $part) {
                $part = trim($part);
                if (strpos($part, ':') !== false) {
                    list($mod, $rawFields) = explode(':', $part, 2);
                    if (trim($mod) === $module) {
                        $hasTextarea = true;
                        $textareaFields = array_map('trim', explode(',', $rawFields));
                    }
                }
            }
        }

        $hasHighlight = false;
        $highlightFields = array();
        if (!empty($highlightConfig)) {
            foreach (explode(';', $highlightConfig) as $part) {
                $part = trim($part);
                if (strpos($part, ':') !== false) {
                    list($mod, $rawFields) = explode(':', $part, 2);
                    if (trim($mod) === $module) {
                        $hasHighlight = true;
                        $highlightFields = array_map('trim', explode(',', $rawFields));
                    }
                }
            }
        }

        if (!$hasTextarea && !$hasHighlight) {
            return;
        }

        $isDetailView = ($action === 'DetailView');
        $isEditView = ($action === 'EditView');
        $isListView = ($action === 'index');

        $defaultLanguage = stic_SettingsUtils::getSetting('TTS_DEFAULT_LANGUAGE') ?: 'es';
        $availableLanguages = stic_SettingsUtils::getSetting('TTS_AVAILABLE_LANGUAGES') ?: 'es,en,ca';

        $langLabels = array(
            'ca' => 'Català',
            'es' => 'Español',
            'en' => 'English',
            'eu' => 'Euskera',
            'gl' => 'Galego',
            'pt' => 'Português',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'it' => 'Italiano',
        );

        $langCodes = array_map('trim', explode(',', $availableLanguages));
        $languagesArray = array();
        foreach ($langCodes as $code) {
            $languagesArray[] = array('code' => $code, 'label' => $langLabels[$code] ?? strtoupper($code));
        }

        $safeModule = htmlspecialchars($module, ENT_QUOTES, 'UTF-8');
        $safeLanguage = htmlspecialchars($defaultLanguage, ENT_QUOTES, 'UTF-8');
        $safeTextareaFields = json_encode($textareaFields);
        $safeHighlightFields = json_encode($highlightFields);

        $config = array(
            'module' => $safeModule,
            'fields' => $highlightFields,
            'textareaFields' => $textareaFields,
            'defaultLanguage' => $safeLanguage,
            'availableLanguages' => $availableLanguages,
            'languages' => $languagesArray,
            'isDetailView' => $isDetailView,
            'isEditView' => $isEditView,
            'isListView' => $isListView,
            'hasTextarea' => $hasTextarea,
            'hasHighlight' => $hasHighlight,
        );
        $safeConfig = json_encode($config);

        echo '<script>var sticTtsConfig = ' . $safeConfig . ';</script>' . "\n";
        echo '<link rel="stylesheet" href="custom/themes/SinergiaCRMCustom/tts_client.css">' . "\n";
        echo '<script src="custom/include/TextToSpeech/javascript/tts_client.js"></script>' . "\n";
        echo '<script src="custom/include/TextToSpeech/javascript/tts_player.js"></script>' . "\n";
        echo '<script src="custom/include/TextToSpeech/javascript/tts_buttons.js"></script>' . "\n";
    }
}
