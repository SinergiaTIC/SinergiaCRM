<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class DHA_PlantillasDocumentosViewConfig extends SugarView {


   ///////////////////////////////////////////////////////////////////////////
   public function preDisplay(){
      if(!is_admin($GLOBALS['current_user']))
         sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']); 
   }
    
   ///////////////////////////////////////////////////////////////////////////
   protected function _getModuleTitleParams($browserTitle = false) {
      global $mod_strings, $app_list_strings;
       
      return array(
         "<a href='index.php?module=DHA_PlantillasDocumentos&action=index'>".$app_list_strings['moduleList']['DHA_PlantillasDocumentos']."</a>",
         $mod_strings['LBL_CONFIG']
      );
   } 
    
   ///////////////////////////////////////////////////////////////////////////
   public function display() {
      require_once('modules/DHA_PlantillasDocumentos/UI_Hooks.php');
      require_once('modules/DHA_PlantillasDocumentos/MassGenerateDocument.php');
      
      global $mod_strings;
      global $app_list_strings;
      global $app_strings;
      global $current_user;
      global $theme;
      global $currentModule;
      global $config;
      global $db;
      global $sugar_version;
      
      $configurator = new Configurator();
      $MassGenerateDocument = new MassGenerateDocument();

      $enabled_modules = MailMergeReports_after_ui_frame_hook_enabled_modules();
      $disabled_modules = MailMergeReports_after_ui_frame_hook_disabled_modules();
      
      natcasesort($enabled_modules);
      natcasesort($disabled_modules);
        
      $json_enabled = array();
      foreach($enabled_modules as $mod => $label) {
         $json_enabled[] = array("module" => $mod, 'label' => $label);
      }
        
      $json_disabled = array();
      foreach($disabled_modules as $mod => $label) {
         $json_disabled[] = array("module" => $mod, 'label' => $label);
      }
      
      $roles = array();
      $sql = 'select * from acl_roles where deleted = 0 order by name ';
      $dataset = $db->query($sql);
      while ($row = $db->fetchByAssoc($dataset)) {
         $id = $row['id'];
         $roles[$id]['id'] = $id;
         $roles[$id]['name'] = $row['name'];
         $roles[$id]['description'] = $row['description'];
         $roles[$id]['enabled_level'] = $MassGenerateDocument->role_enabled_level($id);
         $roles[$id]['enabled_options'] = get_select_options_with_id($app_list_strings['dha_plantillasdocumentos_enable_roles_dom'], $roles[$id]['enabled_level']);
      }      

      $this->ss->assign('config', $configurator->config);
      $this->ss->assign('enabled_modules', json_encode($json_enabled));
      $this->ss->assign('disabled_modules', json_encode($json_disabled));
      $this->ss->assign('MOD', $GLOBALS['mod_strings']);
      $this->ss->assign('APP', $GLOBALS['app_strings']);
      $this->ss->assign('theme', $GLOBALS['theme']);
      $this->ss->assign('ROLES', $roles); 

      $sugar_7 = '0';
      $sugar_7_7 = '0';
      $csrf_form_token_input = '';
      $csrf_form_token_input_id = 'csrf_form_token_input_id';  // necesario para evitar errores en versiones distintas a Sugar 7.7
      
      if (version_compare($sugar_version, '7.0.0', '>=')) {
         $sugar_7 = '1';
         
         if (version_compare($sugar_version, '7.7.0', '>=')) {
            $sugar_7_7 = '1';
            
            require_once ('modules/DHA_PlantillasDocumentos/librerias/dharma_csrf_form_token.php');
            $csrf_form_token_input = get_sugar_csrf_form_token(false);
            $csrf_form_token_input_id = get_sugar_csrf_form_token_id();
         }
      }

      $this->ss->assign('sugar_7', $sugar_7);
      $this->ss->assign('sugar_7_7', $sugar_7_7);       
      $this->ss->assign('csrf_form_token_input', $csrf_form_token_input);
      $this->ss->assign('csrf_form_token_input_id', $csrf_form_token_input_id);      
            
      
      $dha_plantillasdocumentos_idiomas_dom = $app_list_strings['dha_plantillasdocumentos_idiomas_dom'];
      natcasesort($dha_plantillasdocumentos_idiomas_dom);
      if (!empty($configurator->config['DHA_templates_default_lang'])) {
          $this->ss->assign('DHA_templates_default_lang', get_select_options_with_id($dha_plantillasdocumentos_idiomas_dom, $configurator->config['DHA_templates_default_lang']));
      } else {
          $this->ss->assign('DHA_templates_default_lang', get_select_options_with_id($dha_plantillasdocumentos_idiomas_dom, ''));
      }      
       
      // A partir de la version 6.5.0 la función original getClassicModuleTitle no funciona bien, tiene un bug por el que solo saca a partir del segundo parámetro, 
      // por eso he dejado de momento LBL_MODULE_CONFIG_DESC en lugar de LBL_CONFIG ... cuando lo arreglen hay que cambiarlo
      echo getClassicModuleTitle(
            "DHA_PlantillasDocumentos", 
            array(
               "<a href='index.php?module=DHA_PlantillasDocumentos&action=index'>{$app_list_strings['moduleList']['DHA_PlantillasDocumentos']}</a>",
               $mod_strings['LBL_MODULE_CONFIG_DESC'], //$mod_strings['LBL_CONFIG'],
            ), 
            false
      );
        
      echo $this->ss->fetch('modules/DHA_PlantillasDocumentos/views/view.config.tpl');
   }
   
}