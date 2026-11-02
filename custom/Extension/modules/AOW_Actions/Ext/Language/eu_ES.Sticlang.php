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
$mod_strings['LBL_PORTALACTIONS'] = 'Atariaren ekintzak';
$mod_strings['LBL_PORTAL_ACTIONS_TYPE'] = 'Ekintza';
$mod_strings['LBL_PORTAL_ACTIONS_TARGET_APP'] = 'Birbideratzeko helburuko aplikazioa';
$mod_strings['LBL_PORTAL_ACTIONS_GENERIC_APP'] = 'Orokorra (aplikazio espezifikorik gabe)';
$mod_strings['LBL_PORTAL_ACTIONS_INVITATION'] = 'Gonbidapena bidali';
$mod_strings['LBL_PORTAL_ACTIONS_RESET'] = 'Pasahitza berrezartzea bidali';
$mod_strings['LBL_PORTAL_ACTIONS_MODULES_HELP'] = 'Ekintza honek Pertsonak eta Erakundeak moduluetako lan-fluxuei aplikatzen zaie bakarrik.';
$mod_strings['LBL_PORTAL_ACTIONS_TEMPLATE_DEFAULT'] = 'Lehenetsia (Atariaren konfiguraziotik)';
// STIC-Custom 20261102 AAM - Portal Actions lan-fluxu ekintzaren lineako laguntza
$mod_strings['LBL_PORTAL_ACTIONS_TYPE_HELP'] = '<b>Gonbidapena bidali</b> — atariaren erabiltzailea sortzen du (beharrezkoa bada), atariaren sarbidea gaitzen du eta kredentzialak bidaltzen ditu lehen aldiz pasahitza ezartzeko esteka batekin (24 ordu balio du).<br><b>Pasahitza berrezartzea bidali</b> — ordu bateko esteka bidaltzen du atariaren erabiltzaile existentzaile bati pasahitz berria ezar dezan. Bi kasuetan, erregistroak atariaren erabiltzaile-izenik ez badu, Atariaren konfigurazioan ezarritako iturritik betetzen da.';
$mod_strings['LBL_PORTAL_ACTIONS_TARGET_APP_HELP'] = 'Gonbidapenak seinalatutako atariaren aplikazioa. Auditoretzaren erregistroan grabatzen da, $portal_app_name txantiloi-aldagaia betetzen du eta pasahitza ezarri osteko birbideratze gisa erabiltzen da. «Orokorra (aplikazio espezifikorik gabe)» aplikazio-erreferentziarik ez du bidaltzen.';
$mod_strings['LBL_PORTAL_ACTIONS_INDIVIDUAL_HELP'] = 'Markatuta: mezu elektroniko bat erregistro bakoitzeko (hartzaile bakoitzak mezu banandu bat jasotzen du). Markatu gabe: mezu bakarra helbide guztiekin «Norri» eremuan.';
$mod_strings['LBL_PORTAL_ACTIONS_TEMPLATE_HELP'] = 'Lehenetsita, ekintza mota honentzat Atariaren konfigurazioan hautatutako txantiloia erabiltzen du; aukeratu beste bat lan-fluxu honetan gainidatzitzeko. Txantiloiaren aldagai erabilgarriak:<br><br>• Erregistroaren edozein eremu, adib. $contact_first_name, $account_name<br>• <b>$portal_app_name</b> — helburuko aplikazioaren izena (ez badago, atariaren titulua)<br>• <b>$portal_address</b> — atariaren/aplikazioaren URLa<br>• <b>$portal_reset_link</b> — pasahitza ezartzeko esteka (gonbidapena, 24 ordu) edo berrezartzekoa (1 ordu)<br>• <b>$portal_title</b> — atariaren izenburua';

