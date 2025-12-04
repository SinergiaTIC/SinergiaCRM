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
            case 'tel':
            case 'url':
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
        $html = "<h1>".translate('LBL_RESPONSE_SUMMARY_DATA', 'stic_Advanced_Web_Forms')."</h1>";
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

    /**
     * Genera un resumen en texto plano con todos los datos del forumlario.
     *
     * @param ExecutionContext $context El contexto que contiene los datos.
     * @return string Un string de texto plano con el resumen.
     */
    public static function generateSummaryText(ExecutionContext $context): string
    {
        // Título principal
        $title = translate('LBL_RESPONSE_SUMMARY_DATA', 'stic_Advanced_Web_Forms');
        $text = $title . "\n" . str_repeat('=', mb_strlen($title)) . "\n\n";
        
        $formData = $context->formData; 
        
        foreach ($context->formConfig->data_blocks as $block) {
            // Título del bloque
            $blockTitle = mb_strtoupper($block->text);
            $text .= $blockTitle . "\n";
            $text .= str_repeat('-', mb_strlen($blockTitle)) . "\n";
            
            foreach ($block->fields as $fieldDef) {
                if (empty($fieldDef->label) || $fieldDef->type_field === DataBlockFieldType::HIDDEN) {
                    continue;
                }

                $formKey = "{$block->name}.{$fieldDef->name}";
                if ($fieldDef->type_field === DataBlockFieldType::UNLINKED) {
                    $formKey = "_detached.{$formKey}";
                }
                $value = $formData[$formKey] ?? '';
                
                // Formato: "Etiqueta: Valor"
                $text .= "{$fieldDef->label}: {$value}\n";
            }
            $text .= "\n";
        }
        
        return $text;
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

        // Obtenemos los adjuntos de la plantilla
        $attachments = $emailTemplate->getAttachments() ?: [];

        // Comprobamos si necesitamos el resumen del formulario
        // Variable mágica {::form_summary::} que será reemplazada por el resumen HTML del formulario
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

        // Obtenemos la dirección de correo del sistema
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

        // Procesamos y añadimos los adjuntos
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

        // Datos básicos
        $emailBean->name = $subject;
        $emailBean->date_sent_received = TimeDate::getInstance()->nowDb();
        $emailBean->type = 'out';
        $emailBean->status = 'sent';
        $emailBean->assigned_user_id = $GLOBALS['current_user']->id ?? '1';

        // Direcciones
        $emailBean->from_addr = $from;
        $emailBean->to_addrs = $to;

        // Contenido
        $emailBean->description = strip_tags($body);
        $emailBean->description_html = $body;

        // Relación con el bean padre
        $emailBean->parent_type = $parentBean->module_dir;
        $emailBean->parent_id = $parentBean->id;

        // Guardado del email
        $emailBean->save();

        // Vinculación del email al bean padre
        if ($parentBean->load_relationship('emails')) {
            $parentBean->emails->add($emailBean->id);
        }

        // Duplicación de los adjuntos
        foreach ($attachments as $originalNote) {
            $newNote = BeanFactory::newBean('Notes');
            $newNote->id = create_guid();
            $newNote->new_with_id = true;

            // Vinculación con el email archivado
            $newNote->parent_id = $emailBean->id;
            $newNote->parent_type = 'Emails';

            // Copiamos las propiedades de la Nota original
            $newNote->name = $originalNote->name;
            $newNote->filename = $originalNote->filename;
            $newNote->file_mime_type = $originalNote->file_mime_type;
            
            // Copiamos el archivo físico
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
}
