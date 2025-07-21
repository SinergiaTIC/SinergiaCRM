<?php
// custom/modules/YourModule/SticSignEntryPoint.php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

// Incluye los archivos necesarios para cargar la clase de la vista
require_once('include/MVC/View/SugarView.php');
require_once('modules/stic_Signatures/views/view.signatureportal.php'); // Asegúrate de que esta ruta sea correcta

// Inicia una instancia de la clase de la vista
$view = new stic_SignaturePortal();

// Llama al método display() de la vista
$view->display();

// Termina la ejecución del script para asegurar que no se cargue nada adicional del framework de SuiteCRM
sugar_die('');