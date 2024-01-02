<?php

require_once('include/MVC/View/SugarView.php');

class stic_IncorporaView extends SugarView {

	protected $tpl = '';
    protected $templateDir = '';

    public function __construct() {
    	$this->options['show_search'] = false;
    	$this->templateDir =  __DIR__."/../tpls";
    }
    
    function display()  {

        parent::display();
        $this->ss->assign('MAP',$this->view_object_map);
        $this->ss->display("{$this->templateDir}/{$this->tpl}");	// Renderiza la plantilla indicada
    }
}

