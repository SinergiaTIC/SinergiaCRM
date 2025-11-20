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

include_once "modules/stic_Advanced_Web_Forms/actions/coreActions.php";

/**
 * SendEmailToDataBlockAction
 *
 * Acción que envía un email al registro procesado (Persona, Interesado, Usuario o Organización) contenido en un Bloque de Datos.
 */
class SendEmailToDataBlockAction extends HookBeanActionDefinition {
    public function __construct() {
        $this->isActive = true;
        $this->isUserSelectable = true;
        $this->isCommon = true;

        $this->category = 'email';
        $this->baseLabel = 'LBL_SEND_EMAIL_TO_DATABLOCK_ACTION';
    }

    /**
     * Módulos soportados por la acción
     */
    protected function getSupportedModules(): array {
        return ['Contacts', 'Users', 'Prospects', 'Leads', 'Accounts'];
    }

    /**
     * Nombre del parámetro que contiene el bloque de datos.
     * @return string
     */
    protected function getDataBlockParameterText(): string {
        return $this->translate('RECIPIENT_BLOCK_TEXT');
    }

    /**
     * La descripción (help text) del parámetro de bloque de datos.
     * @return string
     */
    protected function getDataBlockParameterDescription(): string {
        return $this->translate('RECIPIENT_BLOCK_DESC');
    }

    /**
     * getCustomParameters()
     * Definición de los parámetroes ADICIONALES que son necesarios para la acción
     * El parámtreo del Bloque de Datos principal lo pide la clase padre.
     */
    protected function getCustomParameters(): array
    {
        // La Plantilla de Email a usar
        $paramTemplate = new ActionParameterDefinition();
        $paramTemplate->name = 'email_template';
        $paramTemplate->text = $this->translate('TEMPLATE_TEXT'); 
        $paramTemplate->description = $this->translate('TEMPLATE_DESC');
        $paramTemplate->type = ActionParameterType::CRM_RECORD;
        $paramTemplate->supportedModules = ['EmailTemplates'];
        $paramTemplate->required = true;

        return [$paramTemplate];
    }


    public function executeWithBean(ExecutionContext $context, FormAction $actionConfig, SugarBean $bean, DataBlockResolved $block): ActionResult
    {
        // Obtención de los parámetros adicionales (ParameterResolver asegura que no sean nulos porque son obligatorios)
        
        /** @var ?BeanReference $templateRef */
        $templateRef = $actionConfig->getResolvedParameter('email_template');
        
        if (!$templateRef) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Email template parameter is missing.");
        }

        // Obtenemos el email del Bean. Asumimos el campo estandard 'email1'
        $emailAddress = $bean->email1 ?? null;
        // Validamos que el email es correcto
        if (empty($emailAddress) || !filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "DataBlock '{$block->dataBlock->name}' does not have a valid 'email1' field ('{$emailAddress}').");
        }

        // Enviamos el email
        try {
            $this->sendEmail($emailAddress, $templateRef->beanId, $bean, $context);
        } catch (\Exception $e) {
            return new ActionResult(ResultStatus::ERROR, $actionConfig, "Error sending email: " . $e->getMessage());
        }

        // Notificación del resultado
        $actionResult = new ActionResult(ResultStatus::OK, $actionConfig, "Email sent to: {$emailAddress}");
        $dataToLog = ['email_sent_to' => $emailAddress, 'template_id' => $templateRef->beanId];
        $actionResult->registerBeanModificationFromBlock($bean, $block, BeanModificationType::UPDATED, $dataToLog);

        return $actionResult;
    }

    /**
     * Implementa el envío de correo electrónico usando una plantilla y un bean de contexto.
     * @param string $toAddress La dirección de correo del destinatario.
     * @param string $templateId El ID de la plantilla de correo electrónico a usar.
     * @param SugarBean $contextBean El bean que sirve de contexto para la plantilla.
     * @param ExecutionContext $context El contexto global.
     * @throws \Exception Si hay algún error en el proceso de envío.
     */
    private function sendEmail(string $toAddress, string $templateId, SugarBean $contextBean, ExecutionContext $context): void
    {
        // Cargamos la plantilla de correo
        $emailTemplate = BeanFactory::retrieveBean('EmailTemplates', $templateId);
        if (!$emailTemplate) {
            throw new \Exception("Email template not found: '{$templateId}'.");
        }

        // Obtenemos los adjuntos de la plantilla
        $attachments = $this->getAttachments($emailTemplate);

        // Comprobamos si necesitamos el resumen del formulario
        // Variable mágica {::form_summary::} que será reemplazada por el resumen HTML del formulario
        $needsSummaryHtml = strpos((string)$emailTemplate->body_html, '{::form_summary::}') !== false;
        $needsSummaryText = strpos((string)$emailTemplate->body, '{::form_summary::}') !== false;
        if ($needsSummaryHtml) {
            $summaryHtml = AWF_Utils::generateSummaryHtml($context);
            $emailTemplate->body_html = str_replace('{::form_summary::}', $summaryHtml, (string)$emailTemplate->body_html);
        }
        if ($needsSummaryText) {
            $summaryText = AWF_Utils::generateSummaryText($context);
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
        try {
            $this->archiveEmail($subject, $body, $systemFromAddress, $toAddress, $contextBean, $attachments);
        } catch (\Exception $e) {
            // Si falla el archivado no paramos el proceso pero lo registramos en el log
            $GLOBALS['log']->error('Line '.__LINE__.': '.__METHOD__.':  Email sent but error archiving: ' . $e->getMessage());
        }
    }


    /**
     * Obtiene los adjuntos asociados a una plantilla de correo electrónico.
     * @param EmailTemplate $template La plantilla de correo electrónico.
     * @return array Lista de objetos Note que representan los adjuntos
     */
    private function getAttachments(EmailTemplate $template): array
    {
        $attachments = [];
        if (!empty($template->id)) {
            $noteBean = BeanFactory::newBean('Notes');
            // Obtenemos las Notas vinculadas a la plantilla de email
            $notes = $noteBean->get_full_list('', "parent_type = 'Emails' AND parent_id = '" . $template->id . "'");
            
            if ($notes != null) {
                $attachments = $notes;
            }
        }
        return $attachments;
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
    private function archiveEmail(string $subject, string $body, string $from, string $to, SugarBean $parentBean, array $attachments): void
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
            $newNote->parent_type = 'Emails'; // Important: Ha de ser 'Emails' perquè surti com adjunt
            
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
}