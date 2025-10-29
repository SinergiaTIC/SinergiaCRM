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
// Prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

enum DataBlockFieldType: string {
    case UNLINKED  = 'unlinked';
    case FORM      = 'form';
    case HIDDEN    = 'hidden';
}
enum DataBlockFieldValueType: string {
    case EDITABLE   = 'editable';
    case SELECTABLE = 'selectable';
    case FIXED      = 'fixed';
    case DATABLOCK  = 'dataBlock';
}

class FormDataBlockField {
    public FormDataBlock $data_block;             // El Bloque de datos al que pertenece

    public string $name;                         // Nombre del campo
    public string $text_original;                // Texto original del campo
    public string $label;                        // Etiqueta que aparecerá con el campo
    public DataBlockFieldType $type_field;       // Tipo de campo: unlinked, form, hidden
    public bool $required_in_form;               // Indica si el campo será obligado en el formulario
    public string $type_in_form;                 // Tipo de editor en el formulario: stic_advanced_web_forms_field_in_form_type_list
    public string $subtype_in_form;              // SubTipo de editor en el formulario: stic_advanced_web_forms_field_in_form_subtype_list
    public string $type;                         // Tipo de datos del campo
    public DataBlockFieldValueType $value_type;  // Tipo de valor: editable, selectable, fixed, dataBlock
    public string $value;                        // El valor del campo
    public string $value_text;                   // El texto a mostrar para el valor del campo

    /**
     * Crea una instancia de FormDataBlockField a partir de un array JSON.
     * @param FormDataBlock $dataBlock El Bloque de datos al que pertenece
     * @param array $data Los datos en formato array
     * @return FormDataBlockField La instancia creada
     */
    public static function fromJsonArray(FormDataBlock $dataBlock, array $data): self {
        $dto = new self();
        $dto->data_block = $dataBlock;

        $dto->name = $data['name'];
        $dto->text_original = $data['text_original'];
        $dto->label = $data['label'];
        $dto->type_field = DataBlockFieldType::from($data['type_field']);
        $dto->required_in_form = $data['required_in_form'];
        $dto->type_in_form = $data['type_in_form'];
        $dto->subtype_in_form = $data['subtype_in_form'];
        $dto->type = $data['type'];
        $dto->value_type = DataBlockFieldValueType::from($data['value_type']);
        $dto->value = $data['value'];
        $dto->value_text = $data['value_text'];

        return $dto;
    }
}