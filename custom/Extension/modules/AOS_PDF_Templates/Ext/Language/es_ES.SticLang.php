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

$mod_strings['LBL_ACCOUNT_SAMPLE'] = 'Ejemplo de organización';
$mod_strings['LBL_CONTACT_SAMPLE'] = 'Ejemplo de persona';
$mod_strings['LBL_LEAD_SAMPLE'] = 'Ejemplo de interesado';
$mod_strings['LBL_BODY_DESCRIPTION_PDF_URL'] = 'Utilice este enlace para descargar el fichero PDF:<br>';
$mod_strings['LBL_AUTHORIZED_SIGNER'] = 'Firmante autorizado';
$mod_strings['LBL_AUTHORIZED_SIGNERS'] = 'Firmantes autorizados';
$mod_strings['LBL_STIC_FILE_NAME'] = 'Nombre fichero';
$mod_strings['LBL_STIC_FILE_NAME_HELP'] = 'Nombre del fichero PDF que se descarga. Admite texto fijo y variables '
    . 'entre llaves dobles: {{campo}} de un campo del registro, {{relate.campo}} de un registro relacionado '
    . '(incluido el asignado a: {{assigned_user_link.campo}}), '
    . 'y {{record_id}}, {{template_name}}, {{now_date}} o {{now_datetime}}. Las variables {{nombre}}, '
    . '{{apellidos}} y {{nif}} se rellenan con el nombre, los apellidos y el NIF del registro. No escriba '
    . 'la extensión .pdf: se añade sola. Los caracteres no admitidos por el sistema de ficheros y las '
    . 'variables sin dato se sustituyen por "_".';
$mod_strings['LBL_STIC_FILE_NAME_SELECTOR_PLACEHOLDER'] = 'Seleccione una variable para insertarla en el nombre';
$mod_strings['LBL_STIC_FILE_NAME_GROUP_RECORD'] = 'Campos del módulo';
$mod_strings['LBL_STIC_FILE_NAME_GROUP_CONTEXT'] = 'Datos de la generación';
$mod_strings['LBL_STIC_FILE_NAME_RELATED_NAME'] = 'Nombre del registro relacionado';
$mod_strings['LBL_STIC_FILE_NAME_VAR_RECORD_ID'] = 'ID del registro';
$mod_strings['LBL_STIC_FILE_NAME_VAR_TEMPLATE_NAME'] = 'Nombre de la plantilla';
$mod_strings['LBL_STIC_FILE_NAME_VAR_NOW_DATE'] = 'Fecha de generación';
$mod_strings['LBL_STIC_FILE_NAME_VAR_NOW_DATETIME'] = 'Fecha y hora de generación';
