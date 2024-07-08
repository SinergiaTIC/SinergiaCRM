<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use SuiteCRM\Utility\SuiteValidator;

include_once 'include/Exceptions/SugarControllerException.php';

include_once __DIR__ . '/EmailsDataAddressCollector.php';
include_once __DIR__ . '/EmailsControllerActionGetFromFields.php';

class stic_MessagesController extends SugarController
{
    /**
     * @see EmailsViewCompose
     */
    public function action_ComposeView()
    {
        $this->view = 'compose';
        // For viewing the Compose as modal from other modules we need to load the Emails language strings
        if (isset($_REQUEST['in_popup']) && $_REQUEST['in_popup']) {
            if (!is_file('cache/jsLanguage/stic_Messages/' . $GLOBALS['current_language'] . '.js')) {
                require_once('include/language/jsLanguage.php');
                jsLanguage::createModuleStringsCache('stic_Messages', $GLOBALS['current_language']);
            }
            echo '<script src="cache/jsLanguage/stic_Messages/'. $GLOBALS['current_language'] . '.js"></script>';
        }
        if (isset($_REQUEST['ids']) && isset($_REQUEST['targetModule'])) {
            $toAddressIds = explode(',', rtrim($_REQUEST['ids'], ','));
            foreach ($toAddressIds as $id) {
                $destinataryBean = BeanFactory::getBean($_REQUEST['targetModule'], $id);
                if ($destinataryBean) {
                    $idLine = '<input type="hidden" class="email-compose-view-to-list" ';
                    $idLine .= 'data-record-module="' . $_REQUEST['targetModule'] . '" ';
                    $idLine .= 'data-record-id="' . $id . '" ';
                    $idLine .= 'data-record-name="' . $destinataryBean->name . '" ';
                    $idLine .= 'data-record-email="' . $destinataryBean->email1 . '">';
                    echo $idLine;
                }
            }
        }
        if (isset($_REQUEST['relatedModule']) && isset($_REQUEST['relatedId'])) {
            $relateBean = BeanFactory::getBean($_REQUEST['relatedModule'], $_REQUEST['relatedId']);
            $relateLine = '<input type="hidden" class="email-relate-target" ';
            $relateLine .= 'data-relate-module="' . $_REQUEST['relatedModule'] . '" ';
            $relateLine .= 'data-relate-id="' . $_REQUEST['relatedId'] . '" ';
            $relateLine .= 'data-relate-name="' . $relateBean->name . '">';
            echo $relateLine;
        }
    }
}