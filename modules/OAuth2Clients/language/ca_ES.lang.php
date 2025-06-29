<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2019 SalesAgility Ltd.
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

$mod_strings = [
    'LBL_MODULE_NAME' => 'OAuth2 Clients',
    'LBL_MODULE_TITLE' => 'OAuth2 Clients',
    'LBL_MODULE_ID' => 'OAuth2 Clients',
    'LBL_IS_CONFIDENTIAL' => 'És confidencial',
    'LBL_SECRET' => 'Secret',
    'LBL_SECRET_HASHED' => 'Canvi secret',
    'LBL_LEAVE_BLANK' => 'Deixeu en blanc, llevat que canvii',
    'LBL_REMEMBER_SECRET' => 'Si us plau prengui nota del secret ja que no estarà disponible després de desar.',
    'LBL_REDIRECT_URL' => 'Adreça URL de redireccionament',
    'LBL_ALLOWED_GRANT_TYPE' => 'Tipus de concessió permeses',
    'LBL_DURATION_AMOUNT' => 'Duració',
    'LBL_DURATION_UNIT' => 'Unitat de durada',
    'LBL_DURATION_VALUE' => 'Valor de la durada',
    'LBL_USER' => 'Usuari associat',

    'LBL_OAUTHTOKENS_SUBPANEL_TITLE' => 'Tokens de OAuth2 actiu',
    'LBL_TOKEN_ID' => 'Token',
    'LBL_DATE_ENTERED' => 'Data de creació',
    'LBL_ACCESS_TOKEN_EXPIRES' => 'Caducitat del token d\'accés',
    'LBL_REFRESH_TOKEN_EXPIRES' => 'Caducitat del refresc del token',

    'LNK_OAUTH2_TOKEN_LIST'=> 'Llista de tokens OAuth2',
    'LNK_OAUTH2_CLIENT_LIST' => 'Llista de clients OAuth2',

    'LNK_NEW_OAUTH2_CLIENT' => 'Crear Client OAuth2',
    'LNK_NEW_OAUTH2_PASSWORD_CLIENT' => 'Nova contrasenya Client',
    'LNK_NEW_OAUTH2_CREDENTIALS_CLIENT' => 'Nou Client de credencials de Client',
    'LNK_NEW_OAUTH2_IMPLICIT_CLIENT' => 'Nou Client implícit',
    'LNK_NEW_OAUTH2_AUTHORIZATION_CLIENT' => 'Nova autorització Client',

];