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

$mod_strings = array (
  'LBL_ASSIGNED_TO_ID' => 'Asignado a (ID)',
  'LBL_ASSIGNED_TO_NAME' => 'Asignado a',
  'LBL_ASSIGNED_TO' => 'Asignado a',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Asignado a',
  'LBL_LIST_ASSIGNED_USER' => 'Asignado a',
  'LBL_CREATED' => 'Creado por',
  'LBL_CREATED_USER' => 'Creado por',
  'LBL_CREATED_ID' => 'Creado por (ID)',
  'LBL_MODIFIED' => 'Modificado por',
  'LBL_MODIFIED_NAME' => 'Modificado por',
  'LBL_MODIFIED_USER' => 'Modificado por',
  'LBL_MODIFIED_ID' => 'Modificado por (ID)',
  'LBL_SECURITYGROUPS' => 'Grupos de seguridad',
  'LBL_SECURITYGROUPS_SUBPANEL_TITLE' => 'Grupos de seguridad',
  'LBL_ID' => 'ID',
  'LBL_DATE_ENTERED' => 'Fecha de Creación',
  'LBL_DATE_MODIFIED' => 'Fecha de Modificación',
  'LBL_DESCRIPTION' => 'Descripción',
  'LBL_DELETED' => 'Eliminado',
  'LBL_NAME' => 'Nombre',
  'LBL_LIST_NAME' => 'Nombre',
  'LBL_EDIT_BUTTON' => 'Editar',
  'LBL_REMOVE' => 'Desvincular',
  'LBL_ASCENDING' => 'Ascendente',
  'LBL_DESCENDING' => 'Descendente',
  'LBL_LIST_FORM_TITLE' => 'Lista de registros de Calendario Laboral',
  'LBL_MODULE_NAME' => 'Calendario Laboral',
  'LBL_MODULE_TITLE' => 'Calendario Laboral',
  'LBL_HOMEPAGE_TITLE' => 'Mis registros de Calendario Laboral',
  'LNK_NEW_RECORD' => 'Crear registro de Calendario Laboral',
  'LNK_LIST' => 'Ver registros de Calendario Laboral',
  'LNK_IMPORT_STIC_WORK_CALENDAR' => 'Importar registros de Calendario Laboral',
  'LBL_SEARCH_FORM_TITLE' => 'Búsqueda de registros de Calendario Laboral',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Ver Historial',
  'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Actividades',
  'LBL_STIC_WORK_CALENDAR_SUBPANEL_TITLE' => 'Calendario Laboral',
  'LBL_NEW_FORM_TITLE' => 'Nuevo Calendario Laboral',
  'LBL_TYPE' => 'Tipo',
  'LBL_START_DATE' => 'Fecha de y hora de inicio',
  'LBL_APPLICATION_DATE' => 'Fecha de aplicación',
  'LBL_END_DATE' => 'Fecha de y hora de finalización',  
  'LBL_START_DATE_ERROR' => 'La fecha y hora de inicio debe ser anterior a la fecha y hora de finalización.',
  'LBL_END_DATE_ERROR' => 'La fecha y hora de finalización debe ser posterior a la fecha y hora de inicio.',
  'LBL_END_DATE_EXCCEDS_24_HOURS' => 'La diferencia entre la fecha y hora de finalización y la de inicio debe de ser menor a 24 horas.',
  'LBL_INCOMPATIBLE_TYPE_WITH_EXISTING_RECORDS' => 'Existen otros registros con tipo incompatible al actual para el mismo usuario asignado y dentro del rango horario definido. Compruebe estos registros y modifique el rango horario de este registro o de aquel que genera el conflicto.',
  'LBL_ERROR_REQUEST_INCOMPATIBLE_TYPE' => 'Error al comprobar si ya existen registros de Calendario laboral incompatibles con el registro que se está creando. Consulte con el administrador.',
  'LBL_ERROR_CODE_REQUEST_INCOMPATIBLE_TYPE' => 'Código de error: ',
  'LBL_DURATION' => 'Duración',
  'LBL_WEEKDAY' => 'Día de la semana',
  'LBL_DEFAULT_PANEL' => 'Datos generales',
  'LBL_PANEL_RECORD_DETAILS' => 'Detalles del registro',
  'LBL_ALL_DAY_HELP' => 'Si el tipo de registro no es laborable o ausencia puntual, el registro afecta a todo el día y no será necesario indicar la hora de inicio ni la hora y fecha de finalización.',

  // Asistente de creación de registros de Calendario laboral
  'LNK_CREATE_PERIODIC_RECORDS' => 'Crear registros de forma periódica',
  'LBL_CANCEL_BUTTON' => 'Cancelar',
  'LBL_REPEAT_DOW' => 'Día de la semana',
  'LBL_REPEAT_END_AFTER' => 'Después de',
  'LBL_REPEAT_END_BY' => 'El día',
  'LBL_REPEAT_END' => 'Finalizar',
  'LBL_REPEAT_INTERVAL' => 'Intervalo',
  'LBL_REPEAT_OCCURRENCES' => 'registros de Calendario laboral',
  'LBL_REPEAT_TYPE' => 'Repetir',
  'LBL_REPEAT_UNTIL' => 'Repetir hasta',
  'LBL_SAVE_BUTTON' => 'Guardar',
  'LBL_TIME_START' => 'Fecha y hora de inicio del primer registro de Calendario laboral',
  'LBL_TIME_FINAL' => 'Fecha y hora final del primer registro de Calendario laboral',
  'LBL_TITLE' => 'Crear registros de Calendario laboral',
  'LBL_WORK_CALENDAR_DURATION' => 'Duración del registro de Calendario laboral',

  // Asistente para modificar la hora de registros de Calendario laboral
  'LBL_MASS_UPDATE_DATES_BUTTON_TITTLE' => 'Actualizar hora de inicio y de fin',
  'LBL_MASS_UPDATE_DATES_TITTLE' => 'Actualización masiva de la hora de inicio y de finalización',
  'LBL_MASS_UPDATE_VALIDATION_IMPORTANT' => 'IMPORTANTE:',
  'LBL_MASS_UPDATE_VALIDATION_TEXT' => 'En este proceso no se aplican las siguientes validaciones que sí se aplican desde la vista de edición:',
  'LBL_MASS_UPDATE_VALIDATION_1' => 'Comprobar que la fecha y hora de inicio sea anterior a la fecha y hora de finalización.',
  'LBL_MASS_UPDATE_VALIDATION_2' => 'Comprobar que la duración no sea superior a 24 horas.',
  'LBL_MASS_UPDATE_VALIDATION_3' => 'Comprobar si ya existe otro registro con tipo incompatible que se solape con el nuevo rango horario.',
  'LBL_MASS_UPDATE_TEXT' => 'Indique el valor que quiere sumar, restar o asignar a uno o ambos campos:',
  'LBL_MASS_UPDATE_DATES_FIELD' => 'Campo',
  'LBL_MASS_UPDATE_DATES_OPERADOR' => 'Operador',
  'LBL_MASS_UPDATE_DATES_HORAS' => 'Horas',
  'LBL_MASS_UPDATE_DATES_MINUTES' => 'Minutos',
  'LBL_CANCEL_BUTTON' => 'Cancelar',
  'LBL_UPDATE_BUTTON' => 'Actualizar',
);