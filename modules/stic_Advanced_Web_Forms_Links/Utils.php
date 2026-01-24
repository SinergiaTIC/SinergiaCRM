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

/**
 * Retorna un HTML formateado con los datos modificados a un bean por las acciones del fomulario
 * @param SugarBean $focus El bean del stic_Advanced_Web_Forms_Links
 * @param string $field El campo (no usado)
 * @param mixed $value El valor (no usado)
 * @param string $view La vista (no usado)
 * @return string HTML con los datos modificados
 */
function getLinkDataHtml($focus, $field, $value, $view) {
    global $app_list_strings;

    $module = $focus->parent_type;
    $moduleLabel = $module;
    if (isset($app_list_strings['moduleListSingular'][$module])) {
        $moduleLabel = $app_list_strings['moduleListSingular'][$module];
    }

    $actionLabel = $focus->record_action;
    if (isset($app_list_strings['stic_advanced_web_forms_links_record_action_list'][$actionLabel])) {
        $actionLabel = $app_list_strings['stic_advanced_web_forms_links_record_action_list'][$actionLabel];
    }
    
    // Color del badge según la acción
    $badgeColor = '#6c757d'; // Gris (default)
    if ($focus->record_action === 'created') $badgeColor = '#198754'; // Verde
    if ($focus->record_action === 'updated') $badgeColor = '#0d6efd'; // Azul

    // Nombre del registro relacionado
    $recordName = $focus->parent_name;
    if (empty($recordName) && !empty($focus->parent_id) && !empty($module)) {
        $relatedBean = BeanFactory::getBean($module, $focus->parent_id);
        if ($relatedBean) {
            $recordName = $relatedBean->get_summary_text();
        }
    }
    if (empty($recordName) || trim($recordName) === '') {
        if (!empty($focus->parent_id)) {
            // Si no tenemos nombre, pero sí ID
            $recordName = $focus->parent_id; 
        } else {
            // Si no tenemos ni nombre ni ID
            $recordName = "<i>(". translate('LBL_NO_NAME', 'stic_Advanced_Web_Forms_Links') .")</i>";
        }
    }
    $recordUrl = "#";
    $targetAttr = "";
    $linkClass = "awf-link-record";
    
    if (!empty($focus->parent_id)) {
        $recordUrl = "index.php?module={$module}&action=DetailView&record={$focus->parent_id}";
        $targetAttr = "target='_blank'";
    } else {
        $linkClass = "text-muted"; 
        $targetAttr = "onclick='return false;'"; // Desactivar clic
    }

    $html = "
    <style>
        .awf-link-container { 
            border: 1px solid #dee2e6; 
            border-radius: 6px; 
            overflow: hidden; 
            font-family: system-ui, -apple-system, sans-serif;
            max-width: 800px;
        }
        .awf-link-header { 
            background-color: #f8f9fa; 
            padding: 10px 15px; 
            border-bottom: 1px solid #dee2e6; 
            display: flex; 
            align-items: center; 
            justify-content: space-between;
        }
        .awf-link-title-group {
            display: flex;
            flex-direction: column;
        }
        .awf-link-module { 
            font-weight: bold; 
            font-size: 1.1em; 
            color: #212529; 
            line-height: 1.2;
        }
        .awf-link-record {
            font-size: 0.9em;
            color: #6c757d;
            text-decoration: none;
            margin-top: 2px;
        }
        .awf-link-record:hover {
            text-decoration: underline;
            color: #495057;
        }
        .awf-link-badge { 
            background-color: {$badgeColor}; 
            color: white; 
            padding: 4px 8px; 
            border-radius: 4px; 
            font-size: 0.85em; 
            text-transform: uppercase; 
            font-weight: 600;
            white-space: nowrap;
            margin-left: 15px;
        }
        .awf-link-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .awf-link-table th { 
            background-color: #fff; 
            color: #495057; 
            padding: 8px 15px; 
            text-align: left; 
            border-bottom: 1px solid #eee; 
            width: 35%; 
            vertical-align: top;
        }
        .awf-link-table td { padding: 8px 15px; border-bottom: 1px solid #eee; color: #212529; }
        .awf-link-table tr:last-child th, .awf-link-table tr:last-child td { border-bottom: none; }
        .awf-field-tech { color: #adb5bd; font-size: 0.8em; font-weight: normal; margin-top: 2px; display: block; font-family: monospace; }
        .awf-empty-msg { padding: 15px; font-style: italic; color: #6c757d; text-align: center; }
    </style>";

    $html .= "<div class='awf-link-container'>";
    $html .= "<div class='awf-link-header'>
                <div class='awf-link-title-group'>
                    <span class='awf-link-module'>{$moduleLabel}</span>
                    <a href='{$recordUrl}' class='{$linkClass}' {$targetAttr}>{$recordName}</a>
                </div>
                <span class='awf-link-badge'>{$actionLabel}</span>
              </div>";

    $data = json_decode(html_entity_decode($focus->submitted_data), true);
    if (empty($focus->submitted_data) || !is_array($data) || empty($data)) {
        $html .= "<div class='awf-empty-msg'>". translate('LBL_NO_MODIFIED_DATA', 'stic_Advanced_Web_Forms_Links') ."</div>";
    } else {
        $html .= '<table class="awf-link-table">';

        // Obtenemos los field_defs del módulo para poder traducir las etiquetas
        $fieldDefs = [];
        if (!empty($module)) {
            $seed = BeanFactory::newBean($module);
            if ($seed) {
                $fieldDefs = $seed->field_defs;
            }
        }

        foreach ($data as $key => $val) {
            if ($key === 'id' || $key === 'date_modified') continue;

            // Obtenemos la etiqueta del campo
            $label = $key;
            if (isset($fieldDefs[$key]['vname'])) {
                $label = translate($fieldDefs[$key]['vname'], $module);
                $label = rtrim($label, ':');
            }

            // Formateamos el valor
            if (is_array($val)) {
                $val = implode(', ', $val);
            } elseif (is_bool($val)) {
                global $app_strings;
                $val = $val ? $app_strings['LBL_YES'] : $app_strings['LBL_NO'];
            }
            
            $displayValue = nl2br(htmlspecialchars((string)$val));

            $html .= "<tr>";
            $html .= "<th>
                        {$label}
                        <span class='awf-field-tech'>{$key}</span>
                    </th>";
            $html .= "<td>{$displayValue}</td>";
            $html .= "</tr>";
        }
        $html .= '</table>';
    }

    $html .= "</div>";

    return $html;
}