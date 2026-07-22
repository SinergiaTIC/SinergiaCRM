<?php
if (!defined('sugarEntry')) define('sugarEntry', true);

// Mock bean para pruebas sin CRM
class MockBean
{
    public $module_dir = 'Accounts';
    public $field_defs = array();
    public $id = 'mock-id-123';

    public function __construct()
    {
        $this->field_defs = array(
            'name' => array('type' => 'varchar', 'vname' => 'LBL_NAME'),
            'email1' => array('type' => 'varchar', 'vname' => 'LBL_EMAIL'),
            'phone_office' => array('type' => 'varchar', 'vname' => 'LBL_PHONE'),
            'description' => array('type' => 'text', 'vname' => 'LBL_DESCRIPTION'),
            'industry' => array('type' => 'enum', 'vname' => 'LBL_INDUSTRY', 'options' => 'industry_dom'),
            'do_not_call' => array('type' => 'bool', 'vname' => 'LBL_DO_NOT_CALL'),
            'date_entered' => array('type' => 'datetime', 'vname' => 'LBL_DATE_ENTERED'),
            'empty_field' => array('type' => 'varchar', 'vname' => 'LBL_EMPTY'),
        );

        $this->name = 'Test Company SL';
        $this->email1 = 'test@company.com';
        $this->phone_office = '934567890';
        $this->description = '<p>Empresa de <b>pruebas</b> con HTML</p>';
        $this->industry = 'Technology';
        $this->do_not_call = true;
        $this->date_entered = '2025-01-15 10:30:00';
        $this->empty_field = '';
    }

    public function ACLAccess($action)
    {
        return true;
    }
}

// Mock de return_module_language
if (!function_exists('return_module_language')) {
    function return_module_language($language, $module) {
        return array(
            'LBL_NAME' => 'Nombre',
            'LBL_EMAIL' => 'Correo electrónico',
            'LBL_PHONE' => 'Teléfono',
            'LBL_DESCRIPTION' => 'Descripción',
            'LBL_INDUSTRY' => 'Sector',
            'LBL_DO_NOT_CALL' => 'No llamar',
            'LBL_DATE_ENTERED' => 'Fecha de creación',
            'LBL_EMPTY' => 'Campo vacío',
        );
    }
}

// Mock de return_app_list_strings_language
if (!function_exists('return_app_list_strings_language')) {
    function return_app_list_strings_language($language) {
        return array(
            'industry_dom' => array(
                'Technology' => 'Tecnología',
                'Education' => 'Educación',
            ),
        );
    }
}

require_once '/application/sinergiacrm/custom/include/TextToSpeech/Entrypoints/ttsTextAssembler.php';

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

echo "=== TtsTextAssembler Tests ===\n\n";

$bean = new MockBean();
$assembler = new TtsTextAssembler();

// 1. Campos básicos
$result = $assembler->buildFromBean($bean, array('name', 'email1', 'phone_office'));
test('Incluye nombre', strpos($result, 'Nombre: Test Company SL') !== false);
test('Incluye email', strpos($result, 'Correo electrónico: test@company.com') !== false);
test('Incluye teléfono', strpos($result, 'Teléfono: 934567890') !== false);
test('Separa con ". "', strpos($result, '. ') !== false);

// 2. Omite campos vacíos
$result = $assembler->buildFromBean($bean, array('name', 'empty_field', 'email1'));
test('Omite campo vacío', strpos($result, 'Campo vacío') === false);
test('No omite campos con valor', strpos($result, 'Nombre') !== false && strpos($result, 'Correo electrónico') !== false);

// 3. Stripea HTML
$result = $assembler->buildFromBean($bean, array('description'));
test('Strippe etiquetas HTML', strpos($result, '<p>') === false && strpos($result, '<b>') === false);
test('Conserva texto tras strip', strpos($result, 'Empresa de pruebas con HTML') !== false);

// 4. Campo enum traducido
$result = $assembler->buildFromBean($bean, array('industry'));
test('Enum traducido', strpos($result, 'Tecnología') !== false);
test('Enum raw no aparece', strpos($result, 'Technology') === false);

// 5. Campo booleano true
$result = $assembler->buildFromBean($bean, array('do_not_call'));
test('Booleano true → "Yes"', strpos($result, 'Yes') !== false);

// 6. Orden de campos
$result = $assembler->buildFromBean($bean, array('phone_office', 'email1', 'name'));
$posPhone = strpos($result, 'Teléfono');
$posEmail = strpos($result, 'Correo electrónico');
$posName = strpos($result, 'Nombre');
test('Respeta orden de campos', $posPhone < $posEmail && $posEmail < $posName);

// 7. buildFromText
$text = 'Texto directo sin bean.';
$result = $assembler->buildFromText($text);
test('buildFromText devuelve el texto', $result === $text);

// 8. Todos los campos con valores
$result = $assembler->buildFromBean($bean, array('name', 'email1', 'phone_office', 'description', 'industry', 'do_not_call', 'date_entered'));
test('Todos los campos presentes', substr_count($result, ': ') >= 6);

echo "\n=== Resultados: $passed passed, $failed failed ===\n";
exit($failed > 0 ? 1 : 0);
