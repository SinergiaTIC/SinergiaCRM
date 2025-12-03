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
     * Genera el codi HTML complet, indentat i llest.
     */
    public function generate(FormConfig $config, string $formId, string $actionUrl): string {
        $layout = $config->layout;
        
        // Assegurem que l'ID és segur per a CSS
        $wrapperId = 'stic-awf-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $formId);

        // 1. Construcció de l'HTML en brut
        $htmlRaw = "<!DOCTYPE html>\n<html lang='es'>\n<head>\n";
        $htmlRaw .= "<meta charset='UTF-8'>\n";
        $htmlRaw .= "<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
        $htmlRaw .= "<title>Formulari</title>\n";
        
        // Llibreries (Bootstrap + Alpine)
        $htmlRaw .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">' . "\n";
        $htmlRaw .= '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>' . "\n";
        
        $htmlRaw .= "</head>\n<body class='bg-light'>\n"; // bg-light per defecte al body per veure el contrast
        
        // Wrapper d'aïllament
        $htmlRaw .= "<div id='{$wrapperId}'>\n";
        
        // Estils i Contingut
        $htmlRaw .= $this->generateCss($layout, $wrapperId);
        $htmlRaw .= $this->generateBody($config, $wrapperId, $actionUrl);
        $htmlRaw .= $this->generateJs($layout);
        
        $htmlRaw .= "</div>\n</body>\n</html>";

        // 2. "Beautify" (Indentar el codi)
        return $this->formatHtml($htmlRaw);
    }

    private function generateCss(FormLayout $layout, string $wrapperId): string {
        $theme = $layout->theme;
        $customCss = $this->decode($layout->custom_css);
        $primaryRgb = $this->hex2rgb($theme->primary_color);

        // Mapeig Grid Fluid
        $sectionMap = ['1' => '100%', '2' => '500px', '3' => '350px'];
        $fieldMap   = ['1' => '100%', '2' => '300px', '3' => '200px', '4' => '150px'];
        $secWidth   = $sectionMap[$theme->sections_per_row] ?? '100%';
        $fieldWidth = $fieldMap[$theme->fields_per_row] ?? '100%';

        // Ombres
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
                /* --- 1. VARIABLES BOOTSTRAP (Sobreescriptura) --- */
                --bs-primary: {$theme->primary_color};
                --bs-primary-rgb: {$primaryRgb};
                --bs-body-bg: {$theme->form_bg_color};
                --bs-body-color: {$theme->text_color};
                --bs-border-color: {$theme->border_color};
                --bs-border-radius: {$theme->border_radius}px;
                --bs-body-font-family: {$theme->font_family};
                
                /* Corregim els botons perquè usin el radi global */
                --bs-btn-border-radius: {$theme->border_radius}px; 

                /* --- 2. VARIABLES PRÒPIES (AWF) --- */
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
                line-height: 1.5;
                padding: 2rem 1rem;
                min-height: 100vh;
            }
        
            /* --- CORRECCIÓ D'ESCALAT (REM -> EM) --- */
            /* Forcem els components de Bootstrap a usar la mida del wrapper */
            
            /* 1. Controls de Formulari i Botons */
            #{$wrapperId} .form-control,
            #{$wrapperId} .form-select,
            #{$wrapperId} .btn,
            #{$wrapperId} .input-group-text, 
            #{$wrapperId} .form-check-input,
            #{$wrapperId} .form-check-label {
                font-size: 1em; /* 100% de la mida configurada al wrapper */
            }

            /* 2. Encapçalaments (H1-H6) */
            /* Mantenim l'escala visual de Bootstrap però relativa al wrapper */
            #{$wrapperId} h1, #{$wrapperId} .h1 { font-size: 2.5em; }
            #{$wrapperId} h2, #{$wrapperId} .h2 { font-size: 2em; }
            #{$wrapperId} h3, #{$wrapperId} .h3 { font-size: 1.75em; }
            #{$wrapperId} h4, #{$wrapperId} .h4 { font-size: 1.5em; }
            #{$wrapperId} h5, #{$wrapperId} .h5 { font-size: 1.25em; }
            #{$wrapperId} h6, #{$wrapperId} .h6 { font-size: 1em; }
            
            /* 3. Ajustos específics */
            /* Treure marge inferior dels labels per fer-ho més compacte */
            #{$wrapperId} .form-label {
                margin-bottom: 0;
            }
            /* Assegurar que els botons tinguin el mateix radi que els inputs */
            #{$wrapperId} .btn {
                border-radius: var(--bs-border-radius);
            }
            #{$wrapperId} .card-header {
                font-size: 1em; /* Assegurar que la capçalera de la card no es faci petita */
            }
            #{$wrapperId} .form-text, 
            #{$wrapperId} .text-muted.small {
                font-size: 0.875em; /* Text petit proporcional */
            }

            /* Botons Primaris (Forcem color) */
            #{$wrapperId} .btn-primary {
                background-color: var(--bs-primary);
                border-color: var(--bs-primary);
            }
            #{$wrapperId} .btn-primary:hover { 
                filter: brightness(0.9); 
            }

            /* Targeta Principal */
            #{$wrapperId} .awf-main-card {
                width: 100%;
                max-width: var(--awf-max-width);
                margin: 0 auto; /* Centrat */
                
                background-color: var(--bs-body-bg);
                border: var(--awf-border-width) solid var(--bs-border-color);
                border-radius: var(--bs-border-radius);
                box-shadow: var(--awf-box-shadow);
            }

            /* Seccions Internes (Cards) */
            #{$wrapperId} .awf-section-card {
                background-color: var(--bs-body-bg);
                border: 1px solid var(--bs-border-color);
                border-radius: calc(var(--bs-border-radius) - 2px); /* Una mica menys que el pare */
                /* L'ombra de la secció interna la deixem subtil */
                box-shadow: 0 2px 4px rgba(0,0,0,0.05); 
            }
            
            /* Capçaleres de Secció 'Calculades' */
            #{$wrapperId} .awf-section-card .card-header {
                /* TRUC: En lloc de posar un color gris fix, posem una capa negra al 3%.
                Això enfosqueix lleugerament qualsevol color de fons que tinguis. 
                Si el fons és vermell, la capçalera serà un vermell una mica més fosc. */
                background-color: rgba(0, 0, 0, 0.03);
                
                /* Assegurem que el text hereta el color configurat */
                color: var(--bs-body-color);
                font-weight: bold;
                border-bottom: 1px solid var(--bs-border-color);
            }
            
            #{$wrapperId} .awf-section-panel {
                /* Panel net: sense vora ni ombra */
                border: none;
                background: transparent;
                box-shadow: none;
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
            
            /* User-provided custom CSS */
            {$customCss}
        </style>";
    }

    private function generateBody(FormConfig $config, string $wrapperId, string $actionUrl): string {
        $layout = $config->layout;
        
        $headerHtml = $this->decode($layout->header_html);
        $footerHtml = $this->decode($layout->footer_html);

        // Inici wrapper
        $html = "<div class='awf-main-card p-4 p-md-5 my-4'>";
        
        // Capçalera
        if (!empty($headerHtml)) {
            $html .= "<div class='mb-4'>{$headerHtml}</div>";
        }

        // Formulari
        $html .= "<form action='{$actionUrl}' method='POST' x-data='{ d: {} }' class='needs-validation' novalidate>";
        
        // Graella de Seccions
        $html .= "<div class='awf-grid-sections'>";

        foreach ($layout->structure as $section) {
            // Determinem la classe segons el tipus de contenidor
            $containerClass = ($section->containerType === 'card') ? 'awf-section-card' : 'awf-section-panel';
            $hasHeader = !empty($section->title);

            $html .= "<div class='card h-100 {$containerClass}'>"; // Afegim classe 'card' de bootstrap base + la nostra
            
            if ($hasHeader) {
                // Si és panel (transparent), potser volem un títol simple (h4), no un card-header
                if ($section->containerType === 'panel') {
                    $html .= "<div class='p-3 pb-0'><h4 class='h5 mb-0'>".htmlspecialchars($section->title)."</h4><hr class='mt-2 mb-0'></div>";
                } else {
                    $html .= "<div class='card-header fw-bold'>".htmlspecialchars($section->title)."</div>";
                }
            }
            
            $html .= "<div class='card-body'><div class='awf-grid-fields'>";

            foreach ($section->elements as $element) {
                if ($element->type == 'datablock') {
                    $block = $config->data_blocks[$element->ref_id] ?? null;
                    if ($block) {
                        $html .= $this->generateDataBlockHtml($block);
                    }
                }
            }

            $html .= "</div></div></div>"; // Tancar grid, body, card
        }
        
        $html .= "</div>"; // Tancar sections grid

        // Botó Enviar
        $btnText = htmlspecialchars($layout->submit_button_text);
        $html .= "<div class='mt-4 text-end'>
                    <button type='submit' class='btn btn-primary px-5 py-2 fw-bold shadow-sm'>{$btnText}</button>
                  </div>";

        $html .= "</form>";

        // Peu
        if (!empty($footerHtml)) {
            $html .= "<div class='mt-5 pt-3 border-top text-muted small text-center'>{$footerHtml}</div>";
        }

        $html .= "</div>"; // Tancar main-card
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
        $inputName = ($field->type_field === DataBlockFieldType::UNLINKED ? '_detached.' : '') . $block->name . '.' . $field->name;
        $label = htmlspecialchars($field->label);
        $requiredAttr = $field->required_in_form ? 'required' : '';
        $asterisk = $field->required_in_form ? ' <span class="text-danger">*</span>' : '';

        $html = "<div class='mb-0'>";
        
        // Checkbox especial (sense label superior)
        if ($field->type_in_form === 'checkbox') {
            $html .= "<div class='form-check mt-2'>";
            $html .= "<input type='checkbox' name='{$inputName}' class='form-check-input' value='1' id='f_{$inputName}' {$requiredAttr}>";
            $html .= "<label class='form-check-label' for='f_{$inputName}'>{$label}{$asterisk}</label>";
            $html .= "</div></div>";
            return $html;
        }

        $html .= "<label class='form-label'>{$label}{$asterisk}</label>";

        if ($field->type_in_form === 'select') {
            $html .= "<select name='{$inputName}' class='form-select' {$requiredAttr}>";
            $html .= "<option value='' selected disabled>-- Selecciona --</option>";
            foreach ($field->value_options as $opt) {
                if ($opt->is_visible) {
                    $html .= "<option value='" . htmlspecialchars($opt->value) . "'>" . htmlspecialchars($opt->text) . "</option>";
                }
            }
            $html .= "</select>";
        } else if ($field->type_in_form === 'textarea') {
            $html .= "<textarea name='{$inputName}' class='form-control' rows='3' {$requiredAttr}></textarea>";
        } else {
            $type = $field->type_in_form ?: 'text';
            $html .= "<input type='{$type}' name='{$inputName}' class='form-control' {$requiredAttr}>";
        }
        
        $html .= "</div>";
        return $html;
    }

    private function generateJs(FormLayout $layout): string {
        $js = "";
        $customJs = $this->decode($layout->custom_js);
        if (!empty($customJs)) {
            $js .= "<script>\ndocument.addEventListener('DOMContentLoaded', function() {\n{$customJs}\n});\n</script>";
        }
        return $js;
    }

    /**
     * Formata l'HTML per fer-lo llegible (Beautify).
     */
    private function formatHtml(string $html): string {
        // Utilitzem DOMDocument per parsejar i formatar
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        // Suprimim errors de parsing HTML5 (DOMDocument és vell i es queixa de tags moderns)
        libxml_use_internal_errors(true);
        
        // Hack per UTF-8: afegir meta charset si no hi és o forçar càrrega
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        libxml_clear_errors();

        // Si volem el DOCTYPE i <html>, usem saveHTML() complet
        // Com que hem usat NOIMPLIED, hem de reconstruir el wrapper si el volem
        // Però com que volem el codi sencer... millor treure els flags si l'input és una pàgina sencera.
        
        // Re-intentem amb càrrega completa si l'input té <html>
        if (strpos($html, '<html') !== false) {
            $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            return $dom->saveHTML();
        }
        
        return $dom->saveHTML();
    }

    /**
     * Helper to decode Base64 safely
     */
    private function decode(string $data): string {
        if (empty($data)) return '';
        
        // Si la dada NO sembla Base64 (per compatibilitat amb dades velles), la retornem tal qual
        if (strpos($data, '<') !== false) return $data; 

        $decoded = base64_decode($data, true);
        if ($decoded === false) return $data;
        
        // Verifiquem UTF-8
        if (!mb_detect_encoding($decoded, 'UTF-8', true)) return $data;
        
        return $decoded;
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