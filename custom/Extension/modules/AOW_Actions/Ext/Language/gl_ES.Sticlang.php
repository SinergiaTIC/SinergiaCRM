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
$mod_strings['LBL_PORTALACTIONS'] = 'Accións do portal';
$mod_strings['LBL_PORTAL_ACTIONS_TYPE'] = 'Acción';
$mod_strings['LBL_PORTAL_ACTIONS_TARGET_APP'] = 'Aplicativo de destino para a redirección';
$mod_strings['LBL_PORTAL_ACTIONS_GENERIC_APP'] = 'Xenérica (sen aplicativo específico)';
$mod_strings['LBL_PORTAL_ACTIONS_INVITATION'] = 'Enviar invitación';
$mod_strings['LBL_PORTAL_ACTIONS_RESET'] = 'Enviar restablecemento de contrasinal';
$mod_strings['LBL_PORTAL_ACTIONS_MODULES_HELP'] = 'Esta acción só se aplica a fluxos de traballo sobre os módulos Persoas e Organizacións.';
$mod_strings['LBL_PORTAL_ACTIONS_TEMPLATE_DEFAULT'] = 'Predeterminada (da Configuración do portal)';
// STIC-Custom 20261102 AAM - Axuda en liña da acción de fluxo Accións do portal
$mod_strings['LBL_PORTAL_ACTIONS_TYPE_HELP'] = '<b>Enviar invitación</b> — crea o usuario do portal (se é preciso), activa o acceso ao portal e envía as credenciais cunha ligazón para establecer o contrasinal por primeira vez (válido 24 horas).<br><b>Enviar restablecemento de contrasinal</b> — envía unha ligazón dunha hora para establecer un novo contrasinal a un usuario existente do portal. En ambos casos, se o rexistro non ten nome de usuario do portal, compléase coa fonte configurada na Configuración do portal.';
$mod_strings['LBL_PORTAL_ACTIONS_TARGET_APP_HELP'] = 'Aplicativo do portal ao que apunta a invitación. Rexistrase no rexistro de auditoría, completa a variábel $portal_app_name do modelo e úsase como redirección despois de establecer o contrasinal. «Xenérica (sen aplicativo específico)» non envía ningunha referencia ao aplicativo.';
$mod_strings['LBL_PORTAL_ACTIONS_INDIVIDUAL_HELP'] = 'Marcado: un correo por rexistro (cada destinatario recibe unha mensaxe separada). Sen marcar: un único correo con tódolos enderezos no campo «Para».';
$mod_strings['LBL_PORTAL_ACTIONS_TEMPLATE_HELP'] = 'Por defecto usa o modelo seleccionado na Configuración do portal para o tipo de acción actual; escolla outro para sobrescribilo neste fluxo. Variábeis dispoñibles no modelo:<br><br>• Calquera campo do rexistro, p. ex. $contact_first_name, $account_name<br>• <b>$portal_app_name</b> — nome do aplicativo de destino (se non o hai, usa o título do portal)<br>• <b>$portal_address</b> — URL do portal/aplicativo<br>• <b>$portal_reset_link</b> — ligazón para establecer o contrasinal (invitación, 24 h) ou restablecelo (restablecemento, 1 h)<br>• <b>$portal_title</b> — título do portal';

