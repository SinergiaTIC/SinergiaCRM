<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$module_name = 'DHA_PlantillasDocumentos';
$object_name = 'DHA_PlantillasDocumentos';
$_module_name = 'dha_plantillasdocumentos';
$popupMeta = array(
   'moduleMain' => $module_name,
   'varName' => $object_name,
   'orderBy' => $_module_name.'.name',
   'whereClauses' => array(
      'name' => $_module_name . '.name', 
   ),
   'searchInputs'=> array(
      'name', 
   ),
);
?>