<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'custom/include/TextToSpeech/providers/TTSProviderInterface.php';
require_once 'custom/include/TextToSpeech/providers/TTSProviderBase.php';
require_once 'custom/include/TextToSpeech/providers/ttsTextFragmenter.php';

class ttsDeepgramProvider extends TTSProviderBase
{
    protected $id = 'deepgram';
    protected $name = 'Deepgram';

    protected $supportedLanguages = array('es', 'en', 'ca');

    private $voices = array(
        'en' => 'aura-2-thalia-en',
        'es' => 'aura-2-alvaro-es',
        'ca' => 'aura-2-alvaro-es',
    );

    private $defaultEndpoint = 'https://api.eu.deepgram.com/v1/speak';

    public function getRestEndpoint($config = array())
    {
        $endpoint = $this->getConfig('endpoint', $this->defaultEndpoint);
        $language = isset($config['language']) ? $config['language'] : 'es';
        $voice = isset($config['voice']) ? $config['voice'] : '';

        $params = $this->getQueryParams($config);
        return $endpoint . '?' . http_build_query($params);
    }

    public function getQueryParams($config = array())
    {
        $language = isset($config['language']) ? $config['language'] : 'es';
        $voice = isset($config['voice']) ? $config['voice'] : '';
        $encoding = isset($config['encoding']) ? $config['encoding'] : 'mp3';

        return array(
            'model' => $this->buildModelName($voice, $language),
            'encoding' => $encoding,
        );
    }

    public function getMaxCharsPerRequest()
    {
        $configValue = $this->getConfig('max_chars_per_request', 2000);
        return (int)$configValue;
    }

    public function mapLanguage($language)
    {
        $langMap = array(
            'es' => 'es',
            'en' => 'en',
            'ca' => 'ca',
        );
        return isset($langMap[$language]) ? $langMap[$language] : $language;
    }

    public function mapVoice($voice, $language)
    {
        if (!empty($voice)) {
            return $voice;
        }
        return isset($this->voices[$language]) ? $this->voices[$language] : '';
    }

    public function getSupportedVoices($language = null)
    {
        if ($language !== null && isset($this->voices[$language])) {
            return array($this->voices[$language]);
        }
        return array_values($this->voices);
    }

    public function validateCredentials($apiKey, $config = array())
    {
        if (empty($apiKey)) {
            return array('valid' => false, 'message' => 'Deepgram TTS API key is empty');
        }
        $result = $this->testEndpoint($apiKey);
        if ($result !== null) {
            return array('valid' => true, 'message' => 'Deepgram TTS credentials are valid');
        }
        return array('valid' => false, 'message' => 'Failed to connect to Deepgram TTS endpoint');
    }

    public function synthesize($text, $config = array())
    {
        $maxChars = $this->getMaxCharsPerRequest();
        $fragmenter = new TtsTextFragmenter($maxChars);
        $fragments = $fragmenter->fragment($text);

        $apiKey = $this->getApiKey();
        if (empty($apiKey)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Deepgram API key not configured');
            return null;
        }

        $totalAudio = '';
        $totalChars = 0;

        foreach ($fragments as $index => $fragment) {
            $result = $this->synthesizeFragment($fragment, $apiKey, $config);
            if ($result === null) {
                if ($index > 0) {
                    break;
                }
                return null;
            }
            $totalAudio .= $result['audio'];
            $totalChars += $result['charCount'];
        }

        return $this->normalizeResponse($totalAudio, $totalChars, count($fragments));
    }

    private function synthesizeFragment($text, $apiKey, $config)
    {
        $url = $this->getRestEndpoint($config);
        $body = $this->getRequestBody($text, $config);
        $headers = $this->getAuthHeaders($apiKey, $config);

        $timeout = (int)$this->getConfig('curl_timeout', 30);

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HEADER => true,
        ));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false || !empty($curlError)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': cURL error: ' . $curlError);
            return null;
        }

        if ($httpCode !== 200) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ': Deepgram returned HTTP ' . $httpCode);
            return null;
        }

        $headerStr = substr($response, 0, $headerSize);
        $audioBody = substr($response, $headerSize);

        $charCount = 0;
        if (preg_match('/^dg-char-count:\s*(\d+)/mi', $headerStr, $matches)) {
            $charCount = (int)$matches[1];
        } else {
            $charCount = mb_strlen($text);
        }

        return array('audio' => $audioBody, 'charCount' => $charCount);
    }

    private function buildModelName($voice, $language)
    {
        if (!empty($voice)) {
            return $voice;
        }
        $mapped = $this->mapVoice($voice, $language);
        if (!empty($mapped)) {
            return $mapped;
        }
        return 'aura-2-alvaro-' . $language;
    }

    private function testEndpoint($apiKey)
    {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://api.eu.deepgram.com/v1/speak?model=aura-2-alvaro-es&encoding=mp3',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(array('text' => 'test')),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token ' . $apiKey,
                'Content-Type: application/json',
            ),
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ));
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false || !empty($curlError)) {
            return null;
        }

        if ($httpCode === 200 || $httpCode === 400) {
            return true;
        }
        return null;
    }
}
