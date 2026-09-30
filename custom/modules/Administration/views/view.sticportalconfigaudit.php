<?php
require_once 'include/MVC/View/SugarView.php';

class AdministrationViewSticportalconfigaudit extends SugarView
{
    public function preDisplay()
    {
        global $current_user;
        if (!is_admin($current_user)) {
            sugar_die("Unauthorized access to administration.");
        }
        $this->ss->assign('RETURN_MODULE', 'Administration');
        $this->ss->assign('RETURN_ACTION', 'sticportalconfig');
    }

    public function display()
    {
        header('Location: index.php?module=stic_Portal_Login_Audit&action=index&return_module=Administration&return_action=sticportalconfig');
        exit;
    }
}
