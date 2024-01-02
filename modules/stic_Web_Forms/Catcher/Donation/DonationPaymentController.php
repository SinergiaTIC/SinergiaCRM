<?php

require_once __DIR__ . "/../Include/Payment/PaymentController.php";
require_once __DIR__ . "/DonationPaymentBO.php";

class DonationPaymentController extends PaymentController
{
    /**
     * Overload the constructor to use a different BO class than the default used by the payment controller
     */
    public function __construct($version = 1)
    {
        parent::__construct($version);
        $this->bo = new DonationPaymentBO();
    }
}
