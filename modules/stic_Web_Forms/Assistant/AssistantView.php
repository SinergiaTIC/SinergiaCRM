<?php

require_once 'include/MVC/View/SugarView.php';

class stic_Web_FormsAssistantView extends SugarView
{
    protected $tpl = '';
    protected $includeDir = '';
    protected $commonTemplateDir = '';
    protected $templateDir = '';
    protected $javascriptDir = '';
    protected $autoForm = true; // Indicates if the default form is used (allows data persistence)
    protected $recaptchaConfiguration = []; // The selected reCAPTCHA configuration

    /**
     * View Constructor
     */
    public function __construct()
    {
        $this->includeDir = "modules/stic_Web_Forms/Assistant/Include";
        $this->commonTemplateDir = $this->templateDir = "{$this->includeDir}/tpls";
        $this->javascriptDir = "{$this->includeDir}/javascript";
    }

        /**
     * Do what is needed before showing the view
     */
    public function preDisplay()
    {
        parent::preDisplay();

        $persistent = $this->view_object_map['PERSISTENT_DATA'];
        $this->ss->assign('RECAPTCHA_CHECKED', !empty($persistent['include_recaptcha']) ? 'checked' : '');
        $this->ss->assign('RECAPTCHA_CONFIGKEYS', $persistent['recaptcha_configKeys']);
        $this->ss->assign('RECAPTCHA_SELECTED', $persistent['recaptcha_selected']);

        // Get selected reCAPTCHA configuration
        if(isset($persistent['recaptcha_selected']) && $persistent['recaptcha_selected']!='') {
            $index = intval($persistent['recaptcha_selected']);
            $key = $persistent['recaptcha_configKeys'][$index];
            $this->recaptchaConfiguration = $persistent['recaptcha_configs'][$key];
        }
    }

    /**
     * Display the view
     */
    public function display()
    {
        parent::display();
        $persistent = $this->view_object_map['PERSISTENT_DATA'];
        $this->view_object_map['JSON_PERSISTENT_DATA'] = !empty($persistent) ? htmlspecialchars(json_encode($persistent)) : '';
        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ":  JSON_PERSISTENT_DATA: {$this->view_object_map['JSON_PERSISTENT_DATA']}");

        $this->ss->assign('MAP', $this->view_object_map);
        $this->ss->assign('TEMPLATE_DIR', $this->templateDir);
        $this->ss->assign('INCLUDE_TEMPLATE_DIR', $this->commonTemplateDir);

        if ($this->autoForm) { // Show the form header
            echo $this->ss->display("{$this->commonTemplateDir}/Header.tpl");
        }

        $this->ss->display("{$this->templateDir}/{$this->tpl}"); // Render the indicated template

        if ($this->autoForm) { // Show the form footer
            echo $this->ss->display("{$this->commonTemplateDir}/Footer.tpl");
        }
    }
}
