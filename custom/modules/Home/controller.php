<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
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
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

require_once 'modules/Home/controller.php';
require_once 'SticInclude/SdaSso.php';
class CustomHomeController extends HomeController
{
    /**
     * Redirects the user to SinergiaDA if they have permission, otherwise log an error message.
     *
     * When SSO is configured, a short-lived token is appended so SinergiaDA
     * opens the session without asking for credentials. An optional "return"
     * route (relative path only) is forwarded to SinergiaDA.
     */
    public function action_sdaRedirect()
    {
        global $sugar_config, $current_user, $mod_strings;
        $allowed = SticSdaSso::getAllowed($current_user->id);
        $currentDomain = $_SERVER['HTTP_HOST'];
        $sdaUrl = $sugar_config['stic_sinergiada_public']['url'] ?? "https://" . str_replace("sinergiacrm", "sinergiada", $currentDomain);

        // Idioma de la URL de SDA: preferencia del usuario; si no, el idioma actual
        // de la sesión; y en último término, el idioma por defecto del CRM.
        $userLang = $current_user->getPreference('language') ?: ($GLOBALS['current_language'] ?? '');
        $lang = explode('_', $userLang ?: $sugar_config['default_language'])[0];

        // SinergiaDA puede publicarse en la raíz o bajo una carpeta de idioma
        // ({sda}/{lang}/). El build localizado (el habitual) usa carpetas de
        // idioma; para un despliegue servido en la raíz, definir
        //   $sugar_config['stic_sinergiada']['sso_locale_path'] = false;
        // (en dev también vale true: el servidor de desarrollo sirve la app en
        // cualquier ruta y el router usa el hash).
        $sdaBase = rtrim($sdaUrl, '/');
        $localePath = $sugar_config['stic_sinergiada']['sso_locale_path'] ?? true;
        if ($localePath) {
            // Solo se usan idiomas realmente publicados por SDA (carpetas /xx/);
            // si el del usuario no está, se usa el primero de la lista.
            $sdaLanguages = $sugar_config['stic_sinergiada']['sso_locale_languages'] ?? ['es', 'ca', 'en', 'gl'];
            if (!in_array($lang, $sdaLanguages, true)) {
                $lang = $sdaLanguages[0] ?? 'es';
            }
            $sdaBase .= '/' . $lang;
        }

        if ($allowed !== false) {
            $return = SticSdaSso::sanitizeReturn($_GET['return'] ?? '');
            $token = SticSdaSso::isReady() ? SticSdaSso::mint($current_user->user_name, $allowed) : null;

            if ($token !== null) {
                $ssoUrl = $sdaBase . '/?t=' . rawurlencode($token)
                        . ($return !== '' ? '#' . $return : '#/home');
                // 302 (no 301) + no-cache: un 301 se cachea en el navegador y
                // dejaba a los usuarios atascados en una URL antigua sin token.
                header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
                header('Pragma: no-cache');
                header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
                header('HTTP/1.1 302 Found');
                header('Location: ' . $ssoUrl);
                die();
            }

            // SSO not configured: plain redirect (legacy behaviour)
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Pragma: no-cache');
            header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
            header('HTTP/1.1 302 Found');
            header('Location: ' . $sdaBase . '/#');
            die();
        } else {
            SugarApplication::appendErrorMessage("<p class='msg-fatal-lock'>{$mod_strings['LBL_STIC_SINERGIADA_NOT_ALLOWED']}");
            SugarApplication::redirect($_SERVER['HTTP_REFERER']);
            $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': ' . "An error occurred while trying to redirect to SinergiaDA. The user [{$current_user->user_name}] does not have access to SinergiaDA");
        }
    }
}
