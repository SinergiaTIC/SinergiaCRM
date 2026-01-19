<?php
// 1. Cargar el entorno de SuiteCRM
if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('include/entryPoint.php');

// 2. Simular los datos que enviaría Twilio en el $_POST
$_POST['MessageSid'] = 'DEBUG_SID_' . uniqid();
$_POST['From'] = 'whatsapp:+34636642141'; // Asegúrate de que este número exista en un contacto
$_POST['To'] = 'whatsapp:+14155238886';
$_POST['Body'] = 'Mensaje de prueba desde VM';
$_POST['SmsStatus'] = 'received';
$_POST['NumMedia'] = 0;

// 3. Incluir y ejecutar tu clase
// Ajusta esta ruta si tu archivo está en otra ubicación
require_once('modules/stic_Messages/WhatsAppWebhookEntryPoint.php');

echo "<h2>Iniciando simulación de Webhook...</h2>";

try {
    $ep = new WhatsAppWebhookEntryPoint();
    $ep->run();
    echo "<p style='color:green;'>✅ Ejecución completada. Revisa el módulo stic_Messages y el log de SuiteCRM.</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
}