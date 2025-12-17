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

        return $htmlRaw;
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

        // Spacing and Height
        $fieldSpacing = $theme->field_spacing ?? '1rem';
        $sectionHeight = ($theme->equal_height_sections ?? true) ? '100%' : 'auto';

        // Label Weight Bold
        $labelWeightVal = ($theme->label_weight_bold ?? false) ? '700' : '400';

        // Submit Full Width
        $submitWidthVal = ($theme->submit_full_width ?? false) ? '100%' : 'auto';

        // Input Style: 'standard', 'flat', 'filled'
        $inputStyle = $theme->input_style ?? 'standard';
        $inputCssProps = "";
        $inputFocusCssProps = "";

        switch ($inputStyle) {
            case 'flat':
                $inputCssProps = "
                    border-width: 0 0 1px 0;
                    border-radius: 0;
                    background-color: transparent;
                    padding-left: 0;
                ";
                $inputFocusCssProps = "box-shadow: none; border-bottom-width: 2px;";
                break;
            case 'filled':
                $inputCssProps = "
                    border-width: 0 0 1px 0;
                    border-radius: 4px 4px 0 0;
                    background-color: rgba(0,0,0, 0.04);
                ";
                $inputFocusCssProps = "box-shadow: none; background-color: rgba(0,0,0, 0.06); border-bottom-width: 2px;";
                break;
            case 'standard':
            default:
                $inputCssProps = "";
                $inputFocusCssProps = "";
                break;
        }

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
  --awf-field-spacing: {$fieldSpacing};
  --awf-section-height: {$sectionHeight};
  --awf-label-weight: {$labelWeightVal};
  --awf-submit-width: {$submitWidthVal};

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
#{$wrapperId} .small {
  font-size: 0.85em;
}
#{$wrapperId} .extra-small {
  font-size: 0.75em;
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
  position: relative;
  overflow: hidden;
}

/* Preview ribbon */
#{$wrapperId} .awf-preview-ribbon {
  position: absolute;
  top: 5px;
  right: -95px;
  transform: rotate(45deg);
  background-color: #dc3545;
  color: #ffffff;
  padding: 5px 40px;
  font-size: 14px;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 1px;
  box-shadow: 0 10px 5px rgba(0,0,0,0.3);
  z-index: 1050;
  pointer-events: none; 
  user-select: none;
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

/* Form Fields */
#{$wrapperId} .awf-field {
  margin-bottom: var(--awf-field-spacing);
}

/* Help Text */
#{$wrapperId} .awf-help-text {
  font-size: 0.85em;   /* small */
  color: #6c757d;    /* text-muted */
  font-style: italic;  /* fst-italic */
  margin-top: 0.25rem;
}

/* Sections */
#{$wrapperId} .awf-section-card,
#{$wrapperId} .awf-section-panel {
  height: var(--awf-section-height);
}

/* Section Title: Panel */
#{$wrapperId} .awf-section-title-panel {
  font-size: 1.25em;      /* h5 */
  margin-bottom: 0;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid var(--bs-border-color);
}

/* Section Title: Card */
#{$wrapperId} .awf-section-title-card {
  font-weight: 700;      /* fw-bold */
  margin: 0;
}

/* Form Footer */
#{$wrapperId} .awf-footer {
  margin-top: 3rem;       /* mt-5 */
  padding-top: 1rem;      /* pt-3 */
  border-top: 1px solid var(--bs-border-color);
  font-size: 0.875em;     /* small */
  color: #6c757d;       /* text-muted */
  text-align: center;
}

/* Required Asterisc */
#{$wrapperId} .awf-required {
  color: #dc3545;       /* text-danger */
  font-weight: bold;
}

/* Labels */
#{$wrapperId} label, 
#{$wrapperId} .form-label, 
#{$wrapperId} .form-check-label {
  font-weight: var(--awf-label-weight);
}

/* Text fields: Inputs / Selects / Textarea */
#{$wrapperId} .form-control,
#{$wrapperId} .form-select {
  {$inputCssProps}
}
#{$wrapperId} .form-control:focus,
#{$wrapperId} .form-select:focus {
  border-color: var(--bs-primary);
  {$inputFocusCssProps}
}

#{$wrapperId} .form-select {
    " . ($inputStyle !== 'standard' ? "background-position: right 0.75rem center;" : "") . "
}

/* Submit Button */
#{$wrapperId} .awf-submit-btn {
  width: var(--awf-submit-width);
}
#{$wrapperId} .awf-submit-container {
  width: 100%;
  text-align: " . ($submitWidthVal === '100%' ? 'center' : 'right') . ";
}

/* User-provided custom CSS */
  {$customCss}
</style>" ."\r\n".str_repeat('  ', $this->indent);
    }

    private function generateBody(FormConfig $config, string $wrapperId, string $actionUrl, bool $isPreview): string {
        $layout = $config->layout;
        
        $headerHtml = $this->decode($layout->header_html);
        $footerHtml = $this->decode($layout->footer_html);

        $checkUrl = str_replace('entryPoint=stic_AWF_responseHandler', 'entryPoint=stic_AWF_checkStatus', $actionUrl);
        $closedFormTitle = htmlspecialchars($layout->closed_form_title);
        $closedFormText = $layout->closed_form_text;

        // Alpine logic: Combine status, initial check if Form is active and sending form data in a single object

        $jsCheckStatus = "";
        if (!$isPreview) {
            $jsCheckStatus = "
                fetch('{$checkUrl}')
                .then(r => r.json())
                .then(d => {
                    this.isActive = d.active;
                    this.message = this.message == '' ? d.message : this.message;
                }).catch(e => console.error(e));
            ";
        }

        $safeMessage = json_encode($closedFormText);
        $alpineData = "{
            isActive: true,
            message: '{$safeMessage}',
            submitting: false,
            init() {
                {$jsCheckStatus}
            },
            submitForm(formElement) {
                // Browser native validation
                if (formElement.checkValidity() === false) {
                    formElement.reportValidity();
                    return;
                }
                this.submitting = true;
                formElement.submit(); 
            }
        }";
        $safeAlpineData = htmlspecialchars($alpineData, ENT_QUOTES, 'UTF-8');

        $html = "";
        // Begin awf-main-card (wrapper)
        $html .= "<div class='awf-main-card p-4 p-md-5 my-4' x-data=\"{$safeAlpineData}\">" ."\r\n".str_repeat('  ', ++$this->indent);
        {
            // Begin awf-relative-wrapper (overlay Wrapper)
            $html .= "<div class='awf-relative-wrapper'>" ."\r\n".str_repeat('  ', ++$this->indent);
            {
                if ($isPreview) {
                    $ribbonText = translate('LBL_PREVIEW_RIBBON', 'stic_Advanced_Web_Forms');
                    $html .= "<div class='awf-preview-ribbon'>{$ribbonText}</div>" ."\r\n".str_repeat('  ', $this->indent);

                    // Floating ToolBar
                    $html .= "<div style='position: fixed; top: 20px; left: 50%; transform: translateX(-50%);".
                                         "background: #343a40; color: #fff; padding: 8px 16px; border-radius: 50px;".
                                         "box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999;".
                                         "display: flex; align-items: center; gap: 12px;". 
                                         "font-family: system-ui, sans-serif; font-size: 14px;'>" ."\r\n".str_repeat('  ', ++$this->indent);
                    {
                        $toolBarText = translate('LBL_PREVIEW_TOOLBAR', 'stic_Advanced_Web_Forms');
                        $activeText = translate('LBL_PREVIEW_ACTIVE_TEXT', 'stic_Advanced_Web_Forms');
                        $inactiveText = translate('LBL_PREVIEW_INACTIVE_TEXT', 'stic_Advanced_Web_Forms');
                        $html .= "<span style='opacity: 0.8; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;'>{$toolBarText}</span>" ."\r\n".str_repeat('  ', $this->indent);
                        $html .= "<div style='width: 1px; height: 16px; background: rgba(255,255,255,0.2);'></div>" ."\r\n".str_repeat('  ', $this->indent);
                        $html .= "<div class='form-check form-switch mb-0' style='min-height: auto;'>" ."\r\n".str_repeat('  ', ++$this->indent);
                        {
                            $html .= "<input class='form-check-input' type='checkbox' role='switch' id='simActiveSwitch' x-model='isActive' style='cursor: pointer;'>" ."\r\n".str_repeat('  ', $this->indent);
                            $html .= "<label class='form-check-label text-white' for='simActiveSwitch' style='cursor: pointer;'>" ."\r\n".str_repeat('  ', ++$this->indent);
                            {
                                $html .= "<span x-text=\"isActive ? '🟢 {$activeText}' : '🔴 {$inactiveText}'\"></span>" ."\r\n".str_repeat('  ', $this->indent);
                            }
                            $html .= "</label>" ."\r\n".str_repeat('  ', --$this->indent);
                        }
                        $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
                    }
                    $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
                }

                // Overlay (Only if !isActive) ---
                $html .= "<div class='awf-overlay' x-show='!isActive' style='display: none;' x-transition>" ."\r\n".str_repeat('  ', ++$this->indent);
                {
                    $html .= "<div class='awf-overlay-content'>" ."\r\n".str_repeat('  ', ++$this->indent);
                    {
                        $html .= "<h3 class='h4 text-danger awf-field'>{$closedFormTitle}</h3>" ."\r\n".str_repeat('  ', $this->indent);
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
                    $alpineSubmit = "";
                } else {
                    $formAttributes = "action='{$actionUrl}' method='POST'";
                    $alpineSubmit = "@submit.prevent='submitForm(\$el)'";
                }

                // Begin Form
                $html .= "<form {$formAttributes} {$alpineSubmit} class='needs-validation' novalidate>" ."\r\n".str_repeat('  ', ++$this->indent);
                {
                    // Honeypot: Invisible anti-spam
                    $html .= "<div style='display:none; opacity:0; position:absolute; left:-9999px;'>" ."\r\n".str_repeat('  ', ++$this->indent);
                    {
                        $html .= "<label for='awf_website'>".translate('LBL_HONEYPOT_LABEL', 'stic_Advanced_Web_Forms')."</label>" ."\r\n".str_repeat('  ', $this->indent);
                        $html .= "<input type='text' id='awf_website' name='awf_honey_pot' value='' tabindex='-1' autocomplete='off'>" ."\r\n".str_repeat('  ', $this->indent);
                    }
                    $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);

                    // Sections Grid
                    $html .= "<div class='awf-grid-sections'>" ."\r\n".str_repeat('  ', ++$this->indent);
                    {
                        foreach ($layout->structure as $section) {
                            $containerClass = ($section->containerType === 'card') ? 'awf-section-card' : 'awf-section-panel';
                            $html .= "<div class='card {$containerClass}'>" ."\r\n".str_repeat('  ', ++$this->indent);
                            {
                                if (!empty($section->title)) {
                                    if ($section->containerType === 'panel') {
                                        $html .= "<div class='awf-section-header-panel'>" ."\r\n".str_repeat('  ', ++$this->indent);
                                        {
                                            $html .= "<h4 class='awf-section-title-panel'>".htmlspecialchars($section->title)."</h4>" ."\r\n".str_repeat('  ', $this->indent);
                                        }
                                        $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
                                    } else if ($section->containerType === 'card') {
                                        $html .= "<div class='card-header awf-section-title-card'>" ."\r\n".str_repeat('  ', ++$this->indent);
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
                                                    $html .= $this->generateDataBlockHtml($block, $layout->theme);
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
                    $html .= "<div class='mt-4 awf-submit-container'>" ."\r\n".str_repeat('  ', ++$this->indent);
                    {
                        $btnText = htmlspecialchars($layout->submit_button_text);
                        $html .= "<button type='submit' class='btn btn-primary px-4 py-2 awf-submit-btn' :disabled='submitting' :class=\"{'disabled': submitting}\">" ."\r\n".str_repeat('  ', ++$this->indent);
                        {
                            $html .= "<span x-show='submitting' class='spinner-border spinner-border-sm me-2' role='status' aria-hidden='true' style='display: none;'></span>" ."\r\n".str_repeat('  ', $this->indent);
                            $html .= "<span>{$btnText}</span>" ."\r\n".str_repeat('  ', $this->indent);
                        }
                        $html .= "</button>" ."\r\n".str_repeat('  ', --$this->indent);
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

    private function generateDataBlockHtml(FormDataBlock $block, FormTheme $theme): string {
        $html = "";
        foreach ($block->fields as $field) {
            if ($field->type_field === DataBlockFieldType::HIDDEN) continue;
            $html .= $this->renderField($block, $field, $theme);
        }
        return $html;
    }

    private function renderField(FormDataBlock $block, FormDataBlockField $field, FormTheme $theme): string {
        $inputName = ($field->type_field === DataBlockFieldType::UNLINKED ? '_detached.' : '') . $block->name . '.' . $field->name;
        $label = htmlspecialchars($field->label);
        $requiredAttr = $field->required_in_form ? 'required' : '';
        $asterisk = $field->required_in_form ? " <span class='awf-required'>*</span>" : '';
        $description = '';
        if ($field->description != '') {
            $description = "<div class='awf-help-text'>{$field->description}</div>";
        }

        // Checkbox has its own representation
        if ($field->subtype_in_form === 'select_checkbox') {
            $html = "<div class='form-check awf-field'>" ."\r\n".str_repeat('  ', ++$this->indent);
            {
                $html .= "<input type='checkbox' name='{$inputName}' class='form-check-input' value='1' id='f_{$inputName}' {$requiredAttr}>" ."\r\n".str_repeat('  ', $this->indent);
                $html .= "<label class='form-check-label' for='f_{$inputName}'>{$label} {$asterisk}</label>" ."\r\n".str_repeat('  ', $this->indent);
                $html .= $description ."\r\n".str_repeat('  ', $this->indent);
            }
            $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
            return $html;
        }
        // Switch has its own representation
        if ($field->subtype_in_form === 'select_switch') {
            $html = "<div class='form-check form-switch awf-field'>" ."\r\n".str_repeat('  ', ++$this->indent);
            {
                $html .= "<input type='checkbox' role='switch' name='{$inputName}' class='form-check-input' value='1' id='f_{$inputName}' {$requiredAttr}>" ."\r\n".str_repeat('  ', $this->indent);
                $html .= "<label class='form-check-label' for='f_{$inputName}'>{$label} {$asterisk}</label>" ."\r\n".str_repeat('  ', $this->indent);
                $html .= $description ."\r\n".str_repeat('  ', $this->indent);
            }
            $html .= "</div>" ."\r\n".str_repeat('  ', --$this->indent);
            return $html;
        }

        $placeholder = htmlspecialchars($field->placeholder ?? '');
        $isFloating = $theme->floating_labels && 
                      $field->subtype_in_form !== 'select_checkbox' && 
                      $field->subtype_in_form !== 'select_switch' &&
                      $field->subtype_in_form !== 'select_checkbox_list' &&
                      $field->subtype_in_form !== 'select_radio';
        // Placeholder is required in Floating labels
        $placeholder = $isFloating ? ($placeholder ?: '...') : $placeholder; 

        $wrapperClass = $isFloating ? 'form-floating awf-field' : 'awf-field';
        $html = "<div class='{$wrapperClass}'>" ."\r\n".str_repeat('  ', ++$this->indent);
        {
            $controlHtml = "";
            if ($field->type_in_form == 'textarea') {
                $controlHtml .= "<textarea name='{$inputName}' class='form-control' id='f_{$inputName}' placeholder='{$placeholder}' style='height: 100px' {$requiredAttr}></textarea>";
            } else if ($field->type_in_form == 'select') {
                // TODO: Review select sub_types 
                // $field->subtype_in_form: 'select', 'select_multiple', 'select_checkbox_list', 'select_radio', 'select_checkbox', 'select_switch'
                $controlHtml .= "<select name='{$inputName}' class='form-select' id='f_{$inputName}' {$requiredAttr}>" ."\r\n".str_repeat('  ', ++$this->indent);
                $controlHtml .= "<option value='' selected></option>" ."\r\n".str_repeat('  ', $this->indent);
                foreach ($field->value_options as $opt) {
                    if ($opt->is_visible) {
                        $val = htmlspecialchars($opt->value);
                        $txt = htmlspecialchars($opt->text);
                        $controlHtml .= "<option value='{$val}'>{$txt}</option>" ."\r\n".str_repeat('  ', $this->indent);
                    }
                }
                $controlHtml .= "</select>" ."\r\n".str_repeat('  ', --$this->indent);
            } else {
                $controlType = 'text';
                switch ($field->subtype_in_form) {
                    case 'text_email': $controlType = 'email'; break;
                    case 'text_tel': $controlType = 'tel'; break;
                    case 'text_password': $controlType = 'password'; break;
                    case 'number': $controlType = 'number'; break;
                    case 'date': $controlType = 'date'; break;
                    case 'date_time': $controlType = 'time'; break;
                    case 'date_datetime': $controlType = 'datetime-local'; break;
                }
                $controlHtml .= "<input type='{$controlType}' name='{$inputName}' class='form-control' id='f_{$inputName}' placeholder='{$placeholder}' {$requiredAttr}>" ."\r\n".str_repeat('  ', $this->indent);
            }

            if ($isFloating) {
                // Floating order: Input, Label
                $html .= $controlHtml ."\r\n".str_repeat('  ', $this->indent);
                $html .= "<label for='f_{$inputName}'>{$label} {$asterisk}</label>" ."\r\n".str_repeat('  ', $this->indent);
            } else {
                // Default order: Label, Input
                $html .= "<label for='f_{$inputName}' class='form-label'>{$label} {$asterisk}</label>" ."\r\n".str_repeat('  ', $this->indent);
                $html .= $controlHtml ."\r\n".str_repeat('  ', $this->indent);
            }
            $html .= $description ."\r\n".str_repeat('  ', $this->indent);
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