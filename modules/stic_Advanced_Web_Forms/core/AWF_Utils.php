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

class AWF_Utils {
    /**
     * Convierte un valor string del formulario al tipo PHP correcto basándose en el tipo de campo en el CRM.
     * @param mixed $valueToCast El valor a convertir
     * @param ?string $crmFieldType El tipo de campo en el CRM
     * @param ExecutionContext $context El contexto de ejecución
     * @return mixed El valor convertido
     */
    public static function castCrmValue(mixed $valueToCast, ?string $crmFieldType, ExecutionContext $context): mixed {
        // Si no es un string (ej: un array de un multiselect), lo retornamos tal cual.
        if (!is_string($valueToCast)) {
            return $valueToCast;
        }

        // Si no hay tipo definido, lo tratamos como texto
        if ($crmFieldType === null) {
            $crmFieldType = 'text';
        }

        switch ($crmFieldType) {
            // Boolean
            case 'bool':
            case 'checkbox':
                $lowerValue = strtolower(trim($valueToCast));
                return !($lowerValue === 'false' || $lowerValue === '0' || $lowerValue === 'off' || $lowerValue === '');

            // Numéricos
            case 'int':
                return (int)$valueToCast;
            
            case 'float':
            case 'double':
            case 'decimal':
            case 'currency': 
                return (float)$valueToCast;

            // Fechas y horas
            case 'date':
            case 'time':
            case 'datetime':
            case 'datetimecombo':
                $baseTimestamp = (int)$context->submissionTimestamp;
                // strtotime también gestiona "today", "+1 day", etc.
                $parsedTime = @strtotime($valueToCast, $baseTimestamp);
                
                if ($parsedTime === false) {
                    $GLOBALS['log']->warning("Line ".__LINE__.": ".__METHOD__.": Can not parse date '{$valueToCast}'.");
                    return null;
                }
                try {
                    $dateTimeObj = new \DateTime();
                    $dateTimeObj->setTimestamp($parsedTime);
                    return $dateTimeObj;
                } catch (\Exception $e) { return null; }

            // Strings 
            case 'varchar':
            case 'text':
            case 'relate':
            case 'enum':
            case 'multienum':
            case 'phone':
            case 'email':
            case 'text':
            default:
                return (string)$valueToCast;
        }
    }

    /**
     * Genera un resumen en HTML con todos los datos del forumlario.
     *
     * @param ExecutionContext $context El contexto que contiene los datos.
     * @return string Un string HTML con la tabla resumen.
     */
    public static function generateSummaryHtml(ExecutionContext $context): string
    {
        $html = "<h1>".translate('LBL_SUMMARY_DATA', 'stic_Advanced_Web_Forms')."</h1>";
        $formData = $context->formData; 
        
        foreach ($context->formConfig->data_blocks as $block) {
            $html .= "<h2>{$block->text}</h2>";
            $html .= "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
            
            foreach ($block->fields as $fieldDef) {
                if (empty($fieldDef->label) || $fieldDef->type_field === DataBlockFieldType::HIDDEN) {
                    continue;
                }

                $formKey = "{$block->name}.{$fieldDef->name}";
                if ($fieldDef->type_field === DataBlockFieldType::UNLINKED) {
                    $formKey = "_detached.{$formKey}";
                }
                $value = $formData[$formKey] ?? '';
                
                $html .= "<tr>";
                $html .= "<td style='width: 30%;'><strong>" . htmlspecialchars($fieldDef->label) . "</strong></td>";
                $html .= "<td>" . htmlspecialchars($value) . "</td>";
                $html .= "</tr>";
            }
            $html .= "</table>";
        }
        
        return $html;
    }
}
