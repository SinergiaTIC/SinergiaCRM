<?php

class stic_Validation_ActionsController extends SugarController {

    /**
     * This function allows the validation actions associated with a scheduler task to be executed from the console.
     * In addition to the name of the scheduled task, and the action itself, the "no-incremental" parameter can be included in the url, although with caution, as this will erase the last execution date of all validation actions.
     * For example: location.href='index.php?module=stic_Validation_Actions&action=runValidationActions&scheduler=7386c4b1-bcc2-4f6f-be88-7e2a2e5778b5&no-incremental'
     *
     * @return void
     */
    public function action_runValidationActions() {

        global $db;
        $scheduler = $_REQUEST['scheduler'];

        if (isset($_REQUEST['no-incremental'])) {
            $db->query("update stic_validation_actions set last_execution=null where 1;");
        }

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ':  Forcing the data validation task linked with scheduler ' . $scheduler);

        require_once 'modules/stic_Validation_Actions/DataAnalyzer/DataAnalyzer.php';

        $schedulerBean = BeanFactory::getBean('Schedulers', $scheduler);

        $schedulerBean->load_relationship('stic_validation_actions_schedulers');
        $checkActions = $schedulerBean->stic_validation_actions_schedulers->getBeans();
        $da = new stic_DataAnalyzer();
        $da->processActions($schedulerBean, $checkActions);

        SugarApplication::redirect('index.php?module=stic_Validation_Actions&action=index');

    }

}
