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
     * Genera el documento HTML completo (con doctype, head, body).
     * Para visualizaciones standalone o iframes.
     */
    public function generate(FormConfig $config, string $formId, string $actionUrl, bool $isPreview = false): string {
        $this->indent = 0;
        $htmlRaw = "<!DOCTYPE html>" .$this->newLine();
        $htmlRaw .= "<html lang='es'>" .$this->newLine('+');
        {
            $htmlRaw .= "<head>" .$this->newLine('+');
            {
                $htmlRaw .= "<meta charset='UTF-8'>" .$this->newLine();
                $htmlRaw .= "<meta name='viewport' content='width=device-width, initial-scale=1.0'>" .$this->newLine();
                $htmlRaw .= "<title>Advanced Web Form</title>" .$this->newLine();
        
                // Librerías externas (Bootstrap + Alpine)
                $htmlRaw .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">' .$this->newLine();
                $htmlRaw .= '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>' .$this->newLine();
            }
            $htmlRaw .= "</head>" .$this->newLine('-');
            $htmlRaw .= "<body class='bg-light'>" .$this->newLine('+');
            {
                // Inyectamos el HTML del formulario
                $htmlRaw .= $this->generateFormHtml($config, $formId, $actionUrl, $isPreview);
            }
            $htmlRaw .= "</body>" .$this->newLine('-');
        }     
        $htmlRaw .= "</html>" .$this->newLine('-');

        return $htmlRaw;
    }


    /**
     * Genera sólo el HTML del formulario (el div wrapper y su contenido, sin head ni body).
     * Para incrustar en otras páginas.
     */
    public function generateFormHtml(FormConfig $config, string $formId, string $actionUrl, bool $isPreview): string {
        $layout = $config->layout;
       
        // Ensure Id is valid for CSS
        $wrapperId = 'stic-awf-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $formId);

        // Wrapper 
        $htmlRaw = "<div id='{$wrapperId}'>" .$this->newLine('+');
        {
            // Styles and Content
            $htmlRaw .= $this->generateCss($layout, $wrapperId);
            $htmlRaw .= $this->generateBody($config, $wrapperId, $actionUrl, $isPreview);
            $htmlRaw .= $this->generateJs($config, $formId);
        }
        $htmlRaw .= "</div>" .$this->newLine('-');

        return $htmlRaw;
    }

    private function generateCss(FormLayout $layout, string $wrapperId): string {
        $theme = $layout->theme;
        $customCss = $this->decode($layout->custom_css);
        $primaryRgb = AWF_Utils::hex2rgb($theme->primary_color);
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
        $selectCssProps = "";
        $inputFocusCssProps = "";
        $floatingLabelFix = "";

        switch ($inputStyle) {
            case 'flat':
                $inputCssProps = "
  border-width: 0 0 1px 0;
  border-radius: 0;
  background-color: transparent;
  padding-left: 0;
                ";
                $inputFocusCssProps = "box-shadow: none; border-bottom-width: 2px;";
                $floatingLabelFix = "background-color: transparent !important;";
                $selectCssProps = "background-position: right 0.75rem center;";
                break;
            case 'filled':
                $inputCssProps = "
  border-width: 0 0 1px 0;
  border-radius: 4px 4px 0 0;
  background-color: rgba(0,0,0, 0.04);
                ";
                $inputFocusCssProps = "box-shadow: none; background-color: rgba(0,0,0, 0.06); border-bottom-width: 2px;";
                $floatingLabelFix = "background-color: transparent !important;";
                $selectCssProps = "background-position: right 0.75rem center;";
                break;
            case 'standard':
            default:
                $inputCssProps = "";
                $inputFocusCssProps = "";
                $floatingLabelFix = "";
                break;
        }

        if ($inputCssProps!="") {
            $inputCssProps = "/* Text fields: Inputs / Selects / Textarea */
#{$wrapperId} .form-control,
#{$wrapperId} .form-select {
{$inputCssProps}
}
            ";
        }
        if ($selectCssProps!="") {
            $selectCssProps = "#{$wrapperId} .form-select { 
  {$selectCssProps}
}
            ";
        }
        if ($floatingLabelFix!="") {
            $floatingLabelFix = "#{$wrapperId} .form-floating > .form-control:focus ~ label::after,
#{$wrapperId} .form-floating > .form-control:not(:placeholder-shown) ~ label::after,
#{$wrapperId} .form-floating > .form-select ~ label::after {
{$floatingLabelFix}
}
            ";
        }

        // Adapt Bootstrap validation marks
        $validationFix = "
/* Remove Bootstrap positive validation */
#{$wrapperId} .was-validated .form-control:valid,
#{$wrapperId} .was-validated .form-select:valid,
#{$wrapperId} .was-validated .form-check-input:valid {
  border-color: var(--bs-border-color);
  background-image: none;
  box-shadow: none;
}
/* Adjust Bootstrap negative validation mark */
#{$wrapperId} .was-validated .form-control:invalid,
#{$wrapperId} .was-validated .form-select:invalid {
  background-image: none !important;
  border-color: #dc3545;
}";

        // Icons
        $iconRules = $this->getSvgIconsData();
        $iconCss = implode("\n", $iconRules);
        $iconCss = str_replace('%WRAPPER_ID%', $wrapperId, $iconCss);
        $chevronCss = $this->getChevronCss($wrapperId);

        $browserIconFix = "
/* Hide native Webkit icons (Chrome/Edge/Safari) */
#{$wrapperId} .awf-icon-date::-webkit-calendar-picker-indicator,
#{$wrapperId} .awf-icon-date-time::-webkit-calendar-picker-indicator,
#{$wrapperId} .awf-icon-date-datetime::-webkit-calendar-picker-indicator {
  background: transparent;
  bottom: 0;
  color: transparent;
  cursor: pointer;
  height: auto;
  left: 75%;
  position: absolute;
  right: 0;
  top: 0;
  width: auto;
  z-index: 10;
}
        
#{$wrapperId} .awf-icon-date, 
#{$wrapperId} .awf-icon-date-time, 
#{$wrapperId} .awf-icon-date-datetime {
  position: relative; 
}
";

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
  font-size: 1em;
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
#{$wrapperId} .btn-primary:hover { filter: brightness(0.9); }
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
#{$wrapperId} .form-label { margin-bottom: 0; }
#{$wrapperId} .btn { border-radius: var(--bs-border-radius); }
#{$wrapperId} .card-header { font-size: 1em; }
#{$wrapperId} .form-text, #{$wrapperId} .small { font-size: 0.85em; }
#{$wrapperId} .extra-small { font-size: 0.75em; }

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
#{$wrapperId} .awf-section-card {
  background-color: var(--bs-body-bg);
  border: 1px solid var(--bs-border-color);
  border-radius: calc(var(--awf-card-radius) - 2px);
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  height: var(--awf-section-height);
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

{$inputCssProps}
#{$wrapperId} .form-control:focus,
#{$wrapperId} .form-select:focus {
  border-color: var(--bs-primary);
{$inputFocusCssProps}
}
{$selectCssProps}
{$floatingLabelFix}

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

/* Icons */
{$iconCss}
{$chevronCss}
{$browserIconFix}
{$validationFix}
</style>" .$this->newLine();
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
        $alpineData = <<<JS
        {
            isActive: true,
            loadTime: 0,
            message: {$safeMessage},
            submitting: false,
            
            init() {
                this.loadTime = Math.floor(Date.now() / 1000);
                {$jsCheckStatus}
            },

            // Validate single input
            validateInput(input) {
                // Clear previous errors
                input.setCustomValidity(''); 
                input.classList.remove('is-invalid');

                let customErrorMessage = '';
                const form = input.closest('form');

                // Custom validation rules from data-awf-validations
                if (input.dataset.awfValidations) {
                    try {
                        const rules = JSON.parse(input.dataset.awfValidations);
                        
                        for (const rule of rules) {
                            // Check condition
                            if (rule.condition_field && rule.condition_field !== '') {
                                // Find the condition input within the same form
                                const condInputId = 'f_' + rule.condition_field;
                                const condInput = form.querySelector('[id="' + condInputId + '"]');
                                
                                // If condition not met, skip this rule
                                if (condInput && condInput.value != rule.condition_value) {
                                    continue;
                                }
                            }

                            // Execute validator
                            const validatorFn = AWF_Validators[rule.validator];
                            if (validatorFn) {
                                if (!validatorFn(input.value, rule.params, form)) {
                                    // Mark input as invalid
                                    customErrorMessage = rule.message || 'Validation error';
                                    input.setCustomValidity(customErrorMessage);
                                    break; 
                                }
                            }
                        }
                    } catch (e) {
                        console.error('Validation error parsing rules', e);
                    }
                }

                // Check native and custom validity (custom via setCustomValidity)
                if (!input.checkValidity()) {
                    input.classList.add('is-invalid');

                    // Error message: if custom error, use it; else use native browser message
                    const finalMessage = customErrorMessage || input.validationMessage;

                    // Show error message in invalid-feedback div
                    const parent = input.parentElement;
                    let feedback = parent.querySelector('.invalid-feedback');
                    if (!feedback) {
                        feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        parent.appendChild(feedback);
                    }
                    feedback.textContent = finalMessage;

                    return false;
                }

                return true;
            },

            submitForm(formElement) {
                if (this.submitting) return;
                
                let formValid = true;

                const inputs = formElement.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    if (input.willValidate && !this.validateInput(input)) {
                        formValid = false;
                    }
                });

                if (!formValid) {
                    formElement.classList.add('was-validated');
                    
                    // Scroll to first invalid field
                    const firstError = formElement.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus({ preventScroll: true });
                    }
                    return;
                }
                
                this.submitting = true;
                formElement.submit();
            }
        }
JS;

        $safeAlpineData = htmlspecialchars($alpineData, ENT_QUOTES, 'UTF-8');

        $html = "";
        // Begin awf-main-card (wrapper)
        $html .= "<div class='awf-main-card p-4 p-md-5 my-4' x-data=\"{$safeAlpineData}\">" .$this->newLine('+');
        {
            // Begin awf-relative-wrapper (overlay Wrapper)
            $html .= "<div class='awf-relative-wrapper'>" .$this->newLine('+');
            {
                if ($isPreview) {
                    $ribbonText = translate('LBL_PREVIEW_RIBBON', 'stic_Advanced_Web_Forms');
                    $html .= "<div class='awf-preview-ribbon'>{$ribbonText}</div>" .$this->newLine();

                    // Floating ToolBar
                    $html .= "<div style='position: fixed; top: 20px; left: 50%; transform: translateX(-50%);".
                                         "background: #343a40; color: #fff; padding: 8px 16px; border-radius: 50px;".
                                         "box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999;".
                                         "display: flex; align-items: center; gap: 12px;". 
                                         "font-family: system-ui, sans-serif; font-size: 14px;'>" .$this->newLine('+');
                    {
                        $toolBarText = translate('LBL_PREVIEW_TOOLBAR', 'stic_Advanced_Web_Forms');
                        $activeText = translate('LBL_PREVIEW_ACTIVE_TEXT', 'stic_Advanced_Web_Forms');
                        $inactiveText = translate('LBL_PREVIEW_INACTIVE_TEXT', 'stic_Advanced_Web_Forms');
                        $html .= "<span style='opacity: 0.8; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;'>{$toolBarText}</span>" .$this->newLine();
                        $html .= "<div style='width: 1px; height: 16px; background: rgba(255,255,255,0.2);'></div>" .$this->newLine();
                        $html .= "<div class='form-check form-switch mb-0' style='min-height: auto;'>" .$this->newLine('+');
                        {
                            $html .= "<input class='form-check-input' type='checkbox' role='switch' id='simActiveSwitch' x-model='isActive' style='cursor: pointer;'>" .$this->newLine();
                            $html .= "<label class='form-check-label text-white' for='simActiveSwitch' style='cursor: pointer;'>" .$this->newLine('+');
                            {
                                $html .= "<span x-text=\"isActive ? '🟢 {$activeText}' : '🔴 {$inactiveText}'\"></span>" .$this->newLine();
                            }
                            $html .= "</label>" .$this->newLine('-');
                        }
                        $html .= "</div>" .$this->newLine('-');
                    }
                    $html .= "</div>" .$this->newLine('-');
                }

                // Overlay (Only if !isActive) ---
                $html .= "<div class='awf-overlay' x-show='!isActive' style='display: none;' x-transition>" .$this->newLine('+');
                {
                    $html .= "<div class='awf-overlay-content'>" .$this->newLine('+');
                    {
                        $html .= "<h3 class='h4 text-danger awf-field'>{$closedFormTitle}</h3>" .$this->newLine();
                        $html .= "<p class='mb-0 lead' x-text='message'>{$closedFormText}</p>" .$this->newLine();
                    }
                    $html .= "</div>" .$this->newLine('-');
                }
                $html .= "</div>" .$this->newLine('-');

                // Header
                if (!empty($headerHtml)) {
                    $html .= "<div class='mb-4'>{$headerHtml}</div>" .$this->newLine();
                }

                if ($isPreview) {
                    // In preview: deactivate submit action and show alert
                    $formAttributes = 'action="#" autocomplete="off" onsubmit="event.preventDefault(); alert(\''.translate('LBL_PREVIEW_MODE_ALERT', 'stic_Advanced_Web_Forms').'\'); return false;"';
                    $alpineSubmit = "";
                } else {
                    $formAttributes = "action='{$actionUrl}' method='POST' autocomplete='off' novalidate enctype='multipart/form-data'";
                    $alpineSubmit = "@submit.prevent='submitForm(\$el)'";
                }

                // Begin Form
                $html .= "<form {$formAttributes} {$alpineSubmit} class='needs-validation'>" .$this->newLine('+');
                {
                    // Honeypot: Invisible anti-spam
                    $html .= "<div style='display:none; opacity:0; position:absolute; left:-9999px;'>" .$this->newLine('+');
                    {
                        $html .= "<label for='awf_website'>".translate('LBL_HONEYPOT_LABEL', 'stic_Advanced_Web_Forms')."</label>" .$this->newLine();
                        $html .= "<input type='text' id='awf_website' name='awf_honey_pot' value='' tabindex='-1' autocomplete='off'>" .$this->newLine();
                    }
                    $html .= "</div>" .$this->newLine('-');

                    // TimeTrap: Hidden field to track time spent on form
                    $html .= "<input type='hidden' name='awf_submission_ts' x-model='loadTime'>";

                    // Captura de la url del formulario
                    $html .= "<input type='hidden' name='awf_form_url' x-init=\"\$el.value = (window.self !== window.top) ? document.referrer : window.location.href\">" . $this->newLine();
                    
                    // Sections Grid
                    $html .= "<div class='awf-grid-sections'>" .$this->newLine('+');
                    {
                        foreach ($layout->structure as $section) {
                            $containerClass = ($section->containerType === 'card') ? 'awf-section-card' : 'awf-section-panel';

                            // Collapsible logic
                            $isCollapsible = !empty($section->isCollapsible);
                            $startOpen = empty($section->isCollapsed) ? 'true' : 'false';
                            $xDataAttr = $isCollapsible ? "x-data=\"{ open: {$startOpen} }\" @invalid.capture=\"open = true\"" : "";
                            $styleAttr = $isCollapsible ? "style='height: auto !important;'" : "";

                            $html .= "<div class='card {$containerClass}' {$xDataAttr} {$styleAttr}>" .$this->newLine('+');
                            {
                                // Header
                                if ($section->showTitle && !empty($section->title)) {
                                    $toggleBtn = "";
                                    $cursorStyle = "";

                                    if ($isCollapsible) {
                                        $cursorStyle = "cursor: pointer;"; 
                                        $toggleBtn = "<button type='button' class='btn btn-sm btn-link text-decoration-none text-reset p-0 ms-2' @click='open = !open'>" .$this->newLine('+');
                                        {
                                            $toggleBtn .= "<span class='awf-icon-toggle' :class=\"open ? 'open' : ''\"></span>" .$this->newLine();
                                        }
                                        $toggleBtn .= "</button>" .$this->newLine('-');
                                    }

                                    if ($section->containerType === 'panel') {
                                        $clickAction = $isCollapsible ? "@click='open = !open'" : "";

                                        $html .= "<div class='awf-section-header-panel d-flex justify-content-between align-items-center' {$clickAction} style='{$cursorStyle}'>" .$this->newLine('+');
                                        {
                                            $html .= "<h4 class='awf-section-title-panel mb-0 border-0 pb-0'>".htmlspecialchars($section->title)."</h4>" .$this->newLine();
                                            $html .= $toggleBtn .$this->newLine();
                                        }
                                        $html .= "</div>" .$this->newLine('-');
                                        $html .= "<hr class='mt-1 mb-3' style='opacity: 0.15'>" .$this->newLine();

                                    } else if ($section->containerType === 'card') {
                                        $clickAction = $isCollapsible ? "@click='open = !open'" : "";

                                        $html .= "<div class='card-header awf-section-title-card d-flex justify-content-between align-items-center' {$clickAction} style='{$cursorStyle}'>" .$this->newLine('+');
                                        {
                                            $html .= "<span>".htmlspecialchars($section->title)."</span>" .$this->newLine();
                                            $html .= $toggleBtn .$this->newLine();
                                        }
                                        $html .= "</div>" .$this->newLine('-');
                                    }
                                }
                                
                                $showAttr = $isCollapsible ? "x-show='open' x-transition" : "";
                                $html .= "<div class='card-body' {$showAttr}>" .$this->newLine('+');
                                {
                                    $html .= "<div class='awf-grid-fields'>" .$this->newLine('+');
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
                                    $html .= "</div>" .$this->newLine('-');
                                }
                                $html .= "</div>" .$this->newLine('-');
                            }
                            $html .= "</div>" .$this->newLine('-');
                        }
                    }
                    $html .= "</div>" .$this->newLine('-');

                    // Send Button
                    $html .= "<div class='mt-4 awf-submit-container'>" .$this->newLine('+');
                    {
                        $btnText = htmlspecialchars($layout->submit_button_text);
                        $html .= "<button type='submit' class='btn btn-primary px-4 py-2 awf-submit-btn' :disabled='submitting' :class=\"{'disabled': submitting}\">" .$this->newLine('+');
                        {
                            $html .= "<span x-show='submitting' class='spinner-border spinner-border-sm me-2' role='status' aria-hidden='true' style='display: none;'></span>" .$this->newLine();
                            $html .= "<span>{$btnText}</span>" .$this->newLine();
                        }
                        $html .= "</button>" .$this->newLine('-');
                    }
                    $html .= "</div>" .$this->newLine('-');
                }
                $html .= "</form>" .$this->newLine('-');

                // Footer
                if (!empty($footerHtml)) {
                    $html .= "<div class='mt-5 pt-3 border-top text-muted small text-center'>{$footerHtml}</div>" .$this->newLine();
                }
            }
            $html .= "</div>" .$this->newLine('-');
        }
        $html .= "</div>" .$this->newLine('-');

        return $html;
    }

    private function generateDataBlockHtml(FormDataBlock $block, FormTheme $theme): string {
        $html = "";
        foreach ($block->fields as $field) {
            if ($field->type_field === DataBlockFieldType::FIXED) continue;
            $html .= $this->renderField($block, $field, $theme);
        }
        return $html;
    }

    private function renderField(FormDataBlock $block, FormDataBlockField $field, FormTheme $theme): string {
        $inputName = ($field->type_field === DataBlockFieldType::UNLINKED ? '_detached.' : '') . $block->name . '.' . $field->name;

        // Render hidden fields differently: only input without label or wrapper
        if ($field->type_in_form === 'hidden') {
            $val = htmlspecialchars($field->value ?? '', ENT_QUOTES, 'UTF-8');
            return "<input type='hidden' name='{$inputName}' id='f_{$inputName}' value='{$val}'>" . $this->newLine();
        }

        $label = htmlspecialchars($field->label);
        $requiredAttr = $field->required_in_form ? 'required' : '';
        $asterisk = $field->required_in_form ? " <span class='awf-required'>*</span>" : '';

        $validationsAttr = " @blur='validateInput(\$el)'";;
        if (!empty($field->validations)) {
            $rules = [];
            foreach ($field->validations as $val) {
                $rules[] = [
                    'name' => $val->name,
                    'validator' => $val->validator,
                    'message' => $val->message,
                    'params' => $val->params,
                    'condition_field' => $val->condition_field ?? '',
                    'condition_value' => $val->condition_value ?? ''
                ];
            }
            if (!empty($rules)) {
                $jsonRules = htmlspecialchars(json_encode($rules), ENT_QUOTES, 'UTF-8');
                $validationsAttr .= " data-awf-validations='{$jsonRules}'";
            }
        }

        $description = "";
        if ($field->description != '') {
            $parsedDesc = $this->parseAnchorMarkdown($field->description);
            $description = "<div class='form-text awf-help-text'>{$parsedDesc}</div>";
        }

        // --- SPECIAL CASES (Single Checkbox / Switch) with own representation ---

        // Single Checkbox 
        if ($field->subtype_in_form === 'select_checkbox') {
            $html = "<div class='form-check awf-field'>" .$this->newLine('+');
            {
                $html .= "<input type='checkbox' name='{$inputName}' class='form-check-input' value='1' id='f_{$inputName}' {$requiredAttr}>" .$this->newLine();
                $html .= "<label class='form-check-label' for='f_{$inputName}'>{$label} {$asterisk}</label>" .$this->newLine();
                $html .= $description .$this->newLine();
            }
            $html .= "</div>" .$this->newLine('-');
            return $html;
        }
        // Single Switch
        if ($field->subtype_in_form === 'select_switch') {
            $html = "<div class='form-check form-switch awf-field'>" .$this->newLine('+');
            {
                $html .= "<input type='checkbox' role='switch' name='{$inputName}' class='form-check-input' value='1' id='f_{$inputName}' {$requiredAttr}>" .$this->newLine();
                $html .= "<label class='form-check-label' for='f_{$inputName}'>{$label} {$asterisk}</label>" .$this->newLine();
                $html .= $description .$this->newLine();
            }
            $html .= "</div>" .$this->newLine('-');
            return $html;
        }

        // --- COMMON CASES ---

        $userPlaceholder = htmlspecialchars($field->placeholder ?? '');
        $isFloating = $theme->floating_labels && 
                      $field->subtype_in_form !== 'select_checkbox_list' && 
                      $field->subtype_in_form !== 'select_radio' &&
                      $field->subtype_in_form !== 'select_multiple';

        if ($isFloating) {
            // Placeholder is required in Floating labels
            $placeholder = $userPlaceholder !== '' ? $userPlaceholder : '...';
        } else {
            $placeholder = $userPlaceholder;
        }

        $wrapperClass = $isFloating ? 'form-floating awf-field' : 'awf-field';
        $html = "<div class='{$wrapperClass}'>" .$this->newLine('+');
        {
            $controlHtml = "";

            // Text Areas
            if ($field->type_in_form == 'textarea') {
                $controlHtml .= "<textarea {$validationsAttr} name='{$inputName}' class='form-control' id='f_{$inputName}' placeholder='{$placeholder}' style='height: 100px' {$requiredAttr}></textarea>";

            // Selects & Lists
            } else if ($field->type_in_form == 'select') {
                // Radio & Checkbox lists
                if ($field->subtype_in_form === 'select_radio' || $field->subtype_in_form === 'select_checkbox_list') {
                    $inputType = ($field->subtype_in_form === 'select_radio') ? 'radio' : 'checkbox';
                    $isMulti = ($inputType === 'checkbox');
                    $finalName = $inputName . ($isMulti ? '[]' : ''); // If multiple, name is array (ex: names[])

                    $controlHtml .= "<div class='awf-option-group pt-1'>" .$this->newLine('+');
                    {
                        foreach ($field->value_options as $opt) {
                            if ($opt->is_visible) {
                                $val = htmlspecialchars($opt->value);
                                $txt = htmlspecialchars($opt->text);
                                $optId = "f_{$inputName}_" . preg_replace('/[^a-zA-Z0-9]/', '', $val); 
                                $req = ($requiredAttr && !$isMulti) ? 'required' : '';  // Note: 'required' in checkboxes groups is complex in pure HTML5. 

                                $controlHtml .= "<div class='form-check'>" .$this->newLine('+');
                                {
                                    $controlHtml .= "<input {$validationsAttr} type='{$inputType}' name='{$finalName}' id='{$optId}' value='{$val}' class='form-check-input' {$req}>" .$this->newLine();
                                    $controlHtml .= "<label class='form-check-label' for='{$optId}'>{$txt}</label>" .$this->newLine();
                                }
                                $controlHtml .= "</div>" .$this->newLine('-');
                            }
                        }
                    }
                    $controlHtml .= "</div>" .$this->newLine('-');

                } 
                // Select && Select muliple
                else {
                    $isMultipleSelect = ($field->subtype_in_form === 'select_multiple');
                    $finalName = $inputName . ($isMultipleSelect ? '[]' : '');
                    $multipleAttr = $isMultipleSelect ? 'multiple' : '';
                    
                    $controlHtml .= "<select {$validationsAttr} name='{$finalName}' class='form-select' id='f_{$inputName}' {$multipleAttr} {$requiredAttr}>" .$this->newLine('+');
                    {
                        // Empty option only if not muliple
                        if (!$isMultipleSelect) {
                            $controlHtml .= "<option value='' selected></option>" .$this->newLine();
                        }
                        // Other options
                        foreach ($field->value_options as $opt) {
                            if ($opt->is_visible) {
                                $val = htmlspecialchars($opt->value);
                                $txt = htmlspecialchars($opt->text);
                                $controlHtml .= "<option value='{$val}'>{$txt}</option>" .$this->newLine();
                            }
                        }
                    }
                    $controlHtml .= "</select>" .$this->newLine('-');
                }

            // Inputs
            } else {
                $controlType = 'text';
                $autocomplete = 'off';

                switch ($field->subtype_in_form) {
                    case 'text_email': $controlType = 'email'; break;
                    case 'text_tel': $controlType = 'tel'; break;
                    case 'text_password': $controlType = 'password'; $autocomplete = 'new-password'; break;
                    case 'number': $controlType = 'number'; break;
                    case 'date': $controlType = 'date'; break;
                    case 'date_time': $controlType = 'time'; break;
                    case 'date_datetime': $controlType = 'datetime-local'; break;
                }
                $iconClass = $this->getIconClass($field->subtype_in_form);
                $cssClasses = 'form-control ' . ($iconClass ?? '');
                $controlHtml .= "<input {$validationsAttr} type='{$controlType}' name='{$inputName}' class='{$cssClasses}' id='f_{$inputName}' placeholder='{$placeholder}' autocomplete='{$autocomplete}' {$requiredAttr}>" .$this->newLine();
            }

            if ($isFloating) {
                // Floating order: Input, Label
                $html .= $controlHtml .$this->newLine();
                $html .= "<label for='f_{$inputName}'>{$label} {$asterisk}</label>" .$this->newLine();
            } else {
                // Default order: Label, Input
                $html .= "<label for='f_{$inputName}' class='form-label'>{$label} {$asterisk}</label>" .$this->newLine();
                $html .= $controlHtml .$this->newLine();
            }
            $html .= $description .$this->newLine();
        }
        $html .= "</div>" .$this->newLine('-');

        return $html;
    }

    private function generateJs(FormConfig $config, string $formId): string {
        $js = "";

        // == VALIDATORS ==
        // Get used validators
        $usedValidators = [];
        foreach ($config->data_blocks as $block) {
            foreach ($block->fields as $field) {
                if (!empty($field->validations)) {
                    foreach ($field->validations as $val) {
                        $usedValidators[$val->validator] = true;
                    }
                }
            }
        }
        if (!empty($usedValidators)) {
            // Discover all validator actions
            $validatorActions = ActionDiscoveryService::discoverActions([ActionType::VALIDATOR]);

            // Generate JS for used validators only
            $jsValidators = "const AWF_Validators = {\n";
            $hasDefinitions = false;
            foreach ($validatorActions as $action) {
                if ($action instanceof ValidatorActionDefinition && isset($usedValidators[$action->getName()])) {
                    $jsValidators .= "  '{$action->getName()}': " . $action->getValidationJS() . ",\n";
                    $hasDefinitions = true;
                }
            }
            $jsValidators .= "};\n";
            if ($hasDefinitions) {
                $js .= "<script>\n{$jsValidators}\n</script>" . $this->newLine();
            }
        }

        // == FRONTEND ACTIONS ==
        if (isset($config->flows['0'])) {
            // Load Hook and UI actions to check for IFrontendAction
            $possibleActions = ActionDiscoveryService::discoverActions([ActionType::HOOK, ActionType::UI]);
            $actionMap = [];
            foreach ($possibleActions as $a) {
                $actionMap[$a->getName()] = $a;
            }

            foreach ($config->flows['0']->actions as $formAction) {
                if (isset($actionMap[$formAction->name])) {
                    $def = $actionMap[$formAction->name];
                    if ($def instanceof IFrontendAction) {
                        $assets = $def->getFrontendAssets($formAction->parameters, $config, $formId);
                        if (!empty($assets['script'])) {
                            foreach ($assets['script'] as $scriptContent) {
                                $js .= "<script>\n{$scriptContent}\n</script>" . $this->newLine();
                            }
                        }
                    }
                }
            }
        }

        // == AUTO FILL FIELDS ==
        $js .= "<script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Read URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.forEach((value, key) => {
                    if (['entryPoint', 'id', 'module', 'action'].includes(key)) return;

                    // Find matching inputs by name
                    const inputs = document.querySelectorAll(`[name='\${key}'], [name$='_\${key}'], [name$='.\${key}']`);
                    inputs.forEach(input => {
                        if (!input.value) {
                            input.value = value;
                            input.dispatchEvent(new Event('input'));
                        }
                    });
                });
            } catch (e) { console.warn('AWF Prefill Error:', e); }
        });
        </script>" . $this->newLine();
        
        // == CUSTOM JS ==
        // Add custom JS from layout
        $customJs = $this->decode($config->layout->custom_js);
        if (!empty($customJs)) {
            $js .= "<script>\ndocument.addEventListener('DOMContentLoaded', function() {\n{$customJs}\n});\n</script>" .$this->newLine();
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

    private function newLine(?string $inc = ''){
        if ($inc=='+') 
            return "\r\n".str_repeat('  ', ++$this->indent);;

        if ($inc=='-') 
            return "\r\n".str_repeat('  ', --$this->indent);;

        return "\r\n".str_repeat('  ', $this->indent);;
    }

    /**
     * Gets the css class name for the control
     */
    private function getIconClass(string $subtype): ?string {
        $icons = ['text_email', 'text_tel', 'text_password', 'number', 'date', 'date_time', 'date_datetime'];
        if (in_array($subtype, $icons)) {
            return 'awf-icon-' . str_replace('_', '-', $subtype);
        }
        return null;
    }

    private function getSvgIconsData(): array {
        // Icon color
        $hexColor = '#6c757d';
        
        $definitions = [
            'text_email' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/></svg>',
            'text_tel' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16"><path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/></svg>',
            'text_password' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4M4.5 7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7zM8 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/></svg>',
            'number' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hash" viewBox="0 0 16 16"><path d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z"/></svg>',
            'date' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/></svg>',
            'date_time' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/></svg>',
        ];
        $definitions['date_datetime'] = $definitions['date'];

        $cssRules = [];
        foreach ($definitions as $type => $svg) {
            $svgColored = str_replace('currentColor', $hexColor, $svg);
            $encoded = base64_encode($svgColored);
            $className = 'awf-icon-' . str_replace('_', '-', $type);
            $dataUri = "data:image/svg+xml;base64,{$encoded}";
            
            // CSS definition
            $cssRules[] = "
#%WRAPPER_ID% .{$className} {
  background-image: url(\"{$dataUri}\");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 1rem 1rem;
  padding-right: 2.5rem !important;
}";
        }

        return $cssRules;
    }

    private function getChevronCss(string $wrapperId): string {
        $color = '#6c757d';
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/></svg>';
        $svgColored = str_replace('currentColor', $color, $svg);
        $encoded = base64_encode($svgColored);
        $dataUri = "data:image/svg+xml;base64,{$encoded}";

        return "
/* Chevron Toggle Icon */
#{$wrapperId} .awf-icon-toggle {
  display: inline-block;
  width: 1.25em;
  height: 1.25em;
  background-image: url(\"{$dataUri}\");
  background-repeat: no-repeat;
  background-position: center;
  background-size: contain;
  transition: transform 0.2s ease-in-out;
  vertical-align: middle;
}
/* Rotation state managed by class */
#{$wrapperId} .awf-icon-toggle.open {
  transform: rotate(180deg);
}";
    }

    private function parseAnchorMarkdown(string $text): string {
        if (empty($text)) return '';

        $pattern = '/\[([^\]]+)\]\(([^\)]+)\)/';
        $replacement = '<a href="$2" target="_blank" rel="noopener noreferrer" class="awf-link" title="$1">$1</a>';
        
        $html = preg_replace($pattern, $replacement, $text);

        return nl2br($html);
    }

}