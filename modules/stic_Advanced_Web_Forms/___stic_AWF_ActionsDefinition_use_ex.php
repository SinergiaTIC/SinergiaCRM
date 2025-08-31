<?php

// // Dins de la classe StripePaymentAction, que implementa stic_AWF_Deferred_ActionInterface
// class StripePaymentAction implements stic_AWF_Deferred_ActionInterface {
//     public function processWebhook(array $requestData): stic_AWF_WebhookResult
//     {
//         // Lògica per verificar la signatura del webhook de Stripe (seguretat)
//         // ...

//         $eventType = $requestData['type'] ?? null;
//         $session = $requestData['data']['object'] ?? [];

//         $transactionId = $session['id'] ?? null;

//         switch ($eventType) {
//             case 'checkout.session.completed':
//                 // El pagament s'ha completat amb èxit.
//                 return new stic_AWF_WebhookResult(
//                     externalTransactionId: $transactionId,
//                     status: stic_AWF_WebhookResult::STATUS_SUCCESS,
//                     message: 'Pagament completat correctament.',
//                     extraData: [
//                         'amount_total' => $session['amount_total'],
//                         'currency' => $session['currency'],
//                         'payment_status' => $session['payment_status'],
//                     ]
//                 );

//             case 'checkout.session.async_payment_failed':
//                 // El pagament ha fallat.
//                 return new stic_AWF_WebhookResult(
//                     externalTransactionId: $transactionId,
//                     status: stic_AWF_WebhookResult::STATUS_FAILURE,
//                     message: 'El pagament ha fallat.'
//                 );

//             default:
//                 // Un altre tipus d'esdeveniment de Stripe que no ens interessa processar.
//                 return new stic_AWF_WebhookResult(
//                     externalTransactionId: $transactionId,
//                     status: stic_AWF_WebhookResult::STATUS_IGNORED,
//                     message: "Webhook de tipus '$eventType' ignorat."
//                 );
//         }
//     }
// }

// /* Ejemplo */
// class stic_AWF_ManualReviewAction 
//   implements stic_AWF_Deferred_ManualReviewInterface { 
//     public function getManualReviewButtons(): array {
//       return [ 
//         ['label' => 'Aprobar', 'action' => 'approve_manual_review', 'class' => 'btn-success'], 
//         ['label' => 'Rechazar', 'action' => 'reject_manual_review', 'class' => 'btn-danger'], 
//       ];
//     }
// }
