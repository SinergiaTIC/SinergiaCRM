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

class stic_AWFUtils {
    /**
     * Converts a form string value to the correct PHP type based on the CRM field type.
     * @param mixed $valueToCast The value to convert
     * @param ?string $crmFieldType The CRM field type
     * @param ExecutionContext $context The execution context
     * @return mixed The converted value
     */
    public static function castCrmValue(mixed $valueToCast, ?string $crmFieldType, ExecutionContext $context): mixed {
        
        if (is_array($valueToCast)) {
            // If it's an array from a multiselect: Array -> encoded multienum string
            if ($crmFieldType === 'multienum') {
                return encodeMultienumValue($valueToCast);
            }
            // If it's an array with subarrays/objects, store as JSON
            $firstElement = reset($valueToCast);
            if (is_array($firstElement) || is_object($firstElement)) {
                return json_encode($valueToCast, JSON_UNESCAPED_UNICODE);
            }
            // For other array types (not multienum), convert to comma-separated string
            return implode(',', $valueToCast);
        }

        // If no type is defined, treat as text
        if ($crmFieldType === null) {
            $crmFieldType = 'text';
        }

        switch ($crmFieldType) {
            // Boolean
            case 'bool':
            case 'checkbox':
                $lowerValue = strtolower(trim($valueToCast));
                return !($lowerValue === 'false' || $lowerValue === '0' || $lowerValue === 'off' || $lowerValue === '');

            // Numerics
            case 'int':
                return (int)$valueToCast;
            
            case 'float':
            case 'double':
            case 'decimal':
            case 'currency': 
                return (float)$valueToCast;

            // Dates and times
            case 'date':
            case 'time':
            case 'datetime':
            case 'datetimecombo':
                $baseTimestamp = (int)$context->submissionTimestamp;
                // strtotime also handles "today", "+1 day", etc.
                $parsedTime = @strtotime($valueToCast, $baseTimestamp);
                
                if ($parsedTime === false) {
                    $GLOBALS['log']->warn("Line ".__LINE__.": ".__METHOD__.": Can not parse date '{$valueToCast}'.");
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
            case 'tel':
            case 'url':
            case 'email':
            case 'text':
            default:
                return (string)$valueToCast;
        }
    }

    /**
     * Genera un resumen en HTML con todos los datos del formulario basándose en el Layout.
     *
     * @param ExecutionContext $context El contexto que contiene los datos.
     * @param array $options Opciones de visualización ['showTitle' => bool, 'useFlex' => bool, 'includeCss' => bool]
     * @return string Un string HTML con la tabla resumen.
     */
    public static function generateSummaryHtml(ExecutionContext $context, array $options = []): string
    {
        $theme = $context->formConfig->layout->theme;
        $layout = $context->formConfig->layout;
        $formData = $context->formData;

        // Opciones por defecto
        $showTitle = $options['showTitle'] ?? true;
        $useFlex = $options['useFlex'] ?? false;
        $includeCss = $options['includeCss'] ?? true;

        $fontFamily = $theme->font_family ?? 'system-ui, -apple-system, sans-serif';
        $fontSize = $theme->font_size ?? 16;
        $primaryColor = $theme->primary_color ?? '#0d6efd';
        $textColor = $theme->text_color ?? '#212529';
        $borderColor = $theme->border_color ?? '#dee2e6';
        $formWidth = $useFlex ? '100%' : ($theme->form_width ?? '800px');
        
        $html = '';
        if ($includeCss) {
            $css = "
            <style>
                .awf-summary-container {font-family: {$fontFamily};font-size: {$fontSize}px;color: {$textColor};max-width: {$formWidth};margin: 0 auto;line-height: 1.5;}
                .awf-summary-title {color: {$primaryColor};border-bottom: 2px solid {$primaryColor};padding-bottom: 10px;margin-bottom: 20px;font-size: 1.5em;}
                .awf-summary-section-title {color: {$theme->text_color};background-color: {$theme->page_bg_color};padding: 8px 12px;margin-top: 25px;margin-bottom: 5px;font-size: 1.2em;font-weight: bold;border-bottom: 2px solid {$borderColor};}
                .awf-summary-table {width: 100%;border-collapse: collapse;margin-bottom: 0px;}
                .awf-summary-table td {padding: 8px 12px;border-bottom: 1px solid {$borderColor};vertical-align: top;}
                .awf-summary-label {width: 35%;font-weight: bold;color: {$textColor};background-color: rgba(0,0,0,0.02);}
                .awf-summary-value {width: 65%;}
                " . ($useFlex ? "
                .awf-sections-wrapper { display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-start; }
                .awf-section-item { 
                    flex: 1 1 250px;
                    min-width: 250px; 
                    border: 1px solid {$borderColor}; 
                    border-radius: 6px; 
                    overflow: hidden; 
                    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                }
                .awf-summary-section-title { margin-top: 0; margin-bottom: 0; border-top: 0; }
                " : "") . "
            </style>";
            $html .= $css;
        }
        $html .= "<div class='awf-summary-container'>";
        if ($showTitle) {
            $html .= "<h1>".translate('LBL_RESPONSE_SUMMARY_DATA', 'stic_Advanced_Web_Forms')."</h1>";
        }
        $html .= "<div class='awf-sections-wrapper'>";

        // Iterate through sections (layout)
        foreach ($layout->structure as $section) {
            $html .= "<div class='awf-section-item'>";

            // If the section has a title and should be displayed
            if ($section->showTitle && !empty($section->title)) {
                $html .= "<div class='awf-summary-section-title'>" . htmlspecialchars($section->title) . "</div>";
            }
            $html .= "<table class='awf-summary-table'>";
            $hasFields = false;

            // Elements of the section (data blocks)
            foreach ($section->elements as $element) {
                if ($element->type !== 'datablock') continue;

                $block = $context->getDataBlockById($element->ref_id);
                if (!$block) continue;

                foreach ($block->fields as $fieldDef) {
                    // Only show visible fields in the form
                    if ($fieldDef->type_field === DataBlockFieldType::FIXED) {
                        continue;
                    }
                    // If it has no label, it is not displayed
                    if (empty($fieldDef->label)) {
                        continue;
                    }

                    $formKey = "{$block->name}.{$fieldDef->name}";
                    if ($fieldDef->type_field === DataBlockFieldType::UNLINKED) {
                        $formKey = "_detached.{$formKey}";
                    }
                    $formKey = str_replace('.', '_', $formKey);
                    
                    // Value to display
                    $value = $formData[$formKey] ?? '';

                    if (!empty($fieldDef->value_options)) {
                        // Helper function to find the text of a value
                        $findLabel = function($val) use ($fieldDef) {
                            foreach ($fieldDef->value_options as $opt) {
                                if ($opt->value == $val) return $opt->text;
                            }
                            return $val; 
                        };
    
                        if (is_array($value)) {
                            $labels = array_map($findLabel, $value);
                            $displayValue = implode(', ', $labels);
                        } else {
                            $displayValue = $findLabel($value);
                        }
                    } else {
                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }
                        $displayValue = $value;
                    }
                    
                    $displayValue = nl2br(htmlspecialchars((string)$displayValue));

                    $html .= "<tr>";
                    $html .= "<td class='awf-summary-label'>" . htmlspecialchars($fieldDef->label) . "</td>";
                    $html .= "<td class='awf-summary-value'>" . $displayValue . "</td>";
                    $html .= "</tr>";
                    $hasFields = true;
                }
            }

            if (!$hasFields) {
                // If the section had no visible fields, we close the table and continue (CSS will make it invisible or minimal)
            }
            $html .= "</table>";
            $html .= "</div>";
        }
        
        $html .= "</div>";
        $html .= "</div>";
        
        return $html;
    }

    /**
     * Generates a plain-text summary with all form data based on the Layout.
     *
     * @param ExecutionContext $context The context containing the data.
     * @return string A plain-text string with the summary.
     */
    public static function generateSummaryText(ExecutionContext $context): string
    {
        // Main title
        $title = translate('LBL_RESPONSE_SUMMARY_DATA', 'stic_Advanced_Web_Forms');
        $text = $title . "\n" . str_repeat('=', mb_strlen($title)) . "\n\n";
        
        $layout = $context->formConfig->layout;
        $formData = $context->formData; 
        
        foreach ($layout->structure as $section) {
            
            // Section title
            if ($section->showTitle && !empty($section->title)) {
                $sectionTitle = mb_strtoupper($section->title);
                $text .= $sectionTitle . "\n";
                $text .= str_repeat('-', mb_strlen($sectionTitle)) . "\n";
            } else {
                // Visual separator if there is no title
                $text .= "--------------------\n";
            }

            foreach ($section->elements as $element) {
                if ($element->type !== 'datablock') continue;

                $block = $context->getDataBlockById($element->ref_id);
                if (!$block) continue;

                foreach ($block->fields as $fieldDef) {
                    if ($fieldDef->type_field === DataBlockFieldType::FIXED) continue;
                    if (empty($fieldDef->label)) continue;

                    $formKey = "{$block->name}.{$fieldDef->name}";
                    if ($fieldDef->type_field === DataBlockFieldType::UNLINKED) {
                        $formKey = "_detached.{$formKey}";
                    }
                    $formKey = str_replace('.', '_', $formKey);

                    // Valor a mostrar
                    $value = $formData[$formKey] ?? '';

                    if (!empty($fieldDef->value_options)) {
                         $findLabel = function($val) use ($fieldDef) {
                            foreach ($fieldDef->value_options as $opt) {
                                if ($opt->value == $val) return $opt->text;
                            }
                            return $val; 
                        };
    
                        if (is_array($value)) {
                            $labels = array_map($findLabel, $value);
                            $displayValue = implode(', ', $labels);
                        } else {
                            $displayValue = $findLabel($value);
                        }
                    } else {
                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }
                        $displayValue = $value;
                    }
                    
                    // Formato: "Etiqueta: Valor"
                    $text .= "{$fieldDef->label} {$displayValue}\n";
                }
            }
            $text .= "\n";
        }
        
        return $text;
    }

    /**
     * Renderiza una página HTML básica usando los estilos del formulario
     */
    public static function renderGenericResponse(FormConfig $config, string $title, string $message)
    {
        // Limpiar el buffer de salida para eliminar warnings o errores previos
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Evitar mostrar nuevos errores o warnings
        ini_set('display_errors', 0);
        error_reporting(0);
        
        $theme = $config->layout->theme;
        $fontFamily = $theme->font_family ?? 'sans-serif';
        $bgColor = $theme->page_bg_color ?? '#f8f9fa';
        $textColor = $theme->text_color ?? '#212529';
        $formBg = $theme->form_bg_color ?? '#ffffff';
        $primaryColor = $theme->primary_color ?? '#0d6efd';
        $customCss = $config->layout->custom_css ?? '';
        $customJs = $config->layout->custom_js ?? '';
        
        echo "
<!DOCTYPE html>
<html>
    <head>
        <meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>" . htmlspecialchars($title) . "</title>
        <style>
            body { font-family: {$fontFamily}; background-color: {$bgColor}; color: {$textColor}; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
            .message-card { background-color: {$formBg}; padding: 40px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-width: 600px; width: 90%; text-align: center; }
            h1 { color: {$primaryColor}; margin-bottom: 20px; }
            {$customCss}
        </style>
    </head>
    <body>
        <div class='message-card'>
            <h1>" . htmlspecialchars($title) . "</h1>
            <div>" . nl2br(htmlspecialchars($message)) . "</div>
        </div>";
        if (!empty($config->layout->custom_js)) { 
            echo "<script>" . $config->layout->custom_js . "</script>"; 
        }
        echo "
    </body>
</html>";
        
        sugar_cleanup(true);
    }
    
    
    /**
     * Método para enviar un correo basado en una plantilla
     *
     * @param string $toAddress Dirección de correo del destinatario.
     * @param string $templateId ID de la plantilla de correo a usar.
     * @param SugarBean $contextBean El bean que sirve de contexto para la plantilla.
     * @param ExecutionContext $context El contexto de ejecución del formulario.
     * @param ?SugarBean $parentBeanForArchive (Opcional) Bean padre para archivar el correo. Si no se proporciona, se usará $contextBean.
     * @throws \Exception Si hay errores en el envío o en la plantilla.
     */
    public static function sendTemplateEmail(
        string $toAddress, 
        string $templateId, 
        SugarBean $contextBean, 
        ExecutionContext $context, 
        ?SugarBean $parentBeanForArchive = null
    ): void 
    {
        // Cargamos la plantilla de correo
        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = BeanFactory::getBean('EmailTemplates', $templateId);
        if (!$emailTemplate) {
            throw new \Exception("Email template not found: '{$templateId}'.");
        }

        // Get attachments from the template
        $attachments = $emailTemplate->getAttachments() ?: [];

        // Check if we need the form summary
        // Magic variable {::form_summary::} that will be replaced by the form HTML summary
        $needsSummaryHtml = strpos((string)$emailTemplate->body_html, '{::form_summary::}') !== false;
        $needsSummaryText = strpos((string)$emailTemplate->body, '{::form_summary::}') !== false;

        if ($needsSummaryHtml) {
            $summaryHtml = self::generateSummaryHtml($context);
            $emailTemplate->body_html = str_replace('{::form_summary::}', $summaryHtml, (string)$emailTemplate->body_html);
        }
        if ($needsSummaryText) {
            $summaryText = self::generateSummaryText($context);
            $emailTemplate->body = str_replace('{::form_summary::}', $summaryText, (string)$emailTemplate->body);
        }

        // Parsemos el contenido de la plantilla
        $subject = $emailTemplate->parse_template_bean($emailTemplate->subject, $contextBean->module_dir, $contextBean);
        $bodyHtml = $emailTemplate->parse_template_bean($emailTemplate->body_html, $contextBean->module_dir, $contextBean);
        $bodyText = $emailTemplate->parse_template_bean($emailTemplate->body, $contextBean->module_dir, $contextBean);
        $body = $bodyHtml;
        if (empty($bodyHtml)) {
            $body = nl2br($bodyText);
        }

        // Configuramos el Mailer
        require_once 'include/SugarPHPMailer.php';
        $mailer = new SugarPHPMailer();
        $mailer->prepForOutbound();
        $mailer->setMailerForSystem();

        // Get the system email address
        $admin = BeanFactory::newBean('Administration');
        $admin->retrieveSettings();
        $systemFromAddress = $admin->settings['notify_fromaddress'] ?? '';
        $systemFromName = $admin->settings['notify_fromname'] ?? '';
        if (empty($systemFromAddress)) {
            throw new \Exception("System email address not configured.");
        }

        $mailer->From = $systemFromAddress;
        $mailer->FromName = $systemFromName;

        // Configuramos el correo
        $mailer->addAddress($toAddress);
        $mailer->Subject = $subject;
        $mailer->Body = $body;
        $mailer->isHTML(true); 
        $mailer->CharSet = 'UTF-8';

        // Process and add attachments
        $mailer->handleAttachments($attachments);

        // Enviar
        if (!$mailer->send()) {
            throw new \Exception("Error sending email to {$toAddress}: " . $mailer->ErrorInfo);
        }

        // Archivamos el email
        // Si no se indica el padre, usamos el contexto 
        $parentBean = $parentBeanForArchive ?? $contextBean;
        try {
            self::archiveEmail($subject, $body, $systemFromAddress, $toAddress, $parentBean, $attachments);
        } catch (\Exception $e) {
            // Si falla el archivado no paramos el proceso pero lo registramos en el log
            $GLOBALS['log']->error('Line '.__LINE__.': '.__METHOD__.':  Email sent but error archiving: ' . $e->getMessage());
        }
    }

    /**
     * Archiva el correo electrónico enviado como un registro en el módulo Emails, vinculado al bean padre.
     * También duplica los adjuntos del correo.
     * 
     * @param string $subject El asunto del correo.
     * @param string $body El cuerpo del correo en HTML.
     * @param string $from La dirección de correo del remitente.
     * @param string $to La dirección de correo del destinatario.
     * @param SugarBean $parentBean El bean al que se vinculará el correo archivado.
     * @param array $attachments Lista de objetos Note que representan los adjuntos originales.
     * @throws \Exception Si hay algún error durante el proceso de archivado.
     */
    private static function archiveEmail(string $subject, string $body, string $from, string $to, SugarBean $parentBean, array $attachments): void
    {
        /** @var Email $emailBean */
        $emailBean = BeanFactory::newBean('Emails');

        // Basic data
        $emailBean->name = $subject;
        $emailBean->date_sent_received = TimeDate::getInstance()->nowDb();
        $emailBean->type = 'out';
        $emailBean->status = 'sent';
        $emailBean->assigned_user_id = $GLOBALS['current_user']->id ?? '1';

        // Addresses
        $emailBean->from_addr = $from;
        $emailBean->to_addrs = $to;

        // Content
        $emailBean->description = strip_tags($body);
        $emailBean->description_html = $body;

        // Relationship with parent bean
        $emailBean->parent_type = $parentBean->module_dir;
        $emailBean->parent_id = $parentBean->id;

        // Email saving
        $emailBean->save();

        // Link email to parent bean
        if ($parentBean->load_relationship('emails')) {
            $parentBean->emails->add($emailBean->id);
        }

        // Duplication of attachments
        foreach ($attachments as $originalNote) {
            $newNote = BeanFactory::newBean('Notes');
            $newNote->id = create_guid();
            $newNote->new_with_id = true;

            // Link with archived email
            $newNote->parent_id = $emailBean->id;
            $newNote->parent_type = 'Emails';

            // Copy the original Note properties
            $newNote->name = $originalNote->name;
            $newNote->filename = $originalNote->filename;
            $newNote->file_mime_type = $originalNote->file_mime_type;
            
            // Copy the physical file
            $source = "upload://{$originalNote->id}";
            $dest = "upload://{$newNote->id}";

            if (file_exists($source) && copy($source, $dest)) {
                $newNote->save();
            } else {
                $GLOBALS['log']->warn('Line '.__LINE__.': '.__METHOD__.":  Cannot copy attachment file from {$source} to {$dest}.");
            }
        }
    }


    /**
     * Convierte un color hexadecimal a su representación RGB.
     * @param string $hex El color en formato hexadecimal (ej: "#FF5733" o "FF5733").
     * @return string El color en formato RGB separado por comas (ej: "255, 87, 51").
     */
    public static function hex2rgb(string $hex): string 
    {
        $hex = str_replace("#", "", $hex);
    
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        
        return "{$r}, {$g}, {$b}"; // Retorna "255, 87, 51"
    }

    /**
     * Parsea un link en Markdown
     * @param string $text El texto a parsear
     * @return string El texto parseado (en html)
     */
    public static function parseAnchorMarkdown(string $text): string {
        if (empty($text)) return '';

        $pattern = '/\[([^\]]+)\]\(([^\)]+)\)/';
        $replacement = '<a href="$2" target="_blank" rel="noopener noreferrer" class="awf-link" title="$1">$1</a>';
        
        $html = preg_replace($pattern, $replacement, $text);

        return nl2br($html);
    }
}
