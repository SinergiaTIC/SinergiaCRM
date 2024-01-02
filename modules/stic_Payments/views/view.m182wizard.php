<?php
class stic_PaymentsViewm182wizard extends SugarView {

    public function __construct() {
        parent::__construct();
    }

    public function preDisplay() {
        parent::preDisplay();

    }

    public function display() {
        parent::display();
        $this->ss->assign("LAB", $this->view_object_map, $list_label);
        $this->ss->assign("INT", $this->view_object_map, $list_intern);
        $this->ss->assign("ERR", $this->view_object_map, $error);
        $this->ss->assign("VAL", $this->view_object_map, $missingSettings);
        $this->ss->display('modules/stic_Payments/tpls/M182Wizard.tpl');
    }

}
