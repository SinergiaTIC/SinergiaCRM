<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

interface TTSProviderInterface
{
    public function getId();
    public function getName();
    public function isConfigured();
    public function getSupportedLanguages();
    public function getSupportedVoices($language = null);
    public function getRestEndpoint($config = array());
    public function getAuthHeaders($apiKey, $config = array());
    public function getRequestBody($text, $config = array());
    public function getQueryParams($config = array());
    public function mapLanguage($language);
    public function mapVoice($voice, $language);
    public function getMaxCharsPerRequest();
    public function validateCredentials($apiKey, $config = array());
    public function synthesize($text, $config = array());
}
