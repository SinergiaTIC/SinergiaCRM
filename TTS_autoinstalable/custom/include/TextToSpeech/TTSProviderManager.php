<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'custom/include/TextToSpeech/providers/TTSProviderInterface.php';
require_once 'custom/include/TextToSpeech/ttsCsrfUtils.php';

class TTSProviderManager
{
    private static $instance = null;

    private $providers = array();
    private $providerInstances = array();
    private $activeProviderId = null;

    private function __construct()
    {
        $this->registerBuiltInProviders();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new TTSProviderManager();
        }
        return self::$instance;
    }

    private function registerBuiltInProviders()
    {
        $this->registerProvider(
            'deepgram',
            'ttsDeepgramProvider',
            'custom/include/TextToSpeech/providers/ttsDeepgramProvider.php'
        );

        $activeProvider = ttsGetConfigValue('TTS_PROVIDER', 'tts_provider', 'deepgram');
        if (!empty($activeProvider) && isset($this->providers[$activeProvider])) {
            $this->activeProviderId = $activeProvider;
        } elseif (!empty($this->providers)) {
            $this->activeProviderId = key($this->providers);
        }

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Built-in providers registered. Active: ' . ($this->activeProviderId ?? 'none'));
    }

    public function registerProvider($id, $className, $filePath)
    {
        if (isset($this->providers[$id])) {
            return false;
        }

        $this->providers[$id] = array(
            'className' => $className,
            'filePath' => $filePath,
        );
        unset($this->providerInstances[$id]);
        return true;
    }

    public function setActiveProvider($providerId)
    {
        if (!isset($this->providers[$providerId])) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Unknown provider ' . $providerId);
            return false;
        }
        $this->activeProviderId = $providerId;
        return true;
    }

    public function getActiveProvider()
    {
        if ($this->activeProviderId === null) {
            return null;
        }
        return $this->getProvider($this->activeProviderId);
    }

    public function getProvider($providerId)
    {
        if (!isset($this->providers[$providerId])) {
            return null;
        }
        if (isset($this->providerInstances[$providerId])) {
            return $this->providerInstances[$providerId];
        }

        $config = $this->providers[$providerId];
        $filePath = $config['filePath'];

        if (!file_exists($filePath)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': File not found: ' . $filePath);
            return null;
        }

        require_once $filePath;
        $className = $config['className'];

        if (!class_exists($className)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Class not found: ' . $className);
            return null;
        }

        $instance = new $className();
        if (!($instance instanceof TTSProviderInterface)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Does not implement TTSProviderInterface');
            return null;
        }

        $this->providerInstances[$providerId] = $instance;
        return $instance;
    }

    public function getProviders()
    {
        $result = array();
        foreach ($this->providers as $id => $config) {
            $provider = $this->getProvider($id);
            $result[] = array(
                'id' => $id,
                'name' => $provider ? $provider->getName() : $id,
                'configured' => $provider ? $provider->isConfigured() : false,
            );
        }
        return $result;
    }

    public function isAnyProviderConfigured()
    {
        foreach ($this->providers as $id => $config) {
            $provider = $this->getProvider($id);
            if ($provider && $provider->isConfigured()) {
                return true;
            }
        }
        return false;
    }

    public function getClientConfig()
    {
        $provider = $this->getActiveProvider();
        if ($provider === null) {
            return array('available' => false, 'reason' => 'No provider configured');
        }
        if (!$provider->isConfigured()) {
            return array('available' => false, 'reason' => 'Provider not configured');
        }

        require_once 'modules/stic_Settings/Utils.php';
        $defaultLanguage = stic_SettingsUtils::getSetting('TTS_DEFAULT_LANGUAGE') ?: 'es';
        $availableLanguages = stic_SettingsUtils::getSetting('TTS_AVAILABLE_LANGUAGES') ?: 'es,en,ca';
        $dailyLimit = stic_SettingsUtils::getSetting('TTS_DAILY_CHAR_LIMIT') ?: '50000';
        $maxRecords = stic_SettingsUtils::getSetting('TTS_MAX_RECORDS_LIST') ?: '50';

        return array(
            'available' => true,
            'provider' => $provider->getId(),
            'providerName' => $provider->getName(),
            'defaultLanguage' => $defaultLanguage,
            'availableLanguages' => array_map('trim', explode(',', $availableLanguages)),
            'dailyLimit' => (int)$dailyLimit,
            'maxRecords' => (int)$maxRecords,
            'maxCharsPerRequest' => $provider->getMaxCharsPerRequest(),
            'voices' => $provider->getSupportedVoices(),
        );
    }
}
