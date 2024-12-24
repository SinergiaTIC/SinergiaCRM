<?php
require_once 'modules/Home/controller.php';
class CustomHomeController extends HomeController
{
    /**
     * Redirects the user to SinergiaDA if they have permission, otherwise log an error message.
     */
    public function action_sdaRedirect()
    {
        global $sugar_config, $current_user;
        $db = DBManagerFactory::getInstance();
        $q = "SELECT sda_allowed_c FROM users_cstm WHERE id_c='" . $current_user->id . "'";
        $r = $db->query($q);
        $a = $db->fetchByAssoc($r);
        if ($a['sda_allowed_c'] == 1) {
            $currentDomain = $_SERVER['HTTP_HOST'];
            $lang = explode('_', $sugar_config['default_language'])[0];
            $sdaUrl = $sugar_config['stic_sinergiada_public']['url'] ?? "https://" . str_replace("sinergiacrm", "sinergiada", $currentDomain);
            $sdaUrl .= "/{$lang}/#";
            SugarApplication::redirect($sdaUrl);
            die();
        } else {
            $GLOBALS['log']->info('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "An error occurred while trying to redirect to SinergiaDA. The user [{$current_user->user_name}] does not have access to SinergiaDA");
        }
    }
}
