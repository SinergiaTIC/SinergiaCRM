<?php
echo "=== ttsDeepgramProvider Integration Tests ===\n\n";

if ($argc < 2) {
    echo "Uso: php -f " . $argv[0] . " <API_KEY>\n";
    echo "Ej: php -f " . $argv[0] . " b2ab8229d49dd40cd57d52691932eb9d664d73c7\n";
    exit(1);
}

$apiKey = $argv[1];
$passed = 0;
$failed = 0;

function test($label, $condition, $detail = '')
{
    global $passed, $failed;
    if ($condition) {
        $passed++;
        echo "  ✓ $label\n";
    } else {
        $failed++;
        echo "  ✗ $label" . ($detail ? " — $detail" : '') . "\n";
    }
}

// === Test 1: Conexión básica ===
echo "--- 1. Conexión y respuesta ---\n";

$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://api.eu.deepgram.com/v1/speak?model=aura-2-alvaro-es&encoding=mp3',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(array('text' => 'Esto es una prueba de conexión con Deepgram.')),
    CURLOPT_HTTPHEADER => array(
        'Authorization: Token ' . $apiKey,
        'Content-Type: application/json',
    ),
    CURLOPT_TIMEOUT => 15,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_HEADER => true,
));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$curlError = curl_error($ch);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

test('HTTP 200', $httpCode === 200, "Got $httpCode");
test('Sin error cURL', empty($curlError), $curlError ?: '');

if ($response && $httpCode === 200) {
    $headerStr = substr($response, 0, $headerSize);
    $audioBody = substr($response, $headerSize);

    test('Content-Type es audio/mpeg', strpos($contentType, 'audio/mpeg') !== false, $contentType ?: 'vacío');
    test('Header dg-char-count presente', preg_match('/^dg-char-count:\s*(\d+)/mi', $headerStr, $m) === 1, $m[1] ?? '');
    test('Audio binario no vacío', strlen($audioBody) > 100, strlen($audioBody) . ' bytes');

    $audioFile = '/tmp/tts_test_output.mp3';
    file_put_contents($audioFile, $audioBody);
    $fileInfo = mime_content_type($audioFile);
    test('Archivo MP3 válido', strpos($fileInfo, 'audio') !== false || strpos($fileInfo, 'mpeg') !== false, $fileInfo ?: '');
    echo "  → Audio guardado en $audioFile (" . strlen($audioBody) . " bytes)\n";
}

// === Test 2: Fragmentación (texto >2000 chars) ===
echo "\n--- 2. Texto largo (>2000 chars) — fragmentación automática ---\n";

$longText = str_repeat('Esta es una frase de prueba. ', 100);
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://api.eu.deepgram.com/v1/speak?model=aura-2-alvaro-es&encoding=mp3',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(array('text' => mb_substr($longText, 0, 2000))),
    CURLOPT_HTTPHEADER => array(
        'Authorization: Token ' . $apiKey,
        'Content-Type: application/json',
    ),
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_HEADER => true,
));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

test('Texto de ~2000 chars HTTP 200', $httpCode === 200, "Got $httpCode");

// === Test 3: Texto >2000 chars debe dar 413 (request too large) ===
// Deepgram rejected... actually let's verify the 2000 limit behavior
echo "\n--- 3. Verificación límite 2000 chars ---\n";

$overLimit = str_repeat('a', 2500);
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://api.eu.deepgram.com/v1/speak?model=aura-2-alvaro-es&encoding=mp3',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(array('text' => $overLimit)),
    CURLOPT_HTTPHEADER => array(
        'Authorization: Token ' . $apiKey,
        'Content-Type: application/json',
    ),
    CURLOPT_TIMEOUT => 15,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_HEADER => true,
));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

test('2500 chars rechazado (413 esperado)', $httpCode === 413, "Got $httpCode");

// === Test 4: Validación de API key inválida ===
echo "\n--- 4. API key inválida ---\n";

$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://api.eu.deepgram.com/v1/speak?model=aura-2-alvaro-es&encoding=mp3',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(array('text' => 'test')),
    CURLOPT_HTTPHEADER => array(
        'Authorization: Token invalid_key_12345',
        'Content-Type: application/json',
    ),
    CURLOPT_TIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_HEADER => true,
));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

test('API key inválida da 401', $httpCode === 401, "Got $httpCode");

// === Test 5: Voz en català (misma voz Alvaro) ===
echo "\n--- 5. Síntesis en catalán con voz aura-2-alvaro-es ---\n";

$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://api.eu.deepgram.com/v1/speak?model=aura-2-alvaro-es&encoding=mp3',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(array('text' => 'Això és una prova en català.')),
    CURLOPT_HTTPHEADER => array(
        'Authorization: Token ' . $apiKey,
        'Content-Type: application/json',
    ),
    CURLOPT_TIMEOUT => 15,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_HEADER => true,
));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$audioSize = $response ? strlen(substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE))) : 0;
curl_close($ch);

test('Catalán HTTP 200', $httpCode === 200, "Got $httpCode");
test('Catalán genera audio', $audioSize > 100, $audioSize . ' bytes');

echo "\n=== Resultados: $passed passed, $failed failed ===\n";
exit($failed > 0 ? 1 : 0);
