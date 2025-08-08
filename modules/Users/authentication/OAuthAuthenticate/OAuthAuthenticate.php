<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * This file is used to control the authentication process.
 * It will call on the user authenticate and controll redirection
 * based on the users validation
 *
 */

require_once('modules/Users/authentication/SugarAuthenticate/SugarAuthenticate.php');
#[\AllowDynamicProperties]
class OAuthAuthenticate extends SugarAuthenticate
{
    public $userAuthenticateClass = 'OAuthAuthenticateUser';
    public $authenticationDir = 'OAuthAuthenticate';
    private $provider = '';
    private $utilsClass = null;


    public function __construct($provider = '')
    {
        $this->provider = $provider;
        $this->setUtilsClass($provider);
        parent::__construct();

    }
    protected function setUtilsClass($provider)
    {
        $utilsClass = $provider . 'Utils';
        $this->utilsClass = $utilsClass;
        if (file_exists('custom/modules/Users/authentication/OAuthAuthenticate/Providers/'.$provider.'/'.$utilsClass.'.php')) {
            require_once('custom/modules/Users/authentication/OAuthAuthenticate/Providers/'.$provider.'/'.$utilsClass.'.php');
        } else if (file_exists('modules/Users/authentication/OAuthAuthenticate/Providers/'.$provider.'/'.$utilsClass.'.php')) {
            require_once('modules/Users/authentication/OAuthAuthenticate/Providers/'.$provider.'/'.$utilsClass.'.php');
        } else {
            $this->utilsClass = '';
        }
    }

    public static function getShowBasicLoginForm()
    {
        global $sugar_config;
        if (isset($sugar_config['authentication_oauth_show_basic']) && $sugar_config['authentication_oauth_show_basic'] == true) {
            return true;
        }
        return false;

    }

    public static function getEnabledOAuthProviders () 
    {
        global $sugar_config;

        $providers = [];
        if (isset($sugar_config['authentication_oauth_providers']) && is_array($sugar_config['authentication_oauth_providers'])) {
            foreach ($sugar_config['authentication_oauth_providers'] as $provider => $settings) {
                if (isset($settings['enabled']) && $settings['enabled'] == true) {
                    $providers[] = $provider;
                }
            }
        }
        return $providers;
    }

    public function getLoginParams()
    {
        global $sugar_config;
        $provider = $this->provider;
        if ($sugar_config['authentication_oauth_providers'] ?? false) {
            $oauthProviders = $sugar_config['authentication_oauth_providers'];
            if (isset($oauthProviders[$provider]) && $oauthProviders[$provider]['enabled'] == true) {
                $utilsClass = $this->utilsClass;
                if ($utilsClass) {
                    return $utilsClass::getLoginParams($sugar_config['authentication_oauth_providers'][$provider]);
                }
                return [];
            }
        }
        return [];
    }

    public function getLoginTemplate(&$ss)
    {
        $utilsClass = $this->utilsClass;
        if ($utilsClass) {
            return $utilsClass::getLoginTemplate($ss);
        }
        return '';
    }

    public function loginAuthenticate($username, $password, $fallback=false, $PARAMS = array())
    {
        global $mod_strings, $sugar_config;
        unset($_SESSION['login_error']);
        unset($_REQUEST['login_token']);

        if (isset($_REQUEST['oauth_provider'])) {
            switch ($_REQUEST['oauth_provider']) {
                case 'Google':
                    $this->provider = 'Google';
                    break;
                case 'facebook':
                    $this->provider = 'Facebook';
                    break;
                case 'twitter':
                    $this->provider = 'Twitter';
                    break;
                default:
                    $_SESSION['login_error'] = $mod_strings['LBL_OAUTH_AUTH_ERR_INVALID_PROVIDER'];
                    return false;
            }
            $this->__construct($this->provider);
            $payload = $this->utilsClass::verifyToken();

            if (is_array($payload) && isset($payload['email']) && !empty($payload['email'])) {
                $email = $payload['email'];

                $userBean = BeanFactory::newBean('Users');
	            $userBean->retrieve_by_email_address($payload['email']);

                $result = array('code' => 0);
	
                if(empty($userBean->id)) {
                    $_SESSION['login_error'] = $mod_strings['LBL_GOOGLE_AUTH_ERR_INVALID_EMAIL_1'] . $email . $mod_strings['LBL_GOOGLE_AUTH_ERR_INVALID_EMAIL_2'];
                    return false;
                }
                $this->userAuthenticate->loadUserOnSession($userBean->id);

	            $this->postLoginAuthenticate();

            } else {
                $_SESSION['login_error'] = $mod_strings['LBL_GOOGLE_AUTH_ERR_INVALID_TOKEN'];
                return false;
            }
        } else {
            parent::loginAuthenticate($username, $password, $fallback, $PARAMS);
        }
    }
}
