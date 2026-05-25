<?php

class stic_WhistleblowingController extends SugarController
{
    public function pre_editview() { return $this->checkWhistleblowingAccess(); }
    public function pre_listview() { return $this->checkWhistleblowingAccess(); }
    public function pre_detailview() { return $this->checkWhistleblowingAccess(); }
    
    protected function checkWhistleblowingAccess()
    {
        global $current_user, $sugar_config;
        require_once 'modules/stic_Settings/Utils.php';
		$allowed_users_str = stic_SettingsUtils::getSetting('WHISTLEBLOWING_ALLOWED_USERS');
        $allowed_users = explode(',', $allowed_users_str);
        
        $allowed_users = array_map('trim', $allowed_users);

        // Validar si el usuario actual está en la lista y no está siendo emulado
        if (!in_array($current_user->id, $allowed_users) || (isset($_SESSION['stic_impersonate_original_user']) && $_SESSION['stic_impersonate_original_user']!=$current_user->id)) {

            echo "<div style='text-align: center; margin-top: 100px; font-family: Helvetica, Arial, sans-serif;'>";
            echo "<h2 style='color: #d32f2f;'>Acceso Restringido</h2>";
            echo "<p>No tienes permisos suficientes para acceder al módulo de Canal Ético.</p>";
            echo "<p>Serás redirigido a la página de inicio en <span id='timer'>4</span> segundos...</p>";
            echo "</div>";

            echo "
            <script type='text/javascript'>
                var seconds = 4;
                var countdown = setInterval(function() {
                    seconds--;
                    document.getElementById('timer').textContent = seconds;
                    if (seconds <= 0) {
                        clearInterval(countdown);
                        window.location.href = 'index.php?module=Home&action=index';
                    }
                }, 1000);
            </script>";

            die();
        }

        return true;
    }
}