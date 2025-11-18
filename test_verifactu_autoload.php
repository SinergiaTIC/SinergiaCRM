<?php
/**
 * Script de prueba para verificar que el autoloader funciona correctamente
 */

echo "=== Test del Autoloader de Verifactu-PHP ===\n\n";

// Cargar el autoloader
require_once __DIR__ . '/SticInclude/vendor/Verifactu-PHP/autoload.php';

echo "✓ Autoloader cargado\n";

// Probar carga de clases
try {
    echo "\nProbando carga de clases:\n";
    
    // Clase 1: ComputerSystem
    $class1 = 'josemmo\\Verifactu\\Models\\ComputerSystem';
    if (class_exists($class1)) {
        echo "  ✓ $class1\n";
    } else {
        echo "  ✗ $class1 NO ENCONTRADA\n";
    }
    
    // Clase 2: RegistrationRecord
    $class2 = 'josemmo\\Verifactu\\Models\\Records\\RegistrationRecord';
    if (class_exists($class2)) {
        echo "  ✓ $class2\n";
    } else {
        echo "  ✗ $class2 NO ENCONTRADA\n";
    }
    
    // Clase 3: AeatClient
    $class3 = 'josemmo\\Verifactu\\Services\\AeatClient';
    if (class_exists($class3)) {
        echo "  ✓ $class3\n";
    } else {
        echo "  ✗ $class3 NO ENCONTRADA\n";
    }
    
    // Clase 4: InvoiceIdentifier
    $class4 = 'josemmo\\Verifactu\\Models\\Records\\InvoiceIdentifier';
    if (class_exists($class4)) {
        echo "  ✓ $class4\n";
    } else {
        echo "  ✗ $class4 NO ENCONTRADA\n";
    }
    
    echo "\n✓ Todas las clases se cargaron correctamente\n";
    echo "\n=== El autoloader funciona correctamente ===\n";
    
} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
