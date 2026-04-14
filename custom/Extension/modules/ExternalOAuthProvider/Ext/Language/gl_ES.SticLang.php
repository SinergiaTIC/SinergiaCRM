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
$mod_strings['LBL_CONNECTOR_DEFAULT_CONFIGURED_OPTIONS'] = 'Opcións configuradas por defecto';
$mod_strings['LBL_CONNECTOR_HELP'] = 'Para facilitar a creación do rexistro de provedor OAuth externo, ao seleccionar un conector configuranse varias opcións por defecto e, en consecuencia, non é necesario indicalas explicitamente. As opcións configuradas por defecto móstranse na área inferior. Cabe ter en conta que o conector xenérico non configura ningunha opción por defecto. Máis información no apartado <a href="https://wikisuite.sinergiacrm.org/index.php?title=Configuraci%C3%B3n_de_correo#Proveedores_OAuth_externos" target="_blank">Provedores Oauth Externos</a> do wiki de SinergiaCRM.';
$mod_strings['LBL_CLIENT_ID_HELP'] = 'Neste campo debe indicarse o id de cliente xerado na aplicación creada no provedor de correo externo.';
$mod_strings['LBL_CLIENT_SECRET_HELP'] = 'Neste campo debe indicarse o valor do segredo xerado na aplicación creada no provedor de correo externo. É importante non confundir o valor do segredo co id do segredo.';
$mod_strings['LBL_SCOPE_HELP'] = 'Neste campo poden indicarse os permisos outorgados á aplicación creada no provedor de correo externo.';
$mod_strings['LBL_AUTHORIZE_URL_HELP'] = 'Neste campo debe indicarse a URL á que se conectará SinergiaCRM para realizar o proceso de autenticación e autorización.';
$mod_strings['LBL_AUTHORIZE_URL_OPTIONS_HELP'] = 'Neste campo poden indicarse parámetros que serán engadidos á URL de autorización e que serven para personalizar a experiencia de usuario durante o proceso de autenticación e autorización co provedor de correo. Por exemplo: "access_type=offline" solicita un token de refresco para o acceso sen conexión, "prompt=consent" forza ao provedor para mostrar a pantalla de permisos aínda que xa se concedesen, etc.';
$mod_strings['LBL_URL_ACCESS_TOKEN_HELP'] = 'Neste campo debe indicarse a URL á que SinergiaCRM enviará o código de autorización obtido coa URL de autorización para obter os tokens de acceso e de refresco.';
$mod_strings['LBL_REDIRECT_URI_HELP'] = 'Neste campo móstrase a URL de redirección que debe indicarse no campo URI de redirección da aplicación creada no provedor de correo externo. É a URL de SinergiaCRM á que o provedor externo redirixirá ao usuario despois de realizar o proceso de autenticación e autorización.';
$mod_strings['LBL_EXTRA_PROVIDER_PARAMS_HELP'] = 'Neste campo poden indicarse parámetros adicionais (URL ou datos) específicos do provedor para realizar determinadas accións. Por exemplo: "urlResourceOwnerDetails=><url_información_usuario>" define a URL onde SinergiaCRM pode conectarse para obter información do usuario unha vez estea autenticado.';
