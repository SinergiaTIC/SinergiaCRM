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

    private $indent = 0;

    /**
     * Genera el codi HTML complet, indentat i llest.
     */
    public function generate(FormConfig $config, string $formId, string $actionUrl, bool $isPreview = false): string {
        $layout = $config->layout;
       
        // Ensure Id is valid for CSS
        $wrapperId = 'stic-awf-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $formId);

        $this->indent = 0;
        $htmlRaw = "<!DOCTYPE html>" ."\r\n".str_repeat('  ', $this->indent);
        $htmlRaw .= "<html lang='es'>" ."\r\n".str_repeat('  ', ++$this->indent);
        {
            $htmlRaw .= "<head>" ."\r\n".str_repeat('  ', ++$this->indent);
            {
                $htmlRaw .= "<meta charset='UTF-8'>" ."\r\n".str_repeat('  ', $this->indent);
                $htmlRaw .= "<meta name='viewport' content='width=device-width, initial-scale=1.0'>" ."\r\n".str_repeat('  ', $this->indent);
                $htmlRaw .= "<title>Advanced Web Form</title>" ."\r\n".str_repeat('  ', $this->indent);
        
                // (Bootstrap + Alpine)
                $htmlRaw .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">' ."\r\n".str_repeat('  ', $this->indent);
                $htmlRaw .= '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>' ."\r\n".str_repeat('  ', $this->indent);
            }
            $htmlRaw .= "</head>" ."\r\n".str_repeat('  ', --$this->indent);
            $htmlRaw .= "<body class='bg-light'>" ."\r\n".str_repeat('  ', ++$this->indent);
            {
                // Wrapper 
                $htmlRaw .= "<div id='{$wrapperId}'>" ."\r\n".str_repeat('  ', ++$this->indent);
                {
                    // Styles and Content
                    $htmlRaw .= $this->generateCss($layout, $wrapperId);
                    $htmlRaw .= $this->generateBody($config, $wrapperId, $actionUrl, $isPreview);
                    $htmlRaw .= $this->generateJs($layout);
                }
                $htmlRaw .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
            }
            $htmlRaw .= "</body>" ."\r\n".str_repeat('  ', --$this->indent);
        }     
        $htmlRaw .= "</html>" ."\r\n".str_repeat('  ', --$this->indent);

        return $this->formatHtml($htmlRaw);
    }

    private function generateCss(FormLayout $layout, string $wrapperId): string {
        $theme = $layout->theme;
        $customCss = $this->decode($layout->custom_css);
        $primaryRgb = $this->hex2rgb($theme->primary_color);
        $btnTextColor = $this->getContrastColor($theme->primary_color);

        // Grid
        $secCols   = intval($theme->sections_per_row ?? 1);
        $fieldCols = intval($theme->fields_per_row ?? 1);
        
        $secMinPx   = '350px'; 
        $fieldMinPx = '200px';

        // Shadows
        $shadows = [
            'none' => 'none', 
            'sm' => '0 .125rem .25rem rgba(0,0,0,.075)',
            'normal' => '0 .5rem 1rem rgba(0,0,0,.15)', 
            'lg' => '0 1rem 3rem rgba(0,0,0,.175)'
        ];
        $shadowVal = $shadows[$theme->shadow_intensity] ?? $shadows['normal'];

        return "<style>
#{$wrapperId} {
  /* Bootstrap Vars */
  --bs-primary: {$theme->primary_color};
  --bs-primary-rgb: {$primaryRgb};
  --bs-body-bg: {$theme->form_bg_color};
  --bs-body-color: {$theme->text_color};
  --bs-border-color: {$theme->border_color};
  --bs-border-radius: {$theme->border_radius_controls}px;
  --bs-body-font-family: {$theme->font_family};
  --bs-btn-border-radius: {$theme->border_radius_controls}px;

  /* Stic Vars */
  --awf-page-bg: {$theme->page_bg_color};
  --awf-max-width: {$theme->form_width};
  --awf-box-shadow: {$shadowVal};
  --awf-border-width: {$theme->border_width}px;
  --awf-sec-cols: {$secCols};
  --awf-sec-min-px: {$secMinPx};
  --awf-field-cols: {$fieldCols};
  --awf-field-min-px: {$fieldMinPx};
  --awf-card-radius: {$theme->border_radius_container}px;
                
  /* Base Styles */
  background-color: var(--awf-page-bg);
  font-family: var(--bs-body-font-family);
  color: var(--bs-body-color);
  font-size: {$theme->font_size}px;
  line-height: 1.5;
  padding: 2rem 1rem;
  min-height: 100vh;
}
        
/* Use wraper sizes in Bootstrap components*/
#{$wrapperId} .form-control,
#{$wrapperId} .form-select,
#{$wrapperId} .btn,
#{$wrapperId} .input-group-text, 
#{$wrapperId} .form-check-input,
#{$wrapperId} .form-check-label {
  font-size: 1em; /* 100% de la mida configurada al wrapper */
}
#{$wrapperId} .form-control:focus,
#{$wrapperId} .form-select:focus,
#{$wrapperId} .form-check-input:focus {
  border-color: var(--bs-primary);
  box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
}
#{$wrapperId} .form-check-input:checked {
  background-color: var(--bs-primary);
  border-color: var(--bs-primary);
}
#{$wrapperId} .btn-primary {
  --bs-btn-bg: var(--bs-primary);
  --bs-btn-border-color: var(--bs-primary);
  --bs-btn-hover-bg: var(--bs-primary);
  --bs-btn-hover-border-color: var(--bs-primary);
  --bs-btn-color: {$btnTextColor}; 
  --bs-btn-active-bg: var(--bs-primary);
  --bs-btn-active-border-color: var(--bs-primary);
  --bs-btn-focus-shadow-rgb: var(--bs-primary-rgb);
  color: var(--bs-btn-color);
}
#{$wrapperId} .btn-primary:hover { 
  filter: brightness(0.9); 
}
#{$wrapperId} .btn-primary:active, 
#{$wrapperId} .btn-primary.active { 
  filter: brightness(0.85);
  background-color: var(--bs-primary) !important;
  border-color: var(--bs-primary) !important;
}
#{$wrapperId} h1, #{$wrapperId} .h1 { font-size: 2.5em; }
#{$wrapperId} h2, #{$wrapperId} .h2 { font-size: 2em; }
#{$wrapperId} h3, #{$wrapperId} .h3 { font-size: 1.75em; }
#{$wrapperId} h4, #{$wrapperId} .h4 { font-size: 1.5em; }
#{$wrapperId} h5, #{$wrapperId} .h5 { font-size: 1.25em; }
#{$wrapperId} h6, #{$wrapperId} .h6 { font-size: 1em; }
            
/* Specific settings */
#{$wrapperId} .form-label {
  margin-bottom: 0;
}
#{$wrapperId} .btn {
  border-radius: var(--bs-border-radius);
}
#{$wrapperId} .card-header {
  font-size: 1em;
}
#{$wrapperId} .form-text,
#{$wrapperId} .text-muted.small {
  font-size: 0.875em;
}

/* Main Card */
#{$wrapperId} .awf-main-card {
  width: 100%;
  max-width: var(--awf-max-width);
  min-width: 350px;
  margin: 0 auto;
  background-color: var(--bs-body-bg);
  border: var(--awf-border-width) solid var(--bs-border-color);
  border-radius: var(--awf-card-radius);
  box-shadow: var(--awf-box-shadow);
}

/* Sections */
#{$wrapperId} .awf-section-card {
  background-color: var(--bs-body-bg);
  border: 1px solid var(--bs-border-color);
  border-radius: calc(var(--awf-card-radius) - 2px);
  box-shadow: 0 2px 4px rgba(0,0,0,0.05); 
}
/* Header Sections */
#{$wrapperId} .awf-section-card .card-header {
  background-color: rgba(0, 0, 0, 0.03);
  color: var(--bs-body-color);
  font-weight: bold;
  border-bottom: 1px solid var(--bs-border-color);
}
#{$wrapperId} .awf-section-card .card-header:first-child {
  border-radius: var(--awf-card-radius) var(--awf-card-radius) 0 0;
}
#{$wrapperId} .awf-section-panel {
  border: none;
  background: transparent;
  box-shadow: none;
}

/* Grids */
#{$wrapperId} .awf-grid-sections {
  display: grid; 
  gap: 1.5rem;
  grid-template-columns: repeat(auto-fit, minmax(max(var(--awf-sec-min-px), calc((100% - (1.5rem * (var(--awf-sec-cols) - 1))) / var(--awf-sec-cols))), 1fr));
}
#{$wrapperId} .awf-grid-fields {
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(auto-fit, minmax(max(var(--awf-field-min-px), calc((100% - (1rem * (var(--awf-field-cols) - 1))) / var(--awf-field-cols))), 1fr));
}

/* Lock overlay */
#{$wrapperId} .awf-overlay {
  position: absolute;
  top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(5px);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  border-radius: var(--bs-border-radius);
}
#{$wrapperId} .awf-overlay-content {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  border: 1px solid var(--bs-border-color);
  max-width: 80%;
}
#{$wrapperId} .awf-relative-wrapper {
  position: relative;
  min-height: 300px;
}
            
/* User-provided custom CSS */
  {$customCss}
</style>" ."\r\n".str_repeat('  ', $this->indent);
    }

    private function generateBody(FormConfig $config, string $wrapperId, string $actionUrl, bool $isPreview): string {
        $layout = $config->layout;
        
        $headerHtml = $this->decode($layout->header_html);
        $footerHtml = $this->decode($layout->footer_html);

        $checkUrl = str_replace('entryPoint=stic_AWF_response_handler', 'action=checkStatus', $actionUrl);
        // Alpine logic to check if Form is active
        $alpineData = "{
  isActive: true,
  message: '',
  check() {
    fetch('{$checkUrl}')
    .then(r => r.json())
    .then(d => {
      this.isActive = d.active;
      this.message = d.message;
    }).catch(e => console.error(e));
  }
}";
        $html = "";
        // Begin awf-main-card (wrapper)
        $html .= "<div class='awf-main-card p-4 p-md-5 my-4' x-data=\"{$alpineData}\" x-init=\"check()\">" ."\r\n".str_repeat('  ', ++$this->indent);
        {
            // Begin awf-relative-wrapper (overlay Wrapper)
            $html .= "<div class='awf-relative-wrapper'>" ."\r\n".str_repeat('  ', ++$this->indent);
            {
                // Overlay (Only if !isActive) ---
                $closedFormTitle = htmlspecialchars($layout->closed_form_title);
                $closedFormText = htmlspecialchars($layout->closed_form_text);
                $html .= "<div class='awf-overlay' x-show='!isActive' style='display: none;' x-transition>" ."\r\n".str_repeat('  ', ++$this->indent);
                {
                    $html .= "<div class='awf-overlay-content'>" ."\r\n".str_repeat('  ', ++$this->indent);
                    {
                        $html .= "<h3 class='h4 text-danger mb-3'>{$closedFormTitle}</h3>" ."\r\n".str_repeat('  ', $this->indent);
                        $html .= "<p class='mb-0 lead' x-text='message'>{$closedFormText}</p>" ."\r\n".str_repeat('  ', $this->indent);
                    }
                    $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
                }
                $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);

                // Header
                if (!empty($headerHtml)) {
                    $html .= "<div class='mb-4'>{$headerHtml}</div>" ."\r\n".str_repeat('  ', $this->indent);
                }

                if ($isPreview) {
                    // In preview: deactivate submit action and show alert
                    $formAttributes = 'action="#" onsubmit="event.preventDefault(); alert(\''.translate('LBL_PREVIEW_MODE_ALERT', 'stic_Advanced_Web_Forms').'\'); return false;"';
                } else {
                    $formAttributes = "action='{$actionUrl}' method='POST'";
                }

                // Begin Form
                $html .= "<form {$formAttributes} action='{$actionUrl}' method='POST' x-data='{ d: {}, submitting: false }' class='needs-validation' novalidate ".
                         "@submit.prevent=\"if (\$el.checkValidity()) { submitting = true; \$el.submit(); } else { \$el.classList.add('was-validated'); }\">" ."\r\n".str_repeat('  ', ++$this->indent);
                {
                    // Sections Grid
                    $html .= "<div class='awf-grid-sections'>" ."\r\n".str_repeat('  ', ++$this->indent);
                    {
                        foreach ($layout->structure as $section) {
                            $containerClass = ($section->containerType === 'card') ? 'awf-section-card' : 'awf-section-panel';
                            $html .= "<div class='card h-100 {$containerClass}'>" ."\r\n".str_repeat('  ', ++$this->indent);
                            {
                                if (!empty($section->title)) {
                                    if ($section->containerType === 'panel') {
                                        $html .= "<div class='p-3 pb-0'>" ."\r\n".str_repeat('  ', ++$this->indent);
                                        {
                                            $html .= "<h4 class='h5 mb-0 pb-1 border-bottom'>".htmlspecialchars($section->title)."</h4>" ."\r\n".str_repeat('  ', $this->indent);
                                        }
                                        $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
                                    } else if ($section->containerType === 'card') {
                                        $html .= "<div class='card-header fw-bold'>" ."\r\n".str_repeat('  ', ++$this->indent);
                                        {
                                            $html .= htmlspecialchars($section->title) ."\r\n".str_repeat('  ', $this->indent);
                                        }
                                        $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
                                    }
                                }
                                
                                $html .= "<div class='card-body'>" ."\r\n".str_repeat('  ', ++$this->indent);
                                {
                                    $html .= "<div class='awf-grid-fields'>" ."\r\n".str_repeat('  ', ++$this->indent);
                                    {
                                        foreach ($section->elements as $element) {
                                            if ($element->type == 'datablock') {
                                                $block = $config->data_blocks[$element->ref_id] ?? null;
                                                if ($block) {
                                                    $html .= $this->generateDataBlockHtml($block);
                                                }
                                            }
                                        }
                                    }
                                    $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent); // End awf-grid-fields
                                }
                                $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent); // End card-body
                            }
                            $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent); // End card
                        }
                    }
                    $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent); // End awf-grid-sections

                    // Send Button
                    $html .= "<div class='mt-4 text-end'>" ."\r\n".str_repeat('  ', ++$this->indent);
                    {
                        $btnText = htmlspecialchars($layout->submit_button_text);
                        $html .= "<div class='mt-4 text-end'>" ."\r\n".str_repeat('  ', ++$this->indent);
                        {
                            $html .= "<button type='submit' class='btn btn-primary px-4 py-2' :disabled='submitting' :class=\"{'disabled': submitting}\">" ."\r\n".str_repeat('  ', ++$this->indent);
                            {
                                $html .= "<span x-show='submitting' class='spinner-border spinner-border-sm me-2' role='status' aria-hidden='true' style='display: none;'></span>" ."\r\n".str_repeat('  ', $this->indent);
                                $html .= "<span>{$btnText}</span>" ."\r\n".str_repeat('  ', $this->indent);
                            }
                            $html .= "</button>" ."\r\n".str_repeat('  ', --$this->indent);
                        }
                        $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
                    }
                    $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
                }
                $html .= "</form>" ."\r\n".str_repeat('  ', --$this->indent); // End Form

                // Footer
                if (!empty($footerHtml)) {
                    $html .= "<div class='mt-5 pt-3 border-top text-muted small text-center'>{$footerHtml}</div>" ."\r\n".str_repeat('  ', $this->indent);
                }
            }
            $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent); // End awf-relative-wrapper
        }
        $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent); // End awf-main-card

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

        // Checkbox (label after)
        if ($field->type_in_form === 'checkbox') {
            $html = "<div class='mb-0'>" ."\r\n".str_repeat('  ', ++$this->indent);
            {
                $html .= "<div class='form-check mt-2'>" ."\r\n".str_repeat('  ', ++$this->indent);
                {
                    $html .= "<input type='checkbox' name='{$inputName}' class='form-check-input' value='1' id='f_{$inputName}' {$requiredAttr}>" ."\r\n".str_repeat('  ', $this->indent);
                    $html .= "<label class='form-check-label' for='f_{$inputName}'>{$label}{$asterisk}</label>" ."\r\n".str_repeat('  ', $this->indent);
                }
                $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
            }
            $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
            return $html;
        }

        $html = "<div class='mb-0'>" ."\r\n".str_repeat('  ', ++$this->indent);
        {
            $html .= "<label class='form-label'>{$label}{$asterisk}</label>" ."\r\n".str_repeat('  ', $this->indent);

            if ($field->type_in_form === 'select') {
                $html .= "<select name='{$inputName}' class='form-select' {$requiredAttr}>" ."\r\n".str_repeat('  ', ++$this->indent);
                {
                    $html .= "<option value='' selected disabled>-- Selecciona --</option>" ."\r\n".str_repeat('  ', $this->indent);
                    foreach ($field->value_options as $opt) {
                        if ($opt->is_visible) {
                            $html .= "<option value='" . htmlspecialchars($opt->value) . "'>" . htmlspecialchars($opt->text) . "</option>" ."\r\n".str_repeat('  ', $this->indent);
                        }
                    }
                }
                $html .= "</select>" ."\r\n".str_repeat('  ', --$this->indent);
            } else if ($field->type_in_form === 'textarea') {
                $html .= "<textarea name='{$inputName}' class='form-control' rows='3' {$requiredAttr}></textarea>" ."\r\n".str_repeat('  ', $this->indent);
            } else {
                $type = $field->type_in_form ?: 'text';
                $html .= "<input type='{$type}' name='{$inputName}' class='form-control' {$requiredAttr}>" ."\r\n".str_repeat('  ', $this->indent);
            }
        }
        $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
        return $html;
    }

    private function generateJs(FormLayout $layout): string {
        $js = "";
        $customJs = $this->decode($layout->custom_js);
        if (!empty($customJs)) {
            $js .= "<script>\ndocument.addEventListener('DOMContentLoaded', function() {\n{$customJs}\n});\n</script>" ."\r\n".str_repeat('  ', $this->indent);
        }
        return $js;
    }

    /**
     * Formata l'HTML per fer-lo llegible (Beautify).
     */
    private function formatHtml(string $html): string {
        // Use DOMDocument to clean
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        // Supress parsin HTML5 errors
        libxml_use_internal_errors(true);
        
        // Hack for UTF-8
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        libxml_clear_errors();

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
        if (strpos($data, '<') !== false) return $data; 

        $decoded = base64_decode($data, true);
        if ($decoded === false) return $data;
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

    /**
     * Calculates whether the text should be black or white according to the font color using the YIQ formula.
     */
    private function getContrastColor($hexColor) {
        $hexColor = str_replace('#', '', $hexColor);
        
        if (strlen($hexColor) == 3) {
            $r = hexdec(substr($hexColor, 0, 1) . substr($hexColor, 0, 1));
            $g = hexdec(substr($hexColor, 1, 1) . substr($hexColor, 1, 1));
            $b = hexdec(substr($hexColor, 2, 1) . substr($hexColor, 2, 1));
        } else {
            $r = hexdec(substr($hexColor, 0, 2));
            $g = hexdec(substr($hexColor, 2, 2));
            $b = hexdec(substr($hexColor, 4, 2));
        }

        // YIQ Luminosity Formula
        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        // If it is fosc (<128), white text. If it is clear, black text.
        return ($yiq >= 128) ? '#000000' : '#ffffff';
    }
}