<?php
   if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
   class DHA_DocumentTemplatesProjectTaskHook_class {
      function after_ui_frame_method($event, $arguments) {
         require_once('modules/DHA_PlantillasDocumentos/UI_Hooks.php');
         MailMergeReports_after_ui_frame_hook ($event, $arguments);
      }
   }
?>      