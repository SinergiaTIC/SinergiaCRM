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

class FormHtmlGeneratorService {

    /**
     * Genera el código HTML completo del formulario.
     * * @param FormConfig $config La configuración completa.
     * @param string $formId El ID del formulario (para el scoping CSS).
     * @param string $actionUrl La URL donde se hará el POST (entryPoint).
     * @return string El HTML final.
     */
    public function generate(FormConfig $config, string $formId, string $actionUrl): string {
        $layout = $config->layout;
        $wrapperId = 'stic-awf-' . $formId;

        // Generar CSS (Tema + Custom)
        $css = $this->generateCss($layout, $wrapperId);

        // Generar Formulario
        $body = $this->generateBody($config, $wrapperId, $actionUrl);

        // Generar JS (Alpine + Custom)
        $js = $this->generateJs($layout);

        // Html final
        return "
            <div id='{$wrapperId}'>
                {$css}
                {$body}
                {$js}
            </div>
        ";
    }

    private function generateCss(FormLayout $layout, string $wrapperId): string {
        $theme = $layout->theme;
        $primaryRgb = $this->hex2rgb($theme->primary_color);

        // Mapeo de columnas a px (Grid Fluido)
        $sectionMap = ['1' => '100%', '2' => '500px', '3' => '350px'];
        $fieldMap   = ['1' => '100%', '2' => '300px', '3' => '200px', '4' => '150px'];
        $secWidth = $sectionMap[$theme->sections_per_row] ?? '100%';
        $fieldWidth = $fieldMap[$theme->fields_per_row] ?? '100%';

        // Sombras
        $shadows = [
            'none' => 'none', 
            'sm' => '0 .125rem .25rem rgba(0,0,0,.075)',
            'normal' => '0 .5rem 1rem rgba(0,0,0,.15)', 
            'lg' => '0 1rem 3rem rgba(0,0,0,.175)'
        ];
        $shadowVal = $shadows[$theme->shadow_intensity] ?? $shadows['normal'];

        return "
        <style>
            #{$wrapperId} {
                /* Bootstrap Scoped Vars */
                --bs-primary: {$theme->primary_color};
                --bs-primary-rgb: {$primaryRgb};
                --bs-body-bg: {$theme->form_bg_color};
                --bs-body-color: {$theme->text_color};
                --bs-border-color: {$theme->border_color};
                --bs-border-radius: {$theme->border_radius}px;
                --bs-body-font-family: {$theme->font_family};

                /* Own Vars */
                --awf-page-bg: {$theme->page_bg_color};
                --awf-max-width: {$theme->form_width};
                --awf-box-shadow: {$shadowVal};
                --awf-border-width: {$theme->border_width}px;
                --awf-sec-min-width: {$secWidth};
                --awf-field-min-width: {$fieldWidth};
                
                /* Base Styles */
                background-color: var(--awf-page-bg);
                font-family: var(--bs-body-font-family);
                color: var(--bs-body-color);
                font-size: {$theme->font_size}px;
                padding: 2rem 1rem;
                min-height: 100vh; /* Opcional */
            }

            /* Buttons */
            #{$wrapperId} .btn-primary {
                --bs-btn-bg: var(--bs-primary);
                --bs-btn-border-color: var(--bs-primary);
                --bs-btn-hover-bg: var(--bs-primary);
                --bs-btn-hover-border-color: var(--bs-primary);
            }
            #{$wrapperId} .btn-primary:hover { filter: brightness(0.9); }

            /* Main Card */
            #{$wrapperId} .awf-main-card {
                width: 100%;
                max-width: var(--awf-max-width);
                margin: 0 auto;
                background-color: var(--bs-body-bg);
                border: var(--awf-border-width) solid var(--bs-border-color);
                border-radius: var(--bs-border-radius);
                box-shadow: var(--awf-box-shadow);
            }

            /* Grids */
            #{$wrapperId} .awf-grid-sections {
                display: grid; gap: 1.5rem;
                grid-template-columns: repeat(auto-fit, minmax(min(100%, var(--awf-sec-min-width)), 1fr));
            }
            #{$wrapperId} .awf-grid-fields {
                display: grid; gap: 1rem;
                grid-template-columns: repeat(auto-fit, minmax(min(100%, var(--awf-field-min-width)), 1fr));
            }

            /* User-provided custom CSS*/
            {$layout->custom_css}
        </style>";
    }

    private function generateBody(FormConfig $config, string $wrapperId, string $actionUrl): string {
        $layout = $config->layout;
        
        // Start Container
        $html = "<div class='awf-main-card p-4 p-md-5'>";
        
        // Header
        if (!empty($layout->header_html)) {
            $html .= "<div class='mb-4'>{$layout->header_html}</div>";
        }

        // FORM (Alpine x-data init)
        $html .= "<form action='{$actionUrl}' method='POST' x-data='{ d: {} }'>";
        
        // Open grid-sections
        $html .= "<div class='awf-grid-sections'>";

        // Sections
        foreach ($layout->structure as $section) {
            // TODO: Manage container types (tabs, etc.)
            // $containerType = $section->containerType; // 'panel';

            // Begin card
            $html .= "<div class='card shadow-none border h-100'>";
            if (!empty($section->title)) {
                $html .= "<div class='card-header bg-light fw-bold'>" . htmlspecialchars($section->title) . "</div>";
            }            
            // Begin card-body
            $html .= "<div class='card-body'>";
            // Begin grid-fields
            $html .= "<div class='awf-grid-fields'>";

            // Elements
            foreach ($section->elements as $el) {
                if ($el['type'] == 'datablock') {
                    $blockId = $el['ref_id'];
                    $block = $config->data_blocks[$blockId] ?? null;
                    if ($block) {
                        $html .= $this->generateDataBlockHtml($block);
                    }
                }
            }

            $html .= "</div>"; // End grid-fields
            $html .= "</div>"; // End card-body
            $html .= "</div>"; // End card
        }
        
        $html .= "</div>"; // End grid-sections

        // Buttons
        $btnText = htmlspecialchars($layout->submit_button_text);
        $html .= "<div class='mt-4 text-end'>
                    <button type='submit' class='btn btn-primary px-4 py-2'>{$btnText}</button>
                  </div>";

        $html .= "</form>";

        // Footer
        if (!empty($layout->footer_html)) {
            $html .= "<div class='mt-4 text-muted small'>{$layout->footer_html}</div>";
        }

        $html .= "</div>"; // End awf-main-card
        return $html;
    }

    private function generateDataBlockHtml(FormDataBlock $block): string {
        $html = "";
        foreach ($block->fields as $field) {
            if ($field->type_field === DataBlockFieldType::HIDDEN) continue;
            
            $html .= $this->renderField($block, $field);
        }
        return $html;
    }

    private function renderField(FormDataBlock $block, FormDataBlockField $field): string {
        // Build name
        $inputName = $block->name . '.' . $field->name;
        if ($field->type_field === DataBlockFieldType::UNLINKED) {
            $inputName = '_detached.' . $inputName;
        }
        
        $label = htmlspecialchars($field->label);
        $requiredAttr = $field->required_in_form ? 'required' : '';
        $asterisk = $field->required_in_form ? ' <span class="text-danger">*</span>' : '';

        // Begin field wrapper
        $html = "<div class='mb-0'>";
        $html .= "<label class='form-label'>{$label}{$asterisk}</label>";

        // Input type
        switch ($field->type_in_form) {
            case 'select':
                $html .= "<select name='{$inputName}' class='form-select' {$requiredAttr}>";
                $html .= "<option value='' selected disabled>-- Selecciona --</option>";
                foreach ($field->value_options as $opt) {
                    if ($opt->is_visible) {
                        $val = htmlspecialchars($opt->value);
                        $text = htmlspecialchars($opt->text);
                        $html .= "<option value='{$val}'>{$text}</option>";
                    }
                }
                $html .= "</select>";
                break;

            case 'textarea':
                $html .= "<textarea name='{$inputName}' class='form-control' rows='3' {$requiredAttr}></textarea>";
                break;

            case 'checkbox': 
                // Set special wrapper and return it
                $html = "<div class='form-check'>";
                $html .= "<input type='checkbox' name='{$inputName}' class='form-check-input' value='1' id='f_{$inputName}' {$requiredAttr}>";
                $html .= "<label class='form-check-label' for='f_{$inputName}'>{$label}{$asterisk}</label>";
                $html .= "</div>"; 
                return $html;

            case 'date':
            case 'number':
            case 'email':
            case 'password':
            case 'text':
            default:
                $type = $field->type_in_form ?: 'text';
                $html .= "<input type='{$type}' name='{$inputName}' class='form-control' {$requiredAttr}>";
                break;
        }

        $html .= "</div>"; // End wrapper
        return $html;
    }

    private function generateJs(FormLayout $layout): string {
        $js = "";
        
        // Alpine.js 
        // $js .= "<script src='modules/stic_Advanced_Web_Forms/resources/lib/alpine/alpine.min.js' defer></script>";
        // From cdn:
        $js .= '<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>';

        // Custom JS
        if (!empty($layout->custom_js)) {
            $js .= "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    {$layout->custom_js}
                });
            </script>";
        }
        return $js;
    }

    private function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        return "{$r}, {$g}, {$b}";
    }
}