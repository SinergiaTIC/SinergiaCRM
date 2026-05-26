<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class stic_WhistleblowingViewDetail extends ViewDetail
{
    /**
     * El mètode preDisplay s'executa abans de processar Smarty
     */
    public function preDisplay()
    {
        global $current_user;


        require_once 'modules/stic_Settings/Utils.php';
        $allowed_users_str = stic_SettingsUtils::getSetting('WHISTLEBLOWING_ALLOWED_USERS');
        $allowed_users = array_map('trim', explode(',', $allowed_users_str));

        $is_impersonating = (isset($_SESSION['stic_impersonate_original_user']) && $_SESSION['stic_impersonate_original_user'] != $current_user->id);

        if (!in_array($current_user->id, $allowed_users) || $is_impersonating) {
            SugarApplication::appendErrorMessage("No tens permisos per veure aquest registre.");
            SugarApplication::redirect('index.php?module=stic_Whistleblowing&action=index');
            return;
        }

        parent::preDisplay();
    }
}