<?php
/**
 * Autoloader simple para Verifactu-PHP
 * Este archivo carga automáticamente las clases de la librería Verifactu-PHP
 */

spl_autoload_register(function ($class) {
    // Prefijo del namespace de Verifactu
    $prefix = 'josemmo\\Verifactu\\';
    
    // Directorio base de la librería
    $base_dir = __DIR__ . '/src/';
    
    // Verificar si la clase usa el namespace de Verifactu
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No es una clase de Verifactu, continuar con otros autoloaders
        return;
    }
    
    // Obtener el nombre relativo de la clase
    $relative_class = substr($class, $len);
    
    // Reemplazar el namespace con separadores de directorio
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // Si el archivo existe, cargarlo
    if (file_exists($file)) {
        require_once $file;
    }
});
