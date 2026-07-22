<?php
if (!defined('sugarEntry')) define('sugarEntry', true);
require_once '/application/sinergiacrm/custom/include/TextToSpeech/providers/ttsTextFragmenter.php';

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

echo "=== TtsTextFragmenter Tests ===\n\n";

// 1. Texto corto (≤2000 chars) → un solo fragmento
$f = new TtsTextFragmenter(2000);
$result = $f->fragment('Hola mundo');
test('Texto corto devuelve 1 fragmento', count($result) === 1);
test('Fragmento conserva el texto', $result[0] === 'Hola mundo');

// 2. Texto exactamente en el límite
$text = str_repeat('a', 2000);
$result = $f->fragment($text);
test('Texto de 2000 chars devuelve 1 fragmento', count($result) === 1);
test('Fragmento de 2000 chars mide 2000', mb_strlen($result[0]) === 2000);

// 3. Texto que excede el límite sin separadores → corte duro
$text = str_repeat('a', 2500);
$result = $f->fragment($text);
test('2500 chars sin separadores → 2 fragmentos', count($result) === 2);
test('Fragmento 1 mide 2000 chars', mb_strlen($result[0]) === 2000);
test('Fragmento 2 mide 500 chars', mb_strlen($result[1]) === 500);

// 4. Corte por frase (.)
$text = str_repeat('a', 1800) . '. ' . str_repeat('b', 500);
$result = $f->fragment($text);
test('Corte por punto: fragmento 1 termina con ". "', substr($result[0], -2) === '. ');
test('Corte por punto: fragmento 2 empieza con b', $result[1][0] === 'b');

// 5. Corte por coma
$text = str_repeat('a', 1900) . ', ' . str_repeat('b', 200);
$result = $f->fragment($text);
test('Corte por coma: fragmento 1 termina con ", "', substr($result[0], -2) === ', ');
test('Corte por coma: fragmento 2 empieza con b', $result[1][0] === 'b');

// 6. Corte por espacio
$text = str_repeat('a', 1995) . ' ' . str_repeat('b', 100);
$result = $f->fragment($text);
test('Corte por espacio: fragmento 1 termina con espacio', substr($result[0], -1) === ' ');
test('Corte por espacio: fragmento 1 mide ≤2000', mb_strlen($result[0]) <= 2000);

// 7. Texto multibyte (acentos, ñ)
$text = 'áéíóúñü' . str_repeat('a', 2000);
$result = $f->fragment($text);
test('Multibyte no rompe caracteres', mb_strlen($result[0]) > 0);
$combined = implode('', $result);
test('Multibyte: concatenación preserva el texto original', $combined === $text);

// 8. Texto vacío
$result = $f->fragment('');
test('Texto vacío devuelve array con string vacío', count($result) === 1 && $result[0] === '');

// 9. Fragmenter con límite personalizado
$f2 = new TtsTextFragmenter(500);
$text = str_repeat('a', 1200);
$result = $f2->fragment($text);
test('Límite 500: 3 fragmentos (500+500+200)', count($result) === 3);
test('Límite 500: cada fragmento ≤500', array_reduce($result, fn($max, $f) => max($max, mb_strlen($f)), 0) <= 500);

// 10. Corte por salto de línea con puntuación previa
$text = str_repeat('a', 1500) . ".\n" . str_repeat('b', 600);
$result = $f->fragment($text);
test('Corte por ".\\n": 2 fragmentos', count($result) === 2);
test('Corte por ".\\n": fragmento 1 termina en ".\\n"', substr($result[0], -2) === ".\n");

echo "\n=== Resultados: $passed passed, $failed failed ===\n";
exit($failed > 0 ? 1 : 0);
