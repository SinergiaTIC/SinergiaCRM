<?php
// filepath: enviar_factura_ejemplo.php
require_once __DIR__ . '/../../../SticInclude/vendor/Verifactu-PHP/autoload.php';

use DateTimeImmutable;
use josemmo\Verifactu\Models\ComputerSystem;
use josemmo\Verifactu\Models\Records\BreakdownDetails;
use josemmo\Verifactu\Models\Records\FiscalIdentifier;
use josemmo\Verifactu\Models\Records\InvoiceIdentifier;
use josemmo\Verifactu\Models\Records\InvoiceType;
use josemmo\Verifactu\Models\Records\OperationType;
use josemmo\Verifactu\Models\Records\RegimeType;
use josemmo\Verifactu\Models\Records\RegistrationRecord;
use josemmo\Verifactu\Models\Records\TaxType;
use josemmo\Verifactu\Services\AeatClient;

// ============================================================================
// CONFIGURACIÓN (ajusta estos valores según tus datos)
// ============================================================================
$CERTIFICADO_PATH = __DIR__ . '/certificado.pfx';  // Ruta al certificado
$CERTIFICADO_PASSWORD = 'tu_contraseña';            // Contraseña del certificado
$NIF_EMPRESA = 'A00000000';                         // NIF de tu empresa
$NOMBRE_EMPRESA = 'Mi Empresa de Ejemplo, S.L.';   // Nombre de tu empresa
$USAR_PRODUCCION = false;                           // false = preproducción, true = producción

// ============================================================================
// 1. CREAR REGISTRO DE FACTURACIÓN
// ============================================================================
echo "Creando registro de facturación...\n";

$record = new RegistrationRecord();

// Identificador de la factura
$record->invoiceId = new InvoiceIdentifier();
$record->invoiceId->issuerId = $NIF_EMPRESA;
$record->invoiceId->invoiceNumber = 'FACT-' . date('Y-m-d-His');
$record->invoiceId->issueDate = new DateTimeImmutable();

// Datos básicos de la factura
$record->issuerName = $NOMBRE_EMPRESA;
$record->invoiceType = InvoiceType::Simplificada;
$record->description = 'Venta de productos varios';

// Desglose fiscal (IVA 21%)
$record->breakdown[0] = new BreakdownDetails();
$record->breakdown[0]->taxType = TaxType::IVA;
$record->breakdown[0]->regimeType = RegimeType::C01;
$record->breakdown[0]->operationType = OperationType::Subject;
$record->breakdown[0]->baseAmount = '100.00';
$record->breakdown[0]->taxRate = '21.00';
$record->breakdown[0]->taxAmount = '21.00';

// Totales
$record->totalTaxAmount = '21.00';
$record->totalAmount = '121.00';

// Encadenamiento (primera factura de la cadena)
$record->previousInvoiceId = null;
$record->previousHash = null;

// Generar huella (hash)
$record->hashedAt = new DateTimeImmutable();
$record->hash = $record->calculateHash();

// NOTA: Validación deshabilitada por incompatibilidad con Symfony Validator 3.4
// La librería Verifactu-PHP requiere Symfony 7.3+, pero SinergiaCRM usa 3.4
// La validación se realizará en el servidor de AEAT
// $record->validate();
echo "✓ Registro creado (sin validación local)\n";

// ============================================================================
// 2. CONFIGURAR SISTEMA INFORMÁTICO DE FACTURACIÓN (SIF)
// ============================================================================
echo "Configurando sistema informático...\n";

$system = new ComputerSystem();
$system->vendorName = $NOMBRE_EMPRESA;
$system->vendorNif = $NIF_EMPRESA;
$system->name = 'Sistema de Facturación v1';
$system->id = 'SF';
$system->version = '1.0.0';
$system->installationNumber = '001';
$system->onlySupportsVerifactu = true;
$system->supportsMultipleTaxpayers = false;
$system->hasMultipleTaxpayers = false;

// NOTA: Validación deshabilitada por incompatibilidad con Symfony Validator 3.4
// $system->validate();
echo "✓ Sistema configurado (sin validación local)\n";

// ============================================================================
// 3. ENVIAR A LA AEAT
// ============================================================================
echo "Enviando a la AEAT...\n";

$taxpayer = new FiscalIdentifier($NOMBRE_EMPRESA, $NIF_EMPRESA);
$client = new AeatClient($system, $taxpayer);

// Configurar certificado
if (!file_exists($CERTIFICADO_PATH)) {
    die("❌ ERROR: No se encuentra el certificado en: $CERTIFICADO_PATH\n");
}
$client->setCertificate($CERTIFICADO_PATH, $CERTIFICADO_PASSWORD);

// Configurar entorno (preproducción o producción)
$client->setProduction($USAR_PRODUCCION);
$entorno = $USAR_PRODUCCION ? 'PRODUCCIÓN' : 'PREPRODUCCIÓN';
echo "Usando entorno: $entorno\n";

try {
    // Enviar registro
    $response = $client->send([$record])->wait();
    
    echo "\n";
    echo "════════════════════════════════════════════════════════════════\n";
    echo "✓ FACTURA ENVIADA CORRECTAMENTE\n";
    echo "════════════════════════════════════════════════════════════════\n";
    echo "CSV: " . ($response->csv ?? 'N/A') . "\n";
    echo "Estado: " . $response->status->value . "\n";
    echo "Tiempo de espera: {$response->waitSeconds}s\n";
    
    if ($response->submittedAt !== null) {
        echo "Fecha de presentación: " . $response->submittedAt->format('d-m-Y H:i:s') . "\n";
    }
    
    echo "\nDetalles de los registros:\n";
    foreach ($response->items as $index => $item) {
        echo "  Factura " . ($index + 1) . ": {$item->invoiceId->invoiceNumber}\n";
        echo "    - Estado: {$item->status->value}\n";
        if ($item->errorCode !== null) {
            echo "    - Error [{$item->errorCode}]: {$item->errorDescription}\n";
        }
    }
    
    echo "════════════════════════════════════════════════════════════════\n";
    
} catch (Exception $e) {
    echo "\n";
    echo "════════════════════════════════════════════════════════════════\n";
    echo "❌ ERROR AL ENVIAR LA FACTURA\n";
    echo "════════════════════════════════════════════════════════════════\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "════════════════════════════════════════════════════════════════\n";
    exit(1);
}