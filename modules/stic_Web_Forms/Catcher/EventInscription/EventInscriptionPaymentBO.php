<?php

require_once __DIR__ . "/../Include/Payment/PaymentBO.php";

class EventInscriptionPaymentBO extends PaymentBO
{

    /**
     * Overload the payment generation method to carry out subsequent actions of the registration payments only
     * @see PaymentBO::newPayment()
     * @return Objetc A payment or Null
     */
    public function newPayment($namePrefix = '')
    {
        if (empty($this->inscription) || empty($this->inscription->id)) {
            $GLOBALS['log']->error('Line ' . __LINE__ . ': ' . __METHOD__ . ":  There is no registration data to link to the payment. Payment will not be generated.");
            return null;
        } else {
            $payment = parent::newPayment($this->inscription->name);
            if ($payment != null) {
                $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ":  Linking the payment [{$payment->id}] to registration [{$this->inscription->id}]");
                $this->inscription->stic_payments_stic_registrations->add($payment->id);
            }
            return $payment;
        }
    }
}
