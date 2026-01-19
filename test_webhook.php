<?php
// Forzar visualización de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Usa 127.0.0.1 en lugar de localhost para evitar problemas de resolución de nombres
$url = 'http://127.0.0.1:8000/sinergiacrm/index.php?entryPoint=stic_Messages_twilio_response';

$data = [
    'MessageSid' => 'TEST_SID_' . time(),
    'From' => 'whatsapp:+34636642141',
    'To' => 'whatsapp:+14155238886',
    'Body' => 'Prueba directa',
    'SmsStatus' => 'received'
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
// Ignorar errores de proxy o hosts si los hubiera
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); 

$response = curl_exec($ch);

if(curl_errno($ch)){
    echo 'Error de cURL: ' . curl_error($ch);
} else {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "Código HTTP: " . $httpCode . "<br>";
    echo "Respuesta: " . htmlspecialchars($response);
}
curl_close($ch);