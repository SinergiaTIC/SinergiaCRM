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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$mod_strings ['LBL_SENDMESSAGE'] = 'Enviar mensaje';

// STIC-Custom 20260927 AAM - Portal Actions workflow action labels
$mod_strings['LBL_PORTALACTIONS'] = 'Acciones del portal';
$mod_strings['LBL_PORTAL_ACTIONS_TYPE'] = 'Acción';
$mod_strings['LBL_PORTAL_ACTIONS_TARGET_APP'] = 'Aplicación de destino para la redirección';
$mod_strings['LBL_PORTAL_ACTIONS_GENERIC_APP'] = 'Genérica (sin aplicación específica)';
$mod_strings['LBL_PORTAL_ACTIONS_INVITATION'] = 'Enviar invitación';
$mod_strings['LBL_PORTAL_ACTIONS_RESET'] = 'Enviar restablecimiento de contraseña';
$mod_strings['LBL_PORTAL_ACTIONS_MODULES_HELP'] = 'Esta acción solo se aplica a flujos de trabajo sobre los módulos Personas y Organizaciones.';
$mod_strings['LBL_PORTAL_ACTIONS_TEMPLATE_DEFAULT'] = 'Predeterminada (de la Configuración del portal)';
// STIC-Custom 20261102 AAM - Ayuda en línea de la acción de flujo Acciones del portal
$mod_strings['LBL_PORTAL_ACTIONS_TYPE_HELP'] = '<b>Enviar invitación</b> — crea el usuario del portal (si es necesario), activa el acceso al portal y envía las credenciales con un enlace para establecer la contraseña por primera vez (válido 24 horas).<br><b>Enviar restablecimiento de contraseña</b> — envía un enlace de una hora para establecer una nueva contraseña a un usuario existente del portal. En ambos casos, si el registro no tiene nombre de usuario del portal, se rellena con la fuente configurada en la Configuración del portal.';
$mod_strings['LBL_PORTAL_ACTIONS_TARGET_APP_HELP'] = 'Aplicación del portal a la que apunta la invitación. Se registra en el registro de auditoría, rellena la variable $portal_app_name de la plantilla y se usa como redirección tras establecer la contraseña. «Genérica (sin aplicación específica)» no envía ninguna referencia a la aplicación.';
$mod_strings['LBL_PORTAL_ACTIONS_INDIVIDUAL_HELP'] = 'Marcado: un correo por registro (cada destinatario recibe un mensaje separado). Sin marcar: un único correo con todas las direcciones en el campo «Para».';
$mod_strings['LBL_PORTAL_ACTIONS_TEMPLATE_HELP'] = 'Por defecto usa la plantilla seleccionada en la Configuración del portal para el tipo de acción actual; elija otra para sobrescribirla en este flujo. Variables disponibles en la plantilla:<br><br>• Cualquier campo del registro, p. ej. $contact_first_name, $account_name<br>• <b>$portal_app_name</b> — nombre de la aplicación de destino (si no hay, usa el título del portal)<br>• <b>$portal_address</b> — URL del portal/aplicación<br>• <b>$portal_reset_link</b> — enlace para establecer la contraseña (invitación, 24 h) o restablecerla (restablecimiento, 1 h)<br>• <b>$portal_title</b> — título del portal';

