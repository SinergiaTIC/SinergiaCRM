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
 * Returns formatted HTML with the data modified on a bean by the form actions
 * @param SugarBean $focus The bean of stic_AWF_Links
 * @param string $field The field name 
 * @param mixed $value The value of the field
 * @param string $view The view from where it is called (DetailView, ListView...)
 * @return string HTML with the modified data
 */
function getLinkDataHtml($focus, $field, $value, $view) {
    global $app_list_strings, $app_strings;

    $module = $focus->parent_type ?? '';
    $moduleLabel = $module;
    if (isset($app_list_strings['moduleListSingular'][$module])) {
        $moduleLabel = $app_list_strings['moduleListSingular'][$module];
    }

    $actionLabel = $focus->record_action;
    if (isset($app_list_strings['stic_awf_links_record_action_list'][$actionLabel])) {
        $actionLabel = $app_list_strings['stic_awf_links_record_action_list'][$actionLabel];
    }
    
    // Color of the main badge (of the header)
    $badgeColor = '#6c757d'; 
    if ($focus->record_action === 'created') $badgeColor = '#198754'; 
    if ($focus->record_action === 'updated') $badgeColor = '#0d6efd'; 
    if ($focus->record_action === 'enriched') $badgeColor = '#0dc5fd'; 
    if ($focus->record_action === 'skipped') $badgeColor = '#6c757d'; 
    
    // Related record name
    $recordName = $focus->parent_name;
    if (empty($recordName) && !empty($focus->parent_id) && !empty($module)) {
        $relatedBean = BeanFactory::getBean($module, $focus->parent_id);
        if ($relatedBean) {
            $recordName = $relatedBean->get_summary_text();
        }
    }
    if (empty($recordName) || trim($recordName) === '') {
        if (!empty($focus->parent_id)) {
            $recordName = $focus->parent_id; 
        } else {
            $recordName = "<i>(". translate('LBL_NO_NAME', 'stic_AWF_Links') .")</i>";
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
        $targetAttr = "onclick='return false;'";
    }

    // Function to format values for display
    $formatVal = function($v) use ($app_strings) {
        if (is_array($v)) return implode(', ', $v);
        if (is_bool($v)) return $v ? $app_strings['LBL_YES'] : $app_strings['LBL_NO'];
        if ($v === null || $v === '') return '<em style="color:#9aa0a6; font-size:0.9em;">'. translate('LBL_VALUE_EMPTY', 'stic_AWF_Links') .'</em>';
        return nl2br(htmlspecialchars((string)$v));
    };

    // CSS Styles for the HTML output
    $html = "
    <style>
        .awf-link-container { border: 1px solid #dee2e6; border-radius: 6px; overflow: hidden; font-family: system-ui, -apple-system, sans-serif; max-width: 900px; }

        /* Header */
        .awf-link-header { background-color: #f8f9fa; padding: 6px 12px; border-bottom: 1px solid #dee2e6; display: flex; align-items: center; justify-content: space-between; }
        .awf-link-title-group { display: flex; flex-direction: column; }
        .awf-link-module { font-weight: bold; font-size: 1em; color: #212529; line-height: 1.1; }
        .awf-link-record { font-size: 0.85em; color: #6c757d; text-decoration: none; }
        .awf-link-record:hover { text-decoration: underline; color: #495057; }
        .awf-link-badge-main { background-color: {$badgeColor}; color: white; padding: 3px 6px; border-radius: 4px; font-size: 0.75em; text-transform: uppercase; font-weight: 600; white-space: nowrap; margin-left: 10px;}
        
        /* Table */
        .awf-link-table { width: 100%; border-collapse: collapse; font-size: 12.5px; line-height: 1.3; }
        .awf-link-table th, .awf-link-table td { padding: 6px 10px; border-bottom: 1px solid #eee; vertical-align: middle; text-align: left; }
        .awf-link-table th { background-color: transparent; color: #495057; font-weight: 600; width: 30%; position: relative; }
        .awf-link-table td.awf-col-sent { width: 35%; }
        .awf-link-table td.awf-col-final { width: 35%; }
        
        /* Field information */
        .awf-field-info { display: flex; flex-direction: column; gap: 1px; padding-right: 18px;}
        .awf-field-tech { color: #adb5bd; font-size: 0.8em; font-family: monospace; font-weight: normal; }
        
        /* Row background */
        .awf-row-success { background-color: #f6fcf8; }
        .awf-row-success th { border-left: 3px solid #1e8e3e; }
        .awf-row-warning { background-color: #fffbf0; }
        .awf-row-warning th { border-left: 3px solid #f9ab00; }
        .awf-row-danger { background-color: #fef6f6; }
        .awf-row-danger th { border-left: 3px solid #d93025; }
        .awf-row-secondary { background-color: #fbfbfc; }
        .awf-row-secondary th { border-left: 3px solid #adb5bd; }
        .awf-row-info { background-color: #f8fbff; }
        .awf-row-info th { border-left: 3px solid #1a73e8; }
        
        /* Icons */
        .awf-tiny-icon { position: absolute; top: 4px; right: 4px; font-size: 9px; cursor: default; padding: 1px 3px; border-radius: 3px; border: 1px solid transparent; }
        .awf-icon-success { background: #e6f4ea; color: #1e8e3e; border-color: #ceead6; }
        .awf-icon-warning { background: #fef7e0; color: #b06000; border-color: #fce8b2; }
        .awf-icon-danger { background: #fce8e6; color: #d93025; border-color: #fad2cf; }
        .awf-icon-secondary { background: #f1f3f4; color: #5f6368; border-color: #dadce0; }
        .awf-icon-info { background: #e8f0fe; color: #1a73e8; border-color: #d2e3fc; }
        
        /* Values */
        .awf-old-val { color: #d93025; text-decoration: line-through; background: #fce8e6; padding: 1px 4px; border-radius: 3px; margin-right: 4px; display: inline-block; margin-bottom: 1px;}
        .awf-new-val { background: #e6f4ea; color: #1e8e3e; padding: 1px 4px; border-radius: 3px; display: inline-block;}
        .awf-final-val { color: #212529; }
        
        .awf-empty-msg { padding: 10px; font-style: italic; color: #6c757d; text-align: center; font-size: 13px; }
    </style>";

    $html .= "<div class='awf-link-container'>";
    $html .= "<div class='awf-link-header'>
                <div class='awf-link-title-group'>
                    <span class='awf-link-module'>{$moduleLabel}</span>
                    <a href='{$recordUrl}' class='{$linkClass}' {$targetAttr}>{$recordName}</a>
                </div>
                <span class='awf-link-badge-main'>{$actionLabel}</span>
              </div>";

    $data = json_decode(html_entity_decode($focus->submitted_data), true);
    if (empty($focus->submitted_data) || !is_array($data) || empty($data)) {
        $html .= "<div class='awf-empty-msg'>". translate('LBL_NO_MODIFIED_DATA', 'stic_AWF_Links') ."</div>";
    } else {
        $html .= '<table class="awf-link-table">';
        $html .= '<thead><tr>';
        $html .= '<th>'. translate('LBL_FIELD', 'stic_AWF_Links') .'</th>';
        $html .= '<th class="awf-col-sent">'. translate('LBL_SENT_VALUE', 'stic_AWF_Links') .'</th>';
        $html .= '<th class="awf-col-final">'. translate('LBL_FINAL_VALUE', 'stic_AWF_Links') .'</th>';
        $html .= '</tr></thead>';
        $html .= '<tbody>';

        $fieldDefs = [];
        if (!empty($module)) {
            $seed = BeanFactory::newBean($module);
            if ($seed) {
                $fieldDefs = $seed->field_defs;
            }
        }

        foreach ($data as $key => $valData) {
            if ($key === 'id' || $key === 'date_modified') continue;

            $status = 'applied';
            $val = $valData;
            $oldVal = null;

            if (is_array($valData) && isset($valData['status'])) {
                $status = $valData['status'];
                $val = $valData['value'];
                $oldVal = $valData['oldValue'] ?? $valData['old_value'] ?? null;
            }

            $isMetadata = !isset($fieldDefs[$key]);

            $label = $key;
            if (!$isMetadata && isset($fieldDefs[$key]['vname'])) {
                $label = translate($fieldDefs[$key]['vname'], $module);
                $label = rtrim($label, ':');
            } elseif ($isMetadata) {
                $label = ucwords(str_replace('_', ' ', trim($key, '_')));
            }

            $displayVal = $formatVal($val);
            $displayOld = $formatVal($oldVal);
            
            $statusIcon = "";
            $finalHtml = "";
            $sentHtml = $displayVal; 
            $rowClass = "";

            if ($isMetadata) {
                $rowClass = "awf-row-info";
                $statusIcon = "<span class='awf-tiny-icon awf-icon-info' title='". translate('LBL_FIELD_METADATA', 'stic_AWF_Links') ."'>i</span>";
                $sentHtml = "<span style='color: #adb5bd;'>&mdash;</span>";
                $finalHtml = "<span class='awf-final-val'>{$displayVal}</span>";
            } else {
                switch($status) {
                    case 'applied':
                        $rowClass = "awf-row-success";
                        $statusIcon = "<span class='awf-tiny-icon awf-icon-success' title='". translate('LBL_FIELD_CHANGE_APPLIED', 'stic_AWF_Links') ."'>✓</span>";
                        if ($oldVal !== $val) {
                            $finalHtml = "<span class='awf-old-val'>{$displayOld}</span><wbr><span class='awf-new-val'>{$displayVal}</span>";
                        } else {
                            $finalHtml = "<span class='awf-final-val'>{$displayVal}</span>";
                        }
                        break;

                    case 'ignored_enrich':
                        $rowClass = "awf-row-warning";
                        $statusIcon = "<span class='awf-tiny-icon awf-icon-warning' title='". translate('LBL_FIELD_CHANGE_IGNORED_ENRICH', 'stic_AWF_Links') ."'>⚠</span>";
                        $finalHtml = "<span class='awf-final-val'>{$displayOld}</span>";
                        break;

                    case 'skipped_duplicate':
                        $rowClass = "awf-row-danger";
                        $statusIcon = "<span class='awf-tiny-icon awf-icon-danger' title='". translate('LBL_FIELD_CHANGE_SKIPPED_DUPLICATE', 'stic_AWF_Links') ."'>✗</span>";
                        $finalHtml = "<em style='color:#6c757d; font-size: 0.9em;'>". translate('LBL_NOT_APPLIED', 'stic_AWF_Links') ."</em>";
                        break;

                    case 'unchanged':
                    default:
                        $rowClass = "awf-row-secondary";
                        $statusIcon = "<span class='awf-tiny-icon awf-icon-secondary' title='". translate('LBL_FIELD_CHANGE_UNCHANGED', 'stic_AWF_Links') ."'>=</span>";
                        $finalHtml = "<span class='awf-final-val'>{$displayOld}</span>";
                        break;
                }
            }

            $html .= "<tr class='{$rowClass}'>";
            // First column: Small floating icon, Field and Technical Name
            $html .= "<th>
                        {$statusIcon}
                        <div class='awf-field-info'>
                            <span>{$label}</span>
                            <span class='awf-field-tech'>{$key}</span>
                        </div>
                      </th>";
            // Second and Third column
            $html .= "<td class='awf-col-enviat'>{$sentHtml}</td>";
            $html .= "<td class='awf-col-final'>{$finalHtml}</td>";
            $html .= "</tr>";
        }
        $html .= '</tbody></table>';
    }

    $html .= "</div>";

    return $html;
}