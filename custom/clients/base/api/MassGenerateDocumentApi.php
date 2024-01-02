<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/api/SugarApi.php');
require_once('modules/DHA_PlantillasDocumentos/MassGenerateDocument.php');

class MassGenerateDocumentApi extends SugarApi {

   ///////////////////////////////////////////////////////////////////////////////////////////////////
   public function registerApiRest() {
      return array(
         'MassGenerateDocumentGetFormParams' => array(
            'reqType' => 'GET',
            'path' => array('<module>','MMR_GetFormParams'),
            'pathVars' => array('module',''),
            //'jsonParams' => array('filter'),
            'method' => 'getFormParams',
            'shortHelp' => 'An API to get params of generate document form',
            //'longHelp' => 'include/api/help/module_massupdate_put_help.html',
            //'ignoreMetaHash' => true,
            //'ignoreSystemStatusError' => true,
            //'keepSession' => true,
            //'noLoginRequired' => true,
            //'allowDownloadCookie' => true,
            //'rawReply' => true,
            //'noEtag' => true,
         ),
         
         'MassGenerateDocumentCreateDocument' => array(
            'reqType' => 'GET',
            'path' => array('<module>','MMR_GenerateDocument'),
            'pathVars' => array('module',''),
            'method' => 'createDocument',
            'shortHelp' => 'An API to generate documents from templates',
            'rawReply' => true,
            'allowDownloadCookie' => true,            
         ),  
         
         'MassGenerateDocumentCreateDocumentATEmail' => array(
            'reqType' => 'GET',
            'path' => array('<module>','MMR_GenerateDocument_ATEmail'),
            'pathVars' => array('module',''),
            'method' => 'createDocument',
            'shortHelp' => 'An API to generate documents from templates and attach to Email',
         ),
         
         'MassGenerateDocumentCreateDocumentATNote' => array(
            'reqType' => 'GET',
            'path' => array('<module>','MMR_GenerateDocument_ATNote'),
            'pathVars' => array('module',''),
            'method' => 'createDocument',
            'shortHelp' => 'An API to generate documents from templates and attach to Note',
         ),         
      );
   }
   
   ///////////////////////////////////////////////////////////////////////////////////////////////////
   public function getViewName(&$args) {
      $viewname = 'ListView';
      if (isset($args['viewName']))
         $viewname = $args['viewName'];

      if ($viewname == 'list')
         $viewname = 'ListView';
         
      if ($viewname == 'record')
         $viewname = 'DetailView';
         
      return $viewname;
   }

   ///////////////////////////////////////////////////////////////////////////////////////////////////
   public function getFormParams($api, $args) {
      
      $this->requireArgs($args, array('module'));
      
      $modulename = $args['module'];
      $viewname = $this->getViewName($args);

      $bean = BeanFactory::newBean($modulename);
      $massDoc = new MassGenerateDocument();
      $massDoc->setSugarBean($bean);
      
      $result = $massDoc->getMassGenerateDocumentForm_Params ($viewname);
      $result['massdocform_code'] = $massDoc->getMassGenerateDocumentForm_Sugar7($viewname);
      
      unset($massDoc);      
      
      return $result;
   }
   
   
   ///////////////////////////////////////////////////////////////////////////////////////////////////
   public function createDocument($api, $args) {
      
      //$this->requireArgs($args, array('module', 'record_list_id', 'template_id', 'pdf', 'attach_to_email', 'attach_to_note'));
      $this->requireArgs($args, array('module', 'template_id', 'pdf', 'attach_to_email', 'attach_to_note'));

      $modulename = $args['module'];
      
      if (isset($args['record_list_id'])) {
         require_once('include/RecordListFactory.php');
         $recordList = RecordListFactory::getRecordList($args['record_list_id']);
         if (empty($recordList)) {
            throw new SugarApiExceptionNotFound('Could not found the requested recordlist "'.$args['record_list_id'].'"');
         }
         $args['uid'] = $recordList['records'];
      }
      else if (isset($args['record_id'])) {
         $args['uid'] = array();
         $args['uid'][] = $args['record_id'];
      }
      else if (isset($args['uid'])) {
         //
      }
      else {
         throw new SugarApiExceptionNotFound('Could not found the requested record id, record list id or array of record ids');
      }
      
      if (empty($args['uid'])) {
         throw new SugarApiExceptionNotFound('Could not found the requested record ids');
      }
      
      
      $bean = BeanFactory::newBean($modulename);
      if (!$bean->ACLAccess('read')) {
         throw new SugarApiExceptionNotAuthorized('No access to read records for module: '.$args['module']);
      }      
      
      $massDoc = new MassGenerateDocument();
      $massDoc->setSugarBean($bean);
      $result = $massDoc->handleMassGenerateDocument_Sugar7($api, $args);
      
      unset($massDoc);
      
      return $result;
   }

}
