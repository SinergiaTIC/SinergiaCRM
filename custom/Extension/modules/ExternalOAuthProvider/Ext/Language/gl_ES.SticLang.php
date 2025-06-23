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
$mod_strings['LBL_CONNECTOR_DEFAULT_CONFIGURED_OPTIONS'] = 'Opciones configuradas por defecto';
$mod_strings['LBL_CONNECTOR_HELP'] = 'Para facilitar la creación del registro de proveedor OAuth externo, al seleccionar un conector se configuran varias opciones por defecto y, en consecuencia, no es necesario indicarlas explícitamente. Las opciones configuradas por defecto se muestran en el área inferior. Cabe tener en cuenta que el conector genérico no configura ninguna opción por defecto. Más información en el apartado <a href="https://wikisuite.sinergiacrm.org/index.php?title=Configuraci%C3%B3n_de_correo#Proveedores_OAuth_externos" target="_blank">Proveedores Oauth Externos</a> del wiki de SinergiaCRM.';
$mod_strings['LBL_CLIENT_ID_HELP'] = 'En este campo debe indicarse el ID de cliente generado en la aplicación creada en el proveedor de correo externo.';
$mod_strings['LBL_CLIENT_SECRET_HELP'] = 'En este campo debe indicarse el valor del secreto generado en la aplicación creada en el proveedor de correo externo. Es importante no confundir el valor del secreto con el ID del secreto.';
$mod_strings['LBL_SCOPE_HELP'] = 'En este campo pueden indicarse los permisos otorgados a la aplicación creada en el proveedor de correo externo.';
$mod_strings['LBL_AUTHORIZE_URL_HELP'] = 'En este campo debe indicarse la URL donde se conectará SinergiaCRM para realizar el proceso de autenticación y autorización.';
$mod_strings['LBL_AUTHORIZE_URL_OPTIONS_HELP'] = 'En este campo deben indicarse los parámetros adicionales de configuración (dependediendo de cada proveedor) que se añaden a la URL de autorización para poder realizar el proceso de autenticación y autorización.';
$mod_strings['LBL_URL_ACCESS_TOKEN_HELP'] = 'En este campo debe indicarse la URL a la que SinergiaCRM envía el código de autorización obtenido con la URL de autorización para obtener los tokens de acceso y de refresco.';
$mod_strings['LBL_REDIRECT_URI_HELP'] = 'En este campo se muestra la URL de redirección que debe indicarse en el campo URI de redirección de la aplicación creada en el proveedor de correo externo. Es la URL de SinergiaCRM a la que el proveedor externo redirigirá al usuario después de realizar el proceso de autenticación y autorización.';
$mod_strings['LBL_EXTRA_PROVIDER_PARAMS_HELP'] = 'En este campo deben indicarse parámetros adicionales que dependen de cada proveedor y que serán usados en diferentes fases del proceso: Autenticación y autorización, obtención de tokens, etc.';
