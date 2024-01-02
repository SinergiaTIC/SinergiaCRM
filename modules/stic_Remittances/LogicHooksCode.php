<?php

class stic_RemittancesLogicHooks {

    public function before_save(&$bean, $event, $arguments) {
        // Mark the payments as paid if the remittance status is changed to paid
        require_once 'modules/stic_Remittances/Utils.php';
        stic_RemittancesUtils::markPaymentsAsPaidIfRemittanceIsSent($bean);
    }
}
