<?php
//require_once('include\MVC\Controller\entry_point_registry.php');

// Nota Sugar 7: El entry_point debe de empezar por download_xxxx para que Sugar no cambie la url a modo #bwc

$entry_point_registry['download_dha_document_template'] = array('file' => 'modules/DHA_PlantillasDocumentos/download_template.php', 'auth' => true);

?>