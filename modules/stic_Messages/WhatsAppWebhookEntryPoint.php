<?php
/**
 * Archivo: custom/modules/stic_Messages/WhatsAppWebhookEntryPoint.php
 * 
 * Entry Point para recibir webhooks de WhatsApp desde Twilio
 * 
 * Este es el método oficial de SuiteCRM para endpoints públicos
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class WhatsAppWebhookEntryPoint
{
    /**
     * Método principal que ejecuta SuiteCRM cuando se llama al entry point
     */
    public function run()
    {
        // Log de inicio
        $GLOBALS['log']->info('========== WhatsApp Webhook Entry Point ==========');
        $GLOBALS['log']->debug('POST Data: ' . print_r($_POST, true));
        
        // Validar que hay datos
        if (empty($_POST)) {
            $GLOBALS['log']->error('WhatsApp Webhook: No POST data received');
            $this->respondToTwilio('No data received');
            return;
        }
        
        try {
            // Extraer datos del webhook de Twilio
            $messageSid = $_POST['MessageSid'] ?? '';
            $from = $_POST['From'] ?? '';           // whatsapp:+34636642141
            $to = $_POST['To'] ?? '';               // whatsapp:+14155238886
            $body = $_POST['Body'] ?? '';
            $numMedia = intval($_POST['NumMedia'] ?? 0);
            $accountSid = $_POST['AccountSid'] ?? '';
            $messageStatus = $_POST['SmsStatus'] ?? 'received';
            
            // Validar datos mínimos requeridos
            if (empty($messageSid) || empty($from)) {
                $GLOBALS['log']->error('WhatsApp Webhook: Missing MessageSid or From');
                $this->respondToTwilio('Missing required fields');
                return;
            }
            
            // Limpiar números de teléfono (remover prefijo 'whatsapp:')
            $fromPhone = $this->cleanPhoneNumber($from);
            $toPhone = $this->cleanPhoneNumber($to);
            
            $GLOBALS['log']->info("WhatsApp entrante de: {$fromPhone}");
            
            // Verificar si el mensaje ya existe (evitar duplicados)
            if ($this->messageExists($messageSid)) {
                $GLOBALS['log']->info('WhatsApp Webhook: Message already processed');
                $this->respondToTwilio('Already processed');
                return;
            }
            
            // Buscar contacto relacionado por número de teléfono
            $contactInfo = $this->findContactByPhone($fromPhone);
            
            // Crear nuevo mensaje en stic_Messages
            $message = BeanFactory::newBean('stic_Messages');
            
            // Nombre del mensaje
            $messageName = $this->generateMessageName($contactInfo, $body);
            
            // Configurar campos
            $message->name = $messageName;
            $message->type = 'WhatsApp';
            $message->direction = 'inbound';
            $message->phone = $fromPhone;
            $message->sender = $toPhone;
            $message->message = $body;
            $message->status = 'received';
            $message->response = "SID: {$messageSid} | Status: {$messageStatus}";
            
            // Relacionar con contacto si se encontró
            if (!empty($contactInfo['id'])) {
                $message->parent_type = 'Contacts';
                $message->parent_id = $contactInfo['id'];
                $GLOBALS['log']->info("Relacionado con: {$contactInfo['name']} ({$contactInfo['id']})");
            } else {
                $GLOBALS['log']->warning("Contacto no encontrado para: {$fromPhone}");
            }
            
            // Guardar mensaje (sin disparar envío porque direction = inbound)
            $messageId = $message->save();
            
            if ($messageId) {
                $GLOBALS['log']->info("✅ Mensaje creado: {$messageId}");
                
                // Procesar archivos multimedia si existen
                if ($numMedia > 0) {
                    $this->processMediaFiles($messageId, $_POST, $numMedia);
                }
                
                // Responder a Twilio
                $this->respondToTwilio('Message received', $messageId);
            } else {
                $GLOBALS['log']->error('Error guardando mensaje');
                $this->respondToTwilio('Error saving message');
            }
            
        } catch (Exception $e) {
            $GLOBALS['log']->error('WhatsApp Webhook Exception: ' . $e->getMessage());
            $GLOBALS['log']->error('Stack trace: ' . $e->getTraceAsString());
            $this->respondToTwilio('Internal error');
        }
    }
    
    /**
     * Limpiar número de teléfono
     */
    private function cleanPhoneNumber($phone)
    {
        return str_replace('whatsapp:', '', $phone);
    }
    
    /**
     * Verificar si el mensaje ya existe en la base de datos
     */
    private function messageExists($messageSid)
    {
        global $db;
        
        $messageSid = $db->quote($messageSid);
        $query = "SELECT id FROM stic_messages 
                  WHERE response LIKE '%{$messageSid}%' 
                  AND deleted = 0 
                  LIMIT 1";
        
        $result = $db->query($query);
        return ($db->fetchByAssoc($result) !== false);
    }
    
    /**
     * Buscar contacto por número de teléfono
     */
    private function findContactByPhone($phoneNumber)
    {
        global $db;
        
        // Limpiar número para búsqueda
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Buscar en múltiples campos de teléfono
        $query = "SELECT c.id, c.first_name, c.last_name 
                  FROM contacts c
                  WHERE c.deleted = 0
                  AND (
                      REPLACE(REPLACE(REPLACE(REPLACE(c.phone_mobile, '+', ''), ' ', ''), '-', ''), '(', '') LIKE '%{$cleanPhone}%'
                      OR REPLACE(REPLACE(REPLACE(REPLACE(c.phone_work, '+', ''), ' ', ''), '-', ''), '(', '') LIKE '%{$cleanPhone}%'
                      OR REPLACE(REPLACE(REPLACE(REPLACE(c.phone_home, '+', ''), ' ', ''), '-', ''), '(', '') LIKE '%{$cleanPhone}%'
                      OR REPLACE(REPLACE(REPLACE(REPLACE(c.phone_other, '+', ''), ' ', ''), '-', ''), '(', '') LIKE '%{$cleanPhone}%'
                  )
                  LIMIT 1";
        
        $result = $db->query($query);
        
        if ($row = $db->fetchByAssoc($result)) {
            return [
                'id' => $row['id'],
                'name' => trim($row['first_name'] . ' ' . $row['last_name'])
            ];
        }
        
        return ['id' => null, 'name' => null];
    }
    
    /**
     * Generar nombre descriptivo para el mensaje
     */
    private function generateMessageName($contactInfo, $messageBody)
    {
        $parts = ['WhatsApp entrante'];
        
        // Agregar nombre del contacto
        if (!empty($contactInfo['name'])) {
            $parts[] = $contactInfo['name'];
        }
        
        // Agregar fecha y hora
        $parts[] = date('d/m/Y H:i');
        
        // Agregar preview del mensaje
        $preview = mb_substr($messageBody, 0, 30);
        if (mb_strlen($messageBody) > 30) {
            $preview .= '...';
        }
        $parts[] = $preview;
        
        return implode(' - ', $parts);
    }
    
    /**
     * Procesar archivos multimedia adjuntos
     */
    private function processMediaFiles($messageId, $postData, $numMedia)
    {
        global $sugar_config;
        
        $GLOBALS['log']->info("Procesando {$numMedia} archivo(s) multimedia...");
        
        for ($i = 0; $i < $numMedia; $i++) {
            try {
                $mediaUrl = $postData["MediaUrl{$i}"] ?? '';
                $mediaContentType = $postData["MediaContentType{$i}"] ?? '';
                
                if (empty($mediaUrl)) {
                    continue;
                }
                
                $GLOBALS['log']->info("Descargando: {$mediaUrl}");
                
                // Descargar archivo
                $fileContent = $this->downloadFile($mediaUrl);
                
                if ($fileContent === false) {
                    $GLOBALS['log']->error("Error descargando: {$mediaUrl}");
                    continue;
                }
                
                // Crear Note para el adjunto
                $note = BeanFactory::newBean('Notes');
                $note->name = 'WhatsApp Media ' . ($i + 1) . ' - ' . date('Y-m-d H:i:s');
                $note->description = "Archivo recibido por WhatsApp\nTipo: {$mediaContentType}";
                $note->parent_type = 'stic_Messages';
                $note->parent_id = $messageId;
                $note->save();
                
                // Determinar extensión del archivo
                $extension = $this->getExtensionFromMimeType($mediaContentType);
                $filename = $note->id . '_' . time() . '.' . $extension;
                
                // Guardar en directorio upload
                $uploadDir = rtrim($sugar_config['upload_dir'] ?? 'upload', '/') . '/';
                $filePath = $uploadDir . $filename;
                
                if (file_put_contents($filePath, $fileContent)) {
                    // Actualizar Note con información del archivo
                    $note->filename = $filename;
                    $note->file_mime_type = $mediaContentType;
                    $note->save();
                    
                    $GLOBALS['log']->info("✅ Archivo guardado: {$filename}");
                } else {
                    $GLOBALS['log']->error("Error guardando: {$filePath}");
                }
                
            } catch (Exception $e) {
                $GLOBALS['log']->error("Error procesando media {$i}: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Descargar archivo desde URL usando cURL
     */
    private function downloadFile($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode >= 200 && $httpCode < 300 && $content !== false) {
            return $content;
        }
        
        return false;
    }
    
    /**
     * Obtener extensión de archivo desde MIME type
     */
    private function getExtensionFromMimeType($mimeType)
    {
        $mimeMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'video/mp4' => 'mp4',
            'video/3gpp' => '3gp',
            'audio/mpeg' => 'mp3',
            'audio/ogg' => 'ogg',
            'audio/aac' => 'aac',
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'text/plain' => 'txt',
            'text/csv' => 'csv',
        ];
        
        return $mimeMap[$mimeType] ?? 'bin';
    }
    
    /**
     * Responder a Twilio con TwiML (XML)
     */
    private function respondToTwilio($message = '', $messageId = null)
    {
        // Limpiar cualquier output previo
        ob_clean();
        
        // Headers para respuesta XML
        header('Content-Type: text/xml; charset=utf-8');
        
        // TwiML básico
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        
        // Opcional: Enviar respuesta automática
        // Descomenta si quieres que el sistema responda automáticamente
        /*
        if ($messageId) {
            echo '<Message>';
            echo '<Body>Gracias por tu mensaje. Te responderemos pronto.</Body>';
            echo '</Message>';
        }
        */
        
        echo '</Response>';
        
        $GLOBALS['log']->info("Respuesta TwiML enviada: {$message}");
    }
}

$entryPoint = new WhatsAppWebhookEntryPoint();
$entryPoint->run();