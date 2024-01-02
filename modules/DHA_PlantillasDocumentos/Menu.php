<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

global $mod_strings, $app_strings, $sugar_config, $current_user;
 
if(ACLController::checkAccess('DHA_PlantillasDocumentos', 'edit', true))$module_menu[]=Array("index.php?module=DHA_PlantillasDocumentos&action=EditView&return_module=DHA_PlantillasDocumentos&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'],"CreateDHA_PlantillasDocumentos", 'DHA_PlantillasDocumentos');
if(ACLController::checkAccess('DHA_PlantillasDocumentos', 'list', true))$module_menu[]=Array("index.php?module=DHA_PlantillasDocumentos&action=index&return_module=DHA_PlantillasDocumentos&return_action=DetailView", $mod_strings['LNK_LIST'],"DHA_PlantillasDocumentos", 'DHA_PlantillasDocumentos');
//if(ACLController::checkAccess('DHA_PlantillasDocumentos', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=DHA_PlantillasDocumentos&return_module=DHA_PlantillasDocumentos&return_action=index", $app_strings['LBL_IMPORT'],"Import", 'DHA_PlantillasDocumentos');
if(ACLController::checkAccess('DHA_PlantillasDocumentos', 'edit', true))$module_menu[]=Array("index.php?module=DHA_PlantillasDocumentos&action=varlist&return_module=DHA_PlantillasDocumentos&return_action=DetailView", $mod_strings['LBL_LISTA_VARIABLES'],"DHA_PlantillasDocumentosVarList", 'DHA_PlantillasDocumentos');

// if(is_admin($current_user)){
   // $module_menu[]=Array("index.php?module=DHA_PlantillasDocumentos&action=Configuration&return_module=DHA_PlantillasDocumentos&return_action=index", $mod_strings['LBL_CONFIG'],"DHA_PlantillasDocumentosConfig", 'DHA_PlantillasDocumentos');
// }