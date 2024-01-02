<?php 

global $sugar_version;
if (version_compare($sugar_version, '7.0.0', '>=')) {
   global $bwcModules;
   $bwcModules[] = 'DHA_PlantillasDocumentos';
}

?>