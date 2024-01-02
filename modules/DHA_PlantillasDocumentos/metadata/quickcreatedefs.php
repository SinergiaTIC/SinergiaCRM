<?php

// Tiene que ser un require, no require_once para que funcione en el studio (si no es asi, al pinchar en etiquetas da un error). 
// Ver la funcion __construct de GridLayoutMetaDataParser.php y la funcion _loadFromFile de AbstractMetaDataImplementation.php
require ('modules/DHA_PlantillasDocumentos/metadata/editviewdefs.php');

$viewdefs ['DHA_PlantillasDocumentos']['QuickCreate'] = $viewdefs ['DHA_PlantillasDocumentos']['EditView'];  
          
?>