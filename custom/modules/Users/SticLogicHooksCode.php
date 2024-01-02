<?php

class UsersLogicHooks
{
    public function before_save(&$bean, $event, $arguments)
    {
        // Let's check ut property (1 for existing users, other for new ones). This property launches the user wizzard on first login.
        if ($bean->object_name == 'User') // We check that a user is being saved and not another object belonging to a user's configuration such as the user signature
        {
            $ut = $bean->getPreference('ut');
            if ($ut != "1") {
                // Activate 'count_collapsed_subpanels' and set 'monday' as 'first day of the week' on new users.
                // The options are sent to the Users controller using the $_POST array. It is not possible to do it through
                // setPreferences function because they will be overriden by the default global preferences set by the wizard.
                $_POST['user_count_collapsed_subpanels'] = 'on';
                $_POST['fdow'] = 1;
            }   
        }
    }

    public function after_login($event, $arguments)
    {
        // Create cookie with SinergiaCRM version number if not exists
        if (!isset($_COOKIE['SticVersion'])) {
            if (file_exists('SticVersion.php')) {
                include_once 'SticVersion.php';
                $_COOKIE['SticVersion'] = $sticVersion;
                setcookie('SticVersion', $_COOKIE['SticVersion'], time() + 999999999, '/');
            }
        }
    }
}
