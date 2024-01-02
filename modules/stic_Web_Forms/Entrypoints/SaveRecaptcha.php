<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Access the variable created by the entity in SinergiaCRM
$recaptchaSetting = 'GENERAL_RECAPTCHA';
if (isset($_POST['stic-recaptcha-id']) && !empty($_POST['stic-recaptcha-id'])) {
    $recaptchaSetting .= "_".$_POST['stic-recaptcha-id'];
}

include_once 'modules/stic_Settings/Utils.php';
$recaptchaKey = stic_SettingsUtils::getSetting($recaptchaSetting);
if (!$recaptchaKey || empty($recaptchaKey)) {
    $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __FILE__ . ': Failed to retrieve constant ' . $recaptchaSetting . ' from constants module.');
    if (isset($_POST['redirect_ko_url']) && !empty($_POST['redirect_ko_url'])){
        header("Location: {$_POST["redirect_ko_url"]}");
    }
    else {
        $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __FILE__ . ': Could not redirect to the page indicated in redirect_ko_url since it is not set');
    }
} else { 
    // Validate the token received in the form. 
    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $token = $_POST['g-recaptcha-response'];

        // Verify against google the POST request with recaptcha
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $recaptchaKey, 'response' => $token)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $arrResponse = json_decode($response, true);
        
        if(!empty($arrResponse["success"]) && $arrResponse["success"] == true && 
           (!isset($arrResponse["score"]) /* Recaptcha v2 */ ||
            (!empty($arrResponse["score"]) && $arrResponse["score"] >= 0.5) /* Recaptcha v3 */ )) 
        {
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __FILE__ . ': Recaptcha OK. Sending form.');
            // require code to save the data submitted by the form depending on whether it is a stic form (set req_id in the REQUEST) or a suitecrm form
            if (isset($_REQUEST["req_id"])) { // STIC Form
                require_once 'modules/stic_Web_Forms/Entrypoints/Save.php';
            } else {  // SUITECRM Form
                require_once 'custom/modules/Campaigns/WebToPersonCapture.php';    
            }
        } else {
            // Build the error message based on the form information
            $errorMsg = "\n"."- Recaptcha NOT OK. The form has not been sent.";
            $errorMsg .= "\n"."- LastName = " . $_POST['Contacts___last_name'] ?: $_POST['last_name'];
            $errorMsg .= "\n"."- Email = " . $_POST['Contacts___email1'] ?: '';
            $errorMsg .= "\n"."- errorCodes = " . $arrResponse['error-codes'][0] ?: '';
            $errorMsg .= "\n"."- redirect_ko_url = " . $_POST['redirect_ko_url'] ?: '';
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __FILE__ . ': '. $errorMsg);

            // Redirect the user to the KO_URL or print an error message in browser
            if (isset($_POST['redirect_ko_url']) && !empty($_POST['redirect_ko_url'])){
                header("Location: {$_POST["redirect_ko_url"]}");
            } else {
                echo translate('LBL_RECAPTCHA_ERROR', 'stic_Web_Forms');
            }

        }
    } else {
        $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __FILE__ . ': Could not retrieve form recaptcha token.');
    }
} 