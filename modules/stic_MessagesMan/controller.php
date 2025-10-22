<?php

class stic_MessagesManController extends SugarController
{

    /**
     * Execute stic_Payment_CommitmentsUtils::recalculateAllFuturePaymentsViaSQL() method.
     * It is possible to force the execution of this action by executing this command from the browser console:
     * location.href='index.php?module=stic_Payment_Commitments&action=recalculateAllFuturePaymentsViaSQL'
     *
     * @return bool True if the operation was successful.
     */
    public function action_force() {
        require_once('modules/stic_MessagesMan/Utils.php');
        stic_MessagesManUtils::sendQueuedMessages(false);

        header("Location: index.php?module=stic_MessagesMan&action=index");
    }
}
