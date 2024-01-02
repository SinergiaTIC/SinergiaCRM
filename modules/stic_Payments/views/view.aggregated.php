<?php

require_once 'include/MVC/View/SugarView.php';

class ViewAggregated extends SugarView
{

    public function __construct()
    {
        $this->options['show_search'] = false;
    }

    public function preDisplay()
    {
        parent::preDisplay();
    }

    public function display()
    {

        parent::display();
        $this->ss->assign('MAP', $this->view_object_map);
        $this->ss->display("modules/stic_Payments/tpls/AggregatePayments.tpl");
    }
}
