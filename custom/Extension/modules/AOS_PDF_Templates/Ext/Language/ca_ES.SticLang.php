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

$mod_strings['LBL_ACCOUNT_SAMPLE'] = "Exemple d'organització";
$mod_strings['LBL_CONTACT_SAMPLE'] = 'Exemple de persona';
$mod_strings['LBL_LEAD_SAMPLE'] = "Exemple d'interessat";
$mod_strings['LBL_BODY_DESCRIPTION_PDF_URL'] = 'Feu servir aquest enllaç per descarregar el fitxer PDF:<br>';
$mod_strings['LBL_AUTHORIZED_SIGNER'] = 'Firmant autoritzat';
$mod_strings['LBL_AUTHORIZED_SIGNERS'] = 'Firmants autoritzats';
$mod_strings['LBL_STIC_FILE_NAME'] = 'Nom fitxer';
$mod_strings['LBL_STIC_FILE_NAME_HELP'] = 'Nom del fitxer PDF que es descarrega. Admet text fix i variables '
    . 'entre claus dobles: {{camp}} d\'un camp del registre, {{relate.camp}} d\'un registre relacionat '
    . '(inclòs l\'assignat a: {{assigned_user_link.camp}}), '
    . 'i {{record_id}}, {{template_name}}, {{now_date}} o {{now_datetime}}. Les variables {{nombre}}, '
    . '{{apellidos}} i {{nif}} s\'omplen amb el nom, els cognoms i el NIF del registre. No escriviu '
    . 'l\'extensió .pdf: s\'afegeix sola. Els caràcters no admesos pel sistema de fitxers i les '
    . 'variables sense dada se substitueixen per "_".';
$mod_strings['LBL_STIC_FILE_NAME_SELECTOR_PLACEHOLDER'] = 'Seleccioneu una variable per inserir-la al nom';
$mod_strings['LBL_STIC_FILE_NAME_GROUP_RECORD'] = 'Camps del mòdul';
$mod_strings['LBL_STIC_FILE_NAME_GROUP_CONTEXT'] = 'Dades de la generació';
$mod_strings['LBL_STIC_FILE_NAME_RELATED_NAME'] = 'Nom del registre relacionat';
$mod_strings['LBL_STIC_FILE_NAME_VAR_RECORD_ID'] = 'ID del registre';
$mod_strings['LBL_STIC_FILE_NAME_VAR_TEMPLATE_NAME'] = 'Nom de la plantilla';
$mod_strings['LBL_STIC_FILE_NAME_VAR_NOW_DATE'] = 'Data de generació';
$mod_strings['LBL_STIC_FILE_NAME_VAR_NOW_DATETIME'] = 'Data i hora de generació';
