<?php

class stic_RemittancesController extends SugarController {
    public function action_generateSEPADirectDebit() {
        require 'modules/stic_Remittances/GenerateSEPADirectDebits.php';
        generateSEPADirectDebits($this);
    }

    public function action_generateSEPACreditTransfer() {
        require 'modules/stic_Remittances/GenerateSEPACreditTransfers.php';
        generateSEPACreditTransfers($this);
    }
    
    public function action_processRedsysCardPayments() {
        require 'modules/stic_Remittances/ProcessRedsysCardPayments.php';
        processRedsysCardPayments($this);
    }

    public function action_loadSEPAReturns() {
        require_once 'modules/stic_Remittances/LoadSEPAReturns.php';
        SepaReturns::loadSEPAReturns($this);
    }

    public function action_loadFile() {
        $this->view = 'load_file';
    }

    /**
     * This actions comes from the ViewList of stic_Payments. It runs the function that will add the relationship between the payments and the remittance using a
     * SQL query.
     */
    public function action_addPaymentsToRemittance() {
        require 'modules/stic_Remittances/AddPaymentsToRemittance.php';
        addPaymentsToRemittance();
    }

}
