<?php

class stic_Validation_ActionsUtils
{

    /**
     * Execute the Validation Actions associated with $scheduledJob. This function is called from the validationActions scheduled task.
     *
     * @param Object $scheduledJob 
     * @return Boolean True if ok
     */
    public static function runSchedulersValidationTasks($scheduledJob)
    {
        require_once 'modules/stic_Validation_Actions/DataAnalyzer/DataAnalyzer.php';
        $scheduledJob->load_relationship('schedulers');
        $schedulers = $scheduledJob->schedulers->getBeans();
        foreach ($schedulers as $scheduler) {
            $scheduler->load_relationship('stic_validation_actions_schedulers');
            $checkActions = $scheduler->stic_validation_actions_schedulers->getBeans();
            $da = new stic_DataAnalyzer();
            $da->processActions($scheduler, $checkActions);
        }        
        
        // Must return true
        return true;

    }
}
