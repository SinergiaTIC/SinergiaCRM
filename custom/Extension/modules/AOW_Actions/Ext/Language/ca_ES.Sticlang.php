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

$mod_strings['LBL_SENDMESSAGE'] = 'Envia un missatge';

// STIC-Custom 20260927 AAM - Portal Actions workflow action labels
$mod_strings['LBL_PORTALACTIONS'] = 'Accions del portal';
$mod_strings['LBL_PORTAL_ACTIONS_TYPE'] = 'Acció';
$mod_strings['LBL_PORTAL_ACTIONS_TARGET_APP'] = 'Aplicació de destinació per a la redirecció';
$mod_strings['LBL_PORTAL_ACTIONS_GENERIC_APP'] = 'Genèrica (sense aplicació específica)';
$mod_strings['LBL_PORTAL_ACTIONS_INVITATION'] = 'Envia una invitació';
$mod_strings['LBL_PORTAL_ACTIONS_RESET'] = 'Envia un restabliment de contrasenya';
$mod_strings['LBL_PORTAL_ACTIONS_MODULES_HELP'] = 'Aquesta acció només s\'aplica a fluxos de treball sobre els mòduls Persones i Organitzacions.';
$mod_strings['LBL_PORTAL_ACTIONS_TEMPLATE_DEFAULT'] = 'Predeterminada (de la Configuració del portal)';
// STIC-Custom 20261102 AAM - Ajuda en línia de l'acció de flux Accions del portal
$mod_strings['LBL_PORTAL_ACTIONS_TYPE_HELP'] = '<b>Envia una invitació</b> — crea l\'usuari del portal (si cal), activa l\'accés al portal i envia les credencials amb un enllaç per establir la contrasenya per primer cop (vàlid 24 hores).<br><b>Envia un restabliment de contrasenya</b> — envia un enllaç d\'una hora per establir una nova contrasenya a un usuari existent del portal. En tots dos casos, si el registre no té nom d\'usuari del portal, s\'omple amb la font configurada a la Configuració del portal.';
$mod_strings['LBL_PORTAL_ACTIONS_TARGET_APP_HELP'] = 'Aplicació del portal a la qual apunta la invitació. Es registra al registre d\'auditoria, omple la variable $portal_app_name de la plantilla i s\'usa com a redirecció després d\'establir la contrasenya. «Genèrica (sense aplicació específica)» no envia cap referència a l\'aplicació.';
$mod_strings['LBL_PORTAL_ACTIONS_INDIVIDUAL_HELP'] = 'Marcat: un correu per registre (cada destinatari rep un missatge separat). Sense marcar: un únic correu amb totes les adreces al camp «Per a».';
$mod_strings['LBL_PORTAL_ACTIONS_TEMPLATE_HELP'] = 'Per defecte usa la plantilla seleccionada a la Configuració del portal per al tipus d\'acció actual; trieu-ne una altra per sobreescriure-la en aquest flux. Variables disponibles a la plantilla:<br><br>• Qualsevol camp del registre, p. ex. $contact_first_name, $account_name<br>• <b>$portal_app_name</b> — nom de l\'aplicació de destinació (si n\'hi ha cap, usa el títol del portal)<br>• <b>$portal_address</b> — URL del portal/aplicació<br>• <b>$portal_reset_link</b> — enllaç per establir la contrasenya (invitació, 24 h) o restablir-la (restabliment, 1 h)<br>• <b>$portal_title</b> — títol del portal';

