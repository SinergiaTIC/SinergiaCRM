<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


require_once('include/Dashlets/Dashlet.php');
require_once 'modules/adrep_report/adrep_report.php';

class adrep_reportDisplayDashlet extends Dashlet
{
    public $def;
    public $adrep_report;
    public $template;
    public $noresults;

    public function __construct($id, $def = array())
    {
        global $current_user, $app_strings;

        parent::__construct($id);
        $this->isConfigurable = true;
        $this->def = $def;
        if (empty($def['dashletTitle'])) {
            $this->title = translate('LBL_DISPLAY_REPORT_DASHLET', 'adrep_report');
        } else {
            $this->title = $def['dashletTitle'];
        }

        if (!empty($def['adrep_report_id'])) {
            $this->adrep_report = BeanFactory::getBean('adrep_report', $def['adrep_report_id']);
        }
        $this->noresults = !empty($def['adrep_noresults']) ? $def['adrep_noresults'] : 10;;
        $this->template = !empty($def['adrep_template']) ? $def['adrep_template'] : 'DefaultDashlet';
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function adrep_reportDisplayDashlet($id, $def = array())
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct($id, $def);
    }

    public function display()
    {
        global $current_language,$mod_strings,$sugar_config;

        $mod_strings = return_module_language($current_language, 'adrep_report');
        $dashletSmarty = new Sugar_Smarty();
        $dashletTemplate = get_custom_file_if_exists('modules/adrep_report/Dashlets/adrep_reportDisplayDashlet/dashlet.tpl');
        $dashletSmarty->assign('MOD', $mod_strings);
        $dashletSmarty->assign('dashlet_id', $this->id);
        $dashletSmarty->assign('report_id', $this->adrep_report->id);
        $dashletSmarty->assign('template', $this->template);
        $dashletSmarty->assign('noresults', $this->noresults);
				$dashletSmarty->assign('height', $this->def['adrep_height']);

				$url=$sugar_config['site_url'].'/index.php?module=adrep_report&action=RunDashletReport&record='.$this->adrep_report->id.'&template='.$this->template.'&rpp='.$this->noresults.'&sugar_body_only=yes';
$dashletSmarty->assign('url', $url);
        return $dashletSmarty->fetch($dashletTemplate);
    }


    public function process()
    {
    }

    public function displayOptions()
    {
        ob_start();
				error_reporting(E_ALL);
				ini_set('display_errors','on');
        global $current_language, $app_list_strings, $datetime,$mod_strings;
        $mod_strings = return_module_language($current_language, 'adrep_report');
        $optionsSmarty = new Sugar_Smarty();
        $optionsSmarty->assign('MOD', $mod_strings);
        $optionsSmarty->assign('id', $this->id);
        $optionsSmarty->assign('dashletTitle', $this->title);
        $optionsSmarty->assign('adrep_report_id', $this->adrep_report->id);
        $optionsSmarty->assign('adrep_report_name', $this->adrep_report->name);
        $optionsSmarty->assign('adrep_template', $this->template);
				$optionsSmarty->assign('adrep_noresults', $this->noresults);
				$optionsSmarty->assign('adrep_height', $this->def['adrep_height']);
				$rep=new adrep_report();
				$rep->_BuildTemplateList();
				$optionsSmarty->assign('template_list', $app_list_strings['adrep_css_file_list']);

        $optionsTemplate = get_custom_file_if_exists('modules/adrep_report/Dashlets/adrep_reportDisplayDashlet/dashletConfigure.tpl');
        ob_clean();

        return $optionsSmarty->fetch($optionsTemplate);
    }

    public function saveOptions($req)
    {
        $allowedKeys = array_flip(array(
            'adrep_report_id',
            'dashletTitle',
            'adrep_template',
            'adrep_noresults',
            'adrep_report_name',
						'adrep_height'
        ));



        $intersected = array_intersect_key($req, $allowedKeys);

        return $intersected;
    }

    public function hasAccess()
    {
        return true;
    }
}
