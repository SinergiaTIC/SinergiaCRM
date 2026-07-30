<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

function ttsGetConfigValue($settingName, $configKey, $default = null)
{
    if (class_exists('stic_SettingsUtils') && method_exists('stic_SettingsUtils', 'getSetting')) {
        $value = stic_SettingsUtils::getSetting($settingName);
        if (!empty($value) && $value !== false) {
            return $value;
        }
    }
    global $sugar_config;
    if (isset($sugar_config[$configKey])) {
        return $sugar_config[$configKey];
    }
    return $default;
}

function ttsIsValidAjaxRequest()
{
    $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    $serverHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

    if (!empty($origin) && !empty($referer)) {
        $parsedOrigin = parse_url($origin, PHP_URL_HOST);
        $parsedReferer = parse_url($referer, PHP_URL_HOST);
        return $parsedOrigin === $parsedReferer;
    }
    if (!empty($referer)) {
        return parse_url($referer, PHP_URL_HOST) === $serverHost;
    }
    if (!empty($origin)) {
        return parse_url($origin, PHP_URL_HOST) === $serverHost;
    }
    return false;
}

function ttsValidateRequest($options = array())
{
    global $current_user;

    $allowGet = isset($options['allow_get']) ? (bool)$options['allow_get'] : false;
    $jsonInput = isset($options['json_input']) ? (bool)$options['json_input'] : false;

    $result = array('valid' => false, 'error' => null, 'code' => 200, 'data' => null);

    if (empty($current_user->id)) {
        $result['error'] = 'Autenticación requerida.';
        $result['code'] = 401;
        return $result;
    }

    $method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';

    if ($method === 'GET') {
        if (!$allowGet) {
            $result['error'] = 'Método no permitido.';
            $result['code'] = 405;
            return $result;
        }
        $result['valid'] = true;
        return $result;
    }

    if (in_array($method, array('POST', 'PUT', 'DELETE'), true)) {
        if (!ttsIsValidAjaxRequest()) {
            $result['error'] = 'Origen de petición inválido.';
            $result['code'] = 403;
            return $result;
        }

        if ($jsonInput) {
            $rawInput = file_get_contents('php://input');
            $data = json_decode($rawInput, true);
            if ($data === null && !empty($rawInput)) {
                $result['error'] = 'JSON inválido.';
                $result['code'] = 400;
                return $result;
            }
            $result['data'] = $data;
        }

        $result['valid'] = true;
        return $result;
    }

    $result['error'] = 'Método no permitido.';
    $result['code'] = 405;
    return $result;
}

function ttsOutputJson($data, $status = 200)
{
    @ob_end_clean();
    ob_start();
    ob_clean();

    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    ob_flush();
}
