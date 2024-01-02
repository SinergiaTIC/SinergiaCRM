<?php

global $app_list_strings;

$admin_option_defs=array();
$admin_option_defs['Administration']['DHA_PlantillasDocumentos_validations']= array(
   'DHA_PlantillasDocumentos',
   translate('LBL_MODULE_CONFIG_DESC', 'DHA_PlantillasDocumentos'),
   translate('LBL_MODULE_CONFIG_DESC', 'DHA_PlantillasDocumentos'),
   'index.php?module=DHA_PlantillasDocumentos&action=Configuration'
);
   
$admin_group_header[]= array(
   $app_list_strings['moduleList']['DHA_PlantillasDocumentos'], //translate('LBL_MODULE_TITLE', 'DHA_PlantillasDocumentos'),
   '',
   false,
   $admin_option_defs, 
   translate('LBL_MODULE_CONFIG_SECTION_DESC', 'DHA_PlantillasDocumentos')
);

?>