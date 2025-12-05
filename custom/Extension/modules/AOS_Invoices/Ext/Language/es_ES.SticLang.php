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
$mod_strings['LBL_DEFAULT_PANEL'] = 'Datos generales';
$mod_strings['LBL_PANEL_RECORD_DETAILS'] = 'Detalles del registro';
$mod_strings['LBL_ACCOUNT'] = 'Organización';
$mod_strings['LBL_CONTACTS_SUBPANEL_TITLE'] = 'Personas';
$mod_strings['LBL_DEFAULT_SUBPANEL_TITLE'] = 'Organizaciones';
$mod_strings['LBL_DUPLICATE'] = 'Posible organización duplicada';
$mod_strings['LBL_LIST_ACCOUNT_NAME'] = 'Organización';
$mod_strings['LBL_PARENT_ACCOUNT_ID'] = 'ID de la Organización padre';
$mod_strings['LBL_SAVE_ACCOUNT'] = 'Guardar la Organización';
$mod_strings['LNK_ACCOUNT_LIST'] = 'Organizaciones';
$mod_strings['LNK_NEW_ACCOUNT'] = 'Nueva Organización';
$mod_strings['MSG_DUPLICATE'] = 'El registro que está a punto de crear podría ser un duplicado de otra organización existente. Los registros de organización con nombres similares se listan a continuación. Para confirmar la creación de esta organización haga clic en Guardar. En caso contrario, pulse Cancelar.';
$mod_strings['MSG_SHOW_DUPLICATES'] = 'El registro que está a punto de crear podría ser un duplicado de otra organización existente. Los registros de organización con nombres similares se listan a continuación. Para confirmar la creación de esta organización haga clic en Guardar. En caso contrario, pulse Cancelar.';
$mod_strings['LBL_BILLING_ACCOUNT'] = 'Organización';
$mod_strings['LBL_BILLING_CONTACT'] = 'Persona';
$mod_strings['LBL_OPPORTUNITY'] = 'Subvención';
$mod_strings['LBL_DUE_DATE_ERROR'] = 'La fecha de vencimiento debe ser igual o posterior a la fecha de facturación.';
$mod_strings['LBL_INVOICE_DATE_ERROR'] = 'La fecha de facturación debe ser igual o anterior a la fecha de vencimiento.';

// Verifactu labels
$mod_strings['LBL_VERIFACTU_HASH'] = 'Hash de la factura';
$mod_strings['LBL_VERIFACTU_HASH_HELP'] = 'Huella digital única que identifica este registro de factura en el sistema Verifactu. Este valor se genera automáticamente y garantiza la integridad de la información.';
$mod_strings['LBL_VERIFACTU_PREVIOUS_HASH'] = 'Hash de la factura anterior';
$mod_strings['LBL_VERIFACTU_PREVIOUS_HASH_HELP'] = 'Huella digital de la factura anterior en la secuencia. Este campo asegura el encadenamiento continuo de todas las facturas en el sistema Verifactu.';
$mod_strings['LBL_VERIFACTU_CHECK_URL'] = 'Url de verificación de la factura';
$mod_strings['LBL_VERIFACTU_CHECK_URL_HELP'] = 'Url de verificación de la factura, que se incluye en las facturas como un código QR . Esta url permite la verificación rápida de la factura en el sistema de la AEAT.';
$mod_strings['LBL_VERIFACTU_AEAT_STATUS'] = 'Estado AEAT de la factura';
$mod_strings['LBL_VERIFACTU_AEAT_STATUS_HELP'] = 'Estado actual del envío de esta factura a la Agencia Tributaria. Los valores posibles son: Pendiente de envío (aún no se ha comunicado), Enviado y Aceptado (la AEAT ha confirmado la recepción), o Error de envío (se produjo un problema en la comunicación).';
$mod_strings['LBL_VERIFACTU_AEAT_RESPONSE'] = 'Respuesta AEAT de la factura';
$mod_strings['LBL_VERIFACTU_AEAT_RESPONSE_HELP'] = 'Respuesta completa recibida de la AEAT tras el envío de la factura. Si hubo algún error, aquí se detallará la información del mismo para su revisión y corrección.';
$mod_strings['LBL_VERIFACTU_CANCEL_ID'] = 'ID de la factura rectificada';
$mod_strings['LBL_VERIFACTU_CANCEL_ID_HELP'] = 'En caso de facturas rectificativas o de anulación, este campo almacena el identificador de la factura original que está siendo anulada o corregida.';
$mod_strings['LBL_VERIFACTU_CANCEL_NAME'] = 'Factura rectificada';
$mod_strings['LBL_VERIFACTU_CANCEL_NAME_HELP'] = 'Factura original que está siendo corregida o anulada por esta factura rectificativa. Este campo es obligatorio para facturas rectificativas y permite navegar directamente a la factura original.';
$mod_strings['LBL_VERIFACTU_CSV'] = 'Codigo seguro de verificación';
$mod_strings['LBL_VERIFACTU_CSV_HELP'] = 'Código devuelto por AEAT tras el envío de la factura. Este código único permite verificar la autenticidad de la factura en el sistema de la AEAT.';
$mod_strings['LBL_STIC_INVOICE_TYPE'] = 'Tipo de factura';
$mod_strings['LBL_STIC_INVOICE_TYPE_HELP'] = 'Clasificación de la factura según su naturaleza. Deben ser configuradas en el apartado de Administración -> Ajustes AOP.';
$mod_strings['LBL_VERIFACTU_SUBMITTED_AT'] = 'Fecha de envío a Verifactu';
$mod_strings['LBL_VERIFACTU_SUBMITTED_AT_HELP'] = 'Fecha y hora en que esta factura fue enviada al sistema Verifactu para su validación y registro ante la AEAT.';


$mod_strings['LBL_VERIFACTU_AEAT_OPERATION_TYPE'] = 'Tipo de operación';
$mod_strings['LBL_VERIFACTU_AEAT_OPERATION_TYPE_HELP'] = 'Tipo de operación fiscal asociada a esta factura, según la clasificación establecida por la AEAT. Este campo ayuda a identificar la naturaleza de la transacción para fines tributarios.';

$mod_strings['LBL_SIGNER_SEND_TO_AEAT'] = 'Enviar a AEAT';
$mod_strings['LBL_AEAT_STATUS_PANEL'] = 'Estado de la factura en la AEAT';
$mod_strings['LBL_INVOICE_INVALID_STATUSES_FOR_SEND_TO_AEAT'] = 'La factura no puede ser enviada a la AEAT. El estado debe ser "Emitida" y el estado AEAT debe ser diferente de "Aceptada".';
$mod_strings['LBL_MISSING_SETTINGS'] = 'Faltan configuraciones obligatorias: contraseña del certificado (GENERAL_CERTIFICATE_PASSWORD), certificado de sello de entidad (GENERAL_CERTIFICATE_ENTITY_SEAL), NIF de la organización (GENERAL_ORGANIZATION_ID) o  nombre de la organización (GENERAL_ORGANIZATION_NAME). Por favor, revise las configuraciones de SinergiaCRM.';

// Rectified invoice labels
$mod_strings['LBL_VERIFACTU_IS_RECTIFIED'] = '¿Es factura rectificativa?';
$mod_strings['LBL_VERIFACTU_IS_RECTIFIED_HELP'] = 'Marque esta casilla si esta factura corrige o anula una factura emitida anteriormente. Las facturas rectificativas deben hacer referencia a la factura original que están modificando.';
$mod_strings['LBL_VERIFACTU_RECTIFIED_TYPE'] = 'Tipo de rectificación';
$mod_strings['LBL_VERIFACTU_RECTIFIED_TYPE_HELP'] = 'Indica la modalidad de rectificación: Por sustitución (S) anula completamente la factura original y la reemplaza con datos correctos; Por diferencias (I) solo indica los cambios respecto a la factura original.';
$mod_strings['LBL_VERIFACTU_RECTIFIED_BASE'] = 'Base de la rectificación';
$mod_strings['LBL_VERIFACTU_RECTIFIED_BASE_HELP'] = 'Motivo legal que justifica la emisión de esta factura rectificativa, según el artículo 80 de la Ley del IVA (LIVA). Seleccione el código que mejor describa el motivo de la rectificación.';
$mod_strings['LBL_VERIFACTU_RECTIFIED_DATE'] = 'Fecha de la factura rectificada';
$mod_strings['LBL_VERIFACTU_RECTIFIED_DATE_HELP'] = 'Fecha de expedición de la factura original que se está rectificando. Este dato permite a la AEAT ubicar temporalmente la factura que se corrige.';
$mod_strings['LBL_VERIFACTU_RECTIFIED_PANEL'] = 'Datos de factura rectificativa';
$mod_strings['LBL_CREATE_RECTIFIED_INVOICE'] = 'Crear factura rectificativa';
$mod_strings['LBL_RECTIFIED_INVOICE_VALIDATION_ERROR'] = 'Para facturas rectificativas son obligatorios: tipo de rectificación, base de rectificación, factura rectificada y fecha de la factura rectificada.';
$mod_strings['LBL_RECTIFIED'] = 'Rectificativa';
$mod_strings['LBL_ORIGINAL_INVOICE_RECTIFIED_BY'] = 'Esta factura ha sido rectificada por la factura ';


// Controller messages
$mod_strings['LBL_INVOICE_NOT_FOUND'] = 'Factura no encontrada';
$mod_strings['LBL_ORIGINAL_INVOICE_NOT_SPECIFIED'] = 'No se especificó la factura original.';
$mod_strings['LBL_ORIGINAL_INVOICE_NOT_FOUND'] = 'No se encontró la factura original.';
$mod_strings['LBL_ORIGINAL_INVOICE_MUST_BE_SENT_TO_AEAT'] = 'La factura original debe estar enviada a AEAT para poder rectificarla.';
$mod_strings['LBL_CANNOT_RECTIFY_RECTIFIED_INVOICE'] = 'No se puede crear una factura rectificativa de otra factura rectificativa.';
$mod_strings['LBL_RECTIFIED_INVOICE_CREATED_SUCCESS'] = 'Factura rectificativa creada correctamente. Recuerde completar el tipo y base de rectificación antes de guardar.';


// AEAT communication messages
$mod_strings['LBL_AEAT_COMMUNICATION_SUCCESS'] = 'Comunicación correcta con la AEAT';
$mod_strings['LBL_AEAT_COMMUNICATION_AND_ACCEPTED'] = ' y aceptada';
$mod_strings['LBL_AEAT_SHOW_DETAILS'] = 'Ver detalles';
$mod_strings['LBL_AEAT_SEND_ERROR'] = 'Error al enviar la factura a la AEAT';

// Validation error field labels
$mod_strings['LBL_FIELD_RECTIFIED_TYPE'] = 'Tipo de rectificación';
$mod_strings['LBL_FIELD_RECTIFIED_BASE'] = 'Base de rectificación';
$mod_strings['LBL_FIELD_RECTIFIED_DATE'] = 'Fecha de la factura rectificada';
$mod_strings['LBL_MISSING_FIELDS'] = 'Campos faltantes';

