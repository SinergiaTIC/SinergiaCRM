<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class SugarWidgetSubPanelTopEPSButton extends SugarWidgetSubPanelTopButton
{
    public function getWidgetId($buttonSuffix = true)
    {
        global $app_strings;
        $this->form_value = $app_strings['LBL_SUBPANEL_NEW_MESSAGE_LABEL'];
        return parent::getWidgetId();
    }


    public function &_get_form($defines, $additionalFormFields = null, $asUrl = false)
    {
        global $app_strings;

        $bean = $defines['focus'];

        $button = $app_strings['LBL_SUBPANEL_NEW_MESSAGE_LABEL'];
        $accesskey = $app_strings['LBL_SUBPANEL_NEW_MESSAGE_LABEL'];

        $parent_module = $defines['focus']->module_name;
        $button_module = $defines['module'];

        $form = "<input type='button' name='button' id='custom_modal_button' class='button' 
            title='{$button}' value='{$button}' accesskey='{$accesskey}'
            onclick='openCustomModal(this); return false;'
                 data-phone='{$bean->phone_mobile}'
                 data-module='{$bean->module_name}'
                 data-record-id='{$bean->id}'
                 >";

        // $form = "<div type='hidden' 
        //          onclick='openCustomModal(this); return false;'
        //          data-phone='{$bean->phone_mobile}'
        //          data-module='{$bean->module_name}'
        //          data-record-id='{$bean->id}'
        //          >";


                //  onclick='openCustomModal(\"{$button_module}\", \"{$parent_module}\"); return false;'>";


                // $additional_params = array(
                //     'parent_id' => $defines['focus']->id,
                //     'parent_name' => $defines['focus']->name,
                //     'phone' => $defines['focus']->phone_mobile,
                //     // Add more parameters as needed
                // );
        
                // $params_json = json_encode($additional_params);
        
                // $form = "<input type='button' name='button' id='custom_modal_button' class='button' 
                //          title='{$button}' value='{$button}' accesskey='{$accesskey}'
                //          onclick='openCustomModal(this, {$params_json}); return false;'>";
                //         //  onclick='openCustomModal(\"{$button_module}\", \"{$parent_module}\", {$params_json}); return false;'>";
        




        return $form;
    }

    public function display($defines, $additionalFormFields = null, $nonbutton = false)
    {
        $focus = $defines['focus'];
        $button = $this->_get_form($defines, $additionalFormFields);
        
        return $button;
    }



    // public $form_value = '';

    // public function getWidgetId($buttonSuffix = true)
    // {
    //     global $app_strings;
    //     $this->form_value = 'PAPPAPAPA';// $app_strings['LBL_SUBPANEL_NEW_MESSAGE_LABEL'];
    //     return parent::getWidgetId();
    // }
    // public function &_get_form($defines, $additionalFormFields = null, $nonbutton = false)
    // {
    //     $bean = $defines['focus'];
    //     $button = '<div type="hidden" onclick="currentModule=\''
    //     //   . $bean->module_name . '\';alert(\'bbbb\');$(document).openComposeViewModal(this);" data-module="'
    //       . $bean->module_name . '\';alert(\'bbbb\');$(document).openComposeMessageViewModal(this);" data-module="'
    //       . $bean->module_name . '" data-record-id="'
    //       . $bean->id . '" data-module-name="'
    //       . $bean->name .'" data-email-address="'
    //       . $bean->email1 .'">';

    //     return $button;

    // }

    // public function display($defines, $additionalFormFields = null, $nonbutton = false)
    // {
    //     $focus = new Meeting;
    //     if (!$focus->ACLAccess('EditView')) {
    //         return '';
    //     }
        
    //     $inputID = $this->getWidgetId();

    //     $button = $this->_get_form($defines, $additionalFormFields);

    //     // global $current_user;
    //     // $client = $current_user->getEmailClient();

    //     // if ($client == 'sugar') {
    //     //     $button .= "<input class='button' onclick='return false;' type='button' id='$inputID' value='$this->form_value'>";
    //     // }
    //         $button .= "<input class='button' onclick='alert('ccc');return false;' type='button' id='$inputID' value='$this->form_value'>";

    //     return $button;
    // }

}