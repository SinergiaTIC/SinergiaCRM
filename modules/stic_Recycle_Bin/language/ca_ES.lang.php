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
    'LBL_MODULE_NAME' => 'Paperera de reciclatge',
    'LBL_MODULE_TITLE' => 'Paperera de reciclatge',
    'LBL_HOMEPAGE_TITLE' => 'Paperera de reciclatge',

    // General
    'LBL_SEARCH_FORM_TITLE' => 'Cerca a la paperera de reciclatge',
    'LBL_LIST_FORM_TITLE' => 'Llista de la paperera de reciclatge',
    'LBL_NEW_FORM_TITLE' => 'Nova entrada a la paperera de reciclatge',
    'LNK_LIST' => 'Paperera de reciclatge',

    // Panels
    'LBL_DEFAULT_PANEL' => 'Informació del registre eliminat',

    // Field labels
    'LBL_NAME' => 'Nom del registre',
    'LBL_ID' => 'ID',
    'LBL_DATE_ENTERED' => 'Data de creació',
    'LBL_DATE_MODIFIED' => 'Data de modificació',
    'LBL_MODIFIED' => 'Modificat per',
    'LBL_CREATED' => 'Creat per',
    'LBL_DESCRIPTION' => 'Descripció',
    'LBL_DELETED' => 'Eliminat',
    'LBL_ASSIGNED_TO' => 'Assignat a',
    'LBL_ASSIGNED_TO_ID' => 'ID de l\'usuari assignat',
    'LBL_ASSIGNED_TO_USER' => 'Usuari assignat',
    'LBL_RECYCLE_USER_DELETED_LINK' => 'Enllaç usuari eliminador',
    'LBL_RECYCLE_USER_RESTORED_LINK' => 'Enllaç usuari restaurador',
    'LBL_MODIFIED_USER' => 'Usuari que ha modificat',
    'LBL_CREATED_USER' => 'Usuari que ha creat',
    'LBL_SECURITYGROUPS' => 'Grups de seguretat',

    // Custom fields
    'LBL_RECYCLE_MODULE' => 'Mòdul',
    'LBL_RECYCLE_RECORD_ID' => 'ID del registre',
    'LBL_RECYCLE_RECORD_NAME' => 'Nom del registre',
    'LBL_RECYCLE_DATE_DELETED' => 'Data d\'eliminació',
    'LBL_RECYCLE_USER_DELETED' => 'Eliminat per',
    'LBL_RECYCLE_DATE_RESTORED' => 'Data de restauració',
    'LBL_RECYCLE_USER_RESTORED' => 'Restaurat per',
    'LBL_RECYCLE_RESTORED' => 'Restaurat',

    // Relationships submodule
    'LBL_RECYCLEBIN' => 'Paperera de reciclatge',
    'LBL_RECYCLEBIN_ID' => 'ID de la paperera de reciclatge',
    'LBL_RECYCLEBIN_NAME' => 'Paperera de reciclatge',
    'LBL_RECYCLE_RELATIONSHIP_NAME' => 'Nom de la relació',
    'LBL_RECYCLE_JOIN_TABLE' => 'Taula d\'unió',
    'LBL_RECYCLE_RELATED_MODULE' => 'Mòdul relacionat',
    'LBL_RECYCLE_RELATED_RECORD_ID' => 'ID del registre relacionat',
    'LBL_RECYCLE_RELATED_RECORD_NAME' => 'Nom del registre relacionat',
    'LBL_RECYCLE_BIN_RELATIONSHIPS' => 'Relacions en el moment de l\'eliminació',
    'LBL_RECYCLE_JOIN_LHS_KEY' => 'Clau LHS',
    'LBL_RECYCLE_JOIN_RHS_KEY' => 'Clau RHS',

    // List view
    'LBL_RELATIONSHIP_COUNT' => 'Relacions',

    // Actions
    'LBL_RESTORE' => 'Restaurar',
    'LBL_RESTORE_RECORD' => 'Restaurar registre',
    'LBL_RESTORE_CONFIRM' => 'Esteu segur que voleu restaurar aquest registre?',
    'LBL_MASS_RESTORE' => 'Restaurar seleccionats',
    'LBL_MASS_RESTORE_CONFIRM' => 'Esteu segur que voleu restaurar els registres seleccionats?',
    'LBL_MASS_RESTORE_SUCCESS' => 'S\'han restaurat %d registres correctament.',
    'LBL_MASS_RESTORE_PARTIAL' => 'S\'han restaurat %d registres, %d s\'han omès (ja restaurats o no vàlids).',
    'LBL_MASS_RESTORE_ALL_ALREADY' => 'Tots els %d registres seleccionats ja s\'han restaurat.',
    'LBL_MASS_RESTORE_ALL_ALREADY_RESTORED' => 'Els registres seleccionats ja s\'han restaurat.',
    'LBL_MASS_RESTORE_MIXED_CONFIRM' => 'Alguns dels registres seleccionats ja s\'han restaurat. Voleu continuar amb els registres restants?',
    'LBL_NO_RECORDS_SELECTED' => 'No s\'ha seleccionat cap registre.',
    'LBL_RESTORE_SUCCESS' => 'El registre s\'ha restaurat correctament.',
    'LBL_RESTORE_FAIL' => 'No s\'ha pogut restaurar el registre.',
    'LBL_RESTORE_INVALID_ID' => 'Identificador de registre no vàlid.',
    'LBL_RESTORE_NOT_FOUND' => 'No s\'ha trobat el registre.',
    'LBL_RESTORE_ALREADY' => 'El registre ja s\'ha restaurat.',
    'LBL_RESTORE_NO_TABLE' => 'No s\'ha trobat la taula del registre original.',
    'LBL_RESTORE_RESULTS' => 'Resultats de la restauració',
    'LBL_RESTORE_RECORDS_RESTORED' => 'registres restaurats correctament.',
    'LBL_RESTORE_RECORDS_FAILED' => 'registres que no s\'han pogut restaurar.',
    'LBL_RESTORE_RELATIONS_RESTORED' => 'relacions restaurades.',
    'LBL_RESTORE_RELATIONS_SKIPPED' => 'relacions omeses (els registres relacionats ja no estan disponibles).',

    // Generic
    'LBL_YES' => 'Sí',
    'LBL_NO' => 'No',
    'LBL_NO_RELATIONSHIPS' => 'No s\'han registrat relacions per a aquest registre.',
    'LBL_NO_ACCESS' => 'No teniu permís per accedir a aquesta secció.',

    // Module list
    'LBL_RECYCLE_MODULE_LIST' => 'Tots els mòduls',
);
