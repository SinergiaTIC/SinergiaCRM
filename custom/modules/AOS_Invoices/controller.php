<?php
// controller.php

require_once 'modules/AOS_Invoices/controller.php';
class CustomAOS_InvoicesController extends AOS_InvoicesController
{
   public function action_sendInvoiceToAEAT()
   {
       require_once 'custom/modules/AOS_Invoices/SticUtils.php';


   }
}