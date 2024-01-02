<?php

require_once __DIR__ . "/../Include/Payment/PaymentController.php";
require_once __DIR__ . "/EventInscriptionPaymentBO.php";

class EventInscriptionPaymentController extends PaymentController
{
    /**
     * Overload the constructor to use a different BO class than the default used by the payment controller
     */
    public function __construct()
    {
        parent::__construct(2); // Force version 2 of the payment controller
        $this->bo = new EventInscriptionPaymentBO();
    }
}
