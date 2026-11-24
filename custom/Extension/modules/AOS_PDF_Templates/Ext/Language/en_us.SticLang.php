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

$mod_strings['LBL_BODY_DESCRIPTION_PDF_URL'] = 'Use this link to download the PDF file:<br>';
$mod_strings['LBL_AUTHORIZED_SIGNER'] = 'Authorized Signer';
$mod_strings['LBL_AUTHORIZED_SIGNERS'] = 'Authorized Signers';
$mod_strings['LBL_STIC_FILE_NAME'] = 'File name';
$mod_strings['LBL_STIC_FILE_NAME_HELP'] = 'Name of the downloaded PDF file. It accepts fixed text and variables '
    . 'in double braces: {{field}} of a record field, {{relate.field}} of a related record '
    . '(including the assigned user: {{assigned_user_link.field}}), '
    . 'and {{record_id}}, {{template_name}}, {{now_date}} or {{now_datetime}}. The variables {{nombre}}, '
    . '{{apellidos}} and {{nif}} are filled with the record name, surnames and NIF. Do not write '
    . 'the .pdf extension: it is added automatically. Characters not accepted by the file system and '
    . 'variables without data are replaced by "_".';
$mod_strings['LBL_STIC_FILE_NAME_SELECTOR_PLACEHOLDER'] = 'Select a variable to insert it in the name';
$mod_strings['LBL_STIC_FILE_NAME_GROUP_RECORD'] = 'Module fields';
$mod_strings['LBL_STIC_FILE_NAME_GROUP_CONTEXT'] = 'Generation data';
$mod_strings['LBL_STIC_FILE_NAME_RELATED_NAME'] = 'Related record name';
$mod_strings['LBL_STIC_FILE_NAME_VAR_RECORD_ID'] = 'Record ID';
$mod_strings['LBL_STIC_FILE_NAME_VAR_TEMPLATE_NAME'] = 'Template name';
$mod_strings['LBL_STIC_FILE_NAME_VAR_NOW_DATE'] = 'Generation date';
$mod_strings['LBL_STIC_FILE_NAME_VAR_NOW_DATETIME'] = 'Generation date and time';
