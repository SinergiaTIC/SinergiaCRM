<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'custom/include/TextToSpeech/providers/TTSProviderInterface.php';

abstract class TTSProviderBase implements TTSProviderInterface
{
    protected $id = '';
    protected $name = '';
    protected $supportedLanguages = array();

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isConfigured()
    {
        $apiKey = $this->getApiKey();
        return !empty($apiKey);
    }

    public function getSupportedLanguages()
    {
        return $this->supportedLanguages;
    }

    public function getSupportedVoices($language = null)
    {
        return array();
    }

    public function getAuthHeaders($apiKey, $config = array())
    {
        return array(
            'Authorization: Token ' . $apiKey,
            'Content-Type: application/json',
        );
    }

    public function getRequestBody($text, $config = array())
    {
        return json_encode(array('text' => $text));
    }

    public function getQueryParams($config = array())
    {
        return array();
    }

    public function mapLanguage($language)
    {
        return $language;
    }

    public function mapVoice($voice, $language)
    {
        return $voice;
    }

    public function getMaxCharsPerRequest()
    {
        return 2000;
    }

    public function validateCredentials($apiKey, $config = array())
    {
        if (empty($apiKey)) {
            return array(
                'valid' => false,
                'message' => 'API key is empty',
            );
        }
        return array(
            'valid' => true,
            'message' => 'API key is configured',
        );
    }

    public function getRestEndpoint($config = array())
    {
        return $this->getConfig('endpoint', '');
    }

    abstract public function synthesize($text, $config = array());

    protected function getApiKey()
    {
        require_once 'modules/stic_Settings/Utils.php';
        $prefix = 'TTS_' . strtoupper($this->id) . '_API_KEY';
        $value = stic_SettingsUtils::getSetting($prefix);
        if (!empty($value)) {
            return $value;
        }
        $direct = strtoupper($this->id) . '_API_KEY';
        return stic_SettingsUtils::getSetting($direct);
    }

    protected function getSetting($name)
    {
        require_once 'modules/stic_Settings/Utils.php';
        $full = 'TTS_' . strtoupper($this->id) . '_' . $name;
        $value = stic_SettingsUtils::getSetting($full);
        if (!empty($value) && $value !== false) {
            return $value;
        }
        $alt = strtoupper($this->id) . '_' . $name;
        return stic_SettingsUtils::getSetting($alt);
    }

    protected function getConfig($key, $default = null)
    {
        global $sugar_config;
        if (isset($sugar_config['deepgram_tts_' . $key])) {
            return $sugar_config['deepgram_tts_' . $key];
        }
        if (isset($sugar_config['tts_' . $key])) {
            return $sugar_config['tts_' . $key];
        }
        return $default;
    }

    protected function normalizeResponse($audio, $charCount, $fragments = 1)
    {
        return array(
            'audio' => $audio,
            'charCount' => (int)$charCount,
            'fragments' => (int)$fragments,
        );
    }
}
