<?php
require_once 'modules/Home/controller.php';
class CustomHomeController extends HomeController
{
    /**
     * Redirects the user to SinergiaDA if they have permission, otherwise log an error message.
     */
    public function action_sdaRedirect()
    {
        global $sugar_config, $current_user, $mod_strings;
        $db = DBManagerFactory::getInstance();
        $q = "SELECT sda_allowed_c FROM users_cstm WHERE id_c='" . $current_user->id . "'";
        $r = $db->query($q);
        $a = $db->fetchByAssoc($r);
        $currentDomain = $_SERVER['HTTP_HOST'];
        if ($a['sda_allowed_c'] == 1) {
            $lang = explode('_', $sugar_config['default_language'])[0];
            $sdaUrl = $sugar_config['stic_sinergiada_public']['url'] ?? "https://" . str_replace("sinergiacrm", "sinergiada", $currentDomain);
            $sdaUrl .= "/{$lang}/#";
            SugarApplication::redirect($sdaUrl);
            die();
        } else {
            SugarApplication::appendErrorMessage("<p class='msg-fatal-lock'>{$mod_strings['LBL_STIC_SINERGIADA_NOT_ALLOWED']}");
            SugarApplication::redirect($_SERVER['HTTP_REFERER']);
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "An error occurred while trying to redirect to SinergiaDA. The user [{$current_user->user_name}] does not have access to SinergiaDA");
        }
    }
}
