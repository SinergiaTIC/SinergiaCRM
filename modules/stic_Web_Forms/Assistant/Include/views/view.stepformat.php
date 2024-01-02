<?php

require_once 'modules/stic_Web_Forms/Assistant/AssistantView.php';

class stic_Web_FormsViewStepFormat extends stic_Web_FormsAssistantView
{
    /**
     * Do what is needed before showing the view
     */
    public function preDisplay()
    {
        parent::preDisplay();

        // Prepare the editor
        require_once 'include/SugarTinyMCE.php';
        $tiny = new SugarTinyMCE();
        $tiny->defaultConfig['height'] = 400;
        $tiny->defaultConfig['apply_source_formatting'] = true;
        $tiny->defaultConfig['cleanup'] = false;
        $tiny->defaultConfig['content_css'] = 'cache/themes/Sugar5/css/style.css';

        $ed = $tiny->getInstance('bodyHTML');

        $this->view_object_map['TINY'] = $ed;
        $this->view_object_map['ENCTYPE'] = "multipart/form-data";
        $this->view_object_map['HOURS'] = array
            (
            '00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12',
            '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23',
        );
        $this->view_object_map['MINUTES'] = array
            (
            '00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19',
            '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39',
            '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52', '53', '54', '55', '56', '57', '58', '59',
        );
        $this->tpl = "StepFormat.tpl";
    }

    /**
     * Display the view
     */
    public function display()
    {
        parent::display();
    }
}
