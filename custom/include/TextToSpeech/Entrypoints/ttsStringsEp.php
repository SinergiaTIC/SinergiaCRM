<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'custom/include/TextToSpeech/ttsCsrfUtils.php';

global $current_user, $app_strings;

$validation = ttsValidateRequest(array('allow_get' => true));
if (!$validation['valid']) {
    ttsOutputJson(array('success' => false, 'error' => $validation['error']), $validation['code']);
    return;
}

if (empty($current_user->id)) {
    ttsOutputJson(array('success' => false, 'error' => 'Authentication required'), 401);
    return;
}

$keyMap = array(
    'listen' => 'LBL_TTS_LISTEN',
    'listen_highlighted' => 'LBL_TTS_LISTEN_HIGHLIGHTED',
    'listen_mass' => 'LBL_TTS_LISTEN_MASS',
    'play' => 'LBL_TTS_PLAY',
    'pause' => 'LBL_TTS_PAUSE',
    'stop' => 'LBL_TTS_STOP',
    'next' => 'LBL_TTS_NEXT',
    'prev' => 'LBL_TTS_PREV',
    'speed' => 'LBL_TTS_SPEED',
    'progress' => 'LBL_TTS_PROGRESS',
    'error_generic' => 'LBL_TTS_ERROR_GENERIC',
    'error_empty' => 'LBL_TTS_ERROR_EMPTY',
    'error_limit' => 'LBL_TTS_ERROR_LIMIT',
    'error_no_selection' => 'LBL_TTS_ERROR_NO_SELECTION',
    'error_too_many' => 'LBL_TTS_ERROR_TOO_MANY',
    'loading' => 'LBL_TTS_LOADING',
    'speed_normal' => 'LBL_TTS_SPEED_NORMAL',
    'speed_fast' => 'LBL_TTS_SPEED_FAST',
    'speed_slow' => 'LBL_TTS_SPEED_SLOW',
    'now_playing' => 'LBL_TTS_NOW_PLAYING',
    'playlist' => 'LBL_TTS_PLAYLIST',
    'record' => 'LBL_TTS_RECORD',
    'seek' => 'LBL_TTS_SEEK',
    'language' => 'LBL_TTS_LANGUAGE',
);

$strings = array();
foreach ($keyMap as $shortKey => $lblKey) {
    $strings[$shortKey] = $app_strings[$lblKey] ?? $lblKey;
}

ttsOutputJson(array('success' => true, 'strings' => $strings), 200);
