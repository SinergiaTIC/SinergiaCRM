<?php
/**
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$mod_strings = array(
    // Module info
    'LBL_MODULE_NAME' => 'Papelera de reciclaje',
    'LBL_MODULE_TITLE' => 'Papelera de reciclaje',
    'LBL_HOMEPAGE_TITLE' => 'Papelera de reciclaje',

    // General
    'LBL_SEARCH_FORM_TITLE' => 'Buscar en la papelera',
    'LBL_LIST_FORM_TITLE' => 'Listado de papelera',
    'LBL_NEW_FORM_TITLE' => 'Nueva entrada en papelera',
    'LNK_LIST' => 'Papelera de reciclaje',

    // Panels
    'LBL_DEFAULT_PANEL' => 'Información del registro eliminado',

    // Field labels
    'LBL_NAME' => 'Nombre del registro',
    'LBL_ID' => 'ID',
    'LBL_DATE_ENTERED' => 'Fecha de creación',
    'LBL_DATE_MODIFIED' => 'Fecha de modificación',
    'LBL_MODIFIED' => 'Modificado por',
    'LBL_CREATED' => 'Creado por',
    'LBL_DESCRIPTION' => 'Descripción',
    'LBL_DELETED' => 'Eliminado',
    'LBL_ASSIGNED_TO' => 'Asignado a',
    'LBL_ASSIGNED_TO_ID' => 'ID de usuario asignado',
    'LBL_MODIFIED_USER' => 'Usuario que modificó',
    'LBL_CREATED_USER' => 'Usuario que creó',
    'LBL_SECURITYGROUPS' => 'Grupos de seguridad',

    // Custom fields
    'LBL_RECYCLE_MODULE' => 'Módulo',
    'LBL_RECYCLE_RECORD_ID' => 'ID del registro',
    'LBL_RECYCLE_RECORD_NAME' => 'Nombre del registro',
    'LBL_RECYCLE_DATE_DELETED' => 'Fecha de eliminación',
    'LBL_RECYCLE_USER_DELETED' => 'Usuario que eliminó',
    'LBL_RECYCLE_DATE_RESTORED' => 'Fecha de recuperación',
    'LBL_RECYCLE_USER_RESTORED' => 'Usuario que recuperó',
    'LBL_RECYCLE_RESTORED' => 'Restaurada',

    // Relationships submodule
    'LBL_RECYCLEBIN' => 'Papelera',
    'LBL_RECYCLEBIN_ID' => 'ID de papelera',
    'LBL_RECYCLEBIN_NAME' => 'Papelera',
    'LBL_RECYCLE_RELATIONSHIP_NAME' => 'Nombre de la relación',
    'LBL_RECYCLE_JOIN_TABLE' => 'Tabla de unión',
    'LBL_RECYCLE_RELATED_MODULE' => 'Módulo relacionado',
    'LBL_RECYCLE_RELATED_RECORD_ID' => 'ID del registro relacionado',
    'LBL_RECYCLE_RELATED_RECORD_NAME' => 'Nombre del registro relacionado',
    'LBL_RECYCLE_BIN_RELATIONSHIPS' => 'Relaciones en el momento del borrado',
    'LBL_RECYCLE_JOIN_LHS_KEY' => 'Clave LHS',
    'LBL_RECYCLE_JOIN_RHS_KEY' => 'Clave RHS',

    // List view
    'LBL_RELATIONSHIP_COUNT' => 'Relaciones',

    // Actions
    'LBL_RESTORE' => 'Recuperar',
    'LBL_RESTORE_RECORD' => 'Recuperar registro',
    'LBL_RESTORE_CONFIRM' => '¿Está seguro de que desea recuperar este registro?',
    'LBL_MASS_RESTORE' => 'Recuperar seleccionados',
    'LBL_MASS_RESTORE_CONFIRM' => '¿Está seguro de que desea recuperar los registros seleccionados?',
    'LBL_MASS_RESTORE_SUCCESS' => '%d registros recuperados correctamente.',
    'LBL_MASS_RESTORE_PARTIAL' => '%d registros recuperados, %d omitidos (ya estaban restaurados o no son válidos).',
    'LBL_MASS_RESTORE_ALL_ALREADY' => 'Los %d registros seleccionados ya habían sido restaurados.',
    'LBL_MASS_RESTORE_ALL_ALREADY_RESTORED' => 'Los registros seleccionados ya han sido restaurados.',
    'LBL_MASS_RESTORE_MIXED_CONFIRM' => 'Algunos de los registros seleccionados ya han sido restaurados. ¿Continuar con los registros restantes?',
    'LBL_NO_RECORDS_SELECTED' => 'No hay registros seleccionados.',
    'LBL_RESTORE_SUCCESS' => 'Registro recuperado correctamente.',
    'LBL_RESTORE_FAIL' => 'Error al recuperar el registro.',
    'LBL_RESTORE_INVALID_ID' => 'Identificador de registro no válido.',
    'LBL_RESTORE_NOT_FOUND' => 'Registro no encontrado.',
    'LBL_RESTORE_ALREADY' => 'El registro ya ha sido restaurado.',
    'LBL_RESTORE_NO_TABLE' => 'No se ha encontrado la tabla original del registro.',
    'LBL_RESTORE_RESULTS' => 'Resultados de la recuperación',
    'LBL_RESTORE_RECORDS_RESTORED' => 'registros recuperados correctamente.',
    'LBL_RESTORE_RECORDS_FAILED' => 'registros no se pudieron recuperar.',
    'LBL_RESTORE_RELATIONS_RESTORED' => 'relaciones restablecidas.',
    'LBL_RESTORE_RELATIONS_SKIPPED' => 'relaciones omitidas (registros relacionados ya no disponibles).',

    // Generic
    'LBL_YES' => 'Sí',
    'LBL_NO' => 'No',
    'LBL_NO_RELATIONSHIPS' => 'No hay relaciones registradas para este registro.',
    'LBL_NO_ACCESS' => 'No tiene permiso para acceder a esta sección.',

    // Module list
    'LBL_RECYCLE_MODULE_LIST' => 'Todos los módulos',
);
