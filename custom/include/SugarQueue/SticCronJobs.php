<?php

require_once 'include/SugarQueue/SugarCronJobs.php';

/**
 * Customized CronJob class that runs a single scheduler within the runCycle function
 * STIC#619
 */
class SticCronJobs extends SugarCronJobs
{
    /**
     * Overriding runCycle function to run only the provided scheduler
     * STIC#619
     *
     * @param string $schedulerId Provided scheduler ID
     * @return void
     */
    public function runCycle($schedulerId = '')
    {
        // STIC Custom - If no scheduler is provided, quit
        if (empty($schedulerId)) {
            $GLOBALS['log']->fatal("No scheduler specified.");
            return;
        }
        // End STIC Custom
        
        // throttle
        if (!$this->throttle()) {
            $GLOBALS['log']->fatal("Job runs too frequently, throttled to protect the system.");
            return;
        }
        
        // clean old stale jobs
        if (!$this->queue->cleanup()) {
            $this->jobFailed();
        }
        
        // run 
        if (!$this->disable_schedulers) {

            // STIC Custom - In core original function, all runnable schedulers are queued at this point
            // In this case, only the provided scheduler will be runned
            // Code inspired by checkPendingJobs function
            
            $schedulerBean = BeanFactory::newBean('Schedulers');
            $job = BeanFactory::newBean('SchedulersJobs');

            $runMassEmailScheduler = $schedulerBean->get_full_list('', "schedulers.id = '$schedulerId' AND schedulers.status='Active' AND NOT EXISTS(SELECT id FROM {$job->table_name} WHERE scheduler_id=schedulers.id AND status!='" . SchedulersJob::JOB_STATUS_DONE . "')");

            if (!empty($runMassEmailScheduler)) {
                foreach ($runMassEmailScheduler as $focus) {
                    if ($focus->fireQualified()) {
                        $job = $focus->createJob();
                        $this->queue->submitJob($job, $focus::initUser());
                    }
                }
            } else {
                $GLOBALS['log']->error('----->No Schedulers found');
            }
            
            // End STIC Custom
        }

        // run jobs
        $cutoff = time()+$this->max_runtime;
        register_shutdown_function(array($this, "unexpectedExit"));
        $myid = $this->getMyId();
        for ($count=0;$count<$this->max_jobs;$count++) {
            $this->job = $this->queue->nextJob($myid);
            if (empty($this->job)) {
                return;
            }
            $this->executeJob($this->job);
            if (time() >= $cutoff) {
                break;
            }
        }
        $this->job = null;
    }

}
