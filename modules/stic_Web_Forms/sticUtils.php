<?php

class SticWebFormsUtils 
{
    /**
     * Returns all well defined reCAPTCHA configurations, in an array 
     * (allows multiple reCAPTCHA configurations):
     * 
     * ['(General)']['NAME'] -> (Empty if is "General" reCAPTCHA configuration)
     * ['(General)']['KEY'] -> Private Key for "General" reCAPTCHA configuration
     * ['(General)']['WEBKEY'] -> Public Web Key for "General" reCAPTCHA configuration
     * ['(General)']['VERSION'] -> reCAPTCHA version for "General" configuration
     * 
     * ['FOOTBALL']['NAME'] -> "FOOTBALL", the name for the reCAPTCHA configuration
     * ['FOOTBALL']['KEY'] -> Private Key for "FOOTBALL" reCAPTCHA configuration
     * ['FOOTBALL']['WEBKEY'] -> Public Web Key for "FOOTBALL" reCAPTCHA configuration
     * ['FOOTBALL']['VERSION'] -> reCAPTCHA version for "FOOTBALL" configuration
     *
     * @return array
     */
    public static function getRecaptchaConfigurations()
    {
        require_once "modules/stic_Settings/Utils.php";
        $settingsGeneral = stic_SettingsUtils::getSettingsByType('GENERAL');
        $recaptchaConsts = [];

        if ($settingsGeneral == null) {
            $GLOBALS['log']->fatal('Line ' . __LINE__ . ': ' . __METHOD__ . ": Could not load settings related to GENERAL.");
            return null;
        } else {
            foreach ($settingsGeneral as $key => $value) {
                // Check if is a Recaptcha Configuration (starts with 'GENERAL_RECAPTCHA')
                if (str_starts_with($key, 'GENERAL_RECAPTCHA')) {
                    $recaptchaKey = '';
                    if (str_starts_with($key, 'GENERAL_RECAPTCHA_WEBKEY')) {
                        if (strlen($key) > strlen('GENERAL_RECAPTCHA_WEBKEY_')) {
                            $recaptchaKey = substr($key, strlen('GENERAL_RECAPTCHA_WEBKEY_'));
                        }
                        $recaptchaConsts[$recaptchaKey]['WEBKEY'] = $value;
                    } else if (str_starts_with($key, 'GENERAL_RECAPTCHA_VERSION')) {
                        if (strlen($key) > strlen('GENERAL_RECAPTCHA_VERSION_')) {
                            $recaptchaKey = substr($key, strlen('GENERAL_RECAPTCHA_VERSION_'));
                        }
                        $recaptchaConsts[$recaptchaKey]['VERSION'] = $value;
                    } else {
                        if (strlen($key) > strlen('GENERAL_RECAPTCHA_')) {
                            $recaptchaKey = substr($key, strlen('GENERAL_RECAPTCHA_'));
                        }
                        $recaptchaConsts[$recaptchaKey]['KEY'] = $value;
                        $recaptchaConsts[$recaptchaKey]['NAME'] = $recaptchaKey;
                    }
                }
            }
            // Check all recaptcha configurations: Only configuarations with correct WEBKEY, VERSION and KEY
            $erroneusKeys = [];
            foreach ($recaptchaConsts as $key => $value) {
                if (!isset($value['KEY']) || empty($value['KEY']) ||
                    !isset($value['WEBKEY']) || empty($value['WEBKEY']) ||
                    !isset($value['VERSION']) || ($value['VERSION']<>'2' && $value['VERSION']<>'3')) {
                        array_push($erroneusKeys, $key);
                }
            }
            foreach ($erroneusKeys as $key) {
                unset($recaptchaConsts[$key]);
            }
            if(isset($recaptchaConsts[""])) {
                $recaptchaConsts["(General)"] = $recaptchaConsts[""];
                unset($recaptchaConsts[""]);
            }
            ksort($recaptchaConsts);

            return $recaptchaConsts;
        }
    }
}